<?php
include 'db_connection.php'; // Include the database connection

$task_id = $_GET['id'];
$query = "SELECT * FROM tasks WHERE task_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    die("Task not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container" id="edit_task_container">
        <h1>Edit Task</h1>
        <form id="editTaskForm" method="POST">
            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['task_id']); ?>">
            <div class="mb-3">
                <label for="task_title" class="form-label">Task Title</label>
                <input type="text" class="form-control" id="task_title" name="task_title" value="<?php echo htmlspecialchars($task['task_title']); ?>" required>
            </div>
            <!-- Add other form fields similarly -->
            <div class="mb-3">
                <label for="platform" class="form-label">Platform</label>
                <select name="platform" id="platform" class="form-select">
                    <option value="Instagram">Instagram</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Twitter">X (Twitter)</option>
                    <option value="Linkedin">LinkedIn</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">status</label>
                <select name="status" id="status" class="form-select">
                    <option value="Pending">Pending</option>
                    <option value="In-progress">In Progress</option>
                    <option value="Done">Done</option>
                    <option value="Hold">Hold</option>
                </select>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <div class="flex-fill me-2">
                    <label for="due_date" class="form-label">Due Date</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>">
                </div>
                <div class="flex-fill ms-2">
                    <label for="uploading_date" class="form-label">Uploading Date</label>
                    <input type="date" class="form-control" id="uploading_date" name="uploading_date" value="<?php echo htmlspecialchars($task['uploading_date']); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label for="created_by" class="form-label">Created By</label>
                <select name="created_by" id="created_by" class="form-select">
                    <option default disabled value="">--select--</option>
                    <?php
                    $users = "SELECT * FROM `users` WHERE user_type = 'Admin'  ORDER BY `username` ASC";
                    $result_user = mysqli_query($conn, $users);
                    if (!$result_user) {
                        die("Query failed: " . mysqli_error($conn));
                    }
                    while ($row_select = mysqli_fetch_assoc($result_user)) {
                        $selected = ($task['created_by'] == $row_select['user_id']) ? 'selected' : '';
                        echo "<option value='{$row_select['user_id']}' $selected>{$row_select['username']}</option>";
                    }
                    ?>
                </select>

            </div>
            <div class="mb-3">

                <label for="content_type" class="form-label">Content Type</label>
                <select name="content_type" id="content_type" class="form-select">
                    <option value="" selected>--select--</option>
                    <option value="Post" <?php echo ($task['content_type'] == 'Post') ? 'selected' : ''; ?>>Post</option>
                    <option value="Reel" <?php echo ($task['content_type'] == 'Reel') ? 'selected' : ''; ?>>Reel</option>
                    <option value="Image-story" <?php echo ($task['content_type'] == 'Image-story') ? 'selected' : ''; ?>>Image Story</option>
                    <option value="Carousel" <?php echo ($task['content_type'] == 'Carousel') ? 'selected' : ''; ?>>Carousel</option>
                    <option value="Video-story" <?php echo ($task['content_type'] == 'Video-story') ? 'selected' : ''; ?>>Video Story</option>
                    <option value="Polls" <?php echo ($task['content_type'] == 'Polls') ? 'selected' : ''; ?>>Polls</option>
                    <option value="Articles" <?php echo ($task['content_type'] == 'Articles') ? 'selected' : ''; ?>>Articles</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="content_size" class="form-label">Content Size</label>
                <input type="number" class="form-control" id="content_size" name="content_size" value="<?php echo htmlspecialchars($task['content_size']); ?>">
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Company</label>
                <select name="company" id="company" class="form-select" value="<?php echo htmlspecialchars($task['company']); ?>">
                    <option value="" selected>--select--</option>
                    <?php
                    $companies = "SELECT * FROM `companies`";
                    $result_companies = mysqli_query($conn, $companies);
                    while ($row_select = mysqli_fetch_assoc($result_companies)) {
                        $selected = ($task['company'] == $row_select['company_id']) ? 'selected' : '';
                        echo "<option value='{$row_select['company_id']}' $selected>{$row_select['company_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="assign_to" class="form-label">Assign to</label>
                <select name="assign_to" id="assign_to" class="form-select">
                    <option value="" disabled selected>Select</option>
                    <?php
                    $users = "SELECT * FROM `users` WHERE user_type = 'Artist' OR user_type = 'Production Head' ORDER BY `username` ASC";
                    $result_user = mysqli_query($conn, $users);
                    while ($row_select = mysqli_fetch_assoc($result_user)) {
                        $selected = ($task['assign_to'] == $row_select['user_id']) ? 'selected' : '';
                        echo "<option value='{$row_select['user_id']}' $selected>{$row_select['username']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $('#editTaskForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log(formData);

            $.ajax({
                url: './actions/update_task.php',
                type: 'POST',
                data: $(this).serialize(), // Ensure form data is being serialized
                success: function(response) {
                    alert('Task updated successfully!');
                    window.location.href = 'dashboard.php'; // Redirect back to dashboard
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        });
    </script>
</body>

</html>