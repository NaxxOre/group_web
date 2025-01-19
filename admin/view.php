<?php
include "partials/header.php";

// Make sure the database connection is established
if (!isset($connection)) {
    // Establish the database connection (replace with your own database credentials)
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check the connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

// Fetch all seller account requests
$seller_requests_query = "SELECT * FROM seller_requests";
$seller_requests = mysqli_query($connection, $seller_requests_query);

// Check if the query executed successfully
if (!$seller_requests) {
    die("Seller requests query failed: " . mysqli_error($connection));
}
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <h2>Account Applications</h2>

        <h3>Seller Account Requests</h3>
        <?php if (mysqli_num_rows($seller_requests) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Product Type</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($request = mysqli_fetch_assoc($seller_requests)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($request['name']) ?></td>
                            <td><?= htmlspecialchars($request['email']) ?></td>
                            <td><?= htmlspecialchars($request['location']) ?></td>
                            <td><?= htmlspecialchars($request['product_type']) ?></td>
                            <td><?= htmlspecialchars($request['reason']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No seller account requests found.</p>
        <?php endif; ?>
    </div>
</section>

<?php
include "../partials/footer.php";
?>
