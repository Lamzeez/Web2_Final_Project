<?php
session_start();
include_once '../includes/db.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check for Note ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: notes.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$note_id = $_GET['id'];

$conn = connectDB();

// Delete note
$stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $note_id, $user_id);
$stmt->execute();
$_SESSION['delete_success'] = true;
$stmt->close();

$conn->close();

header("Location: notes.php");
exit();
?>
