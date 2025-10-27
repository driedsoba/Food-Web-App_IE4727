# PHP & MySQL Integration Guide for Feedback System

## Overview
This document outlines the steps needed to integrate the Feedback page with PHP backend and MySQL database.

## Database Schema

### Create Feedback Table
```sql
CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    order_number VARCHAR(50),
    feedback TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved BOOLEAN DEFAULT FALSE,
    INDEX idx_created_at (created_at),
    INDEX idx_approved (approved)
);
```

## PHP API Endpoints

### 1. Submit Feedback (`api/submit-feedback.php`)
```php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (empty($data['name']) || empty($data['email']) || 
            empty($data['rating']) || empty($data['feedback'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }
        
        // Sanitize and validate
        $name = htmlspecialchars(trim($data['name']));
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $rating = intval($data['rating']);
        $orderNumber = htmlspecialchars(trim($data['orderNumber'] ?? ''));
        $feedback = htmlspecialchars(trim($data['feedback']));
        
        if (!$email || $rating < 1 || $rating > 5) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data']);
            exit;
        }
        
        // Insert into database
        $stmt = $pdo->prepare(
            "INSERT INTO feedbacks (name, email, rating, order_number, feedback) 
             VALUES (:name, :email, :rating, :order_number, :feedback)"
        );
        
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':rating' => $rating,
            ':order_number' => $orderNumber,
            ':feedback' => $feedback
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Feedback submitted successfully',
            'id' => $pdo->lastInsertId()
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
```

### 2. Get Feedbacks (`api/get-feedbacks.php`)
```php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get only approved feedbacks, ordered by most recent
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    
    $stmt = $pdo->prepare(
        "SELECT id, name, rating, feedback, created_at 
         FROM feedbacks 
         WHERE approved = TRUE 
         ORDER BY created_at DESC 
         LIMIT :limit OFFSET :offset"
    );
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format dates for display
    foreach ($feedbacks as &$feedback) {
        $date = new DateTime($feedback['created_at']);
        $now = new DateTime();
        $diff = $now->diff($date);
        
        if ($diff->days == 0) {
            $feedback['date'] = 'Today';
        } elseif ($diff->days == 1) {
            $feedback['date'] = '1 day ago';
        } elseif ($diff->days < 7) {
            $feedback['date'] = $diff->days . ' days ago';
        } elseif ($diff->days < 30) {
            $weeks = floor($diff->days / 7);
            $feedback['date'] = $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
        } else {
            $feedback['date'] = $date->format('M d, Y');
        }
        
        unset($feedback['created_at']);
    }
    
    echo json_encode([
        'success' => true,
        'feedbacks' => $feedbacks
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
```

## Frontend Integration Steps

### Update Feedback.jsx

1. **Replace the submit handler** (around line 42):
```javascript
const handleSubmit = async (e) => {
  e.preventDefault();
  
  try {
    const response = await fetch('http://your-domain.com/api/submit-feedback.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData)
    });
    
    const result = await response.json();
    
    if (result.success) {
      // Reset form
      setFormData({
        name: '',
        email: '',
        rating: 0,
        orderNumber: '',
        feedback: ''
      });
      
      alert('Thank you for your feedback! It will be reviewed before being published.');
      
      // Optionally refresh feedbacks list
      fetchFeedbacks();
    } else {
      alert('Error: ' + result.error);
    }
  } catch (error) {
    console.error('Error submitting feedback:', error);
    alert('Failed to submit feedback. Please try again.');
  }
};
```

2. **Add useEffect to fetch feedbacks** (add after state declarations):
```javascript
useEffect(() => {
  fetchFeedbacks();
}, []);

const fetchFeedbacks = async () => {
  try {
    const response = await fetch('http://your-domain.com/api/get-feedbacks.php?limit=20');
    const result = await response.json();
    
    if (result.success) {
      setFeedbacks(result.feedbacks);
    }
  } catch (error) {
    console.error('Error fetching feedbacks:', error);
  }
};
```

3. **Add import for useEffect**:
```javascript
import React, { useState, useEffect } from 'react';
```

## Security Considerations

1. **Input Validation**: Always validate and sanitize inputs on both frontend and backend
2. **SQL Injection**: Use prepared statements (already implemented above)
3. **XSS Protection**: Use `htmlspecialchars()` to escape output
4. **Rate Limiting**: Consider adding rate limiting to prevent spam
5. **Email Validation**: Validate email format
6. **CAPTCHA**: Consider adding reCAPTCHA for additional spam protection
7. **Approval System**: Feedbacks require approval before being shown (approved field)

## Environment Setup

### Development
- Update API URLs in Feedback.jsx to point to your local PHP server
- Use XAMPP/WAMP/MAMP for local PHP development

### Production
- Update API URLs to production domain
- Ensure CORS headers are properly configured
- Use HTTPS for all API calls
- Store database credentials in environment variables, not in code

## Optional Enhancements

1. **Pagination**: Add pagination for feedback display
2. **Search/Filter**: Allow filtering by rating
3. **Admin Panel**: Create admin interface to approve/reject feedbacks
4. **Email Notifications**: Send email when feedback is submitted
5. **Edit/Delete**: Allow users to edit/delete their own feedback
6. **Reply System**: Allow business to reply to feedbacks
7. **Image Upload**: Allow users to upload photos with feedback
