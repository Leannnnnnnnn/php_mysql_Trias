<?php
session_start();
require 'connection.php';
$connect = Connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['add'])) {
    header("Location: employee.php");
    exit;
}

try {
    $empno = trim($_POST['emp_id'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $age = $_POST['age'] ?? null;

    if ($empno === '' || $fname === '' || $lname === '' || $age === null || $age === '') {
        throw new Exception("All fields are required.");
    }

    $sql = "INSERT INTO tbl_employee (emp_id, firstname, lastname, age) 
            VALUES (:emp_id, :fname, :lname, :age)";
    $stmt = $connect->prepare($sql);
    $stmt->execute([
        ':emp_id' => $empno,
        ':fname' => $fname,
        ':lname' => $lname,
        ':age' => $age,
    ]);

    $_SESSION['toast'] = [
        'title' => 'Added',
        'body' => 'New employee has been added.',
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