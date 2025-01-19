<?php
include "partials/header.php";
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <h2>Account Applications</h2>
        <div class="request__options">
            <div class="request__option">
                <h3>Apply for Business Account</h3>
                <a href="<?= ROOT_URL ?>admin/appbus.php" class="btn">Apply Now</a>
            </div>
            <div class="request__option">
                <h3>Apply for Seller Account</h3>
                <a href="<?= ROOT_URL ?>admin/appsell.php" class="btn">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<?php
include "../partials/footer.php";
?>
