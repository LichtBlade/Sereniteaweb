
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to Login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>view</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      height: 100vh;
      overflow: hidden;
    }
    .container {
      display: flex;
      width: 100%;
    }
    .left-section {
      width: 13%;
      background-color: #f4f4f4;
      padding: 15px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;      
    }

    .logout-btn {
      margin-top: auto; 
      padding: 10px 20px;
      background-color: transparent;
      color: #91675B;
      font-weight: bold;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      text-align: left;
      font-size: 16px;
      width: 100%;
      outline: none;
    }

      .logout-btn:hover {
      background-color: #e0e0e0; /* Light grey on hover */
      color: #5d4037; /* Dark brown text on hover */
      border-radius: 20px; /* Rounded edges when hovered */
      padding: 12px 25px; /* Slightly more padding for a rounded effect */
    }

      .logout-btn.active {
      background-color: #d3d3d3; /* Slight grey when clicked */
      color: #5d4037; /* Dark brown text when clicked */
      border-radius: 20px; /* Rounded edges when pressed */
      padding: 12px 25px; /* Slightly more padding for a rounded effect */
    }

    .left-section h2 {
      margin: 0;
      text-align: center;
    }
    .left-section img {
      width: 80%;
      border-radius: 8px;
    }

    .category-btn{
      padding: 10px 20px;
      background-color: transparent; /* No background color by default */
      color: #91675B; /* Default text color */
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: left;
      font-size: 16px;
      font-weight: bold;
      width: 100%;
      transition: background-color 0.3s, color 0.3s, border-radius 0.3s;
      outline: none;
    }

    /* Hover effect */
    .category-btn:hover{
      background-color: #e0e0e0; /* Light grey on hover */
      color: #5d4037; /* Dark brown text on hover */
      border-radius: 20px; /* Curved edges when hovered */
      padding: 12px 25px; /* Slightly more padding for a rounded effect */
    }

    /* Active effect (pressed) */
    .category-btn.active{
      background-color: #91675B; /* Slight grey when clicked */
      color:rgb(255, 255, 255); /* Dark brown text when clicked */
      border-radius: 20px; /* Curved edges when pressed */
      padding: 12px 25px; /* Slightly more padding for a rounded effect */
    }

    .right-section {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      background-color:rgb(255, 255, 255); /* Add this line for the background color */
    }
    
    .items-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .item-card {
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }
    .item-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .content {
      padding: 15px;
    }
    .content h3 {
      margin: 0 0 10px;
      font-size: 18px;
    }
    .content p {
      margin: 0;
      font-size: 14px;
      color: #555;
    }
    .delete-btn {
      background-color: red;
      color: white;
      border: none;
      padding: 8px 12px;
      cursor: pointer;
      border-radius: 5px;
      font-size: 14px;
      margin: 10px;
      align-self: flex-end;
    }
    .delete-btn:hover {
      background-color: darkred;
    }
    .error-message {
      color: red;
      text-align: center;
      margin-top: 10px;
    }
    
  </style>
</head>
<body>
  <div class="container">
    <div class="left-section">
      <img src="uploads/logo.png" alt="Categories Image">
      <button class="category-btn" onclick="loadContent('dashboard.php',this)">Dashboard</button>
      <button class="category-btn" onclick="loadContent('menu.php',this)">Menu</button>
      <button class="category-btn" onclick="loadContent('customer.html',this)">User</button>
      <button class="category-btn" onclick="loadContent('orders.html',this)">Orders</button>
      <button class="logout-btn" onclick="logout()">Logout</button>
    </div>
    <div class="right-section" id="rightSection">
      <iframe src="dashboard.php" width="100%" height="100%" style="border:none;"></iframe>
    </div>
  </div>

  <script>
    // Function to load content and manage active button state
    function loadContent(url, btn) {
      // Dynamically load content via AJAX
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

      if (url === 'items.html') {
        setTimeout(function() {
          if (typeof loadCategory === 'function') {
            loadCategory('Food');
          }
        }, 500); // Delay to ensure script execution
      }

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

    // Function to delete an item (used in deletion interface)
    function deleteItem(id) {
      if (confirm("Are you sure you want to delete this item?")) {
        $.ajax({
          url: "delete_item.php",
          type: "POST",
          data: { id: id },
          success: function(response) {
            alert(response);
            loadContent('fetch_items_delete.php'); // Reload the page with updated data
          },
          error: function(err) {
            console.error("Error deleting item:", err);
          }
        });
      }
    }
  </script>
</body>
</html>
