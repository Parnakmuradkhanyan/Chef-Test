<?php
include './config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone_number = $_POST['telephone_number'];
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

        // ✅ Перевірка на збіг паролів
        if ($password !== $confirm_password) {
            echo "<script>
                    alert('Passwords do not match. Please try again.');
                    window.location.href = 'register.php';
                </script>";
            exit();
        }

    $user_photo = 'img/profile-pic-default.svg';

    if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmp = $_FILES['user_photo']['tmp_name'];
        $fileName = uniqid('avatar_') . "_" . basename($_FILES['user_photo']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            $user_photo = $targetPath;
            
        }
    }

    $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {

        echo "<script>
                alert('This email is already registered. Please use another email.');
                window.location.href = 'register.php';
              </script>";
        $checkStmt->close();
        exit();

    }
    $checkStmt->close();

    $stmt = $conn->prepare("INSERT INTO users (name, surname, email, telephone_number, password, user_photo) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {

        echo "<script>alert('Error of creating the request');</script>";
        exit();

    }

    $stmt->bind_param("ssssss", $name, $surname, $email, $telephone_number, $password, $user_photo);

    if ($stmt->execute()) {

        echo "<script>
                alert('Registration was succesfull!');
                window.location.href = 'login.php';
              </script>";
        exit();

    } else {
        echo "<script>alert('Error happened during the registration: " . addslashes($stmt->error) . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Assist - Register</title>
    <link rel="stylesheet" href="./fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="./icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/register_style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="register-form-container">
                    <img src="./img/color-logo.svg" alt="Chef Assist" class="logo-register">
                    <form method="POST" action="register.php" enctype="multipart-form-data" class="register-form">
                        <div class="inputs-container">
                            <div class="input-container">
                                <p class="input-text">Name</p>
                                <input type="text" required name="name" placeholder="Your name" class="input">
                            </div>

                            <div class="input-container">
                                    <p class="input-text">Surname</p>
                                    <input type="text" required name="surname" placeholder="Your surname" class="input">
                            </div>

                            <div class="input-container">
                                    <p class="input-text">Email</p>
                                    <input type="email" required name="email" placeholder="example@mail.com" class="input">
                            </div>

                            <div class="input-container">
                                    <p class="input-text">Telephone Number</p>
                                    <input type="text" required name="telephone_number" placeholder="01234435" class="input">
                            </div>

                            <div class="input-container">
                                    <p class="input-text">Password</p>
                                    <input type="password" required name="password" placeholder="Password" class="input">
                            </div>

                            <div class="input-container">
                                    <p class="input-text">Confirm Password</p>
                                    <input type="password" required name="confirm_password" placeholder="Password" class="input">
                            </div>

                        </div>
                        
                        <div class="login-container">
                                <p class="already-have-an-account-text">Already have an account?</p>
                                <a href="./login.php" class="login-link">Log in</a>
                        </div>
                        <button type="submit" class="register-btn">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/bootstrap.bundle.min.js"></script>
    
</body>
</html>