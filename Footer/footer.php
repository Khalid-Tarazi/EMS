<link rel="stylesheet" href="Footer\footer.css">
<footer>
    <div class="footer-container">
        <div class="footer-section about">
            <h3>About</h3>
            <p>XYZ Events is your premier platform for managing and attending the best events in the Middle East.</p>
        </div>
        <div class="footer-section">
            <h3>Privacy</h3>
            <p>We value your privacy. For details on how we handle your information, please read our <a
                    href="Footer\privacy.php">Privacy Policy</a>.</p>
        </div>
        <div class="footer-section">
            <h3>Contact Us</h3>
            <p>If you have any questions, feel free to reach out to us at <br><a
                    href="mailto:contact@xyzevents.com">contact@xyzevents.com</a>.</p>
        </div>
        <div class="footer-section">
            <h3>Copyright</h3>
            <p>&copy; <span id="currentYear"></span> <br>XYZ Events. <br>All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="Footer\footer.js"></script>

<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>