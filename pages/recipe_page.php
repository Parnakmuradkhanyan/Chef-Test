<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$user_id = $_SESSION['user_id'];
$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';

$dish_id = isset($_GET['dish_id']) ? (int)$_GET['dish_id'] : 0;

$dish = null;

if ($dish_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM Dish WHERE dish_id = ?");
    $stmt->bind_param("i", $dish_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dish = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();

if (!$dish) {
    die("Dish not found");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - <?php echo htmlspecialchars($dish['name_of_dish']); ?></title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/recipe_page_style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="navbar">
                    <a href="./home.php" class="logo-home-link"><img src="../img/color-logo.svg" alt="Chef Assist" class="logo-home-link-img"></a>
                    <div class="profile-menu-container">
                        <a href="./profile.php" class="profile-pic-link"><img  src="../<?php echo htmlspecialchars($user_photo); ?>" style="border-radius: 50%;" alt="Profile" class="profile-pic"></a>
                        <a href="./menu.php" class="menu-link"><img src="../icons/menu-icon.svg" alt="Menu" class="menu-link"></a>
                    </div>
                </div>

                <div class="arrow-back-container">
                    <a href="./home.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                    <p class="page-name">Recipe <?php echo htmlspecialchars($dish['name_of_dish']); ?></p>
                </div>

                <div class="recipe-cover-container">
                    <div class="recipe-page-container">

                        <div class="main-info-container">

                            <div class="main-info-name-cooking-time-container">
                                <p class="name-of-dish"><?php echo htmlspecialchars($dish['name_of_dish']); ?></p>

                                <div class="cooking-time-container">
                                    <img src="../icons/cooking-time-icon-recipie-page.svg" alt="icon" class="cooking-time-icon">
                                    <p>Cooking time:</p>
                                    <p><?php echo htmlspecialchars($dish['cooking_time']); ?></p>
                                    <p>min</p>
                                </div>

                                <div class="level-of-cooking-container">
                                    <p>Level:</p>
                                    <p><?php echo htmlspecialchars($dish['level_of_cooking']); ?></p>
                                </div>

                            </div>

                            <img src="../<?php echo htmlspecialchars($dish['dish_image']); ?>" alt="Dish Photo" class="dish-photo">

                            <div class="short-desciption-btn-like-container">
                                <p class="short-description"><?php echo htmlspecialchars($dish['short_description']); ?></p>
                                <button class="like-btn"></button>
                            </div>

                        </div>

                        <div class="ingredients-dish-info-container">

                            <div class="ingredients-container">
                                <p class="ingredients-text">Ingredients:</p>
                                <ul class="ingredients-list">
                                    <li>
                                        <p class="ingredient-text">2 ½ pounds russet potatoes, peeled</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 cup vegetable oil for frying</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 cup vegetable oil for frying</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 cup all-purpose flour</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 teaspoon garlic salt</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 teaspoon garlic salt</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 teaspoon onion salt</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 teaspoon paprika</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">1 teaspoon salt</p>
                                    </li>
                                    <li>
                                        <p class="ingredient-text">½ cup water, or as needed</p>
                                    </li>
                                </ul>
                            </div>

                            <div class="dish-info-container">
                                <p class="dish-info-text">Dish:</p>
                                <ul class="dish-info-list">
                                    <li>
                                        <p>Calories:</p>
                                        <p><?php echo htmlspecialchars($dish['calories']); ?></p>
                                        <p>kcal</p>
                                    </li>
                                    <li>
                                        <p>Proteins:</p>
                                        <p><?php echo htmlspecialchars($dish['proteins']); ?></p>
                                        <p>g</p>
                                    </li>
                                    <li>
                                        <p>Fats:</p>
                                        <p><?php echo htmlspecialchars($dish['fats']); ?></p>
                                        <p>g</p>
                                    </li>
                                    <li>
                                        <p>Carbohydrates:</p>
                                        <p><?php echo htmlspecialchars($dish['carbohydrates']); ?></p>
                                        <p>g</p>
                                    </li>
                                </ul>
                            </div>


                        </div>

                        <p class="how-to-cook-text">How to cook:</p>

                        <div class="steps-of-cooking-container">

                            <div class="step-container">
                                <div class="step-num-name">
                                    <p class="step-text">Step</p>
                                    <p class="step-num">1</p>
                                </div>

                                <p class="step-description">Slice potatoes into French fries and place them into a large bowl of cold water to prevent them from turning brown.</p>

                                <img src="../img/recipe-img/1.PNG" alt="image" class="step-image">
                            </div>
                    
                        </div>

                        <div class="video-instruction-container">
                            <p class="video-instruction-text">Video instruction:</p>
                            <iframe class="video-instruction-frame" src="https://www.youtube.com/embed/CYhYSoJrOLg?si=F2h0gWJKaYrj1rI1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </div>


    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>