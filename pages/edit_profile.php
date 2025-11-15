<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$name = $_SESSION['name'] ?? '';
$surname = $_SESSION['surname'] ?? '';
$email = $_SESSION['email'] ?? '';
$telephone_number = $_SESSION['telephone_number'] ?? '0000000';
$user_photo = $_SESSION['user_photo'] ?? 'img/user-no-profile-pic-photo.svg';
$allergic_ingredients = $_SESSION['allergic_ingredients'] ?? '';
$diet_limit_ingredients = $_SESSION['diet_limit_ingredients'] ?? '';
$level_of_cooking = $_SESSION['level_of_cooking'] ?? '';
$max_calories = $_SESSION['max_calories'] ?? 0;
$max_fats  = $_SESSION['max_fats'] ?? 0;
$max_proteins  = $_SESSION['max_proteins'] ?? 0;
$max_carbohydrates  = $_SESSION['max_carbohydrates'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone_number = $_POST['telephone_number'] ?? '';
    $allergic_ingredients = $_POST['allergic_ingredients'] ?? '';
    $diet_limit_ingredients = $_POST['diet_limit_ingredients'] ?? '';
    $level_of_cooking = $_POST['level_of_cooking'] ?? '';
    $max_calories = $_POST['max_calories'] ?? 0;
    $max_fats = $_POST['max_fats'] ?? 0;
    $max_proteins = $_POST['max_proteins'] ?? 0;
    $max_carbohydrates = $_POST['max_carbohydrates'] ?? 0;

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('This email is occupied by another user.'); window.history.back();</script>";
        exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE telephone_number = ? AND user_id != ?");
    $stmt->bind_param("si", $telephone_number, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('This telephone number is occupied by another user.'); window.history.back();</script>";
        exit();
    }
    $stmt->close();

    if (isset($_FILES['user_cover_photo']) && $_FILES['user_cover_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/';
        $fileName = uniqid('profile_') . '_' . basename($_FILES['user_cover_photo']['name']);
        $user_photo = 'img/' . $fileName;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['user_cover_photo']['tmp_name'], $filePath)) {
            die("Moving the file error!");
        }
    }

    $stmt = $conn->prepare("UPDATE users 
        SET name=?, surname=?, email=?, telephone_number=?, allergic_ingredients=?, diet_limit_ingredients=?, level_of_cooking=?, 
            max_calories=?, max_fats=?, max_proteins=?, max_carbohydrates=?, user_photo=? 
        WHERE user_id=?");

    $stmt->bind_param("ssssssssssssi", 
        $name, $surname, $email, $telephone_number, 
        $allergic_ingredients, $diet_limit_ingredients, $level_of_cooking, 
        $max_calories, $max_fats, $max_proteins, $max_carbohydrates, 
        $user_photo, $user_id
    );

    if ($stmt->execute()) {

        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['email'] = $email;
        $_SESSION['telephone_number'] = $telephone_number;
        $_SESSION['allergic_ingredients'] = $allergic_ingredients;
        $_SESSION['diet_limit_ingredients'] = $diet_limit_ingredients;
        $_SESSION['level_of_cooking'] = $level_of_cooking;
        $_SESSION['max_calories'] = $max_calories;
        $_SESSION['max_fats'] = $max_fats;
        $_SESSION['max_proteins'] = $max_proteins;
        $_SESSION['max_carbohydrates'] = $max_carbohydrates;
        $_SESSION['user_photo'] = $user_photo;

        echo "<script>alert('Profile was successfully updated!'); window.location.href = '../pages/profile.php';</script>";
        exit();
    } else {
        die("Request error: " . $stmt->error);
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
    <title>Chef Assist - Edit My profile</title>
    <link rel="stylesheet" href="../fonts/font-stylesheet.css">
    <link rel="shortcut icon" href="../icons/website-icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/edit_profile_style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="navbar">
                    <a href="./home.php" class="logo-home-link"><img src="../img/color-logo.svg" alt="Chef Assist" class="logo-home-link-img"></a>
                    <div class="profile-menu-container">
                        <a href="./profile.php" class="profile-pic-link"><img src="../img/profile-pic-default.svg" alt="Profile" class="profile-pic"></a>
                        <a href="./menu.php" class="menu-link"><img src="../icons/menu-icon.svg" alt="Menu" class="menu-link"></a>
                    </div>
                </div>

                <div class="arrow-back-container">
                    <a href="./home.php" class="link-arrow-back"><img src="../icons/arrow-back-icon.svg" class="arrow-back-icon" alt="Back"></a>
                    <p class="page-name">Edit My profile</p>
                </div>



                <div class="profile-container">
                    <form  action="edit_profile.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
                         
                        <div class="user-pic-col">
                            <div class="profile-cover-image-div">
                                <img id="profile-cover-image-preview" src="../<?php echo $user_photo; ?>" class="profile-cover-image-preview">
                                <input type="file" name="user_cover_photo" class="custom-file-input" id="userCoverPhoto" accept="image/*" onchange="previewImageCover(event)">
                                <label for="userCoverPhoto" class="custom-file-label profile-cover-change-btn"><div class="change-profile-pic-btn-icon"></div></label>
                            </div>
                        </div>

                        <div class="main-elements-inputs-container">

                            <div class="input-container">
                                <label class="input-label">Name</label>
                                <input required type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Your name" class="input-style">
                            </div>

                            <div class="input-container">
                                <label class="input-label">Surame</label>
                                <input required type="text" name="surname" value="<?php echo htmlspecialchars($surname); ?>" placeholder="Your surname" class="input-style">
                            </div>

                            <div class="input-container">
                                <label class="input-label">Email</label>
                                <input required type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="example@mail.com" class="input-style">
                            </div>

                            <div class="input-container">
                                <label class="input-label">Telephone Number</label>
                                <input required type="text" name="telephone_number" value="<?php echo htmlspecialchars($telephone_number); ?>" placeholder="0123445" class="input-style">
                            </div>

                            <div class="input-container">
                                <label class="input-label">Level of Cooking</label>
                                <select class="input-style" name="level_of_cooking">
                                    <option value="Beginner" <?php echo $level_of_cooking === 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                                    <option value="Average" <?php echo $level_of_cooking === 'Average' ? 'selected' : ''; ?>>Average</option>
                                    <option value="Professional" <?php echo $level_of_cooking === 'Professional' ? 'selected' : ''; ?>>Professional</option>
                                </select>
                            </div>

                            <div class="input-container">
                                <div class="label-icon-container"><label class="input-label">Allergic Ingredients</label><img src="../icons/allergic-ingredients-icon.svg" alt="Icon" class="label-icons"></div>
                                <textarea class="textarea-style" name="allergic_ingredients" placeholder="Peanuts, apples"><?php echo htmlspecialchars($allergic_ingredients); ?></textarea>
                            </div>

                            <div class="input-container">
                                <div class="label-icon-container"><label class="input-label">Diet Limits</label><img src="../icons/diet-llimits-icon.svg" alt="Icon" class="label-icons"></div>
                                <textarea class="textarea-style" name="diet_limit_ingredients" placeholder="Peanuts, apples"><?php echo htmlspecialchars($diet_limit_ingredients); ?></textarea>
                            </div>

                            <div class="input-container">
                                <label class="input-label">Maximal calories per dish - kcal</label>
                                <input type="number" name="max_calories" value="<?php echo htmlspecialchars($max_calories); ?>" placeholder="1233" min="0" class="input-style">
                            </div>

                            <div class="input-container">
                                <label class="input-label">Maximal fats per dish - g</label>
                                <input type="number" name="max_fats" value="<?php echo htmlspecialchars($max_fats); ?>" placeholder="300"  min="0" class="input-style">
                            </div>

                            <div class="input-container">
                                <label class="input-label">Maximal proteins per dish - g</label>
                                <input type="number" name="max_proteins" value="<?php echo htmlspecialchars($max_proteins); ?>" placeholder="30" min="0" class="input-style">
                            </div>

                            <div class="input-container">
                                <label class="input-label">Maximal carbohydrates per dish - g</label>
                                <input type="number" name="max_carbohydrates" value="<?php echo htmlspecialchars($max_carbohydrates); ?>" placeholder="30" min="0" class="input-style">
                            </div>

                        </div>

                        <button type="submit" class="save-the-changes-btn"><img src="../icons/save-the-changes-icon.svg" class="save-the-changes-icon" alt="Icon"><p class="save-the-changes-text">Save the changes</p></button>

                    </form>
                </div>


            </div>
        </div>
    </div>


    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/edit_profile_script.js"></script>
</body>
</html>