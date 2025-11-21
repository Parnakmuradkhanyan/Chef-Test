<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'];
$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';

$dishes = [];
$stmt = $conn->prepare("
    SELECT d.* 
    FROM Favourite_Dish f
    JOIN Dish d ON f.dish_id = d.dish_id
    WHERE f.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $dishes[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - Favourite Recipies</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/favourite_recipies_style.css">
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
                    <a href="./home.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                    <div class="container-name-of-page-icon"><p class="page-name">Favourite Recipies</p><img src="../icons/favourite-recipies-icon-on-name.svg" alt=""></div>
                </div>
                
            </div>

        </div>

        <div class="row row-of-recipies">
            <?php foreach ($dishes as $dish): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mt-sm-3">
                    <div class="recomended-recipe-container">
                        <div class="button-photo-container">
                            <img src="../<?php echo htmlspecialchars($dish['dish_image']); ?>" alt="image" class="dish-recomended-img">
                            <button class="add-delete-favourite-recipe active" data-dish-id="<?php echo $dish['dish_id']; ?>"></button>
                        </div>
                        <div class="info-elements">
                            <p class="title-of-dish"><?php echo htmlspecialchars($dish['name_of_dish']); ?></p>
                            <p class="short-description"><?php echo htmlspecialchars($dish['short_description']); ?></p>
                            <a href="./recipe_page.php?dish_id=<?php echo $dish['dish_id']; ?>" class="view-recipe-btn">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


        <div class="recent-recipe-container">
            <div class="top-element-decor"></div>

            <p class="recent-dish-text">Recent dish</p>

            <div class="photo-name-btn-container">
                <img src="../img/dish-example-photo.svg" alt="Dish" class="recent-dish-image">

                <div class="dish-name-view-btn-container">
                    <div class="dish-name">Spaghetti with vegetables</div>
                    <button class="view-btn">View</button>
                </div>
            </div>

        </div>
    </div>


    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/favourite_recipies_script.php"></script>



</body>
</html>