<?php
require '../db_connection.php';

$response = array(); // Initialize a response array

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid email format.';
            echo json_encode($response);
            exit();
        }

        // Validate password length (e.g., minimum 6 characters)
        if (strlen($password) < 6) {
            $response['status'] = 'error';
            $response['message'] = 'Password must be at least 6 characters long.';
            echo json_encode($response);
            exit();
        }

        // Use a prepared statement to retrieve the user's information
        $stmt = $conn->prepare("SELECT user_id, password, status, user_type FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // Verify the password using password_hash or any other secure method
        if ($user && $user['password'] == $password && $user['status'] == 'Active') {
            // Password is correct and account is active
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];

            // Check if "Remember Me" is checked
            if (isset($_POST['remember_me']) && $_POST['remember_me'] == '1') {
                // Generate a unique token for the user
                $token = bin2hex(random_bytes(32));

                // Store the token in the database
                $stmt = $conn->prepare("UPDATE users SET auth_token = ? WHERE user_id = ?");
                $stmt->bind_param("si", $token, $user['user_id']);
                if ($stmt->execute()) {
                    // Set the token as a cookie with secure and HttpOnly flags
                    if (setcookie('remember_user', $token, time() + 3600 * 24 * 30, '/', '', true, true)) {
                        $response['status'] = 'success';
                        $response['redirectUrl'] = determineRedirectURL();
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'Cookie could not be set.';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Failed to update auth_token in the database: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['status'] = 'success';
                $response['redirectUrl'] = determineRedirectURL();
            }
        } else {
            // Set the login status to error
            $response['status'] = 'error';
            $response['message'] = 'Invalid email or password or account is not active.';
        }

        // Return the response as JSON
        echo json_encode($response);
        exit();
    }
}


// If someone tries to access this script directly, redirect to login page
header('Location: login.php');
exit();

function determineRedirectURL()
{
    // $dashboardUrls = [
    //     'Admin' => 'admin/index.php',
    //     'Artist' => 'artist/index.php',
    //     'Production Head' => 'productionHead/index.php',
    //     'Manager' => 'manager/index.php',
    //     'Accountant' => 'accountant/index.php'
    // ];


   
    // return isset($dashboardUrls[$userType]) ? $dashboardUrls[$userType] : 'default.php';
     // Redirect to the task management page regardless of user type
     return 'http://localhost/bd-management/dashboard.php';
}