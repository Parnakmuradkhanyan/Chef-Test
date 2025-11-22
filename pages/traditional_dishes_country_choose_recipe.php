<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'];
$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';


$country_id = isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;

$country = null;
$dishes = [];


if ($country_id > 0) {

    $stmt = $conn->prepare("SELECT * FROM Countries WHERE id_country = ?");
    $stmt->bind_param("i", $country_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $country = $result->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("
        SELECT d.* 
        FROM Dishes_Countries dc
        JOIN Dish d ON dc.dish_id = d.dish_id
        WHERE dc.country_id = ?
    ");
    $stmt->bind_param("i", $country_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $dishes[] = $row;
    }
    $stmt->close();

    $stmt = $conn->prepare("
        SELECT d.* 
        FROM Users_RecentlyViewedDishes urvd
        JOIN Dish d ON urvd.dish_id = d.dish_id
        WHERE urvd.user_id = ?
        ORDER BY urvd.viewed_at DESC
        LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recent_dish = $result->fetch_assoc();
    $stmt->close();

}

$conn->close();

if (!$country) {
    die("Country not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - <?php echo htmlspecialchars($country['name_of_country']); ?> Traditional Dishes</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/traditional_dishes_x_choose_recipe_style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="navbar">
                    <a href="./home.php" class="logo-home-link"><img src="../img/color-logo.svg" alt="Chef Assist" class="logo-home-link-img"></a>
                    <div class="profile-menu-container">
                        <a href="./profile.php" class="profile-pic-link"><img src="../<?php echo htmlspecialchars($user_photo); ?>" style="border-radius: 50%;" alt="Profile" class="profile-pic"></a>
                        <a href="./menu.php" class="menu-link"><img src="../icons/menu-icon.svg" alt="Menu" class="menu-link"></a>
                    </div>
                </div>

                <div class="arrow-back-container">
                    <a href="./traditional_dishes_choose_country.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                    <div class="container-name-of-page-flag"><p class="page-name">Traditional Dishes <?php echo htmlspecialchars($country['name_of_country']); ?></p><img src="../<?php echo htmlspecialchars($country['flag_of_country']); ?>" alt=""></div>
                </div>
                
            </div>

        </div>



        <div class="row row-of-recipies">
            <?php foreach ($dishes as $dish): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mt-sm-3">
                    <div class="recomended-recipe-container">
                        <img src="../<?php echo htmlspecialchars($dish['dish_image']); ?>" alt="image" class="dish-recomended-img">
                        <div class="info-elements">
                            <p class="title-of-dish"><?php echo htmlspecialchars($dish['name_of_dish']); ?></p>
                            <div class="cooking-time-container">
                                <img src="../icons/cooking-time-icon.svg" alt="Icon" class="cooking-time-icon">
                                <p>Cooking time:</p>
                                <p><?php echo htmlspecialchars($dish['cooking_time']); ?></p>
                                <p>min</p>
                            </div>
                            <div class="level-of-cooking-container">
                                <p>Level:</p>
                                <p><?php echo htmlspecialchars($dish['level_of_cooking']); ?></p>
                            </div>
                            <p class="short-description"><?php echo htmlspecialchars($dish['short_description']); ?></p>
                            <ul class="dish-info-list">
                                <li><p>Calories:</p><p><?php echo htmlspecialchars($dish['calories']); ?></p><p>kcal</p></li>
                                <li><p>Fats:</p><p><?php echo htmlspecialchars($dish['fats']); ?></p><p>g</p></li>
                                <li><p>Proteins:</p><p><?php echo htmlspecialchars($dish['proteins']); ?></p><p>g</p></li>
                                <li><p>Carbohydrates:</p><p><?php echo htmlspecialchars($dish['carbohydrates']); ?></p><p>g</p></li>
                            </ul>
                            <a href="./recipe_page.php?dish_id=<?php echo $dish['dish_id']; ?>" class="view-recipe-btn">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>


    <?php if ($recent_dish): ?>
        <div class="recent-recipe-container">
            <div class="top-element-decor"></div>
            <p class="recent-dish-text">Recent dish</p>
            <div class="photo-name-btn-container">
                <img src="../<?php echo htmlspecialchars($recent_dish['dish_image']); ?>" alt="Dish" class="recent-dish-image">
                <div class="dish-name-view-btn-container">
                    <div class="dish-name"><?php echo htmlspecialchars($recent_dish['name_of_dish']); ?></div>
                    <a href="./recipe_page.php?dish_id=<?php echo $recent_dish['dish_id']; ?>" class="view-btn">View</a>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>