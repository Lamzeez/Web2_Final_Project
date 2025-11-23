<?php
include_once '../includes/header.php';
include_once '../includes/db.php';
include_once '../includes/functions.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = connectDB();

// Check for To-Do ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: todos.php");
    exit();
}
$todo_id = $_GET['id'];

// Handle Update To-Do
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_todo'])) {
    $task = sanitize_input($_POST['task']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

    if (!empty($task)) {
        $stmt = $conn->prepare("UPDATE todos SET task = ?, due_date = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $task, $due_date, $todo_id, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: todos.php");
        exit();
    }
}

// Fetch To-Do
$stmt = $conn->prepare("SELECT task, due_date FROM todos WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $todo_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $todo = $result->fetch_assoc();
} else {
    header("Location: todos.php");
    exit();
}
$stmt->close();
$conn->close();
?>

<h2>Edit To-Do</h2>

<div class="todo-editor">
    <form action="edit_todo.php?id=<?php echo $todo_id; ?>" method="POST">
        <div class="form-group">
            <label for="task">Task:</label>
            <input type="text" id="task" name="task" value="<?php echo htmlspecialchars($todo['task']); ?>" required>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date (Optional):</label>
            <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($todo['due_date']); ?>">
        </div>
        <button type="submit" name="update_todo" class="btn btn-primary">Update To-Do</button>
        <a href="todos.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
include_once '../includes/footer.php';
?>
