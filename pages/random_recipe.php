<?php
session_start();
require_once '../config.php';

$result = $conn->query("SELECT dish_id FROM Dish ORDER BY RAND() LIMIT 1");
$row = $result->fetch_assoc();

$conn->close();

if ($row) {
    header("Location: recipe_page.php?dish_id=" . $row['dish_id']);
    exit;
} else {
    die("No recipes found!");
}
?>
