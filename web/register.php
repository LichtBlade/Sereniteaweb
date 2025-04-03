<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the incoming JSON request
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the data was received
    if (isset($data['email']) && isset($data['password']) && isset($data['role']) && isset($data['name'])) {
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];
        $name = $data['name'];  // New name field

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "Email already exists"]);
            exit();
        }

        // Insert the new user into the database, including the name
        $stmt = $conn->prepare("INSERT INTO users (email, password, role, name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $hashed_password, $role, $name);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User created successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to create user"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing email, password, role, or name"]);
    }
}
?>
