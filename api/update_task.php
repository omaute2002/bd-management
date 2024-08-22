<?php
require '../db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation
    $task_id = intval($_POST['task_id']);
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
    if (empty($task_id) || empty($task_title) || empty($created_by) || empty($company) || empty($assign_to)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare and execute task update
        $stmt = $conn->prepare("UPDATE tasks SET task_title = ?, description = ?, status = ?, due_date = ?, created_by = ?, platform = ?, uploading_date = ?, content_type = ?, content_size = ?, company = ? WHERE task_id = ?");
        $stmt->bind_param("ssssisssssi", $task_title, $description, $status, $due_date, $created_by, $platform, $uploading_date, $content_type, $content_size, $company, $task_id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update task');
        }

        $stmt->close();

        // Update the task assignment
        $stmt = $conn->prepare("UPDATE task_assignments SET company_id = ?, company_user_id = ? WHERE task_id = ?");
        $stmt->bind_param("iii", $company, $assign_to, $task_id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update task assignment');
        }

        $stmt->close();

        // Commit transaction
        $conn->commit();

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Task updated and reassigned successfully']);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();

        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
}
?>
