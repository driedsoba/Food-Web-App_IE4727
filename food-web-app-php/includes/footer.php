    </main>
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; <?php echo date('Y'); ?> LeckerHaus. All rights reserved.</p>
            <p>General Enquiries: info@leckerhaus.com | Phone: +65 6748 9380</p>
            <p>Address: 50 Nanyang Ave, Singapore 639798</p>
        </div>
    </footer>
    <?php if (isset($additionalJS)): ?>
        <script src="<?php echo SITE_URL; ?>/js/<?php echo $additionalJS; ?>"></script>
    <?php endif; ?>
</body>
</html>
