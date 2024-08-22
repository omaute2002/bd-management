<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db_connection.php';

if (isset($_POST['task_id'])) {
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);

    $query = "
        SELECT 
            t.task_id,
            t.task_title,
            t.description,
            t.status,
            t.due_date,
            t.created_by,
            t.created_at,
            t.updated_at,
            t.platform,
            t.uploading_date,
            t.content_type,
            t.content_size,
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
        WHERE 
            t.task_id = '$task_id'
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $task = mysqli_fetch_assoc($result);
        echo json_encode($task);
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    echo 'No task ID provided';
}
?>
