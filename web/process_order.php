<?php
// Include database connection
include('connect.php');

// Start session to access session data (if needed)
session_start();

// Assuming user is logged in and user ID is stored in session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Function to update order status
function update_order_status($order_id, $new_status) {
    global $conn;

    // Convert status to lowercase
    $new_status = strtolower($new_status);

    // Prepare the update query
    $updateQuery = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $new_status, $order_id);

    // Execute the query and return the result
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Handle the update when 'Accept', 'Pending', 'OFD', 'Delivered' button is clicked
if (isset($_POST['action']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    // Define the state transitions
    if ($action == 'accept') {
        // Change status to 'pending'
        if (update_order_status($order_id, 'pending')) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error updating status.";
        }
    }

    if ($action == 'pending') {
        // Change status to 'ofd'
        if (update_order_status($order_id, 'ofd')) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error updating status.";
        }
    }

    if ($action == 'ofd') {
        // Change status to 'delivered'
        if (update_order_status($order_id, 'delivered')) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error updating status.";
        }
    }

    if ($action == 'deliver') {
        // Change status to 'complete'
        if (update_order_status($order_id, 'complete')) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error updating status.";
        }
    }
} else {
    echo "Invalid request.";
}
?>
