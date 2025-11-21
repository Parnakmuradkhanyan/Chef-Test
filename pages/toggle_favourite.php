<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$dish_id = isset($_POST['dish_id']) ? (int)$_POST['dish_id'] : 0;

if ($dish_id > 0) {
    $stmt = $conn->prepare("SELECT 1 FROM Favourite_Dish WHERE user_id = ? AND dish_id = ?");
    $stmt->bind_param("ii", $user_id, $dish_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmtDel = $conn->prepare("DELETE FROM Favourite_Dish WHERE user_id = ? AND dish_id = ?");
        $stmtDel->bind_param("ii", $user_id, $dish_id);
        $stmtDel->execute();
        $stmtDel->close();
        echo json_encode(["status" => "removed"]);
    } else {
        $stmtIns = $conn->prepare("INSERT INTO Favourite_Dish (user_id, dish_id) VALUES (?, ?)");
        $stmtIns->bind_param("ii", $user_id, $dish_id);
        $stmtIns->execute();
        $stmtIns->close();
        echo json_encode(["status" => "added"]);
    }
    $stmt->close();
}
$conn->close();
