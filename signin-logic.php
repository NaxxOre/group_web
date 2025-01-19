<?php
require "config/database.php";

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['submit'])) {
    // Get input values
    $username_email = filter_var($_POST['username_email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validate input
    if (!$username_email) {
        $_SESSION['signin'] = 'Username or Email is Incorrect';
    } elseif (!$password) {
        $_SESSION['signin'] = 'Password required';
    } else {
        // Fetch user from the database
        $fetch_user_query = "SELECT * FROM users WHERE username = '$username_email' OR email = '$username_email'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);

        if (mysqli_num_rows($fetch_user_result) == 1) {
            // Convert the record into an associative array
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];

            // Compare form password with database password
            if (password_verify($password, $db_password)) {
                // Set session for access control
                $_SESSION['user-id'] = $user_record['id'];
                $_SESSION['signin-success'] = "User successfully logged in";

                // Set session based on user type and redirect accordingly
                if ($user_record['user_type'] == 1) {
                    $_SESSION['user_is_admin'] = true;
                    header('location: ' . ROOT_URL . 'admin/index.php');
                    exit();
                } elseif ($user_record['user_type'] == 2) {
                    $_SESSION['user_is_business'] = true;
                    header('location: ' . ROOT_URL . 'business/index.php');
                    exit();
                } elseif ($user_record['user_type'] == 3) {
                    $_SESSION['user_is_seller'] = true;
                    header('location: ' . ROOT_URL . 'seller/index.php');
                    exit();
                } elseif ($user_record['user_type'] == 0) {
                    $_SESSION['user_groot'] = true;
                    header('location: ' . ROOT_URL . 'client/index.php');
                    exit();
                }
            } else {
                $_SESSION['signin'] = "Incorrect password";
            }
        } else {
            $_SESSION['signin'] = "User not found";
        }
    }

    // If any issue, redirect back to the signin page
    if (isset($_SESSION['signin'])) {
        $_SESSION['signin-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signin.php');
        exit(); // Prevent further code execution
    }

} else {
    header('location: ' . ROOT_URL . "signin.php");
    exit(); // Prevent further code execution
}
