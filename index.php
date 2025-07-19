<?php
session_start();
include 'includes/db.php'; // DB connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Shop</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

  <div class="top-navbar">
    <p>WELCOME TO OUR SHOP</p>
    <div class="icons">
        <?php if (isset($_SESSION['user_name'])): ?>
            
            <span style="color:black; margin-right: 10px;">🥰 Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="my_orders.php" style="color:black; margin-right: 10px;"><i class="fas fa-box"></i> My Orders</a>
            <a href="cart.php" style="color:black; margin-right: 10px;"><i class="fas fa-shopping-cart"></i> My Cart</a>
            <a href="logout.php" style="color:black;"><img src="./images/register.png" alt="" width="18px">Logout</a>
        <?php else: ?>
            <a href="cart.php" style="color:black; margin-right: 10px;"><i class="fas fa-shopping-cart"></i> My Cart</a>
            <a href="login.php" style="color:black; margin-right: 10px;"><img src="./images/register.png" alt="" width="18px">Login</a>
            <a href="register.php" style="color:black;"><img src="./images/register.png" alt="" width="18px">Register</a>
        <?php endif; ?>
    </div>
</div>

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(67 0 86);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="./images/logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                Electronic Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                   <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="background-color: rgb(67 0 86); padding: 30px; border-radius: 10px; color: white;">
        <div class="container" style="background-color: white; padding: 20px; border-radius: 8px;">
            <h2 class="text-center my-4" style="color: black;">Our Products</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                if (isset($conn)) {
                    $sql = "SELECT id, name, price, image FROM products ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        die("Error fetching products: " . mysqli_error($conn));
                    }

                    if (mysqli_num_rows($result) == 0) {
                        echo "<p class='text-center w-100' style='color: black;'>No products found.</p>";
                    }

                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                <div class="col">
                    <div class="card h-100 product-card">
                        <img src="./images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title" style="color: black;"><?= htmlspecialchars($row['name']) ?></h5>
                            <div class="star mb-2">
                                <i class="fa-solid fa-star checked" style="color: orange;"></i>
                                <i class="fa-solid fa-star checked" style="color: orange;"></i>
                                <i class="fa-solid fa-star checked" style="color: orange;"></i>
                                <i class="fa-solid fa-star checked" style="color: orange;"></i>
                                <i class="fa-solid fa-star checked" style="color: orange;"></i>
                            </div>
                            <h4 style="color: black;">$<?= htmlspecialchars($row['price']) ?></h4>

                            <form method="post" action="cart.php">
                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
                                <input type="hidden" name="product_price" value="<?= htmlspecialchars($row['price']) ?>">
                                <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['image']) ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-dark mt-2">
                                    <i class="fa fa-cart-shopping"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endwhile; } else {
                    echo "<p class='text-center w-100' style='color: black;'>Database not connected.</p>";
                } ?>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 mb-3">&copy; <?= date('Y') ?> Electronic Shop. All rights reserved.</footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>