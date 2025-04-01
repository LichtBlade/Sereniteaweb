<?php
include 'connect.php';

$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];

// Convert empty values to NULL
$price = isset($_POST['price']) && $_POST['price'] !== "" ? (float)$_POST['price'] : null;
$small_price = isset($_POST['small_price']) && $_POST['small_price'] !== "" ? (float)$_POST['small_price'] : null;
$medium_price = isset($_POST['medium_price']) && $_POST['medium_price'] !== "" ? (float)$_POST['medium_price'] : null;
$large_price = isset($_POST['large_price']) && $_POST['large_price'] !== "" ? (float)$_POST['large_price'] : null;

// Handle image upload
$image_path = "";
if (!empty($_FILES['image']['name'])) {
    $image_path = "uploads/" . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
}

// Fetch the category from the database
$categoryQuery = $conn->prepare("SELECT category FROM stock WHERE id = ?");
$categoryQuery->bind_param("i", $id);
$categoryQuery->execute();
$categoryResult = $categoryQuery->get_result();
$categoryRow = $categoryResult->fetch_assoc();
$category = $categoryRow['category']; // Now we have the category
$categoryQuery->close();

// Start building the query
$query = "UPDATE stock SET name=?, description=?";
$params = [$name, $description];
$types = "ss";

// Add fields dynamically based on category
if ($category == "Food") {
    if (!is_null($price)) {
        $query .= ", price=?";
        $params[] = $price;
        $types .= "d";
    }
} elseif ($category == "Beverages") {
    if (!is_null($small_price)) {
        $query .= ", small_price=?";
        $params[] = $small_price;
        $types .= "d";
    }
    if (!is_null($medium_price)) {
        $query .= ", medium_price=?";
        $params[] = $medium_price;
        $types .= "d";
    }
    if (!is_null($large_price)) {
        $query .= ", large_price=?";
        $params[] = $large_price;
        $types .= "d";
    }
}

// Handle image update
if (!empty($image_path)) {
    $query .= ", image_path=?";
    $params[] = $image_path;
    $types .= "s";
}

$query .= " WHERE id=?";
$params[] = $id;
$types .= "i";

// Prepare and execute statement
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo "Item updated successfully!";
} else {
    echo "Error updating item: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>
