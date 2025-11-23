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

// Check for Note ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: notes.php");
    exit();
}
$note_id = $_GET['id'];

// Handle Update Note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_note'])) {
    $title = sanitize_input($_POST['title']);
    $content = sanitize_input($_POST['content']);

    if (!empty($title)) {
        $stmt = $conn->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $title, $content, $note_id, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: notes.php");
        exit();
    }
}

// Fetch Note
$stmt = $conn->prepare("SELECT title, content FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $note_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $note = $result->fetch_assoc();
} else {
    // Note not found or doesn't belong to user
    header("Location: notes.php");
    exit();
}
$stmt->close();
$conn->close();
?>

<h2>Edit Note</h2>

<div class="note-editor">
    <form action="edit_note.php?id=<?php echo $note_id; ?>" method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="10"><?php echo htmlspecialchars($note['content']); ?></textarea>
        </div>
        <button type="submit" name="update_note" class="btn btn-primary">Update Note</button>
        <a href="notes.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
include_once '../includes/footer.php';
?>
