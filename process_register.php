<?php
// মনে রাখবেন: এই ফাইলের একদম শুরুতে এই PHP ট্যাগ ছাড়া অন্য কোনো স্পেস বা টেক্সট থাকবে না।
session_start();
include 'includes/db.php'; // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect and trim user inputs
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];

    // 2. Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Invalid email format.";
        exit;
    }

    if (strlen($password_raw) < 6) {
        echo "❌ Password must be at least 6 characters.";
        exit;
    }

    // 3. Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "❌ Email already registered.";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // 4. Hash the password securely
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // 5. Insert user into the database with default role 'user'
    $role = 'user'; // নতুন ব্যবহারকারীর রোল 'user' হিসেবে সেট করা হচ্ছে

    $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $phone, $email, $password, $role); // 'role' যোগ করা হয়েছে

    if ($stmt->execute()) {
        // ✅ এই অংশটি পরিবর্তন করা হয়েছে:
        // রেজিস্ট্রেশন সফল হলে সরাসরি লগইন পেজে রিডাইরেক্ট করা হচ্ছে।
        header("Location: login.html");
        exit(); // রিডাইরেক্ট করার পর স্ক্রিপ্ট বন্ধ করা জরুরি
    } else {
        // যদি ডাটাবেজে কোনো ত্রুটি হয়
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>