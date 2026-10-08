<?php
require_once "includes/db.php";
require_once "includes/auth.php";
$id = (int)($_GET["id"] ?? 0);
$stmt = $pdo->prepare("SELECT p.*,u.name AS seller,u.email AS seller_email,u.phone AS seller_phone FROM products p JOIN users u ON u.id=p.user_id WHERE p.id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { header("Location: products.php"); exit; }

$success = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    requireLogin();
    if ($_SESSION["user_id"] == $p["user_id"]) {
        $error = "You cannot contact yourself.";
    } else {
        $message = trim($_POST["message"] ?? "");
        if (strlen($message) < 5) $error = "Please enter a meaningful message.";
        else {
            $stmt = $pdo->prepare("INSERT INTO messages(product_id,sender_id,receiver_id,message) VALUES(?,?,?,?)");
            $stmt->execute([$id,$_SESSION["user_id"],$p["user_id"],$message]);
            $success = "Message sent to the seller.";
        }
    }
}
$pageTitle = $p["title"];
include "includes/header.php";
?>
<div class="detail">
<div>
<?php if ($p["image"]): ?><img src="uploads/<?= h($p["image"]) ?>" alt="<?= h($p["title"]) ?>"><?php else: ?><div class="notice">No image uploaded.</div><?php endif; ?>
</div>
<div>
<span class="badge"><?= h($p["category"]) ?></span>
<h1><?= h($p["title"]) ?></h1>
<p class="price">₹<?= number_format($p["price"],2) ?></p>
<p><?= nl2br(h($p["description"])) ?></p>
<hr>
<h3>Seller</h3>
<p><strong><?= h($p["seller"]) ?></strong></p>
<?php if ($success): ?><div class="success"><?= h($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="error"><?= h($error) ?></div><?php endif; ?>
<?php if (!isset($_SESSION["user_id"])): ?>
<p><a class="btn" href="login.php">Login to Contact Seller</a></p>
<?php elseif ($_SESSION["user_id"] != $p["user_id"]): ?>
<form method="post" data-validate>
<div class="form-group"><label>Your message</label><textarea name="message" required placeholder="Hi, is this item still available?"></textarea></div>
<button class="btn">Contact Seller</button>
</form>
<?php else: ?>
<p class="notice">This is your product. Manage it from My Products.</p>
<?php endif; ?>
</div>
</div>
<?php include "includes/footer.php"; ?>