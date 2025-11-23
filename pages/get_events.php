<?php
session_start();
header('Content-Type: application/json');

include_once '../includes/db.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]); // Return empty array if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = connectDB();

$events = [];

// Fetch Notes
$note_query = "SELECT id, title, created_at FROM notes WHERE user_id = ?";
$stmt = $conn->prepare($note_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => 'Note: ' . $row['title'],
        'start' => $row['created_at'],
        'allDay' => true,
        'color' => '#5a67d8', // Blue for notes
        'extendedProps' => [
            'type' => 'note',
            'id' => $row['id']
        ]
    ];
}
$stmt->close();

// Fetch To-Dos
$todo_query = "SELECT id, task, due_date FROM todos WHERE user_id = ? AND due_date IS NOT NULL";
$stmt = $conn->prepare($todo_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => 'To-Do: ' . $row['task'],
        'start' => $row['due_date'],
        'allDay' => true,
        'color' => '#38a169', // Green for to-dos
        'extendedProps' => [
            'type' => 'todo',
            'id' => $row['id']
        ]
    ];
}
$stmt->close();

$conn->close();

echo json_encode($events);
?>
