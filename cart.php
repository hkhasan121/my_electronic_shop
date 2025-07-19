<?php
session_start();
include 'includes/db.php'; // Make sure this path is correct for your DB connection

// If the user is not logged in, redirect them to the login page.
// The cart functionality relies on a user_id from the session.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Or wherever your login page is
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// --- Add to Cart Logic (Database-driven) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    // For database storage, we only need product_id and user_id.
    // Product name, price, image will be fetched from the 'products' table when displaying.

    // Check if the product already exists in the user's cart in the database
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?"); // Changed cart_items to cart
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the product is already in the cart, increase its quantity
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?"); // Changed cart_items to cart
        $update_stmt->bind_param("ii", $user_id, $product_id);
        $update_stmt->execute();
    } else {
        // If the product is not in the cart, add it as a new item
        $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)"); // Changed cart_items to cart
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        $insert_stmt->execute();
    }
    
    // Redirect back to cart.php to show updated cart and prevent re-submission
    header("Location: cart.php");
    exit();
}

// --- Handle Quantity Update or Item Removal (Database-driven) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    $product_id_to_update = $_POST['product_id'];
    $action = $_POST['action']; // 'increase', 'decrease', 'remove'

    if ($action == 'increase') {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?"); // Changed cart_items to cart
        $stmt->bind_param("ii", $user_id, $product_id_to_update);
        $stmt->execute();
    } elseif ($action == 'decrease') {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?"); // Changed cart_items to cart
        $stmt->bind_param("ii", $user_id, $product_id_to_update);
        $stmt->execute();

        // After decrementing, remove the item if its quantity drops to 0 or less
        $delete_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ? AND quantity <= 0"); // Changed cart_items to cart
        $delete_stmt->bind_param("ii", $user_id, $product_id_to_update);
        $delete_stmt->execute();
    } elseif ($action == 'remove') {
        // Explicitly remove the item regardless of quantity
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?"); // Changed cart_items to cart
        $stmt->bind_param("ii", $user_id, $product_id_to_update);
        $stmt->execute();
    }
    // Redirect back to cart.php to reflect changes and prevent re-submission
    header("Location: cart.php");
    exit();
}

// --- Fetch and Display Cart Items (Database-driven) ---
$cart_items_db = []; // Initialize an array to hold cart items fetched from DB
$query = "
    SELECT ci.product_id, ci.quantity, p.name, p.price, p.image
    FROM cart ci  -- Changed cart_items to cart
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
    -- ORDER BY ci.added_at DESC -- Removed this line
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items_db[] = $row; // Populate the array with items from the database
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet" />
    <style>
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center; /* Center the buttons and input */
            gap: 5px; /* Space between buttons and input */
        }
        .quantity-control button {
            width: 30px; /* Fixed width for buttons */
            height: 30px; /* Fixed height for buttons */
            line-height: 1; /* Adjust line height for vertical alignment */
            padding: 0; /* Remove padding */
            font-size: 1.2rem; /* Larger font for +/- signs */
            display: flex; /* Use flexbox to center content */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            border-radius: .25rem; /* Slightly rounded corners */
        }
        .quantity-control input[type="text"] {
            width: 50px; /* Fixed width for quantity display */
            text-align: center;
            -moz-appearance: textfield; /* Firefox hide arrows */
            pointer-events: none; /* Make input not directly editable */
        }
        .quantity-control input[type="text"]::-webkit-outer-spin-button,
        .quantity-control input[type="text"]::-webkit-inner-spin-button {
            -webkit-appearance: none; /* Hide Chrome/Safari arrows */
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-center">🛒 Your Shopping Cart</h2>

    <?php if (!empty($cart_items_db)): // Now using $cart_items_db from database ?>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price (per item)</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                // Loop through items fetched from the database
                foreach ($cart_items_db as $item):
                    $total_price = $item['price'] * $item['quantity'];
                    $grand_total += $total_price;
                ?>
                <tr>
                    <td><img src="./images/<?= htmlspecialchars($item['image']) ?>" width="60" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>
                        <div class="quantity-control">
                            <form action="cart.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" name="update_cart" class="btn btn-secondary btn-sm">-</button>
                            </form>
                            <input type="text" class="form-control form-control-sm" value="<?= $item['quantity'] ?>" readonly>
                            <form action="cart.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" name="update_cart" class="btn btn-primary btn-sm">+</button>
                            </form>
                        </div>
                    </td>
                    <td>$<?= number_format($total_price, 2) ?></td>
                    <td>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" name="update_cart" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4 class="text-end">Grand Total: $<?= number_format($grand_total, 2) ?></h4>
        <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p class="text-center">🛒 Your cart is currently empty.</p>
        <div class="d-flex justify-content-center mt-4">
            <a href="index.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>