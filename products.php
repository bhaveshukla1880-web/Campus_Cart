<?php
require_once "includes/db.php";
require_once "includes/auth.php";
$term = trim($_GET["q"] ?? "");
if ($term !== "") {
    $like = "%$term%";
    $stmt = $pdo->prepare("SELECT p.*,u.name AS seller FROM products p JOIN users u ON u.id=p.user_id WHERE p.title LIKE ? OR p.category LIKE ? OR p.description LIKE ? ORDER BY p.created_at DESC");
    $stmt->execute([$like,$like,$like]);
} else {
    $stmt = $pdo->query("SELECT p.*,u.name AS seller FROM products p JOIN users u ON u.id=p.user_id ORDER BY p.created_at DESC");
}
$products = $stmt->fetchAll();
$pageTitle = "Browse Products";
include "includes/header.php";
?>
<h1>Browse Products</h1>
<form class="search" method="get">
<input id="productSearch" name="q" value="<?= h($term) ?>" placeholder="Search books, calculators, cycles, electronics...">
</form>
<div class="grid">
<?php foreach ($products as $p): ?>
<div class="product-card">
<?php if ($p["image"]): ?><img src="uploads/<?= h($p["image"]) ?>" alt="<?= h($p["title"]) ?>"><?php endif; ?>
<span class="badge"><?= h($p["category"]) ?></span>
<h3><?= h($p["title"]) ?></h3>
<p><?= h(substr($p["description"],0,100)) ?>...</p>
<p class="price">₹<?= number_format($p["price"],2) ?></p>
<p class="muted">Seller: <?= h($p["seller"]) ?></p>
<a class="btn" href="product.php?id=<?= (int)$p["id"] ?>">View Product</a>
</div>
<?php endforeach; ?>
</div>
<?php if (!$products): ?><p class="notice">No matching products found.</p><?php endif; ?>
<?php include "includes/footer.php"; ?>