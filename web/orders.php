<?php
// Connect to your database
include('connect.php');

// Start the session to access session data
session_start();

// Assuming user is logged in and the user ID is stored in session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch all orders from the database
$query = "SELECT * FROM orders";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all orders in an array
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Orders List</h2>
    <div class="orders-list">
        <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Item ID</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Action</th> <!-- Add a column for the action buttons -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td data-label="Customer Name"><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td data-label="Item ID"><?= htmlspecialchars($order['item_id']) ?></td>
                            <td data-label="Quantity"><?= htmlspecialchars($order['quantity']) ?></td>
                            <td data-label="Total Price"><?= htmlspecialchars($order['total_price']) ?></td>
                            <td data-label="Order Date"><?= htmlspecialchars($order['order_date']) ?></td>
                            <td data-label="Status"><?= htmlspecialchars($order['status']) ?></td>
                            <td data-label="Contact Number"><?= htmlspecialchars($order['contact_number']) ?></td>
                            <td data-label="Address"><?= htmlspecialchars($order['address']) ?></td>
                            <td>
                                <!-- Accept Button to update order status to Pending -->
                                <?php if ($order['status'] !== 'Pending' && $order['status'] !== 'Complete'): ?>
                                    <form method="POST" action="process_order.php">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit">Accept</button>
                                    </form>
                                <?php else: ?>
                                    <span>Already Accepted</span>
                                <?php endif; ?>

                                <!-- OFD Button to update order status to 'ofd' -->
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <form method="POST" action="process_order.php">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <input type="hidden" name="action" value="pending">
                                        <button type="submit">OFD</button>
                                    </form>
                                <?php elseif ($order['status'] === 'ofd'): ?>
                                    <span>OFD</span>
                                <?php endif; ?>

                                <!-- Delivered Button to update order status to Delivered -->
                                <?php if ($order['status'] === 'ofd'): ?>
                                    <form method="POST" action="process_order.php">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <input type="hidden" name="action" value="ofd">
                                        <button type="submit">Delivered</button>
                                    </form>
                                <?php elseif ($order['status'] === 'delivered'): ?>
                                    <span>Delivered</span>
                                <?php endif; ?>

                                <!-- Complete Button to update order status to Complete -->
                                <?php if ($order['status'] === 'delivered'): ?>
                                    <form method="POST" action="process_order.php">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <input type="hidden" name="action" value="deliver">
                                        <button type="submit">Complete</button>
                                    </form>
                                <?php elseif ($order['status'] === 'complete'): ?>
                                    <span>Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">No orders found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
