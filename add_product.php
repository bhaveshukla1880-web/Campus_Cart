<?php
require_once "includes/db.php";
require_once "includes/auth.php";
requireLogin();
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $price = (float)($_POST["price"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $imageName = null;
    if ($title === "" || $category === "" || $description === "" || $price <= 0) {
        $error = "Please enter valid product details.";
    } elseif (!empty($_FILES["image"]["name"])) {
        $allowed = ["jpg","jpeg","png","gif","webp"];
        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        if (!in_array($ext,$allowed,true) || $_FILES["image"]["size"] > 3*1024*1024) {
            $error = "Use JPG, PNG, GIF or WEBP image up to 3 MB.";
        } else {
            $imageName = uniqid("product_",true).".".$ext;
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__."/uploads/".$imageName)) {
                $error = "Image upload failed.";
                $imageName = null;
            }
        }
    }
    if ($error === "") {
        $stmt = $pdo->prepare("INSERT INTO products(user_id,title,category,price,description,image) VALUES(?,?,?,?,?,?)");
        $stmt->execute([$_SESSION["user_id"],$title,$category,$price,$description,$imageName]);
        header("Location: my_products.php?success=Product uploaded successfully."); exit;
    }
}
$pageTitle = "Sell Product";
include "includes/header.php";
?>
<div class="form-card">
<h2>Upload Product</h2>
<p class="muted">Add an image and clear details so another student can understand what you are selling.</p>
<?php if ($error): ?><div class="error"><?= h($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" data-validate>
<div class="form-group"><label>Product Image</label><input id="image" type="file" name="image" accept="image/*"><img id="imagePreview" style="display:none;max-width:220px;margin-top:10px;border-radius:8px"></div>
<div class="form-group"><label>Title</label><input name="title" required placeholder="Scientific Calculator"></div>
<div class="form-group"><label>Category</label><select name="category" required><option value="">Select</option><option>Books</option><option>Electronics</option><option>Furniture</option><option>Cycles</option><option>Clothing</option><option>Other</option></select></div>
<div class="form-group"><label>Price (₹)</label><input type="number" step="0.01" min="1" name="price" required></div>
<div class="form-group"><label>Description</label><textarea name="description" required placeholder="Condition, features, pickup details..."></textarea></div>
<button class="btn">Upload Product</button>
</form>
</div>
<?php include "includes/footer.php"; ?>