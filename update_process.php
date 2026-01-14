<?php
session_start();
require 'connection.php';
$connect = Connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update'])) {
    header("Location: employee.php");
    exit;
}

try {
    $id = $_POST['id'] ?? null;
    $empno = trim($_POST['emp_id'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $age = $_POST['age'] ?? null;

    if (empty($id) || !ctype_digit((string)$id)) {
        throw new Exception("Invalid employee record.");
    }

    if ($empno === '' || $fname === '' || $lname === '' || $age === null || $age === '') {
        throw new Exception("All fields are required.");
    }

    $sql = "UPDATE tbl_employee SET emp_id = :emp_id, firstname = :fname, lastname = :lname, age = :age WHERE id = :id";
    $stmt = $connect->prepare($sql);
    $stmt->execute([
        ':emp_id' => $empno,
        ':fname' => $fname,
        ':lname' => $lname,
        ':age' => $age,
        ':id' => $id
    ]);

    $_SESSION['toast'] = [
        'title' => 'Updated',
        'body' => 'Record has been successfully updated.',
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