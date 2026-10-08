<?php
require_once "includes/db.php";
require_once "includes/auth.php";

$pageTitle = "CampusCart - Student Marketplace";

$stmt = $pdo->query("
    SELECT p.*, u.name AS seller
    FROM products p
    JOIN users u ON u.id = p.user_id
    ORDER BY p.created_at DESC
    LIMIT 6
");

$products = $stmt->fetchAll();

include "includes/header.php";
?>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f5f7fb;
        color: #172033;
        font-family: Arial, Helvetica, sans-serif;
    }

    .home-wrap {
        max-width: 1150px;
        margin: 0 auto;
        padding: 35px 20px 60px;
    }

    /* Hero */
    .welcome-box {
        min-height: 390px;
        border-radius: 24px;
        padding: 55px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        overflow: hidden;
        background: linear-gradient(135deg, #eaf2ff, #ffffff);
        border: 1px solid #dce6f5;
    }

    .welcome-content {
        max-width: 620px;
    }

    .small-title {
        display: inline-block;
        padding: 7px 13px;
        border-radius: 20px;
        background: #ffffff;
        color: #2563eb;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 18px;
        border: 1px solid #d9e5fb;
    }

    .welcome-content h1 {
        font-size: 48px;
        line-height: 1.08;
        margin: 0 0 18px;
        letter-spacing: -1px;
    }

    .welcome-content h1 span {
        color: #2563eb;
    }

    .welcome-content p {
        font-size: 17px;
        line-height: 1.7;
        color: #5b6475;
        margin-bottom: 28px;
    }

    .button-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .main-btn,
    .second-btn {
        text-decoration: none;
        padding: 13px 21px;
        border-radius: 10px;
        font-weight: bold;
        display: inline-block;
        transition: 0.2s;
    }

    .main-btn {
        background: #2563eb;
        color: white;
    }

    .main-btn:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    .second-btn {
        background: white;
        color: #172033;
        border: 1px solid #d6deeb;
    }

    .second-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
    }

    /* Decorative card */
    .hero-card {
        width: 300px;
        min-width: 300px;
        height: 260px;
        background: #ffffff;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 18px 45px rgba(33, 58, 105, 0.12);
        position: relative;
    }

    .hero-card h3 {
        margin-top: 0;
        font-size: 22px;
    }

    .hero-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        margin: 10px 0;
        background: #f5f8fd;
        border-radius: 10px;
        font-size: 14px;
    }

    .hero-item strong {
        color: #2563eb;
    }

    /* Section heading */
    .section-heading {
        margin-top: 60px;
        margin-bottom: 22px;
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 20px;
    }

    .section-heading h2 {
        margin: 0;
        font-size: 28px;
    }

    .section-heading p {
        margin: 5px 0 0;
        color: #6b7280;
    }

    /* How it works */
    .steps {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .step {
        background: white;
        border: 1px solid #e3e8f0;
        border-radius: 16px;
        padding: 25px;
    }

    .step-number {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf2ff;
        color: #2563eb;
        font-weight: bold;
        margin-bottom: 17px;
    }

    .step h3 {
        margin: 0 0 8px;
    }

    .step p {
        color: #687386;
        line-height: 1.6;
        margin: 0;
    }

    /* Product grid */
    .products {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .product-card {
        background: #ffffff;
        border: 1px solid #e3e8f0;
        border-radius: 17px;
        overflow: hidden;
        transition: 0.2s;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(35, 50, 75, 0.10);
    }

    .product-image {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
        background: #edf1f7;
    }

    .no-image {
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf1f7;
        color: #7b8493;
    }

    .product-info {
        padding: 18px;
    }

    .category {
        display: inline-block;
        font-size: 12px;
        color: #2563eb;
        background: #edf4ff;
        padding: 5px 9px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .product-info h3 {
        margin: 0 0 9px;
        font-size: 19px;
    }

    .description {
        color: #687386;
        font-size: 14px;
        line-height: 1.5;
        min-height: 42px;
    }

    .product-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 17px;
        padding-top: 14px;
        border-top: 1px solid #edf0f4;
    }

    .price {
        color: #111827;
        font-size: 18px;
        font-weight: bold;
    }

    .seller {
        color: #737b89;
        font-size: 12px;
    }

    .view-btn {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: #2563eb;
        font-weight: bold;
        font-size: 14px;
    }

    .view-btn:hover {
        text-decoration: underline;
    }

    /* Empty state */
    .empty-box {
        background: white;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        padding: 45px 20px;
        text-align: center;
        color: #6b7280;
    }

    .empty-box h3 {
        color: #172033;
        margin-top: 0;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .welcome-box {
            padding: 40px;
        }

        .hero-card {
            display: none;
        }

        .products {
            grid-template-columns: repeat(2, 1fr);
        }

        .steps {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .home-wrap {
            padding: 20px 14px 40px;
        }

        .welcome-box {
            padding: 30px 22px;
            min-height: auto;
        }

        .welcome-content h1 {
            font-size: 35px;
        }

        .welcome-content p {
            font-size: 15px;
        }

        .products {
            grid-template-columns: 1fr;
        }

        .section-heading {
            display: block;
        }

        .section-heading h2 {
            font-size: 24px;
        }
    }
</style>

<main class="home-wrap">

    <!-- Hero Section -->
    <section class="welcome-box">

        <div class="welcome-content">

            <div class="small-title">
                Student Marketplace
            </div>

            <h1>
                Find useful things from your
                <span>own campus.</span>
            </h1>

            <p>
                CampusCart makes it easy for students to buy and sell
                useful items. Find books, electronics, cycles and other
                things from students around you.
            </p>

            <div class="button-row">

                <a href="index.php" class="main-btn">
                    Browse Products
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <a href="add_product.php" class="second-btn">
                        Sell an Item
                    </a>

                <?php else: ?>

                    <a href="register.php" class="second-btn">
                        Join CampusCart
                    </a>

                <?php endif; ?>

            </div>

        </div>

        <div class="hero-card">

            <h3>What students sell</h3>

            <div class="hero-item">
                <span>📚 Books</span>
                <strong>Easy</strong>
            </div>

            <div class="hero-item">
                <span>💻 Electronics</span>
                <strong>Nearby</strong>
            </div>

            <div class="hero-item">
                <span>🚲 Cycles</span>
                <strong>Local</strong>
            </div>

            <div class="hero-item">
                <span>🎒 Other Items</span>
                <strong>Useful</strong>
            </div>

        </div>

    </section>


    <!-- How CampusCart Works -->
    <section>

        <div class="section-heading">

            <div>
                <h2>How CampusCart works</h2>
                <p>Three simple steps to get started.</p>
            </div>

        </div>

        <div class="steps">

            <div class="step">

                <div class="step-number">1</div>

                <h3>Find an item</h3>

                <p>
                    Browse products posted by other students
                    and find something useful for you.
                </p>

            </div>


            <div class="step">

                <div class="step-number">2</div>

                <h3>Check the details</h3>

                <p>
                    Open the product to see its price,
                    description and seller information.
                </p>

            </div>


            <div class="step">

                <div class="step-number">3</div>

                <h3>Contact the seller</h3>

                <p>
                    Send a message to the student who posted
                    the item and discuss the purchase.
                </p>

            </div>

        </div>

    </section>


    <!-- Latest Products -->
    <section>

        <div class="section-heading">

            <div>
                <h2>Latest products</h2>
                <p>Recently added items from students.</p>
            </div>

        </div>


        <?php if (count($products) > 0): ?>

            <div class="products">

                <?php foreach ($products as $p): ?>

                    <article class="product-card">

                        <?php if (!empty($p['image'])): ?>

                            <img
                                src="uploads/<?= htmlspecialchars($p['image']) ?>"
                                alt="<?= htmlspecialchars($p['name']) ?>"
                                class="product-image"
                            >

                        <?php else: ?>

                            <div class="no-image">
                                No image available
                            </div>

                        <?php endif; ?>


                        <div class="product-info">

                            <span class="category">
                                Campus Item
                            </span>

                            <h3>
                                <?= htmlspecialchars($p['name']) ?>
                            </h3>

                            <div class="description">
                                <?= htmlspecialchars($p['description']) ?>
                            </div>


                            <div class="product-bottom">

                                <div>
                                    <div class="price">
                                        ₹<?= number_format($p['price'], 2) ?>
                                    </div>

                                    <div class="seller">
                                        Seller: <?= htmlspecialchars($p['seller']) ?>
                                    </div>
                                </div>

                            </div>


                            <a
                                href="product.php?id=<?= (int)$p['id'] ?>"
                                class="view-btn"
                            >
                                View Details →
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-box">

                <h3>No products yet</h3>

                <p>
                    Be the first student to post something on CampusCart.
                </p>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <a href="add_product.php" class="main-btn">
                        Add Your First Product
                    </a>

                <?php else: ?>

                    <a href="register.php" class="main-btn">
                        Create an Account
                    </a>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php include "includes/footer.php"; ?>