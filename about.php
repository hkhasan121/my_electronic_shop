<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Electronic Shop - About Us</title>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <div class="top-navbar">
      <p>WELCOME TO OUR SHOP</p>
      <div class="icons">
        <?php
        // লগইন স্ট্যাটাস চেক করা হচ্ছে
        if (isset($_SESSION['user_id'])) {
            // যদি ব্যবহারকারী লগইন করে থাকে
            ?>
            <a href="index.php" style="color: black; text-decoration: none;">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </a>
            <a href="cart.php" style="color: black; text-decoration: none;">
                <img src="./images/cart.png" alt="" width="18px" />My Cart
            </a>
            <a href="my_orders.php" style="color: black; text-decoration: none;">
                <img src="./images/orders.png" alt="" width="18px" />My Orders
            </a>
            <a href="logout.php"><img src="./images/logout.png" alt="" width="18px" />Logout</a>
            <?php
        } else {
            // যদি ব্যবহারকারী লগইন না করে থাকে
            ?>
            <a href="login.php"
              ><img src="./images/register.png" alt="" width="18px" />Login</a
            >
            <a href="register.php"
              ><img src="./images/register.png" alt="" width="18px" />Register</a
            >
            <?php
        }
        ?>
      </div>
    </div>
    <nav class="navbar navbar-expand-lg" id="navbar">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php" id="logo"
          ><span id="span1">E</span>Lectronic <span>Shop</span></a
        >
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span><img src="./images/menu.png" alt="" width="30px" /></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Product</a>
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Category
              </a>
              <ul
                class="dropdown-menu"
                aria-labelledby="navbarDropdown"
                style="background-color: rgb(67 0 86)"
              >
                <li><a class="dropdown-item" href="#">Smart Phone</a></li>
                <li><a class="dropdown-item" href="#">Mobile Phone</a></li>
                <li><a class="dropdown-item" href="#">Cameras</a></li>
                <li><a class="dropdown-item" href="#">Fridge</a></li>
                <li><a class="dropdown-item" href="#">AC</a></li>
                <li><a class="dropdown-item" href="#">Smart Watch</a></li>
                <li><a class="dropdown-item" href="#">Headphone</a></li>
                <li><a class="dropdown-item" href="#">Laptop</a></li>
                <li><a class="dropdown-item" href="#">PC Monitor</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
          </ul>
          <form class="d-flex" id="search">
            <input
              class="form-control me-2"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <button class="btn btn-outline-success" type="submit">
              Search
            </button>
          </form>
        </div>
      </div>
    </nav>
    <div class="container" id="about">
      <h3 class="text-center">ABOUT US</h3>
      <hr style="width: 50px; margin: auto; border: 2px solid #5a006c" />
      <div class="row mt-5">
        <div
          class="col-md-7 py-3 py-md-0 d-flex flex-column justify-content-center"
        >
          <h4>
            Welcome to
            <span style="color: #5a006c; font-weight: bold"
              >Electronic Shop</span
            >
          </h4>
          <p class="mt-3">
            Our story began with a simple passion: to bring the best and latest
            electronics to your doorstep. We believe in providing top-quality
            gadgets, exceptional service, and a shopping experience that's both
            seamless and secure.
          </p>
          <p>
            From the newest smartphones and cutting-edge laptops to
            high-performance cameras and essential home appliances, we curate a
            selection that meets all your electronic needs. Every product in our
            store is chosen for its quality, durability, and innovation.
          </p>
          <a href="#" class="btn btn-dark mt-3" style="width: 150px"
            >Learn More</a
          >
        </div>
        <div class="col-md-5 py-3 py-md-0 text-center">
          <div class="card">
            <img src="./images/background.png" alt="Our team working" />
          </div>
        </div>
      </div>
    </div>
    <div class="container mt-5 text-center" id="why-us">
      <h3 class="mb-4">Why Choose Us?</h3>
      <div class="row">
        <div class="col-md-4">
          <i class="fa-solid fa-cart-shopping fa-3x text-primary"></i>
          <h5 class="mt-3">Wide Selection</h5>
          <p>
            We offer a vast range of products from leading brands to suit every
            budget and need.
          </p>
        </div>
        <div class="col-md-4">
          <i class="fa-solid fa-truck fa-3x text-success"></i>
          <h5 class="mt-3">Fast & Reliable Delivery</h5>
          <p>
            Get your products delivered quickly and safely, right to your home.
          </p>
        </div>
        <div class="col-md-4">
          <i class="fa-solid fa-shield-alt fa-3x text-danger"></i>
          <h5 class="mt-3">Secure Payments</h5>
          <p>Shop with confidence, knowing your transactions are protected.</p>
        </div>
      </div>
    </div>
    <div class="container" id="offer" style="margin-top: 50px">
      <div class="row">
        <div class="col-md-3 py-3 py-md-0">
          <i class="fa-solid fa-cart-shopping"></i>
          <h3>Free Shipping</h3>
          <p>On order over $1000</p>
        </div>
        <div class="col-md-3 py-3 py-md-0">
          <i class="fa-solid fa-rotate-left"></i>
          <h3>Free Returns</h3>
          <p>Within 30 days</p>
        </div>
        <div class="col-md-3 py-3 py-md-0">
          <i class="fa-solid fa-truck"></i>
          <h3>Fast Delivery</h3>
          <p>World Wide</p>
        </div>
        <div class="col-md-3 py-3 py-md-0">
          <i class="fa-solid fa-thumbs-up"></i>
          <h3>Big choice</h3>
          <p>Of products</p>
        </div>
      </div>
    </div>

    <div class="container" id="newslater" style="margin-top: 50px">
      <h3 class="text-center">
        Subscribe To The Electronic Shop For Latest upload.
      </h3>
      <div class="input text-center">
        <input type="text" placeholder="Enter Your Email.." />
        <button id="subscribe">SUBSCRIBE</button>
      </div>
    </div>

    <footer id="footer" class="mt-5">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-6 footer-contact">
              <h3>Electronic Shop</h3>
              <p>Karachi <br />Sindh <br />Pakistan <br /></p>
              <strong>Phone:</strong> +000000000000000 <br />
              <strong>Email:</strong> electronicshop@.com <br />
            </div>
            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Useful Links</h4>
              <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Terms of service</a></li>
                <li><a href="#">Privacy policy</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Our Services</h4>
              <ul>
                <li><a href="#">PS 5</a></li>
                <li><a href="#">Computer</a></li>
                <li><a href="#">Gaming Laptop</a></li>
                <li><a href="#">Mobile Phone</a></li>
                <li><a href="#">Gaming Gadget</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Our Social Networks</h4>
              <p>
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quia,
                quibusdam.
              </p>
              <div class="socail-links mt-3">
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-skype"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr />
      <div class="container py-4">
        <div class="copyright">
          &copy; Copyright <strong><span>Electronic Shop</span></strong
          >. All Rights Reserved
        </div>
        <div class="credits">Designed by <a href="#">SA coding</a></div>
      </div>
    </footer>
    <a href="#" class="arrow"
      ><i><img src="./images/arrow.png" alt="" /></i
    ></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>