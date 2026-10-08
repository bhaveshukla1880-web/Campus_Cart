<?php
require_once "includes/db.php";
require_once "includes/auth.php";
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $error = "Enter a valid name/email and a password of at least 6 characters.";
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users(name,email,password,phone) VALUES(?,?,?,?)");
            $stmt->execute([$name,$email,$hash,$phone]);
            header("Location: login.php?success=Registration successful. Please login.");
            exit;
        } catch (PDOException $e) {
            $error = "Email may already be registered.";
        }
    }
}
$pageTitle = "Register";
include "includes/header.php";
?>
<div class="form-card">
<h2>Create Student Account</h2>
<?php if ($error): ?><div class="error"><?= h($error) ?></div><?php endif; ?>
<form method="post" data-validate>
<div class="form-group"><label>Name</label><input name="name" required></div>
<div class="form-group"><label>Email</label><input type="email" name="email" required></div>
<div class="form-group"><label>Phone</label><input name="phone"></div>
<div class="form-group"><label>Password</label><input type="password" name="password" required></div>
<button class="btn">Register</button>
</form>
</div>
<?php include "includes/footer.php"; ?>