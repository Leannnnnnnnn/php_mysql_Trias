<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Start new session for toast message
session_start();
$_SESSION['toast'] = [
    'title' => 'Logged Out',
    'body' => 'You have been successfully logged out.',
    'type' => 'info'
];

header("Location: login.php");
exit;