<?php
require_once "includes/db.php";
require_once "includes/auth.php";
requireLogin();
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id=? AND user_id=?");
    $stmt->execute([$id,$_SESSION["user_id"]]);
    $old = $stmt->fetch();
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=? AND user_id=?");
    $stmt->execute([$id,$_SESSION["user_id"]]);
    if ($old && $old["image"]) @unlink(__DIR__."/uploads/".$old["image"]);
    header("Location: my_products.php?success=Deleted"); exit;
}
$stmt = $pdo->prepare("SELECT * FROM products WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION["user_id"]]);
$products = $stmt->fetchAll();
$pageTitle = "My Products";
include "includes/header.php";
?>
<h1>My Products</h1>
<?php if (isset($_GET["success"])): ?><div class="success"><?= h($_GET["success"]) ?></div><?php endif; ?>
<p><a class="btn" href="add_product.php">+ Add New Product</a></p>
<div class="grid">
<?php foreach ($products as $p): ?>
<div class="product-card">
<?php if ($p["image"]): ?><img src="uploads/<?= h($p["image"]) ?>" alt="<?= h($p["title"]) ?>"><?php endif; ?>
<h3><?= h($p["title"]) ?></h3><p class="price">₹<?= number_format($p["price"],2) ?></p>
<a class="small-btn" href="product.php?id=<?= (int)$p["id"] ?>">View</a>
<a class="small-btn danger" onclick="return confirm('Delete this product?')" href="?delete=<?= (int)$p["id"] ?>">Delete</a>
</div>
<?php endforeach; ?>
</div>
<?php include "includes/footer.php"; ?>