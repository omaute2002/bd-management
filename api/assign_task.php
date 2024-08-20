<?php
require '../db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input Validation
    $task_id = intval($_POST['task_id']);
    $company_id = intval($_POST['company_id']);
    $company_user_id = intval($_POST['company_user_id']);

    // Check if required fields are not empty
    if (empty($task_id) || empty($company_id) || empty($company_user_id)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Prepare and bind statement
    $stmt = $conn->prepare("INSERT INTO task_assignments (task_id, company_id, company_user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $task_id, $company_id, $company_user_id);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Task assigned successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to assign task']);
    }

    $stmt->close();
    $conn->close();
}
?>
