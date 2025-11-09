    </main>
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; <?php echo date('Y'); ?> LeckerHaus. All rights reserved.</p>
            <p>Authentic German Cuisine | Delivered Fresh Daily</p>
        </div>
    </footer>
    <?php if (isset($additionalJS)): ?>
        <script src="<?php echo SITE_URL; ?>/js/<?php echo $additionalJS; ?>"></script>
    <?php endif; ?>
</body>
</html>
