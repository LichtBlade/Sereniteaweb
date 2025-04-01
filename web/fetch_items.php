<?php
include 'connect.php';
$result = $conn->query("SELECT * FROM stock ");
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
echo json_encode($items);
?>
