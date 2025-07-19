<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Electronic Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="top-navbar">
        <p>WELCOME TO OUR SHOP</p>
        <div class="icons">
            <?php session_start(); if (isset($_SESSION['user_name'])): ?>
                <span style="color:black; margin-right: 10px;">🥰 Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="my_orders.php" style="color:black; margin-right: 10px;"><i class="fas fa-box"></i> My Orders</a>
                <a href="cart.php" style="color:black; margin-right: 10px;"><i class="fas fa-shopping-cart"></i> My Cart</a>
                <a href="logout.php" style="color:black;"><img src="./images/register.png" alt="" width="18px">Logout</a>
            <?php else: ?>
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card p-4 shadow-sm" style="border-color: rgb(67 0 86);">
                    <h2 class="text-center mb-4" style="color: rgb(67 0 86);">Contact Us</h2>
                    <p class="text-center text-muted">We'd love to hear from you! Please fill out the form below.</p>
                    <form action="submit_contact.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark" style="background-color: rgb(67 0 86); border:none;">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="text-center mt-5 mb-3">&copy; <?= date('Y') ?> Electronic Shop. All rights reserved.</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>