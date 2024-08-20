<?php
include 'db_connection.php'; // Include the database connection
$query = "SELECT * FROM tasks"; // Modify the query as needed
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <link rel="stylesheet" href="/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container-fluid p-0">
        <div class="bg-light">
            <h1 class="p-4" id="title">Manage Task Table</h1>
            <div class="d-flex justify-content-end p-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal">Add Task</button>
            </div>

            <!-- Task Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Platform</th>
                            <th>Uploading Date</th>
                            <th>Content Type</th>
                            <th>Content Size</th>
                            <th>Company</th>
                            <th>Assign to</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['task_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['task_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['platform']); ?></td>
                                <td><?php echo htmlspecialchars($row['uploading_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['content_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['content_size']); ?></td>
                                <td><?php echo htmlspecialchars($row['company']); ?></td>
                                <td><?php echo htmlspecialchars($row['assign_to']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo htmlspecialchars($row['task_id']); ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo htmlspecialchars($row['task_id']); ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include 'components/add_task_modal.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Handle the edit button click
            $('.edit-btn').on('click', function() {
                var taskId = $(this).data('id');
                window.location.href = 'edit_task.php?id=' + taskId; // Redirect to edit page
            });

            // Handle the delete button click
            $('.delete-btn').on('click', function() {
                var taskId = $(this).data('id');
                if (confirm('Are you sure you want to delete this task?')) {
                    $.ajax({
                        url: 'delete_task.php',
                        type: 'POST',
                        data: {
                            id: taskId
                        },
                        success: function(response) {
                            alert('Task deleted successfully!');
                            location.reload(); // Refresh the page to reflect changes
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>