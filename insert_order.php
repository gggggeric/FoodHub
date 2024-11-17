<?php
include 'db.php'; // Include your database connection

// Read the raw POST data (JSON format)
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is not empty and is an array
if ($data && is_array($data)) {
    // Loop through each cart item and insert them into the 'orders' table
    foreach ($data as $item) {
        // Validate the required fields
        if (isset($item['productid'], $item['name'], $item['price'], $item['qty'])) {
            // Extract the item details
            $productid = $item['productid'];
            $name = $item['name'];
            $price = $item['price'];
            $qty = $item['qty'];  // Corrected: use 'qty' instead of 'quantity'
            $totPrice = $price * $qty;

            // Prepare the SQL statement to insert the order into the database
            $stmt = $conn->prepare("INSERT INTO orders (productid, name, price, qty, totPrice) VALUES (:productid, :name, :price, :qty, :totPrice)");

            // Bind the values to the statement
            $stmt->bindParam(':productid', $productid);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':qty', $qty);
            $stmt->bindParam(':totPrice', $totPrice);

            // Execute the statement
            $stmt->execute();
        } else {
            // If any required fields are missing, return an error message
            echo json_encode(["status" => "error", "message" => "Invalid item data received"]);
            exit;  // Stop further execution
        }
    }

    // Return a success message as JSON
    echo json_encode(["status" => "success", "message" => "Order confirmed successfully!"]);
} else {
    // If no data was received or data is incorrect, return an error message
    echo json_encode(["status" => "error", "message" => "Invalid order data"]);
}
?>
