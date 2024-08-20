<?php
function checkLoggedInUser()
{
    if (isset($_SESSION['user_id'])) {
        redirectToDashboard();
    }

    if (isset($_COOKIE['remember_user'])) {
        $authToken = $_COOKIE['remember_user'];
        $user = getUserByAuthToken($authToken);

        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];
            redirectToDashboard();
        }
    }
}


function redirectToDashboard(){
    header('Location: http://localhost/bd-management/task-management.php');
    exit();
}

function getUserByAuthToken($authToken)
{
    global $conn;

    $stmt = $conn->prepare("SELECT user_id, user_type FROM users WHERE auth_token = ?");
    $stmt->bind_param("s", $authToken);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    return $user;
}

checkLoggedInUser();