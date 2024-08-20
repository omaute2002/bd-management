<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_management";

// Create connection
try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO tasks (task_title, description, status, due_date, created_by, platform, uploading_date, content_type, content_size, company, assign_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssssssssss", $task_title, $description, $status, $due_date, $created_by, $platform, $uploading_date, $content_type, $content_size, $company, $assign_to);

    // Set parameters and execute
    $task_title = $_POST['task_title']; //done
    $description = $_POST['description']; //done
    $status = $_POST['status']; // done
    $due_date = $_POST['due_date']; //done
    $created_by = $_POST['created_by']; //done
    $platform = $_POST['platform']; //done
    $uploading_date = $_POST['uploading_date'];
    $content_type = $_POST['content_type'];
    $content_size = $_POST['content_size'];
    $company = $_POST['company'];
    $assign_to = $_POST['assign_to'];

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $task_id = $stmt->insert_id;

    // Prepare statement for inserting a task assignment
    $stmt = $conn->prepare("INSERT INTO task_assignments (task_id, company_id, company_user_id, assigned_at) VALUES (?, ?, ?, NOW())");
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $company_id = $company; // Assuming company_id is the same as company for this example
    $company_user_id = $assign_to;
    $stmt->bind_param("iis", $task_id, $company_id, $company_user_id);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    echo "New task added and assigned successfully";



    // Close statement and connection
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>