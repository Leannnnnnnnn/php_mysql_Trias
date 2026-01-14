<?php
session_start();
require 'connection.php';
$connect = Connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['delete'])) {
    header("Location: employee.php");
    exit;
}

try {
    $id = $_POST['id'] ?? null;
    if (empty($id) || !ctype_digit((string)$id)) {
        throw new Exception("Invalid employee record.");
    }

    $stmt = $connect->prepare("DELETE FROM tbl_employee WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['toast'] = [
        'title' => 'Deleted',
        'body' => 'Record has been deleted successfully.',
        'type' => 'success'
    ];

} catch (Exception $e) {
    $_SESSION['toast'] = [
        'title' => 'Error',
        'body' => $e->getMessage(),
        'type' => 'danger'
    ];
}

header("Location: employee.php");
exit;