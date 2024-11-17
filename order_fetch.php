<?php
// insert_order.php or order_fetch.php (Create a new file for fetching orders)
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "SELECT * FROM orders";
    $stmt = $conn->query($query);

    $orders = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $orders[] = $row;
    }

    echo json_encode($orders);
}
?>
