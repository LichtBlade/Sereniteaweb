<?php
include('connect.php');  // Database connection file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Read raw input
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);  // Convert to associative array

// Debug: Check if JSON decoding worked
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'JSON decoding failed: ' . json_last_error_msg()]);
    exit;
}

// Validate input
if (!isset($data['ids'], $data['contact_number'], $data['address']) || !is_array($data['ids'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$ids = $data['ids'];  // Expecting an array of IDs
$contact_number = $data['contact_number'];
$address = $data['address'];

// Convert array of IDs into a comma-separated string for SQL
$id_placeholders = implode(',', array_fill(0, count($ids), '?'));

// Prepare SQL query
$query = "UPDATE orders SET contact_number = ?, address = ?, status = 'Pending' WHERE id IN ($id_placeholders)";
$stmt = $conn->prepare($query);

// Bind parameters dynamically
$types = "ss" . str_repeat('i', count($ids)); // 'ss' for contact_number & address, 'i' for each order ID
$stmt->bind_param($types, $contact_number, $address, ...$ids);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Orders updated to Pending successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update orders']);
}

$conn->close();
?>
