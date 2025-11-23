<?php
// Common footer for Notecore pages
?>
        </div> <?php // Close .content-wrapper from header.php ?>
    </main>

    <footer class="bg-dark-custom text-white text-center py-3 mt-auto">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Notecore. All rights reserved.</p>
        </div>
    </footer>

    <!-- Success Modal -->
    <div id="success-modal-overlay" class="modal-overlay">
        <div id="success-modal" class="modal">
            <div class="modal-content">
                <p id="success-modal-message"></p>
            </div>
            <div class="modal-footer">
                <button id="success-modal-done" class="btn btn-primary">Done</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>js/script.js"></script>
</body>
</html>
