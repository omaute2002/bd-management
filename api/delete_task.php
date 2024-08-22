<?php
include 'db_connection.php';

if (isset($_POST['task_id'])) {
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);

    $delete_query = "DELETE FROM tasks WHERE task_id = '$task_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo 'Task deleted successfully';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    echo 'No task ID provided';
}
?>
