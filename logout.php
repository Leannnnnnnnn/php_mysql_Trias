<?php
session_start();

session_unset();
session_destroy();

session_start();
$_SESSION['toast'] = [
    'title' => 'Logged Out',
    'body' => 'You have been successfully logged out.',
    'type' => 'info'
];

header("Location: login.php");
exit;