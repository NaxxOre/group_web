<?php
include "config/constants.php";

// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get back form data if available after signup failure
$firstname = $_SESSION['signup-data']['firstname'] ?? null;
$lastname = $_SESSION['signup-data']['lastname'] ?? null;
$username = $_SESSION['signup-data']['username'] ?? null;
$email = $_SESSION['signup-data']['email'] ?? null;
$avatar = $_SESSION['signup-data']['avatar'] ?? null;
unset($_SESSION['signup-data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<section class="form__section">
    <div class="container form__section-container">
        <h2>Sign up</h2>
        <?php if(isset($_SESSION['signup'])): ?>
            <div class="alert__message error">
                <p><?= $_SESSION['signup']; unset($_SESSION['signup']); ?></p>
            </div>
        <?php endif; ?>
        <form action="<?= ROOT_URL ?>signup-logic.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="firstname" placeholder="First Name" value="<?= $firstname ?>" required>
            <input type="text" name="lastname" placeholder="Last Name" value="<?= $lastname ?>" required>
            <input type="text" name="username" placeholder="Username" value="<?= $username ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $email ?>" required>
            <input type="password" name="createpassword" placeholder="Create password" required>
            <input type="password" name="confirmpassword" placeholder="Confirm password" required>
            <input type="file" name="avatar" accept="image/*" required>
            <button type="submit" name="submit">Sign up</button>
            <small>Already have an account? <a href="signin.php">Sign in</a></small>
        </form>
    </div>
</section>
</body>
</html>
