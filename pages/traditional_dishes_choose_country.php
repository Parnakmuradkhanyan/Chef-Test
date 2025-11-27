<?php
session_start();
require_once '../config.php';

$countries = [];
$result = $conn->query("SELECT * FROM Countries ORDER BY name_of_country ASC");

while ($row = $result->fetch_assoc()) {
    $countries[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - Traditional dishes</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/traditional_dishes_choose_country_style.css">
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
                    <p class="page-name">Traditional Dishes</p>
                </div>

                <p class="choose-country-text">Choose country ...</p>

                <div class="book-container">
                    <div class="pages-container">
                        <ul class="countries-list">
                            <?php foreach ($countries as $country): ?>
                                <li>
                                    <a href="traditional_dishes_country_choose_recipe.php?country_id=<?php echo $country['id_country']; ?>" class="country-link-go-to">
                                        <img src="../<?php echo htmlspecialchars($country['flag_of_country']); ?>" alt="Flag">
                                        <p><?php echo htmlspecialchars($country['name_of_country']); ?></p>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>