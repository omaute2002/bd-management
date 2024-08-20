<?php
include 'db_connection.php'; // Include the database connection

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

echo "<pre>";
print_r($_POST);
echo "</pre>";
    

try {
    // Retrieve POST data
    $task_title = isset($_POST['task_title']) ? $_POST['task_title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '';
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : '';
    $platform = isset($_POST['platform']) ? $_POST['platform'] : '';
    $uploading_date = isset($_POST['uploading_date']) ? $_POST['uploading_date'] : '';
    $content_type = isset($_POST['content_type']) ? $_POST['content_type'] : '';
    $content_size = isset($_POST['content_size']) ? $_POST['content_size'] : '';
    $company = isset($_POST['company']) ? $_POST['company'] : '';
    $assign_to = isset($_POST['assign_to']) ? $_POST['assign_to'] : '';
    $task_id = isset($_POST['task_id']) ? $_POST['task_id'] : '';

    // Ensure task_id is not empty
    if (empty($task_id)) {
        throw new Exception("Task ID is missing.");
    }

    // Construct SQL query
    $query = "UPDATE tasks SET 
        task_title = '$task_title', 
        description = '$description', 
        status = '$status', 
        due_date = '$due_date', 
        created_by = '$created_by', 
        platform = '$platform', 
        uploading_date = '$uploading_date', 
        content_type = '$content_type', 
        content_size = '$content_size', 
        company = '$company', 
        assign_to = '$assign_to' 
        WHERE task_id = $task_id";

    // Debugging: Print the query to check for issues
    echo "SQL Query: " . htmlspecialchars($query) . "<br>";

    // Execute query
    if (mysqli_query($conn, $query)) {
        echo "Task updated successfully";
    } else {
        throw new Exception("Query failed: " . mysqli_error($conn));
    }

    // Close connection
    mysqli_close($conn);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
