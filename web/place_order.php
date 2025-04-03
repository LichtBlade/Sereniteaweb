<?php
include('connect.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['customer_name'], $data['item_id'], $data['quantity'], $data['total_price'])) {
    $customer_name = $data['customer_name'];
    $item_id = $data['item_id'];
    $quantity = $data['quantity'];
    $total_price = $data['total_price'];
    $order_date = date("Y-m-d H:i:s");
    $status = "Add to Cart";

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, item_id, quantity, total_price, order_date, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siidss", $customer_name, $item_id, $quantity, $total_price, $order_date, $status);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Order placed successfully"]);
    } else {
        echo json_encode(["error" => "Failed to place order"]);
    }
} else {
    echo json_encode(["error" => "Invalid data"]);
}
?>
