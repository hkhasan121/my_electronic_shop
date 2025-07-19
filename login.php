<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Electronic Shop</title>
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
        <a href="login.php"
          ><img src="./images/register.png" alt="" width="18px" />Login</a
        >
        <a href="register.php"
          ><img src="./images/register.png" alt="" width="18px" />Register</a
        >
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
        >
          <span><img src="./images/menu.png" alt="" width="30px" /></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">Product</a></li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                >Category</a
              >
              <ul class="dropdown-menu" style="background-color: rgb(67 0 86)">
                <li><a class="dropdown-item" href="#">Smart Phone</a></li>
                <li><a class="dropdown-item" href="#">Laptop</a></li>
                <li><a class="dropdown-item" href="#">Camera</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
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
            />
            <button class="btn btn-outline-success" type="submit">
              Search
            </button>
          </form>
        </div>
      </div>
    </nav>

    <div class="container" id="login">
      <div class="row">
        <div class="col-md-5 py-3 py-md-0" id="side1">
          <h3 class="text-center">Welcome Back!</h3>
        </div>
        <div class="col-md-7 py-3 py-md-0" id="side2">
          <h3 class="text-center">Account Login</h3>
          <p class="text-center">
            Login using your registered email and password.
          </p>

          <form action="login_process.php" method="POST">
            <div class="input2 text-center">
              <input type="email" name="email" placeholder="Email" required />
              <input
                type="password"
                name="password"
                placeholder="Password"
                required
              />
            </div>
            <p class="text-center" id="btnlogin">
              <button type="submit" class="btn btn-primary">LOG IN</button>
            </p>
          </form>

          <p class="text-center">Forgot Password? <a href="#">Click here</a></p>
        </div>
      </div>
    </div>

    <div class="container" id="newslater" style="margin-top: 100px">
      <h3 class="text-center">
        Subscribe to the Electronic Shop for Latest Updates
      </h3>
      <div class="input text-center">
        <input type="text" placeholder="Enter Your Email.." />
        <button id="subscribe">SUBSCRIBE</button>
      </div>
    </div>

    <footer id="footer">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-6 footer-contact">
              <h3>Electronic Shop</h3>
              <p>
                Dhaka, Bangladesh<br /><strong>Phone:</strong>
                +880000000000<br /><strong>Email:</strong>
                support@electronicshop.com<br />
              </p>
            </div>
            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Useful Links</h4>
              <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Privacy Policy</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Our Services</h4>
              <ul>
                <li><a href="#">Smart Phones</a></li>
                <li><a href="#">Laptops</a></li>
              </ul>
            </div>
            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Our Social Networks</h4>
              <div class="socail-links mt-3">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
