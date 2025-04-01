<?php
include 'connect.php'; // Ensure this file connects to your MySQL database

// Fetch order counts
$pendingOrders = 0;
$completedOrders = 0;
$outForDelivery = 0;
$totalOrders = 0;
$itemOrders = [];

$sqlCheckStatus = "SHOW COLUMNS FROM orders LIKE 'status'";
$resultCheckStatus = $conn->query($sqlCheckStatus);

if ($resultCheckStatus->num_rows > 0) {
    $sqlPending = "SELECT COUNT(*) AS pending FROM orders WHERE status = 'pending'";
    $sqlCompleted = "SELECT COUNT(*) AS completed FROM orders WHERE status = 'completed'";
    $sqlOutForDelivery = "SELECT COUNT(*) AS out_delivery FROM orders WHERE status = 'out for delivery'";
    $sqlTotal = "SELECT COUNT(*) AS total FROM orders";

    $resultPending = $conn->query($sqlPending);
    $resultCompleted = $conn->query($sqlCompleted);
    $resultOutForDelivery = $conn->query($sqlOutForDelivery);
    $resultTotal = $conn->query($sqlTotal);

    if ($resultPending->num_rows > 0) {
        $pendingOrders = $resultPending->fetch_assoc()['pending'];
    }
    if ($resultCompleted->num_rows > 0) {
        $completedOrders = $resultCompleted->fetch_assoc()['completed'];
    }
    if ($resultOutForDelivery->num_rows > 0) {
        $outForDelivery = $resultOutForDelivery->fetch_assoc()['out_delivery'];
    }
    if ($resultTotal->num_rows > 0) {
        $totalOrders = $resultTotal->fetch_assoc()['total'];
    }
}

// Fetch item order counts with LEFT JOIN to get item names
$sqlItems = "SELECT o.item_id, COUNT(*) AS count, s.name 
             FROM orders o 
             LEFT JOIN stock s ON o.item_id = s.id 
             GROUP BY o.item_id";
$resultItems = $conn->query($sqlItems);

while ($row = $resultItems->fetch_assoc()) {
    $itemName = $row['name'] ?? 'Unknown Item'; // Handle missing names
    $itemOrders[$itemName] = $row['count'];
}

// Convert itemOrders to JSON for JavaScript
$itemOrdersJson = !empty($itemOrders) ? json_encode($itemOrders) : '{}';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.1/chart.umd.min.js"></script>
  
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .dashboard-container {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 20px;
      margin-left: 20px;
      margin-top: 20px;
    }

    .card-container {
      display: flex;
      gap: 20px;
      justify-content: flex-start; /* Move cards to the right */
      width: 100%;
    }

    .card {
      width: 250px;
      padding: 10px;
      background: #91675B;
      border-radius: 10px;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
      color: white;
    }

    .card h2 {
      margin: 0;
      font-size: 18px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
      margin-top: 10px;
    }

    .charts-container {
      display: flex;
      gap: 20px;
      margin-top: 40px;
    }

    .chart-card {
      width: 520px;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 10px;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .chart-container {
      width: 100%;
      height: 250px;
    }
  </style>

</head>
<body>
  <div class="dashboard-container">
    <div class="card-container">
      <div class="card">
        <h2>Pending Orders</h2>
        <p><?php echo $pendingOrders; ?></p>
      </div>
      <div class="card">
        <h2>Out for Delivery</h2>
        <p><?php echo $outForDelivery; ?></p>
      </div>
      <div class="card">
        <h2>Orders Completed</h2>
        <p><?php echo $completedOrders; ?></p>
      </div>
      <div class="card">
        <h2>Total Orders</h2>
        <p><?php echo $totalOrders; ?></p>
      </div>
    </div>

    <div class="charts-container">
      <div class="chart-card">
        <h2>Frequently Ordered</h2>
        <div class="chart-container">
          <canvas id="orderChart"></canvas>
        </div>
      </div>

      <div class="chart-card">
        <h2>Weekly Sales</h2>
        <div class="chart-container">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      if (typeof Chart === "undefined") {
        console.error("Chart.js is not loaded!");
        return;
      }

      let itemOrders = <?php echo $itemOrdersJson; ?>;
      
      if (Object.keys(itemOrders).length === 0) {
        console.warn("No order data available. Using placeholder data.");
        itemOrders = {
          "Iced Latte": 10,
          "Cappuccino": 15,
          "Machiato": 8,
          "Matcha": 5,
          "Fries": 10
        };
      }

      console.log("Item Orders Data:", itemOrders);

      // Bar Chart for Frequently Ordered Items
      new Chart(document.getElementById("orderChart").getContext("2d"), {
        type: "bar",
        data: {
          labels: Object.keys(itemOrders),
          datasets: [{
            label: "Orders",
            data: Object.values(itemOrders),
            backgroundColor: "#91675B"
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 10 }
            }
          }
        }
      });

      // Line Graph for Weekly Sales
      new Chart(document.getElementById("salesChart").getContext("2d"), {
        type: "line",
        data: {
          labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
          datasets: [{
            label: "Sales",
            data: [40, 30, 50, 60, 70, 80, 90],
            borderColor: "#FF6384",
            backgroundColor: "rgb(224, 165, 120)",
            borderWidth: 2,
            fill: true
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 100 }
            }
          }
        }
      });
    });
  </script>
</body>
</html>
