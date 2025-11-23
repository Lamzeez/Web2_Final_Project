<?php
session_start();
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
        if ($stmt->execute()) {
            $_SESSION['todo_update_success'] = true;
            header("Location: todos.php");
            exit();
        }
        $stmt->close();
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

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Edit To-Do</h2>
                <form action="edit_todo.php?id=<?php echo $todo_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="task" class="form-label">Task</label>
                        <input type="text" class="form-control" id="task" name="task" value="<?php echo htmlspecialchars($todo['task']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date (Optional)</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo htmlspecialchars($todo['due_date']); ?>">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" name="update_todo" class="btn btn-primary">Update To-Do</button>
                        <a href="todos.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once '../includes/footer.php';
?>
