<?php
require '../db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Input Validation
    $company_id = intval($_GET['company_id']);
    $company_user_id = intval($_GET['company_user_id']);

    // Check if required fields are not empty
    if (empty($company_id) || empty($company_user_id)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Prepare and execute statement
    $stmt = $conn->prepare("
        SELECT t.task_id, t.task_title, t.description, t.status, t.due_date, t.platform, t.uploading_date, t.content_type, t.content_size, t.company
        FROM tasks t
        JOIN task_assignments ta ON t.task_id = ta.task_id
        WHERE ta.company_id = ? AND ta.company_user_id = ?
    ");
    $stmt->bind_param("ii", $company_id, $company_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    $stmt->close();
    $conn->close();

    http_response_code(200);
    echo json_encode($tasks);
}
?>
