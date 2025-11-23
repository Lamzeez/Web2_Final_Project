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

// Handle Create To-Do
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_todo'])) {
    $task = sanitize_input($_POST['task']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO todos (user_id, task, due_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $task, $due_date);
        if ($stmt->execute()) {
            $_SESSION['todo_create_success'] = true;
        }
        $stmt->close();
        header("Location: todos.php");
        exit();
    }
}

// Handle Update To-Do Status
if (isset($_GET['toggle_status']) && is_numeric($_GET['toggle_status'])) {
    $todo_id = $_GET['toggle_status'];
    $stmt = $conn->prepare("UPDATE todos SET is_completed = !is_completed WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $todo_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: todos.php");
    exit();
}

// Fetch To-Dos
$todos = [];
$result = $conn->query("SELECT id, task, is_completed, due_date FROM todos WHERE user_id = $user_id ORDER BY is_completed ASC, due_date ASC, created_at DESC");
if ($result) {
    $todos = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
$modal_message = '';
if (isset($_SESSION['todo_update_success']) && $_SESSION['todo_update_success']) {
    $modal_message = 'To-Do updated successfully!';
    unset($_SESSION['todo_update_success']);
} elseif (isset($_SESSION['todo_create_success']) && $_SESSION['todo_create_success']) {
    $modal_message = 'To-Do created successfully!';
    unset($_SESSION['todo_create_success']);
} elseif (isset($_SESSION['delete_success']) && $_SESSION['delete_success']) {
    $modal_message = 'To-Do deleted successfully!';
    unset($_SESSION['delete_success']);
}
?>
<h2 class="mb-4">Gentle To-Dos</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title">Add a New To-Do</h3>
                <form action="todos.php" method="POST">
                    <div class="mb-3">
                        <label for="task" class="form-label">Task</label>
                        <input type="text" class="form-control" id="task" name="task" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date (Optional)</label>
                        <input type="date" class="form-control" id="due_date" name="due_date">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="create_todo" class="btn btn-primary">Add To-Do</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h3>Your To-Do List</h3>
        <?php if (empty($todos)): ?>
            <div class="alert alert-info" role="alert">
                You have no pending tasks. Add one to get started!
            </div>
        <?php else: ?>
            <div class="todos-list">
                <?php foreach ($todos as $todo): ?>
                    <div class="todo-item <?php echo $todo['is_completed'] ? 'completed' : ''; ?>">
                        <a href="todos.php?toggle_status=<?php echo $todo['id']; ?>" class="status-checkbox"></a>
                        <div class="todo-details">
                            <p><?php echo htmlspecialchars($todo['task']); ?></p>
                            <?php if ($todo['due_date']): ?>
                                <small>Due: <?php echo date("M j, Y", strtotime($todo['due_date'])); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="todo-actions">
                            <a href="edit_todo.php?id=<?php echo $todo['id']; ?>" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-pencil"></i></a>
                            <a href="delete_todo.php?id=<?php echo $todo['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this to-do?');"><i class="bi bi-trash"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include_once '../includes/footer.php';
?>

<script>
<?php if (!empty($modal_message)): ?>
document.addEventListener('DOMContentLoaded', function() {
    showSuccessModal('<?php echo $modal_message; ?>');
});
<?php endif; ?>
</script>
