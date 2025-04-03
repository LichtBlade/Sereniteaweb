<?php
include('connect.php');

header('Content-Type: application/json');

// SQL query to fetch orders with related item details
$query = "
    SELECT orders.*, stock.name AS item_name, stock.price AS item_price, stock.image_path AS item_image
    FROM orders
    JOIN stock ON orders.item_id = stock.id
";

$result = $conn->query($query);

$orders = [];
while ($row = $result->fetch_assoc()) {
    // Create a nested array for item details
    $order = [
        "id" => $row["id"],
        "customer_name" => $row["customer_name"],
        "item_id" => [
            "id" => $row["item_id"], // Item ID from orders
            "name" => $row["item_name"], // Item name from stock
        ],
        "quantity" => $row["quantity"],
        "total_price" => $row["total_price"],
        "order_date" => $row["order_date"],
        "status" => $row["status"],
        "item_name" => $row["item_name"],
        "item_price" => $row["item_price"],
        "item_image" => $row["item_image"]
    ];
    $orders[] = $order;
}

echo json_encode($orders, JSON_PRETTY_PRINT);
?>
