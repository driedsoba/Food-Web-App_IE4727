<?php
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

// Use the Database class (matches feedback.php)
$database = new Database();
$pdo = $database->getConnection();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userId = intval($_SESSION['user_id']);

$STATUSES = [
  'order placed',
  'order accepted',
  'preparing order',
  'delivering order',
  'order delivered'
];

function fetchOrderWithItems($pdo, $orderId, $userId) {
  $stmt = $pdo->prepare("
    SELECT id, user_id, status, total_amount, created_at
    FROM orders
    WHERE id = :id AND user_id = :user_id
    LIMIT 1
  ");
  $stmt->execute([':id' => $orderId, ':user_id' => $userId]);
  $order = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$order) return null;

  $itemsStmt = $pdo->prepare("
    SELECT oi.id, oi.menu_item_id, oi.quantity, oi.price,
           mi.name AS item_name
    FROM order_items oi
    LEFT JOIN menu_items mi ON mi.id = oi.menu_item_id
    WHERE oi.order_id = :order_id
    ORDER BY oi.id ASC
  ");
  $itemsStmt->execute([':order_id' => $orderId]);
  $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
  return $order;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
  if ($method === 'GET') {
    if (isset($_GET['id'])) {
      $orderId = intval($_GET['id']);
      $order = fetchOrderWithItems($pdo, $orderId, $userId);
      if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit;
      }
      echo json_encode(['order' => $order]);
      exit;
    }

    $stmt = $pdo->prepare("
      SELECT id, user_id, status, total_amount, created_at
      FROM orders
      WHERE user_id = :user_id
      ORDER BY created_at DESC, id DESC
    ");
    $stmt->execute([':user_id' => $userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = [];
    foreach ($orders as $o) {
      $full = fetchOrderWithItems($pdo, intval($o['id']), $userId);
      if ($full) $out[] = $full;
    }
    echo json_encode(['orders' => $out]);
    exit;
  }

  if ($method === 'POST') {
    $action = $_GET['action'] ?? ($_POST['action'] ?? '');

    if ($action === 'create') {
      $input = json_decode(file_get_contents('php://input'), true);
      $items = $input['items'] ?? [];

      if (!is_array($items) || count($items) === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'No items to checkout']);
        exit;
      }

      $total = 0.0;
      foreach ($items as $it) {
        $qty = max(1, intval($it['quantity'] ?? 1));
        $price = floatval($it['price'] ?? 0);
        $total += ($price * $qty);
      }

      $pdo->beginTransaction();
      try {
        $insOrder = $pdo->prepare("
          INSERT INTO orders (user_id, status, total_amount, created_at)
          VALUES (:user_id, :status, :total_amount, NOW())
        ");
        $insOrder->execute([
          ':user_id' => $userId,
          ':status' => 'order placed',
          ':total_amount' => $total
        ]);
        $orderId = intval($pdo->lastInsertId());

        $insItem = $pdo->prepare("
          INSERT INTO order_items (order_id, menu_item_id, quantity, price)
          VALUES (:order_id, :menu_item_id, :quantity, :price)
        ");

        foreach ($items as $it) {
          $menuItemId = intval($it['menu_item_id'] ?? $it['id'] ?? 0);
          $qty = max(1, intval($it['quantity'] ?? 1));
          $price = floatval($it['price'] ?? 0);
          if ($menuItemId <= 0) continue;

          $insItem->execute([
            ':order_id' => $orderId,
            ':menu_item_id' => $menuItemId,
            ':quantity' => $qty,
            ':price' => $price
          ]);
        }

        $pdo->commit();
        $order = fetchOrderWithItems($pdo, $orderId, $userId);
        echo json_encode(['success' => true, 'order' => $order]);
        exit;
      } catch (Throwable $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create order', 'details' => $e->getMessage()]);
        exit;
      }
    }

    if ($action === 'advance') {
      $orderId = intval($_GET['id'] ?? $_POST['id'] ?? 0);
      if ($orderId <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing order id']);
        exit;
      }

      $order = fetchOrderWithItems($pdo, $orderId, $userId);
      if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit;
      }

      $current = strtolower(trim($order['status']));
      $idx = array_search($current, $STATUSES, true);
      if ($idx === false) $idx = 0;
      $nextIdx = min($idx + 1, count($STATUSES) - 1);
      $nextStatus = $STATUSES[$nextIdx];

      $upd = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id AND user_id = :user_id");
      $upd->execute([':status' => $nextStatus, ':id' => $orderId, ':user_id' => $userId]);

      $updated = fetchOrderWithItems($pdo, $orderId, $userId);
      echo json_encode(['success' => true, 'order' => $updated]);
      exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Unknown action']);
    exit;
  }

  http_response_code(405);
  header('Allow: GET, POST');
  echo json_encode(['error' => 'Method not allowed']);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
}