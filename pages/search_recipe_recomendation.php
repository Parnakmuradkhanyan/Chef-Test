<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$user_id = $_SESSION['user_id'];
$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';

$max_calories = $_SESSION['max_calories'] ?? 0;
$max_fats = $_SESSION['max_fats'] ?? 0;
$max_proteins = $_SESSION['max_proteins'] ?? 0;
$max_carbohydrates = $_SESSION['max_carbohydrates'] ?? 0;

$allergic_ingredients = $_SESSION['allergic_ingredients'] ?? '';
$diet_limit_ingredients = $_SESSION['diet_limit_ingredients'] ?? '';

$allergic_array = array_map('trim', explode(',', $allergic_ingredients));
$diet_array = array_map('trim', explode(',', $diet_limit_ingredients));

$search_query = $_GET['searchbar_food_input'] ?? '';

$dishes = [];

if (!empty($search_query)) {

    $stmt = $conn->prepare("
        SELECT * FROM Dish 
        WHERE name_of_dish LIKE ?
        AND calories <= ?
        AND fats <= ?
        AND proteins <= ?
        AND carbohydrates <= ?
    ");
    $like = "%".$search_query."%";
    $stmt->bind_param("siiii", $like, $max_calories, $max_fats, $max_proteins, $max_carbohydrates);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($dish = $result->fetch_assoc()) {
        $dish_id = $dish['dish_id'];

        $skip = false;
        if (!empty($allergic_array)) {
            $stmtIng = $conn->prepare("
                SELECT i.name_of_ingredient 
                FROM Dishes_Ingredients di
                JOIN Ingredients i ON di.ingredient_id = i.ingredient_id
                WHERE di.dish_id = ?
            ");
            $stmtIng->bind_param("i", $dish_id);
            $stmtIng->execute();
            $resIng = $stmtIng->get_result();
            while ($rowIng = $resIng->fetch_assoc()) {
                if (in_array(strtolower($rowIng['name_of_ingredient']), array_map('strtolower', $allergic_array))) {
                    $skip = true;
                    break;
                }
            }
            $stmtIng->close();
        }

        if (!$skip && !empty($diet_array)) {
            $stmtIng = $conn->prepare("
                SELECT i.name_of_ingredient 
                FROM Dishes_Ingredients di
                JOIN Ingredients i ON di.ingredient_id = i.ingredient_id
                WHERE di.dish_id = ?
            ");
            $stmtIng->bind_param("i", $dish_id);
            $stmtIng->execute();
            $resIng = $stmtIng->get_result();
            while ($rowIng = $resIng->fetch_assoc()) {
                if (in_array(strtolower($rowIng['name_of_ingredient']), array_map('strtolower', $diet_array))) {
                    $skip = true;
                    break;
                }
            }
            $stmtIng->close();
        }

        if (!$skip) {
            $dishes[] = $dish;
        }
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - Menu</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/search_recipe_recomendation_style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="navbar">
                    <a href="./home.html" class="logo-home-link"><img src="../img/color-logo.svg" alt="Chef Assist" class="logo-home-link-img"></a>
                    <div class="profile-menu-container">
                        <a href="./profile.php" class="profile-pic-link"><img class="profile-pic-link"><img src="../<?php echo htmlspecialchars($user_photo); ?>" style="border-radius: 50%;" alt="Profile" class="profile-pic"></a>
                        <a href="./menu.php" class="menu-link"><img src="../icons/menu-icon.svg" alt="Menu" class="menu-link"></a>
                    </div>
                </div>

                <div class="arrow-back-container">
                    <a href="./home.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                    <p class="page-name">Recomendations</p>
                </div>

                <form action="./search_recipe_recomendation.php" method="get" class="searchbar-container">
                        <input name="searchbar_food_input" placeholder="French fries" required class="searchbar-input" type="text">
                        <button type="submit" class="search-btn"><img src="../icons/searchbar-search-icon.svg" alt="Search" class="search-btn-icon"></button>
                </form>

                
            </div>

        </div>

        <div class="row row-of-recipies">
            <?php if (empty($dishes)): ?>
                <div class="col-12">
                 <p class="no-matching-recipies">No recipes found matching your filters.</p>
                </div>
            <?php else: ?>
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
            <?php endif; ?>
        </div>

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

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>