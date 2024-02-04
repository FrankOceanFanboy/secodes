<?php

// Database connection parameters (replace with your actual database credentials)
$hostname = "your_external_host";
$username = "your_external_username";
$password = "your_external_password";
$database = "your_external_database";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get all orders
function getAllOrders() {
    global $conn;
    $query = "SELECT * FROM orders";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Function to update order status
function updateOrderStatus($orderId, $status) {
    global $conn;
    $query = "UPDATE orders SET order_status = '$status' WHERE id = $orderId";
    return mysqli_query($conn, $query);
}

// Handle order status update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $orderId = $_POST["order_id"];
    $newStatus = $_POST["new_status"];

    if (updateOrderStatus($orderId, $newStatus)) {
        echo "Order status updated successfully.";
    } else {
        echo "Error updating order status.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracker</title>
</head>
<body>

    <h2>Order Tracker</h2>

    <h3>All Orders</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Order Status</th>
            <th>Action</th>
        </tr>
        <?php
        $orders = getAllOrders();
        while ($row = mysqli_fetch_assoc($orders)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['customer_name']}</td>";
            echo "<td>{$row['order_status']}</td>";
            echo "<td><form method='post'><input type='hidden' name='order_id' value='{$row['id']}'><select name='new_status'>
                    <option value='Pending'>Pending</option>
                    <option value='Shipped'>Shipped</option>
                    <option value='Delivered'>Delivered</option>
                </select><input type='submit' name='update_status' value='Update'></form></td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>
