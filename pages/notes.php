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

// Handle Create Note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_note'])) {
    $title = sanitize_input($_POST['title']);
    $content = sanitize_input($_POST['content']);

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);
        if ($stmt->execute()) {
            $_SESSION['note_create_success'] = true;
        }
        $stmt->close();
        header("Location: notes.php");
        exit();
    }
}

// Fetch Notes
$notes = [];
$result = $conn->query("SELECT id, title, content, updated_at FROM notes WHERE user_id = $user_id ORDER BY updated_at DESC");
if ($result) {
    $notes = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>
<h2 class="mb-4">Effortless Notes</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title">Create a New Note</h3>
                <form action="notes.php" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="create_note" class="btn btn-primary">Create Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h3>Your Notes</h3>
        <div class="notes-list">
        <?php if (empty($notes)): ?>
            <div class="alert alert-info" role="alert">
                You haven't created any notes yet. Get started by creating one!
            </div>
        <?php else: ?>
            <?php foreach ($notes as $note): ?>
                <div class="note-item">
                    <h4><?php echo htmlspecialchars($note['title']); ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                    <div class="note-footer">
                        <small>Last updated: <?php echo date("M j, Y, g:i a", strtotime($note['updated_at'])); ?></small>
                        <div>
                            <a href="edit_note.php?id=<?php echo $note['id']; ?>" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-pencil"></i> Edit</a>
                            <a href="delete_note.php?id=<?php echo $note['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this note?');"><i class="bi bi-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php
include_once '../includes/footer.php';
?>

<?php if (isset($_SESSION['note_update_success']) && $_SESSION['note_update_success']): ?>
<script>
    alert('Note updated successfully!');
</script>
<?php unset($_SESSION['note_update_success']); ?>
<?php elseif (isset($_SESSION['note_create_success']) && $_SESSION['note_create_success']): ?>
<script>
    alert('Note created successfully!');
</script>
<?php unset($_SESSION['note_create_success']); ?>
<?php elseif (isset($_SESSION['delete_success']) && $_SESSION['delete_success']): ?>
<script>
    alert('Note deleted successfully!');
</script>
<?php unset($_SESSION['delete_success']); ?>
<?php endif; ?>
