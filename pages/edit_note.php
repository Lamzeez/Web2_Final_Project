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
        if ($stmt->execute()) {
            $_SESSION['note_update_success'] = true;
            header("Location: notes.php");
            exit();
        }
        $stmt->close();
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

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Edit Note</h2>
                <form action="edit_note.php?id=<?php echo $note_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10"><?php echo htmlspecialchars($note['content']); ?></textarea>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" name="update_note" class="btn btn-primary">Update Note</button>
                        <a href="notes.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once '../includes/footer.php';
?>
