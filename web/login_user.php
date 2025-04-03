<?php
session_start();
include 'connect.php';

// Get raw POST data and decode it into an associative array
$inputData = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $inputData['email'];
    $password = $inputData['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email, password, role, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password using password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role']; // Store role in session

            // Return success response with name, role, and other details
            $response = [
                'status' => 'success',
                'message' => 'Login successful',
                'name' => $user['name'],  // Include the user's name
                'role' => $user['role'],
            ];
            echo json_encode($response);
            exit();
        } else {
            // Return error response for incorrect password
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
            exit();
        }
    } else {
        // Return error response if user not found
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit();
    }
}
?>
