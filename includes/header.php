<?php
// Start session on all pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'config.php';

// Common header for Notecore pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteCore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom">
            <div class="container">
                <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">NoteCore</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/home.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/profile.php">Profile</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/login.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="py-5">
        <div class="content-wrapper">
