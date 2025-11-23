<?php
session_start();
require_once './config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT user_id, name, surname, email, telephone_number, level_of_cooking, user_photo, allergic_ingredients, diet_limit_ingredients, max_calories, max_fats, max_proteins, max_carbohydrates, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $name, $surname, $email, $telephone_number, $level_of_cooking, $user_photo, $allergic_ingredients, $diet_limits, $max_calories, $max_fats, $max_proteins, $max_carbohydrates, $hashed_password);
            $stmt->fetch();

            if ($password === $hashed_password) {

                $_SESSION['user_id'] = $user_id;
                $_SESSION['name'] = $name;
                $_SESSION['surname'] = $surname;
                $_SESSION['email'] = $email;
                $_SESSION['telephone_number'] = $telephone_number;
                $_SESSION['level_of_cooking'] = $level_of_cooking;
                $_SESSION['user_photo'] = $user_photo;
                $_SESSION['allergic_ingredients'] = $allergic_ingredients;
                $_SESSION['diet_limit_ingredients'] = $diet_limits;
                $_SESSION['max_calories'] = $max_calories;
                $_SESSION['max_fats'] = $max_fats;
                $_SESSION['max_proteins'] = $max_proteins;
                $_SESSION['max_carbohydrates'] = $max_carbohydrates;

                header("Location: ./pages/home.php");
                exit();

            } else {
                echo "<script>alert('Incorrect password!');</script>";
            }
        } else {
            echo "<script>alert('User with this email was not found!');</script>";
        }

        $stmt->close();

    } else {

        echo "<script>alert('Request Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - Login</title>
    <link rel="stylesheet" href="./fonts/font-stylesheet.css">
    <link rel="manifest" href="./js/manifest.webmanifest">
    <link rel="apple-touch-icon" href="./icons/website-icon.svg">
    <link rel="apple-touch-icon" sizes="152x152" href="./icons/152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./icons/180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="./icons/167.png">
    <link rel="shortcut icon" href="./icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/login_style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="login-form-container">
                    <img src="./img/light-logo.svg" alt="Chef Assist" class="logo-login">
                    <form method="POST" action="login.php" class="login-form">
                        <div class="email-password-register-container">
                            <div class="email-container">
                                <p class="email-text">Email</p>
                                <input type="email" required name="email" placeholder="example@mail.com" class="email-input">
                            </div>
                            <div class="password-container">
                                <p class="password-text">Password</p>
                                <input type="password" required name="password" placeholder="password" class="password-input">
                            </div>
                            <div class="register-container">
                                <a href="#" class="forgot-password-link">Forgot password?</a>
                                <a href="./register.php" class="register-link">Register</a>
                            </div>
                        </div>
                        
                        <button type="submit" class="log-in-btn">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>