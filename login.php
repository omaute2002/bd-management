<?php
require 'db_connection.php'
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Login</h3>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div id="errorMsg" class="text-danger mb-3" style="display: none;"></div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AJAX Script for handling login -->
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                var email = $('#email').val();
                var password = $('#password').val();
                console.log(email);
                console.log(password);
                $.ajax({
                    type: 'POST',
                    url: './middleware/login_process.php',
                    data: {
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        console.log('Server response:', response); // Debugging line
                        var result = JSON.parse(response);

                        if (result.status === 'success') {
                            window.location.href = 'dashboard.php';
                        } else {
                            $('#errorMsg').text(result.message).show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error); // Debugging line
                        $('#errorMsg').text('An error occurred. Please try again.').show();
                    }
                });
            });
        });
        
    </script>
</body>

</html>