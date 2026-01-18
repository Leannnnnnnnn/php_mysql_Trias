<?php
session_start();
require 'connection.php';
$connect = Connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
    header("Location: login.php");
    exit;
}

try {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        throw new Exception("Username and password are required.");
    }

    $stmt = $connect->prepare("SELECT * FROM tbl_users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user->password)) {
        throw new Exception("Invalid username or password.");
    }

    // Login successful
    $_SESSION['user_id'] = $user->id;
    $_SESSION['username'] = $user->username;

    $_SESSION['toast'] = [
        'title' => 'Success',
        'body' => 'Welcome back, ' . htmlspecialchars($user->username) . '!',
        'type' => 'success'
    ];

    header("Location: employee.php");
    exit;

} catch (Exception $e) {
    $_SESSION['toast'] = [
        'title' => 'Login Failed',
        'body' => $e->getMessage(),
        'type' => 'danger'
    ];
    header("Location: login.php");
    exit;
}