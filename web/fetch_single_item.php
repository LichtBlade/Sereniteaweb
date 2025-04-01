<?php
include 'connect.php';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = $conn->prepare("SELECT * FROM stock WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $item = $result->fetch_assoc();
    
    echo json_encode($item);
}?>
