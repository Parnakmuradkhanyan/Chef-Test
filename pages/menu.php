<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$name = $_SESSION['name'] ?? 'Not written';
$surname = $_SESSION['surname'] ?? 'Not written';

$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';

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
    <link rel="stylesheet" href="../css/menu_style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="navbar">
                    <a href="./home.php" class="logo-home-link"><img src="../img/color-logo.svg" alt="Chef Assist" class="logo-home-link-img"></a>
                </div>

                <div class="arrow-back-container">
                    <a href="./home.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                    <p class="page-name">Menu</p>
                </div>

                <div class="main-elements">
                    <a href="./profile.php" class="profile-link">
                        <div class="profile-container">
                            <img src="../<?php echo htmlspecialchars($user_photo); ?>" style="border-radius: 50%;" alt="Picture" class="profile-pic">
                            <div class="name-surname-contianer">
                                <p><?php echo htmlspecialchars($name); ?></p>
                                <p><?php echo htmlspecialchars($surname); ?></p>
                            </div>
                        </div>
                    </a>

                    <ul class="menu-elements-list">
                        <li class="menu-list-element"><a href="./cook_from_fridge.php" class="menu-list-item-link-container"><img src="../icons/cook-from-fridge-icon-home.svg" alt="Icon" class="menu-list-item-icon-1"><p class="menu-list-item-text">Cook from fridge</p></a></li>
                        <li class="menu-list-element"><a href="./random_recipe.php" class="menu-list-item-link-container"><img src="../icons/lets-try-something-new-icon-home.svg" alt="Icon" class="menu-list-item-icon"><p class="menu-list-item-text">Let’s try something new</p></a></li>
                        <li class="menu-list-element"><a href="./traditional_dishes_choose_country.php" class="menu-list-item-link-container"><img src="../icons/traditional-dishes-icon-home.svg" alt="Icon" class="menu-list-item-icon"><p class="menu-list-item-text">Traditional dishes</p></a></li>
                        <li class="menu-list-element"><a href="./favourite_recipies.php" class="menu-list-item-link-container"><img src="../icons/favourite-recipies-icon-home.svg" alt="Icon" class="menu-list-item-icon"><p class="menu-list-item-text">Favourite recipies</p></a></li>
                    </ul>

                    <form action="../logout.php">
                        <button type="submit" class="log-out-btn"><img src="../icons/log-out-btn-icon.svg" alt="Icon" class="log-out-icon"><p class="log-out-text">Log out</p></button>
                    </form>




                </div>


            </div>
        </div>
    </div>



    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>