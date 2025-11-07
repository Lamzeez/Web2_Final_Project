<?php
// Landing Page for NoteCore
include_once 'includes/header.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: pages/home.php");
    exit();
}
?>

<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold">Organize Your Thoughts, Calm Your Mind</h1>
            <p class="lead my-4">NoteCore helps you capture ideas, manage tasks, and plan your days with peace.</p>
            <a href="pages/register.php" class="btn btn-primary btn-lg">Get Started - It's Free!</a>
        </div>
    </div>

    <div class="row mt-5 pt-5">
        <div class="col-12">
            <h2 class="mb-4">Features Designed for You</h2>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card feature-card h-100">
                <div class="card-body">
                    <h3 class="card-title"><i class="bi bi-journal-text me-2"></i>Effortless Notes</h3>
                    <p class="card-text">Jot down thoughts, ideas, and important information with a simple, intuitive interface.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card feature-card h-100">
                <div class="card-body">
                    <h3 class="card-title"><i class="bi bi-check2-square me-2"></i>Gentle To-Dos</h3>
                    <p class="card-text">Manage your tasks without stress. Prioritize, track, and complete your daily goals.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card feature-card h-100">
                <div class="card-body">
                    <h3 class="card-title"><i class="bi bi-calendar-heart me-2"></i>Calendar Peace</h3>
                    <p class="card-text">Visualize your schedule, notes, and to-dos in one calming calendar view.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'includes/footer.php';
?>
