<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$name = $_SESSION['name'] ?? 'Not written';
$surname = $_SESSION['surname'] ?? 'Not written';
$email = $_SESSION['email'] ?? 'Not Written';
$telephone_number = $_SESSION['telephone_number'] ?? '000000000';
$level_of_cooking = $_SESSION['level_of_cooking'] ?? 'Not chosen';
$max_calories = $_SESSION['max_calories'] ?? '0';
$max_fats = $_SESSION['max_fats'] ?? '0';
$max_proteins = $_SESSION['max_proteins'] ?? '0';
$max_carbohydrates = $_SESSION['max_carbohydrates'] ?? '0';

$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';

$user_id = $_SESSION['user_id'];


$allergic_ingredients = $_SESSION['allergic_ingredients'] ?? '';
$ingredients_array = array_map('trim', explode(',', $allergic_ingredients));

$diet_limit_ingredients = $_SESSION['diet_limit_ingredients'] ?? '';
$diet_ingredients_array = array_map('trim', explode(',', $diet_limit_ingredients));




$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - My Profile</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/profile_style.css">
</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="navbar">
                    <a href="./home.php" class="logo-home-link"><img src="../img/color-logo.svg" alt="Chef Assist" class="logo-home-link-img"></a>
                    <div class="profile-menu-container">
                        <a href="./menu.php" class="menu-link"><img src="../icons/menu-icon.svg" alt="Menu" class="menu-link"></a>
                    </div>
                </div>

                <div class="arrow-back-container">
                    <a href="./home.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                    <p class="page-name">My profile</p>
                </div>


                <div class="profile-container">
                    <div class="main-info-container">
                        <img src="../<?php echo htmlspecialchars($user_photo); ?>" style="border-radius: 50%;" alt="Pic" class="profile-pic">

                        <div class="name-surname-contianer"><p><?php echo htmlspecialchars($name); ?></p><p><?php echo htmlspecialchars($surname); ?></p></div>
                        <p class="email-address"><?php echo htmlspecialchars($email); ?></p>
                        <p class="tel-num"><?php echo htmlspecialchars($telephone_number); ?></p>

                        <p class="level-of-cooking"><?php echo htmlspecialchars($level_of_cooking); ?></p>
                    </div>

                    <div class="limits-container">

                        <div class="allergic-ingredients-container">
                            <div class="allergic-ingredients-text-container">
                                <p class="allergic-ingredients-container-text">Allergic Ingredients</p>
                                <img src="../icons/allergic-ingredients-icon.svg" alt="Icon">
                            </div>

                            <div class="no-icon-list-container">
                                <div class="no-icon-container">
                                    <img src="../icons/not-allowed-food-icon.svg" alt="No" class="no-icon">
                                    <p class="no-text">No</p>
                                </div>
                                <ul class="not-allowed-food-list">
                                    <?php foreach ($ingredients_array as $ingredient): ?>
                                        <?php if (!empty($ingredient)): ?>
                                            <li class="not-allowed-food-list-item">
                                                <?php echo htmlspecialchars($ingredient); ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                
                            </div>

                        </div>

                        <div class="dietic-ingredients-container">
                            <div class="dietic-ingredients-text-container">
                                <p class="dietic-ingredients-container-text">Diet Limits</p>
                                <img src="../icons/diet-llimits-icon.svg" alt="Icon">
                            </div>

                            <div class="no-icon-list-container">
                                <div class="no-icon-container">
                                    <img src="../icons/not-allowed-food-icon.svg" alt="No" class="no-icon">
                                    <p class="no-text">No</p>
                                </div>
                                <ul class="not-allowed-food-list">
                                    <?php foreach ($diet_ingredients_array as $diet_ingredient): ?>
                                        <?php if (!empty($diet_ingredient)): ?>
                                            <li class="not-allowed-food-list-item">
                                                <?php echo htmlspecialchars($diet_ingredient); ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                
                            </div>

                        </div>

                        <div class="limits-list-container">
                            <ul class="limits-list">
                                <li class="limits-list-item">
                                    <p>Maximal calories per dish</p>
                                    <p>-</p>
                                    <p><?php echo htmlspecialchars($max_calories); ?></p>
                                    <p>kcal</p>
                                </li>
                                <li class="limits-list-item">
                                    <p>Maximal fats per dish</p>
                                    <p>-</p>
                                    <p><?php echo htmlspecialchars($max_fats); ?></p>
                                    <p>g</p>
                                </li>
                                <li class="limits-list-item">
                                    <p>Maximal proteins per dish</p>
                                    <p>-</p>
                                    <p><?php echo htmlspecialchars($max_proteins); ?></p>
                                    <p>g</p>
                                </li>
                                <li class="limits-list-item">
                                    <p>Maximal carbohydrates per dish</p>
                                    <p>-</p>
                                    <p><?php echo htmlspecialchars($max_carbohydrates); ?></p>
                                    <p>g</p>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <a href="./edit_profile.php" class="edit-my-profile-btn"><img src="../icons/edit-my-profile-btn-icon.svg" alt="icon" class="edit-profile-icon"><p class="edit-my-profile-text">Edit my profile</p></a>





                </div>



            </div>
        </div>
    </div>


    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>