<?php
include 'db_connection.php'; // Include the database connection

// $pending_tasks_query = "SELECT COUNT(*) AS pending_count FROM tasks WHERE status = 'Pending'";
// $pending_tasks_result = mysqli_query($conn, $pending_tasks_query);

// if ($pending_tasks_result) {
//     $pending_tasks_row = mysqli_fetch_assoc($pending_tasks_result);
//     $pending_tasks_count = $pending_tasks_row['pending_count'];
// } else {
//     die("Query failed: " . mysqli_error($conn));
// }

// $in_progress_tasks_query = "SELECT COUNT(*) AS in_progress_count FROM tasks WHERE status = 'In Progress'";
// $in_progress_tasks_result = mysqli_query($conn, $in_progress_tasks_query);

// if ($in_progress_tasks_result) {
//     $in_progress_tasks_row = mysqli_fetch_assoc($in_progress_tasks_result);
//     $in_progress_tasks_count = $in_progress_tasks_row['in_progress_count'];
// } else {
//     die("Query failed: " . mysqli_error($conn));
// }

// // Fetch count of Completed tasks
// $completed_tasks_query = "SELECT COUNT(*) AS completed_count FROM tasks WHERE status = 'Completed'";
// $completed_tasks_result = mysqli_query($conn, $completed_tasks_query);

// if ($completed_tasks_result) {
//     $completed_tasks_row = mysqli_fetch_assoc($completed_tasks_result);
//     $completed_tasks_count = $completed_tasks_row['completed_count'];
// } else {
//     die("Query failed: " . mysqli_error($conn));
// }

