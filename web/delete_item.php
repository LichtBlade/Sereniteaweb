<?php
include 'connect.php';
$id = $_POST['id'];
$conn->query("DELETE FROM stock WHERE id = $id");
?>
