<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-s`cale=1.0" />
    <title>Electronic Shop - Register</title>
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
          ><img src="./images/register.png" width="18px" />Login</a
        >
        <a href="register.php"
          ><img src="./images/register.png" width="18px" />Register</a
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
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Smart Phone</a></li>
                <li><a class="dropdown-item" href="#">Laptop</a></li>
                <li><a class="dropdown-item" href="#">Fridge</a></li>
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

    <div class="container" id="login" style="margin-top: 50px">
      <div class="row">
        <div class="col-md-5 py-3 py-md-0" id="side1">
          <h3 class="text-center">Register</h3>
        </div>
        <div class="col-md-7 py-3 py-md-0" id="side2">
          <h3 class="text-center">Create Account</h3>
          <form action="process_register.php" method="POST" class="text-center">
            <div class="input2">
              <input
                type="text"
                name="name"
                placeholder="Full Name"
                required
              /><br />
              <input
                type="text"
                name="username"
                placeholder="Username"
                required
              /><br />
              <input
                type="number"
                name="phone"
                placeholder="Phone"
                required
              /><br />
              <input
                type="email"
                name="email"
                placeholder="Email"
                required
              /><br />
              <input
                type="password"
                name="password"
                placeholder="Password"
                required
              /><br />
            </div>
            <p class="text-center mt-3" id="btnlogin">
              <button type="submit" class="btn btn-primary">SIGN UP</button>
            </p>
          </form>
        </div>
      </div>
    </div>

    <footer id="footer" class="mt-5">
      <div class="container py-4 text-center">
        <p>&copy; 2025 Electronic Shop. All Rights Reserved.</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>