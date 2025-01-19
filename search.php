<?php
require 'partials/header.php';

// Check if input is present
if ((isset($_GET['search'])) && isset($_GET['submit'])) {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Check if the search input is a valid full date (YYYY-MM-DD)
    $is_full_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $search);

    if ($is_full_date) {
        // If input is a full date, search by the exact date
        $query = "
            SELECT posts.*, users.firstname, users.lastname, users.avatar 
            FROM posts 
            LEFT JOIN users ON posts.author_id = users.id 
            WHERE DATE(posts.date_time) = '$search'
            ORDER BY posts.date_time DESC
        ";
    } else {
        // Check if the input is a partial date (e.g., month day, or day only)
        $month_names = [
            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04',
            'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08',
            'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
        ];

        $is_month_name_and_day = preg_match('/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{1,2}$/', $search);
        $is_day = preg_match('/^\d{1,2}$/', $search);
        $is_month_name = preg_match('/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)$/', $search);
        $is_year = preg_match('/^\d{4}$/', $search);

        if ($is_month_name_and_day) {
            // Search by month and day (e.g., "Jan 08")
            list($month_name, $day) = explode(' ', $search);
            $month = $month_names[$month_name];
            $day = str_pad($day, 2, '0', STR_PAD_LEFT); // Ensure day is in two digits

            $current_year = date('Y');
            $query = "
                SELECT posts.*, users.firstname, users.lastname, users.avatar 
                FROM posts 
                LEFT JOIN users ON posts.author_id = users.id 
                WHERE DATE(posts.date_time) = '$current_year-$month-$day'
                ORDER BY posts.date_time DESC
            ";
        } elseif ($is_month_name) {
            // Search by month only (e.g., "Jan")
            $month = $month_names[$search];
            $query = "
                SELECT posts.*, users.firstname, users.lastname, users.avatar 
                FROM posts 
                LEFT JOIN users ON posts.author_id = users.id 
                WHERE MONTH(posts.date_time) = '$month'
                ORDER BY posts.date_time DESC
            ";
        } elseif ($is_day) {
            // Search by specific day (e.g., "01", "03", "08")
            $current_year = date('Y');
            $current_month = date('m');
            $day = str_pad($search, 2, '0', STR_PAD_LEFT); // Ensure day is in two digits

            $query = "
                SELECT posts.*, users.firstname, users.lastname, users.avatar 
                FROM posts 
                LEFT JOIN users ON posts.author_id = users.id 
                WHERE DATE(posts.date_time) = '$current_year-$current_month-$day'
                ORDER BY posts.date_time DESC
            ";
        } elseif ($is_year) {
            // Search by year (e.g., 2025)
            $query = "
                SELECT posts.*, users.firstname, users.lastname, users.avatar 
                FROM posts 
                LEFT JOIN users ON posts.author_id = users.id 
                WHERE YEAR(posts.date_time) = '$search'
                ORDER BY posts.date_time DESC
            ";
        } else {
            // Check if the search term matches a category
            $category_query = "SELECT id FROM categories WHERE title LIKE '%$search%'";
            $category_result = mysqli_query($connection, $category_query);

            if (mysqli_num_rows($category_result) > 0) {
                // If a category is found, fetch posts from that category
                $category = mysqli_fetch_assoc($category_result);
                $category_id = $category['id'];

                $query = "
                    SELECT posts.*, users.firstname, users.lastname, users.avatar 
                    FROM posts 
                    LEFT JOIN users ON posts.author_id = users.id 
                    WHERE posts.category_id = '$category_id'
                    ORDER BY posts.date_time DESC
                ";
            } else {
                // Otherwise, search by title or author name
                $query = "
                    SELECT posts.*, users.firstname, users.lastname, users.avatar 
                    FROM posts 
                    LEFT JOIN users ON posts.author_id = users.id 
                    WHERE posts.title LIKE '%$search%' 
                    OR CONCAT(users.firstname, ' ', users.lastname) LIKE '%$search%' 
                    ORDER BY posts.date_time DESC
                ";
            }
        }
    }

    $posts = mysqli_query($connection, $query);
} else {
    header("Location: " . ROOT_URL . 'blog.php');
    exit;
}
?>

<?php if ((mysqli_num_rows($posts) > 0)) : ?>
<section class="posts section__extra-margin">
    <div class="container posts__container">
        <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
        <article class="post">
            <div class="post__thumbnail">
                <img src="./images/<?= $post['thumbnail'] ?>" alt="Post Thumbnail">
            </div>
            <div class="post__info">
                <?php
                // Fetch category
                $category_id = $post['category_id'];
                $category_query = "SELECT * FROM categories WHERE id=$category_id";
                $category_result = mysqli_query($connection, $category_query);
                $category = mysqli_fetch_assoc($category_result);
                ?>

                <a href="category-posts.php?id=<?= $post['category_id'] ?>" class="category__button">
                    <?= $category['title'] ?>
                </a>
                <h3 class="post__title">
                    <a href="post.php?id=<?= $post['id'] ?>">
                        <?= $post['title'] ?>
                    </a>
                </h3>
                <p class="post__body">
                    <?= substr($post['body'], 0, 150) ?>...
                </p>
                <div class="post__author">
                    <div class="post__author-avatar">
                        <img src="./images/<?= $post['avatar'] ?>" alt="Author Avatar">
                    </div>
                    <div class="post__author-info">
                        <h5>By: <?= "{$post['firstname']} {$post['lastname']}" ?></h5>
                        <small>
                            <?= date("M d, Y - H:i", strtotime($post['date_time'])) ?>
                        </small>
                    </div>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
</section>
<?php else : ?>
    <div class="alert__message error lg section__extra-margin">
        <p>No post found for this search</p>
    </div>
<?php endif; ?>

<!-- ===================================================================== -->
<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php 
        $all_categories_query = "SELECT * FROM categories";
        $all_categories_result = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while ($category = mysqli_fetch_assoc($all_categories_result)) : ?>
        <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category__button">
            <?= $category['title'] ?>
        </a>
        <?php endwhile; ?>
    </div>
</section>
<!-- =======================END OF CATEGORY =================================== -->

<?php
include './partials/footer.php';
?>
