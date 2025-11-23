<?php
session_start();
include_once '../includes/db.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check for To-Do ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: todos.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$todo_id = $_GET['id'];

$conn = connectDB();

// Delete to-do
$stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $todo_id, $user_id);
$stmt->execute();
<<<<<<< HEAD
$_SESSION['delete_success'] = true;
=======
>>>>>>> b2222f4bea245cb3b0c28215182074daee2b7964
$stmt->close();

$conn->close();

header("Location: todos.php");
exit();
?>
