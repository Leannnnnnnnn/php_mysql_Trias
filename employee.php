<?php
session_start();
require 'connection.php';

$connect = Connect();

// XSS prevention
function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Fetch employees
$stmt = $connect->prepare("SELECT * FROM tbl_employee");
$stmt->execute();
$rows = $stmt->fetchAll();

// Toast message
$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employees</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
.table { margin: 0 auto; width: 80%; }
</style>
</head>
<body>

<h2 class="text-center text-primary mt-3">Employee's Information</h2>

<!-- TOAST -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1080;">
<?php if ($toast): ?>
    <?php $bgClass = "text-bg-" . ($toast['type'] ?? 'info'); ?>
    <div id="liveToast" class="toast <?= h($bgClass) ?>" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <strong><?= h($toast['title'] ?? 'Notice') ?>:</strong>
                <?= h($toast['body'] ?? '') ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
<?php endif; ?>
</div>

<!-- ADD BUTTON -->
<div class="d-flex justify-content-end mb-2 container">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Add New Employee</button>
</div>

<div class="container mt-3">
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Employee No.</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($rows): ?>
        <?php $i = 1; foreach ($rows as $row): ?>
        <tr>
            <td><?= $i++; ?></td>
            <td><?= h($row->emp_id); ?></td>
            <td><?= h($row->firstname); ?></td>
            <td><?= h($row->lastname); ?></td>
            <td><?= h($row->age); ?></td>
            <td class="d-flex gap-2">
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal-<?= h($row->id) ?>">Update</button>
                <form method="POST" action="delete_process.php" onsubmit="return confirm('Are you sure to delete this record?');">
                    <input type="hidden" name="id" value="<?= h($row->id) ?>">
                    <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center">No Record Found</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

<!-- UPDATE MODALS -->
<?php if ($rows): foreach ($rows as $row): ?>
<div class="modal fade" id="updateModal-<?= h($row->id) ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" action="update_process.php">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Update Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= h($row->id) ?>">
                <div class="mb-2">
                    <label class="form-label">Employee Number</label>
                    <input type="text" class="form-control" name="emp_id" value="<?= h($row->emp_id) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="fname" value="<?= h($row->firstname) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lname" value="<?= h($row->lastname) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" value="<?= h($row->age) ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="update">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>
<?php endforeach; endif; ?>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" action="add_process.php">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add New Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Employee Number</label>
                    <input type="text" class="form-control" name="emp_id" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="fname" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lname" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="add">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
(function () {
    var toastEl = document.getElementById('liveToast');
    if (toastEl) new bootstrap.Toast(toastEl, { delay: 3000 }).show();
})();
</script>

</body>
</html>