<?php
include 'connect.php';


$category = $_POST['category'];
$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'];

$small_price = isset($_POST['small_price']) ? $_POST['small_price'] : null;
$medium_price = isset($_POST['medium_price']) ? $_POST['medium_price'] : null;
$large_price = isset($_POST['large_price']) ? $_POST['large_price'] : null;

$image_path = "";
if (isset($_FILES['image'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $target_file;
    }
}

$query = $conn->prepare("INSERT INTO stock (category, name, price, image_path, description, small_price, medium_price, large_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$query->bind_param("ssdssddd", $category, $name, $price, $image_path, $description, $small_price, $medium_price, $large_price);
$query->execute();
?>
