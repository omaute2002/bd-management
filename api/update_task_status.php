<?php
require '../db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation
    $task_id = intval($_POST['task_id']);
    $status = trim($_POST['status']);

    // Check if required fields are not empty
    if (empty($task_id) || empty($status)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Prepare and bind statement
    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
    $stmt->bind_param("si", $status, $task_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Task status updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update task status']);
    }

    $stmt->close();
    $conn->close();
}
?>