// Fetch counts of tasks by status for each company
$tasks_by_company_query = "
    SELECT 
        c.company_name, 
        SUM(CASE WHEN t.status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
        SUM(CASE WHEN t.status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress_count,
        SUM(CASE WHEN t.status = 'Completed' THEN 1 ELSE 0 END) AS completed_count
    FROM 
        tasks t
    LEFT JOIN 
        companies c ON t.company = c.company_id
    GROUP BY 
        c.company_id, c.company_name
";

$tasks_by_company_result = mysqli_query($conn, $tasks_by_company_query);

if (!$tasks_by_company_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Query to fetch tasks along with company names
$query = "
    SELECT 
        t.*, 
        c.company_name, 
        u.username AS assign_to 
    FROM 
        tasks t
    LEFT JOIN 
        companies c ON t.company = c.company_id
    LEFT JOIN 
        task_assignments ta ON t.task_id = ta.task_id
    LEFT JOIN 
        users u ON ta.company_user_id = u.user_id
";
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
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<style>
    /* Define the colors for each status */
    .status-select.pending {
        background-color: #ffcccc;
        /* Light red */
        color: #a94442;
        /* Darker red */
    }

    .status-select.in-progress {
        background-color: #fff3cd;
        /* Light yellow */
        color: #856404;
        /* Darker yellow */
    }

    .status-select.completed {
        background-color: #d4edda;
        /* Light green */
        color: #155724;
        /* Darker green */
    }

    /* Optional: Additional styling to remove the default arrow on the select element */
    .status-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 5px;
    }

    .card-body {
        background-color: #71717a;
    }

    .card-header {
        background-color: #71717a;
    }

    .card-text {
        color: white;
    }

    .card-title {
        color: white;
    }

    .card-container {
        display: flex;
        /* Align cards horizontally */
        flex-wrap: wrap;
        /* Allow cards to wrap to the next line if necessary */
        gap: 16px;
        /* Space between cards */
        justify-content: center;
        /* Center the cards horizontally */
    }

    .card {
        flex: 1 1 calc(30% - 26px);
        /* Adjust the width of the cards and account for gaps */
        max-width: calc(30% - 26px);
        /* Max width to ensure proper wrapping */
        box-sizing: border-box;
        /* Include padding and border in the element's total width and height */
    }

    .card-body {
        display: flex;
        /* Use flexbox inside card body */
        flex-direction: column;
        /* Arrange children vertically */
        align-items: center;
        /* Center children horizontally */
        text-align: center;
        /* Center text inside the card body */
    }
</style>

<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container-fluid p-0">
        <div class="bg-light">
            <h1 class="p-4" id="title">Tasks Table</h1>

            <div class="p-4 card-container">
                <?php while ($row = mysqli_fetch_assoc($tasks_by_company_result)): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <?php echo htmlspecialchars($row['company_name']); ?>
                        </div>
                        <div class="card-body flex">
                            <h5 class="card-title">Task Counts</h5>
                            <p class="card-text">Pending: <?php echo $row['pending_count']; ?></p>
                            <p class="card-text">In Progress: <?php echo $row['in_progress_count']; ?></p>
                            <p class="card-text">Completed: <?php echo $row['completed_count']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

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
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['task_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['task_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>
                                    <select class="status-select form-select"
                                        data-task-id="<?php echo htmlspecialchars($row['task_id']); ?>"
                                        data-initial-status="<?php echo htmlspecialchars($row['status']); ?>">
                                        <option value="Pending" <?php echo ($row['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="In Progress" <?php echo ($row['status'] === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="Completed" <?php echo ($row['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </td>
                                <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['platform']); ?></td>
                                <td><?php echo htmlspecialchars($row['uploading_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['content_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['content_size']); ?></td>
                                <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['assign_to']); ?></td>
                                <td>
                                    <!-- Action Buttons -->
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTaskModal" data-task-id="<?php echo htmlspecialchars($row['task_id']); ?>">View</button>
                                    <a href="edit_task.php?task_id=<?php echo htmlspecialchars($row['task_id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <!-- <button class="btn btn-danger btn-sm delete-task" data-task-id="<?php echo htmlspecialchars($row['task_id']); ?>">Delete</button> -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include 'components/add_task_modal.php'; ?>
        <?php include 'components/view_task_modal.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Handle task submission
            $('#submitTask').on('click', function() {
                $.ajax({
                    url: './api/create_task.php',
                    type: 'POST',
                    data: $('#taskForm').serialize(),
                    success: function(response) {
                        alert('Task added successfully!');
                        $('#taskModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });

            // Handle status change
            function applyStatusColor(selectElement) {
                var status = selectElement.val();
                selectElement.removeClass('pending in-progress completed');
                if (status === 'Pending') {
                    selectElement.addClass('pending');
                } else if (status === 'In Progress') {
                    selectElement.addClass('in-progress');
                } else if (status === 'Completed') {
                    selectElement.addClass('completed');
                }
            }

            // Apply colors on page load based on initial status
            $('.status-select').each(function() {
                applyStatusColor($(this));
            });

            // Update the color when status changes
            $('.status-select').on('change', function() {
                var select = $(this);
                var task_id = select.data('task-id');
                var status = select.val();

                // Apply the color immediately
                applyStatusColor(select);

                // AJAX call to update status in the database
                $.ajax({
                    url: './api/update_task_status.php',
                    type: 'POST',
                    data: {
                        task_id: task_id,
                        status: status
                    },
                    success: function(response) {
                        alert('Task status updated successfully!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });

            // View task details
            $('#viewTaskModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var taskId = button.data('task-id'); // Extract info from data-* attributes

                // Fetch task details using AJAX
                $.ajax({
                    url: './api/get_task_details.php',
                    type: 'POST',
                    data: {
                        task_id: taskId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $('#modal-task-id').text(data.task_id);
                            $('#modal-task-title').text(data.task_title);
                            $('#modal-description').text(data.description);
                            $('#modal-status').text(data.status);
                            $('#modal-due-date').text(data.due_date);
                            $('#modal-created-by').text(data.created_by);
                            $('#modal-created-at').text(data.created_at);
                            $('#modal-updated-at').text(data.updated_at);
                            $('#modal-platform').text(data.platform);
                            $('#modal-uploading-date').text(data.uploading_date);
                            $('#modal-content-type').text(data.content_type);
                            $('#modal-content-size').text(data.content_size);
                            $('#modal-company').text(data.company_name);
                            $('#modal-assign-to').text(data.assign_to); // Make sure to add this line if needed
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching task details:', error);
                    }
                });
            });

            // Delete task
            $('.delete-task').on('click', function() {
                var taskId = $(this).data('task-id');
                if (confirm('Are you sure you want to delete this task?')) {
                    $.ajax({
                        url: './api/delete_task.php',
                        type: 'POST',
                        data: {
                            task_id: taskId
                        },
                        success: function(response) {
                            alert('Task deleted successfully!');
                            location.reload();
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