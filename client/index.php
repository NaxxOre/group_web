<?php
session_start();
require "../config/database.php";

// Check if the user is logged in
if (!isset($_SESSION['user-id'])) {
    header('location: ' . ROOT_URL . 'signin.php');
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user-id'];
$user_query = "SELECT * FROM users WHERE id = '$user_id' LIMIT 1";
$user_result = mysqli_query($connection, $user_query);
$user = mysqli_fetch_assoc($user_result);

if ($user) {
    $username = $user['username'];
    $avatar = $user['avatar'];
} else {
    // Handle case where user doesn't exist
    header('location: ' . ROOT_URL . 'signin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?= $username ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <section class="welcome-section">
        <div class="container">
            <h1>Welcome, <?= $username ?>!</h1>
            <img src="../images/<?= $avatar ?>" alt="Avatar" width="150" height="150">
            <p>You're logged in as a regular user.</p>
            <a href="<?= ROOT_URL ?>logout.php" class="btn">Logout</a>
        </div>
    </section>
</body>
</html>
