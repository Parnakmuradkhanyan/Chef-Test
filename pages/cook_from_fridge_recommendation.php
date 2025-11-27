<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';


$stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

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


$allergic_array = array_map('trim', explode(',', strtolower($user['allergic_ingredients'] ?? '')));
$diet_array = array_map('trim', explode(',', strtolower($user['diet_limit_ingredients'] ?? '')));


$input = $_GET['ingredients'] ?? '';
$ingredients_input = array_map('trim', explode(',', strtolower($input)));

$dishes = [];

if (!empty($ingredients_input)) {

    $placeholders = implode(',', array_fill(0, count($ingredients_input), '?'));
    $types = str_repeat('s', count($ingredients_input));

    $stmt = $conn->prepare("SELECT ingredient_id, name_of_ingredient FROM Ingredients WHERE LOWER(name_of_ingredient) IN ($placeholders)");
    $stmt->bind_param($types, ...$ingredients_input);
    $stmt->execute();
    $result = $stmt->get_result();

    $ingredient_ids = [];
    while ($row = $result->fetch_assoc()) {
        $ingredient_ids[] = $row['ingredient_id'];
    }
    $stmt->close();

    if (!empty($ingredient_ids)) {

        $placeholders = implode(',', array_fill(0, count($ingredient_ids), '?'));
        $types = str_repeat('i', count($ingredient_ids));

        $stmt = $conn->prepare("
            SELECT DISTINCT d.* 
            FROM Dishes_Ingredients di
            JOIN Dish d ON di.dish_id = d.dish_id
            WHERE di.ingredient_id IN ($placeholders)
        ");
        $stmt->bind_param($types, ...$ingredient_ids);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($dish = $result->fetch_assoc()) {

            $skip = false;

            if (($user['max_calories'] > 0 && $dish['calories'] > $user['max_calories']) ||
                ($user['max_fats'] > 0 && $dish['fats'] > $user['max_fats']) ||
                ($user['max_proteins'] > 0 && $dish['proteins'] > $user['max_proteins']) ||
                ($user['max_carbohydrates'] > 0 && $dish['carbohydrates'] > $user['max_carbohydrates'])) {
                $skip = true;
            }

            if (!$skip) {
                $stmtIng = $conn->prepare("
                    SELECT i.name_of_ingredient 
                    FROM Dishes_Ingredients di
                    JOIN Ingredients i ON di.ingredient_id = i.ingredient_id
                    WHERE di.dish_id = ?
                ");
                $stmtIng->bind_param("i", $dish['dish_id']);
                $stmtIng->execute();
                $resIng = $stmtIng->get_result();
                while ($rowIng = $resIng->fetch_assoc()) {
                    $nameLower = strtolower($rowIng['name_of_ingredient']);
                    if (in_array($nameLower, $allergic_array) || in_array($nameLower, $diet_array)) {
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
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - Cook From Fridge</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cook_from_fridge_recommendation_style.css">
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
                    <a href="./cook_from_fridge.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                   <p class="page-name">Recommendations</p>
                </div>
                
            </div>

        </div>

        <div class="row row-of-recipies">
            <?php if (empty($dishes)): ?>
                <p class="no-matching-recipies">No recipes found with your ingredients and filters.</p>
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