<?php
session_start();
include 'includes/db.php'; // ডাটাবেজ কানেকশন

// ব্যবহারকারী লগইন করা আছে কিনা তা নিশ্চিত করুন
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // অথবা আপনার লগইন পেজের সঠিক পথ
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$cart_items_for_checkout = [];
$grand_total = 0;

// --- কার্ট আইটেম ফেচ করুন (চেকআউট পেজের জন্য) ---
// cart এবং products টেবিলের JOIN করে কার্ট আইটেমগুলোর বিস্তারিত তথ্য নেওয়া
$query = "
    SELECT ci.product_id, ci.quantity, p.name, p.price, p.image, p.stock
    FROM cart ci 
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // স্টকের চেয়ে বেশি অর্ডার করা হলে কোয়ান্টিটি সামঞ্জস্য করুন
        $quantity_to_order = $row['quantity'];
        if ($quantity_to_order > $row['stock']) {
            $quantity_to_order = $row['stock']; // স্টকের সমান করে দিন
            // এই ক্ষেত্রে কার্ট টেবিলেও কোয়ান্টিটি আপডেট করা উচিত, কিন্তু আপাতত এখানে ওয়ার্নিং দিচ্ছি
            // একটি ভালো ই-কমার্সে, এই ধাপটি কার্টেই (cart.php) হ্যান্ডেল করা উচিত
            // যাতে ব্যবহারকারী চেকআউটে আসার আগেই সঠিক কোয়ান্টিটি দেখে
            $message = "<div class='alert alert-warning'>Adjusted quantity for " . htmlspecialchars($row['name']) . " due to low stock. Please review your cart.</div>";
        }
        if ($quantity_to_order == 0 && $row['stock'] == 0) {
            // যদি স্টক 0 হয় এবং কার্ট কোয়ান্টিটিও 0 হয়, তবে এই আইটেমটি বাদ দিন
            continue; 
        }


        $cart_items_for_checkout[] = [
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'quantity' => $quantity_to_order,
            'stock' => $row['stock'],
            'subtotal' => $row['price'] * $quantity_to_order
        ];
        $grand_total += ($row['price'] * $quantity_to_order);
    }
} else {
    // যদি কার্ট খালি থাকে, তাহলে শপিং পেজে রিডাইরেক্ট করুন
    header("Location: index.php?message=" . urlencode("Your cart is empty. Please add items before checking out."));
    exit();
}
$stmt->close(); // stmt বন্ধ করুন


// --- অর্ডার প্লেসমেন্ট লজিক ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    if (empty($cart_items_for_checkout)) {
        $message = "<div class='alert alert-danger'>Cannot place an empty order. Please add items to your cart.</div>";
    } else {
        // এখানে ট্রানজাকশন ব্যবহার করা ভালো অনুশীলন, যাতে সব ইনসার্ট সফল না হলে রোলব্যাক করা যায়
        $conn->begin_transaction();
        try {
            // 1. orders টেবিলে নতুন অর্ডার তৈরি করুন
            // এখানে আপনি শিপিং ঠিকানা, পেমেন্ট মেথড ইত্যাদি যোগ করতে পারেন
            $order_status = 'Pending'; // অর্ডারের প্রাথমিক স্ট্যাটাস
            $order_date = date('Y-m-d H:i:s'); // বর্তমান তারিখ ও সময়

            $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total, status, order_date) VALUES (?, ?, ?, ?)");
            $stmt_order->bind_param("idss", $user_id, $grand_total, $order_status, $order_date);
            $stmt_order->execute();
            $order_id = $conn->insert_id; // নতুন তৈরি হওয়া অর্ডারের ID

            // 2. order_items টেবিলে কার্টের প্রতিটি আইটেম যোগ করুন
            $stmt_order_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt_update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($cart_items_for_checkout as $item) {
                // order_items এ যোগ করুন
                $stmt_order_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                $stmt_order_item->execute();

                // স্টকের পরিমাণ আপডেট করুন
                $stmt_update_stock->bind_param("ii", $item['quantity'], $item['product_id']);
                $stmt_update_stock->execute();
            }

            // 3. ব্যবহারকারীর কার্ট খালি করুন
            $stmt_clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt_clear_cart->bind_param("i", $user_id);
            $stmt_clear_cart->execute();

            $conn->commit(); // সবকিছু সফল হলে কমিট করুন
            header("Location: order_confirmation.php?order_id=" . $order_id);
            exit();

        } catch (Exception $e) {
            $conn->rollback(); // কোনো এরর হলে রোলব্যাক করুন
            $message = "<div class='alert alert-danger'>Error placing order: " . $e->getMessage() . "</div>";
            // আপনি চাইলে এখানে বিস্তারিত এরর লগ করতে পারেন
        } finally {
            if (isset($stmt_order)) $stmt_order->close();
            if (isset($stmt_order_item)) $stmt_order_item->close();
            if (isset($stmt_update_stock)) $stmt_update_stock->close();
            if (isset($stmt_clear_cart)) $stmt_clear_cart->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link
      href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap"
      rel="stylesheet"
    />
    <style>
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; // যদি navbar.php ফাইল থাকে ?>

    <div class="container mt-5">
        <h2 class="mb-4">Checkout</h2>

        <?= $message ?>

        <?php if (!empty($cart_items_for_checkout)): ?>
            <h4>Order Summary</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items_for_checkout as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img me-2">
                                <?php endif; ?>
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Grand Total</th>
                            <th>$<?= number_format($grand_total, 2) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <h4 class="mt-4">Shipping Information (Dummy)</h4>
            <p>For simplicity, we are skipping detailed shipping address input for now.</p>
            <p>In a real application, you would collect: Full Name, Address Line 1, Address Line 2, City, State/Province, Postal Code, Country, Phone Number.</p>
            <p>You can also pre-fill this from the user's profile if available.</p>

            <h4 class="mt-4">Payment Method (Simulated)</h4>
            <p>For this example, we are simulating payment as successful upon order placement.</p>
            <p>In a real application, you would integrate with payment gateways (e.g., Stripe, PayPal, SSLCommerz in Bangladesh) here.</p>

            <form action="checkout.php" method="POST" class="mt-4">
                <button type="submit" name="place_order" class="btn btn-success btn-lg">Place Order</button>
                <a href="cart.php" class="btn btn-secondary btn-lg ms-3">Back to Cart</a>
            </form>
        <?php else: ?>
            <p class="text-center">Your cart is empty. Please <a href="index.php">continue shopping</a>.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>