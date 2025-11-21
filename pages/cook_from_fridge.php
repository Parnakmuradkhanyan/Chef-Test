<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';

$user_id = $_SESSION['user_id'];

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
    <link rel="stylesheet" href="../css/cook_from_fridge_style.css">
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
                    <p class="page-name">Cook From Fridge</p>
                </div>

                <p class="choose-avliable-ingredients-text">Choose avliable ingredients ...</p>

                <div class="fridge-container">
                    <form action="./cook_from_fridge_recommendation.php" method="get" class="avliable-ingredients-form">
                        <div class="elements-container">
                            <textarea name="ingredients" required placeholder="Apples, eggs, flour..." class="avliable-ingredients-textarea"></textarea>
                            <button type="submit" class="search-btn">Search</button>
                        </div>
                        
                    </form>
                </div>


            </div>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>