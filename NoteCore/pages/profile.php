<?php
include_once '../includes/header.php';
include_once '../includes/db.php';
include_once '../includes/functions.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$conn = connectDB();
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

?>

<h2 class="mb-4">User Profile</h2>

<div class="card">
    <div class="card-body">
        <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Username</h5>
                    <p class="mb-1"><?php echo htmlspecialchars($user['username']); ?></p>
                </div>
                <button class="btn btn-outline-primary btn-sm">Edit</button>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Email</h5>
                    <p class="mb-1"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <button class="btn btn-outline-primary btn-sm">Edit</button>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Password</h5>
                    <p class="mb-1">********</p>
                </div>
                <button class="btn btn-outline-primary btn-sm">Change Password</button>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Account Status</h5>
                    <p class="mb-1">Active</p>
                </div>
                <button class="btn btn-outline-danger btn-sm">Deactivate Account</button>
            </div>
        </div>
    </div>
</div>

<?php
include_once '../includes/footer.php';
?>
