<?php
session_start();
include 'connect.php';  // Make sure this connects to your database

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login if not logged in
    header('Location: login.php');
    exit();
}

// Get the logged-in user's role and ID
$user_email = $_SESSION['user'];
$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];  // Assuming user ID is stored in the session

// Fetch the user data
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
if (!$result) {
    die("Error fetching user data: " . $conn->error);
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="right-section">
      <h2>Your Profile</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
      <p><strong>Created At:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>
  </div>

  <script>
    // Function to load content dynamically
    function loadContent(url, btn) {
      $("#rightSection").html("<p>Loading...</p>");
      $.ajax({
        url: url,
        type: "GET",
        success: function(response) {
          $("#rightSection").html(response); // Replace the content inside right section
        },
        error: function(err) {
          console.error("Error loading content: ", err);
          $("#rightSection").html('<p class="error-message">Failed to load content. Please try again later.</p>');
        }
      });

      // Remove 'active' class from all buttons
      document.querySelectorAll('.category-btn').forEach(button => {
        button.classList.remove('active');
      });

      // Add 'active' class to the clicked button
      btn.classList.add('active');
    }

    // Function to handle logout
    function logout() {
      window.location.href = 'logout.php';
    }
  </script>
</body>
</html>
