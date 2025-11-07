<?php
include_once '../includes/header.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="text-center mb-5">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p class="lead">What would you like to do today?</p>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card feature-card h-100 text-center">
            <div class="card-body">
                <h3 class="card-title"><i class="bi bi-journal-text me-2"></i>Effortless Notes</h3>
                <p class="card-text">Quickly jot down your thoughts and ideas.</p>
                <a href="notes.php" class="btn btn-primary">Go to Notes</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card feature-card h-100 text-center">
            <div class="card-body">
                <h3 class="card-title"><i class="bi bi-check2-square me-2"></i>Gentle To-Dos</h3>
                <p class="card-text">Manage your tasks and stay organized.</p>
                <a href="todos.php" class="btn btn-primary">Go to To-Dos</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card feature-card h-100 text-center">
            <div class="card-body">
                <h3 class="card-title"><i class="bi bi-calendar-heart me-2"></i>Calendar Peace</h3>
                <p class="card-text">Visualize your schedule, notes, and tasks.</p>
                <a href="calendar.php" class="btn btn-primary">Go to Calendar</a>
            </div>
        </div>
    </div>
</div>

<?php
include_once '../includes/footer.php';
?>
