<?php
include 'db_connection.php'; // Include the database connection

// Check if task_id is provided in the URL
if (!isset($_GET['task_id'])) {
    die('Task ID is required.');
}

$task_id = $_GET['task_id'];

// Fetch the task details
$task_query = "
    SELECT 
        t.*, 
        ta.company_user_id, 
        u.username AS assigned_user_name 
    FROM 
        tasks t 
    LEFT JOIN 
        task_assignments ta ON t.task_id = ta.task_id 
    LEFT JOIN 
        users u ON ta.company_user_id = u.user_id 
    WHERE 
        t.task_id = ?
";
$stmt = mysqli_prepare($conn, $task_query);
mysqli_stmt_bind_param($stmt, 'i', $task_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die('Task not found.');
}

$task = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'components/navbar.php'; ?>

    <div class="container mt-4">
        <h1>Edit Task</h1>
        <form  id="editTaskForm" method="post">
        <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['task_id']); ?>">

            <div class="mb-3">
                <label for="task_title" class="form-label">Task Title</label>
                <input type="text" class="form-control" id="task_title" name="task_title" value="<?php echo htmlspecialchars($task['task_title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Pending" <?php echo ($task['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo ($task['status'] === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo ($task['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="uploading_date" class="form-label">Uploading Date</label>
                <input type="date" class="form-control" id="uploading_date" name="uploading_date" value="<?php echo htmlspecialchars($task['uploading_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="created_by" class="form-label">Created By</label>
                <select name="created_by" id="created_by" class="form-select">
                            <option default disabled value="">--select--</option>
                            <?php
                            $users = "SELECT * FROM `users` WHERE user_type = 'Admin'  ORDER BY `username` ASC";
                            $result_user = mysqli_query($conn, $users);
                            while ($row_select = mysqli_fetch_assoc($result_user)) {
                                echo "<option default value='{$row_select['user_id']}'>{$row_select['username']}</option>";
                            }
                            ?>
                        </select>
            </div>
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
                <label for="content_type" class="form-label">Content Type</label>
                <select name="content_type" id="content_type" class="form-select">
                    <option value="" selected>--select--</option>
                    <option value="Post">Post</option>
                    <option value="Reel">Reel</option>
                    <option value="Image-story">Image Story</option>
                    <option value="Carousel">Carousel</option>
                    <option value="Video-story">Video Story</option>
                    <option value="Polls">Polls</option>
                    <option value="Articles">Articles</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="content_size" class="form-label">Content Size</label>
                <input type="text" class="form-control" id="content_size" name="content_size" value="<?php echo htmlspecialchars($task['content_size']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Company</label>
                <select name="company" id="company" class="form-select">
                    <option value="" selected>--select--</option>
                    <?php
                    $companies = "SELECT * FROM `companies`";
                    $result_companies = mysqli_query($conn, $companies);
                    while ($row_select = mysqli_fetch_assoc($result_companies)) {
                        echo "<option value='{$row_select['company_id']}'>{$row_select['company_name']}</option>";
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
                        echo "<option value='{$row_select['user_id']}'>{$row_select['username']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editTaskForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: './api/update_task.php',
                    type: 'POST',
                    data: $(this).serialize(), // Serialize the form data
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Task updated successfully');
                            window.location.href = 'dashboard.php'; // Redirect to the dashboard
                        } else {
                            alert('Failed to update task: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the task.');
                    }
                });
            });
        });
    </script>
</body>

</html>