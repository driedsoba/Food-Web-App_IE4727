        </div>
    </main>
    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo h(SITE_NAME); ?>. All rights reserved.</p>
        </div>
    </footer>
    <script src="<?php echo SITE_URL; ?>/js/validation.js"></script>
</body>
</html>
<?php
// Close database connection
if (isset($conn)) {
    $conn->close();
}
?>
