
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
    body { /* Body and general text color */
 /* Body and general text color */
body {
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
}

/* Product container text */
.product-container {
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
}

/* Card body text */
.card-body {
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
}

/* Card footer text */
.card-footer {
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
}

/* Card image and borders */
.card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

/* Product row container */
.product-row {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

/* Card styles */
.card {
    border: 1px solid #ffffff !important; /* White border */
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
}

/* Modal text color and white border */
.modal-content {
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
    border: 2px solid #ffffff !important; /* White border */
}

/* Modal header and footer */
.modal-header, .modal-footer {
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
}

/* Table inside modal */
.table th, .table td {
    background-color: #000000;
    color: #FF6500 !important; /* Force orange color */
}

/* Table borders inside modal */
.table, .table th, .table td {
    border: 1px solid #ffffff !important; /* White table borders */
}

 /* Button styles */
 .btn {
        background-color: #FF6500 !important; /* Orange color */
        color: #000000 !important; /* Black text color */
        border: none; /* Remove button border */
    }

    /* Button hover effect */
    .btn:hover {
        background-color: #e85d00 !important; /* Slightly darker shade for hover */
        color: #ffffff !important; /* White text color on hover */
    }

    /* Button focus effect */
    .btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(255, 101, 0, 0.5) !important;
    }

    </style>
</head>
<body style="background-color: #000000;">
<?php include 'navbar.php'; ?>

<!-- Product Section -->

<!-- Product Section -->
<div class="mt-5">
    <h1 class="text-center" style="color: #FF6500;">Our Products</h1>
    <div class="product-container">
        <div class="product-row">
            <?php
            $stmt = $conn->query("SELECT * FROM products");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="col-md-3 mt-3">
                    <div class="card">
                        <img src="images/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p><strong>Price:</strong> $<?php echo $row['price']; ?></p>
                            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
                            <button class="btn btn-warning" onclick="addToCart(<?php echo $row['productid']; ?>, '<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>)">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalLabel">Orders</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table" id="orderTable">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Product Name</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Total</th>
              <th>Order Date</th>
            </tr>
          </thead>
          <tbody id="orderItems">
            <!-- Order items will be injected here dynamically -->
            <?php
            $stmt = $conn->query("SELECT * FROM orders INNER JOIN products ON orders.productid = products.productid");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['orderid']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>$<?php echo $row['price']; ?></td>
                    <td><?php echo $row['qty']; ?></td>
                    <td>$<?php echo $row['totPrice']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table" id="cartTable">
          <thead>
            <tr>
              <th>Select</th>
              <th>Product Name</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="cartItems">
            <!-- Cart items will be injected here dynamically -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onclick="removeSelectedItems()">Remove Selected</button>
        <button type="button" class="btn btn-success" onclick="confirmSelectedOrder()">Confirm Selected Order</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Function to add product to the cart in localStorage
function addToCart(productId, productName, productPrice) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItemIndex = cart.findIndex(item => item.productId === productId);
    if (existingItemIndex !== -1) {
        // Update quantity if item already exists
        cart[existingItemIndex].quantity += 1;
    } else {
        // Add new item to cart
        cart.push({
            productId: productId,
            name: productName,
            price: productPrice,
            quantity: 1
        });
    }
    // Save updated cart to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Item added to cart!');
}

// Function to display cart items from localStorage in the cart modal
function loadCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsContainer = document.getElementById('cartItems');
    cartItemsContainer.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `<tr><td colspan="6" class="text-center">Your cart is empty.</td></tr>`;
    } else {
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            cartItemsContainer.innerHTML += `
                <tr>
                    <td><input type="checkbox" class="selectItem" data-id="${item.productId}" /></td>
                    <td>${item.name}</td>
                    <td>$${item.price.toFixed(2)}</td>
                    <td>
                        <input type="number" class="form-control" value="${item.quantity}" min="1" 
                            onchange="updateQuantity(${item.productId}, this.value)" />
                    </td>
                    <td>$${itemTotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.productId})">Remove</button>
                    </td>
                </tr>
            `;
        });

        // Add total to the table
        cartItemsContainer.innerHTML += `
            <tr>
                <td colspan="4" class="text-end"><strong>Total</strong></td>
                <td><strong>$${total.toFixed(2)}</strong></td>
            </tr>
        `;
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity(productId, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const itemIndex = cart.findIndex(item => item.productId === productId);
    
    if (itemIndex !== -1) {
        // Update the quantity
        cart[itemIndex].quantity = parseInt(quantity);
        // Save the updated cart
        localStorage.setItem('cart', JSON.stringify(cart));
    }
    loadCartItems(); // Reload the cart display
}

// Function to remove item from the cart in localStorage
function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.productId !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItems(); // Reload the cart display
}

// Function to remove selected items from the cart
function removeSelectedItems() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const selectedIds = [...document.querySelectorAll('.selectItem:checked')].map(checkbox => checkbox.getAttribute('data-id'));

    cart = cart.filter(item => !selectedIds.includes(item.productId.toString()));
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCartItems(); // Reload the cart display
}
function confirmSelectedOrder() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const selectedIds = [...document.querySelectorAll('.selectItem:checked')].map(checkbox => checkbox.getAttribute('data-id'));

    if (selectedIds.length === 0) {
        alert('Please select items to confirm!');
        return;
    }

    // Ensure selectedIds are unique and convert to strings for comparison
    const uniqueSelectedIds = [...new Set(selectedIds)];

    // Filter selected items from the cart
    const selectedItems = cart.filter(item => uniqueSelectedIds.includes(item.productId.toString()));

    // Log the selected items to check the filtering process
    console.log('Selected Items:', selectedItems);

    // If no items are selected (just to be extra sure)
    if (selectedItems.length === 0) {
        alert('No items selected!');
        return;
    }

    // Prepare the order data to send to the server
    const orderData = selectedItems.map(item => ({
        productid: item.productId,
        name: item.name,
        price: item.price,
        qty: item.quantity,
        totPrice: item.price * item.quantity
    }));

    // Send the order data to the server in a single request
    fetch('insert_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log('Order placed successfully!');

            // Filter out the selected items from the cart to keep the unselected ones
            const remainingCartItems = cart.filter(item => !uniqueSelectedIds.includes(item.productId.toString()));

            // Update the cart in localStorage with remaining items
            localStorage.setItem('cart', JSON.stringify(remainingCartItems));

            loadCartItems();  // Update the cart display
            alert('Selected items have been confirmed for order!');
        } else {
            console.error('Error placing order:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Load cart items when the page is ready or modal is shown
document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
});

// Also, reload cart items when the modal is shown
const cartModal = document.getElementById('cartModal');
cartModal.addEventListener('shown.bs.modal', function () {
    loadCartItems();
});
</script>
</script>
</body>
</html>
