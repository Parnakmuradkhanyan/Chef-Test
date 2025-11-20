<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];
$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';


$conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - Home</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/home_style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="navbar">
                    <a href="#" class="logo-home-link"><img src="../img/color-logo.svg" alt="Chef Assist" class="logo-home-link-img"></a>
                    <div class="profile-menu-container">
                        <a href="./profile.php" class="profile-pic-link"><img src="../<?php echo htmlspecialchars($user_photo); ?>" style="border-radius: 50%;" alt="Profile" class="profile-pic"></a>
                        <a href="./menu.php" class="menu-link"><img src="../icons/menu-icon.svg" alt="Menu" class="menu-link"></a>
                    </div>
                </div>

                <div class="searchbar-and-greeting-container">
                    <div class="greeting-container">
                        <div class="hello-text-container">
                            <p class="hello-text">Hello, </p>
                            <p class="user-name"><?php echo htmlspecialchars($name); ?></p>
                            <p class="hello-text">!</p>
                        </div>
                        <p class="question-text">What would you like to cook today?</p>
                    </div>
                     <form action="./search_recipe_recomendation.php" method="get" class="searchbar-container">
                        <input name="searchbar_food_input" placeholder="French fries" required class="searchbar-input" type="text">
                        <button type="submit" class="search-btn">
                            <img src="../icons/searchbar-search-icon.svg" alt="Search" class="search-btn-icon">
                        </button>
                    </form>
                </div>


                <div class="action-buttons-container">
                    <a href="./cook_from_fridge.html" class="action-button-link">
                        <div class="action-button-container-text">
                            <div class="action-button-container">
                                <img class="icon-action-button" src="../icons/cook-from-fridge-icon-home.svg" />
                            </div>
                            <p class="button-text">Cook from fridge</p>
                        </div>
                    </a>
                    <a href="./favourite_recipies.html" class="action-button-link">
                        <div class="action-button-container-text">
                            <div class="action-button-container">
                                <img class="icon-action-button" src="../icons/favourite-recipies-icon-home.svg" />
                            </div>
                            <p class="button-text">Favourite recipies</p>
                        </div>
                    </a>
                   <a href="./random_recipe.php" class="action-button-link">
                        <div class="action-button-container-text">
                            <div class="action-button-container">
                                <img class="icon-action-button" src="../icons/lets-try-something-new-icon-home.svg" />
                            </div>
                            <p class="button-text">Let's try something new</p>
                        </div>
                    </a>

                    <a href="./traditional_dishes_choose_country.php" class="action-button-link">
                        <div class="action-button-container-text">
                            <div class="action-button-container">
                                <img class="icon-action-button" src="../icons/traditional-dishes-icon-home.svg" />
                            </div>
                            <p class="button-text">Traditional dishes</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="recent-recipe-container">
        <div class="top-element-decor"></div>

        <p class="recent-dish-text">Recent dish</p>

        <div class="photo-name-btn-container">
            <img src="../img/dish-example-photo.svg" alt="Dish" class="recent-dish-image">

            <div class="dish-name-view-btn-container">
                <div class="dish-name">Spaghetti with vegetables</div>
                <button class="view-btn" action="./recipe_page.html">View</button>
            </div>
        </div>

    </div>
    
    <script src="./js/bootstrap.bundle.min.js"></script>
    
</body>
</html>