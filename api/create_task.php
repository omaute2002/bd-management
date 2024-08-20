<?php
require '../db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation
    $task_title = trim($_POST['task_title']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);
    $due_date = trim($_POST['due_date']);
    $created_by = intval($_POST['created_by']);
    $platform = trim($_POST['platform']);
    $uploading_date = trim($_POST['uploading_date']);
    $content_type = trim($_POST['content_type']);
    $content_size = trim($_POST['content_size']);
    $company = intval($_POST['company']);
    $assign_to = intval($_POST['assign_to']); // Ensure this field is added

    // Check if required fields are not empty
    if (empty($task_title) || empty($created_by) || empty($company) || empty($assign_to)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare and execute task insertion
        $stmt = $conn->prepare("INSERT INTO tasks (task_title, description, status, due_date, created_by, platform, uploading_date, content_type, content_size, company) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisssss", $task_title, $description, $status, $due_date, $created_by, $platform, $uploading_date, $content_type, $content_size, $company);

        if (!$stmt->execute()) {
            throw new Exception('Failed to create task');
        }

        $task_id = $stmt->insert_id;
        $stmt->close();

        // Insert into task_assignments table
        $stmt = $conn->prepare("INSERT INTO task_assignments (task_id, company_id, company_user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $task_id, $company, $assign_to);

        if (!$stmt->execute()) {
            throw new Exception('Failed to assign task');
        }

        $stmt->close();

        // Commit transaction
        $conn->commit();

        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Task created and assigned successfully', 'task_id' => $task_id]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();

        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
}
?>
