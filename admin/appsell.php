<?php
include "partials/header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escape user input for security
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $product_type = mysqli_real_escape_string($connection, $_POST['product_type']);
    $reason = mysqli_real_escape_string($connection, $_POST['reason']);

    // Insert data into the database
    $query = "INSERT INTO seller_requests (name, email, location, product_type, reason) 
              VALUES ('$name', '$email', '$location', '$product_type', '$reason')";

    if (mysqli_query($connection, $query)) {
        $_SESSION['form-success'] = "Seller account application submitted successfully.";
        
        
    } else {
        $_SESSION['form-error'] = "There was an error submitting your application.";
    }
}
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <h2>Seller Account Application</h2>
        <form action="" method="POST" class="form">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="location" placeholder="Location" required>
            <input type="text" name="product_type" placeholder="What type of product do you want to sell?" required>
            <textarea name="reason" placeholder="Reason for Request" required></textarea>
            <button type="submit">Submit Application</button>
        </form>
        <?php if (isset($_SESSION['form-error'])): ?>
            <p class="error"><?= $_SESSION['form-error']; unset($_SESSION['form-error']); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php
include "../partials/footer.php";
?>
