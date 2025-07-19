<?php
session_start();
include 'includes/db.php'; // ডাটাবেজ কানেকশন

// ব্যবহারকারী লগইন করা আছে কিনা তা নিশ্চিত করুন
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // অথবা আপনার লগইন পেজের সঠিক পথ
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0; // URL থেকে order_id নিন
$order_details = null;
$order_items = [];
$error_message = '';

if ($order_id > 0) {
    // 1. অর্ডারের মূল বিস্তারিত তথ্য ফেচ করুন
    $stmt_order = $conn->prepare("SELECT id, user_id, total, status, order_date FROM orders WHERE id = ? AND user_id = ?");
    $stmt_order->bind_param("ii", $order_id, $user_id);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();

    if ($result_order->num_rows > 0) {
        $order_details = $result_order->fetch_assoc();

        // 2. এই অর্ডারের অন্তর্ভুক্ত পণ্যগুলো ফেচ করুন
        $stmt_items = $conn->prepare("SELECT oi.quantity, oi.price, p.name, p.image 
                                       FROM order_items oi 
                                       JOIN products p ON oi.product_id = p.id 
                                       WHERE oi.order_id = ?");
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();

        while ($row_item = $result_items->fetch_assoc()) {
            $order_items[] = $row_item;
        }
        $stmt_items->close();

    } else {
        $error_message = "<div class='alert alert-danger'>Order not found or you do not have permission to view this order.</div>";
    }
    $stmt_order->close();
} else {
    $error_message = "<div class='alert alert-danger'>Invalid Order ID.</div>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap"
      rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Order Confirmation</h2>

        <?php if (!empty($error_message)): ?>
            <?= $error_message ?>
        <?php elseif ($order_details): ?>
            <div class="card p-4 shadow-sm">
                <div class="text-center mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                    <h3 class="mt-3 text-success">Your Order Has Been Placed Successfully!</h3>
                    <p class="lead">Thank you for your purchase.</p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5>Order Details</h5>
                        <p><strong>Order ID:</strong> #<?= htmlspecialchars($order_details['id']) ?></p>
                        <p><strong>Order Date:</strong> <?= htmlspecialchars(date('F j, Y, g:i a', strtotime($order_details['order_date']))) ?></p>
                        <p><strong>Order Status:</strong> <span class="badge bg-info"><?= htmlspecialchars($order_details['status']) ?></span></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5>Total Amount</h5>
                        <p class="fs-4 text-primary">$<?= number_format($order_details['total'], 2) ?></p>
                    </div>
                </div>

                <?php if (!empty($order_items)): ?>
                    <h5>Items in Your Order:</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($item['image'])): ?>
                                                <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img me-2">
                                            <?php endif; ?>
                                            <?= htmlspecialchars($item['name']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                        <td>$<?= number_format($item['price'], 2) ?></td>
                                        <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-warning">No items found for this order. Please contact support if this is an issue.</p>
                <?php endif; ?>

                <div class="mt-4 text-center">
                    <a href="index.php" class="btn btn-primary me-3">Continue Shopping</a>
                    </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                We could not find your order details. Please ensure you have placed an order.
            </div>
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-primary">Go to Homepage</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>