<?php
session_start();
include 'includes/db.php';

// ✅ এখানে admin session চেক করা উচিত user_role দিয়ে নয়
if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin') {
    header("Location: admin/index.php");
    exit();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            if ($role === 'admin') {
                session_regenerate_id(true); // নতুন সেশন আইডি তৈরি করুন

                // ✅ আলাদা session variable ব্যবহার করা হচ্ছে admin এর জন্য
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_name'] = $name;
                $_SESSION['admin_role'] = $role;

                header("Location: admin/index.php");
                exit();
            } else {
                $error_message = "❌ You are not authorized to log in to the admin panel.";
            }
        } else {
            $error_message = "❌ Wrong Password";
        }
    } else {
        $error_message = "❌ No user found with this email.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Admin Login</h2>
        <form action="admin_login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
