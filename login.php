<?php
require_once "includes/db.php";
require_once "includes/auth.php";
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$error = $_GET['error'] ?? "";
$success = $_GET['success'] ?? "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        header("Location: index.php"); exit;
    }
    $error = "Invalid email or password.";
}
$pageTitle = "Login";
include "includes/header.php";
?>
<div class="form-card">
<h2>Student Login</h2>
<?php if ($success): ?><div class="success"><?= h($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="error"><?= h($error) ?></div><?php endif; ?>
<form method="post" data-validate>
<div class="form-group"><label>Email</label><input type="email" name="email" required></div>
<div class="form-group"><label>Password</label><input type="password" name="password" required></div>
<button class="btn">Login</button>
</form>
<p class="muted">Demo: seller@campuscart.test / student123</p>
</div>
<?php include "includes/footer.php"; ?>