<?php
session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: employee.php");
    exit;
}


function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}


$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Employee Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
body {
    background: linear-gradient(135deg, #00defc 0%, #06226e 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.login-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgb(248, 248, 248);
    padding: 40px;
    width: 100%;
    max-width: 400px;
}
.login-header {
    text-align: center;
    margin-bottom: 30px;
}
.login-header h2 {
    color: #000103;
    font-weight: 600;
}

</style>
</head>
<body>


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

<div class="login-container">
    <div class="login-header">
        <h2>Employee Management</h2>
        <p class="text-muted">Please login to continue</p>
    </div>
    
    <form method="POST" action="login_process.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg" name="login">Login</button>
        </div>
    </form>
    
    <div class="text-center mt-3">
        <small class="text-muted">admin / admin123</small>
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