<?php
session_start();

if (isset($_GET['index'])) {
    $index = $_GET['index'];
    unset($_SESSION['cart'][$index]);
    // Reset cart array index
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

header("Location: cart.php");
exit();
