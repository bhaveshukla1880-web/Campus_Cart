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

/* =====================================================
   GENERAL CSS
   ===================================================== */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f7fb;
    color: #1f2937;
}

.container {
    width: min(1120px, 92%);
    margin: auto;
}


/* =====================================================
   HEADER
   ===================================================== */

.site-header {
    background: #172554;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 2px 10px #0002;
}

.nav-wrap {
    min-height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    text-decoration: none;
}

.logo span {
    color: #93c5fd;
}

.site-header nav {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
}

.site-header nav a {
    color: #fff;
    text-decoration: none;
    padding: 8px;
}

.nav-button,
.btn {
    background: #2563eb;
    color: #fff !important;
    border: 0;
    border-radius: 8px;
    padding: 10px 15px;
    text-decoration: none;
    cursor: pointer;
}


/* =====================================================
   PAGE
   ===================================================== */

.page {
    padding: 28px 0 60px;
}


/* =====================================================
   HERO
   ===================================================== */

.hero {
    position: relative;

    background:
        linear-gradient(
            135deg,
            rgba(219, 234, 254, 0.94),
            rgba(239, 246, 255, 0.92)
        ),
        url("https://images.unsplash.com/photo-1564981797816-1043664bf78d?auto=format&fit=crop&w=1600&q=80");

    background-size: cover;
    background-position: center;

    padding: 55px;

    border-radius: 22px;

    margin-bottom: 35px;

    overflow: hidden;

    box-shadow:
        0 15px 40px rgba(30, 64, 175, 0.12);

    animation: heroShow 0.8s ease;
}


/* Decorative circles */

.hero::before {
    content: "";

    position: absolute;

    width: 220px;
    height: 220px;

    border-radius: 50%;

    background: rgba(37, 99, 235, 0.10);

    right: -70px;
    top: -80px;

    animation: floating 5s ease-in-out infinite;
}

.hero::after {
    content: "";

    position: absolute;

    width: 140px;
    height: 140px;

    border-radius: 50%;

    background: rgba(59, 130, 246, 0.08);

    right: 180px;
    bottom: -80px;

    animation: floating 4s ease-in-out infinite reverse;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-label {
    display: inline-block;

    background: #ffffff;

    color: #2563eb;

    padding: 7px 13px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: bold;

    margin-bottom: 15px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.06);

    animation: labelPulse 3s infinite;
}

.hero h1 {
    font-size: 46px;

    line-height: 1.1;

    margin: 0 0 15px;

    max-width: 650px;
}

.hero h1 span {
    color: #2563eb;
}

.hero p {
    max-width: 650px;

    font-size: 17px;

    line-height: 1.7;

    color: #4b5563;
}


/* =====================================================
   BUTTONS
   ===================================================== */

.actions {
    display: flex;

    gap: 12px;

    flex-wrap: wrap;

    margin-top: 22px;
}

.btn {
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}

.btn:hover {
    transform: translateY(-3px);

    box-shadow:
        0 8px 20px rgba(37, 99, 235, 0.20);
}

.btn.secondary {
    background: #e5e7eb;

    color: #111827 !important;
}


/* =====================================================
   SECTION
   ===================================================== */

.section {
    margin: 40px 0;
}

.section-title {
    margin-bottom: 22px;
}

.section-title h2 {
    font-size: 29px;

    margin: 0 0 5px;
}

.section-title p {
    margin: 0;

    color: #6b7280;
}


/* =====================================================
   HOW IT WORKS
   ===================================================== */

.steps {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;
}

.step {
    background: #fff;

    border: 1px solid #e5e7eb;

    border-radius: 15px;

    padding: 25px;

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}

.step:hover {
    transform: translateY(-7px);

    box-shadow:
        0 15px 30px rgba(0,0,0,0.08);
}

.step-number {
    width: 42px;
    height: 42px;

    border-radius: 50%;

    background: #dbeafe;

    color: #2563eb;

    display: flex;

    align-items: center;

    justify-content: center;

    font-weight: bold;

    margin-bottom: 15px;
}

.step h3 {
    margin: 0 0 8px;
}

.step p {
    color: #6b7280;

    line-height: 1.6;

    margin: 0;
}


/* =====================================================
   PRODUCT GRID
   ===================================================== */

.grid {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;
}

.product-card,
.card {
    background: #fff;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

    padding: 18px;

    box-shadow:
        0 3px 12px #0000000a;

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-7px);

    box-shadow:
        0 15px 30px rgba(0,0,0,0.10);
}

.product-card img,
.product-image {
    width: 100%;

    height: 210px;

    object-fit: cover;

    border-radius: 10px;

    background: #e5e7eb;

    transition: transform 0.4s ease;
}

.product-card:hover img {
    transform: scale(1.03);
}

.product-card h3 {
    margin-bottom: 5px;
}

.price {
    font-size: 20px;

    font-weight: bold;

    color: #166534;
}

.muted {
    color: #6b7280;
}


/* =====================================================
   PRODUCT DESCRIPTION
   ===================================================== */

.product-description {
    color: #6b7280;

    font-size: 14px;

    line-height: 1.5;

    min-height: 42px;
}

.product-info {
    padding-top: 15px;
}

.product-tag {
    display: inline-block;

    padding: 5px 9px;

    background: #e0e7ff;

    color: #1e40af;

    border-radius: 20px;

    font-size: 12px;

    margin-bottom: 10px;
}

.view-product {
    display: inline-block;

    margin-top: 14px;

    color: #2563eb;

    text-decoration: none;

    font-weight: bold;
}

.view-product:hover {
    text-decoration: underline;
}


/* =====================================================
   EMPTY MESSAGE
   ===================================================== */

.notice {
    padding: 20px;

    background: #dbeafe;

    border-radius: 10px;

    color: #1e3a8a;
}


/* =====================================================
   FOOTER
   ===================================================== */

.footer {
    background: #111827;

    color: #d1d5db;

    padding: 22px 0;

    text-align: center;
}


/* =====================================================
   ANIMATIONS
   ===================================================== */

@keyframes heroShow {

    from {
        opacity: 0;
        transform: translateY(25px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }

}


@keyframes floating {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(20px);
    }

}


@keyframes labelPulse {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.04);
    }

}


/* =====================================================
   RESPONSIVE DESIGN
   ===================================================== */

@media (max-width: 800px) {

    .grid {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .steps {
        grid-template-columns: 1fr;
    }

    .hero h1 {
        font-size: 36px;
    }

    .hero {
        padding: 38px;
    }

}


@media (max-width: 560px) {

    .nav-wrap {
        align-items: flex-start;

        padding: 12px 0;

        flex-direction: column;
    }

    .grid {
        grid-template-columns: 1fr;
    }

    .hero {
        padding: 28px 22px;
    }

    .hero h1 {
        font-size: 31px;
    }

    .hero p {
        font-size: 15px;
    }

    .site-header nav {
        gap: 5px;
    }

}


/* =====================================================
   ADDITIONAL FORM STYLES
   ===================================================== */

.form-card {
    max-width: 700px;

    margin: auto;

    background: #fff;

    padding: 28px;

    border-radius: 16px;
}

.form-group {
    margin: 15px 0;
}

.form-group label {
    display: block;

    font-weight: bold;

    margin-bottom: 6px;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;

    padding: 11px;

    border: 1px solid #cbd5e1;

    border-radius: 8px;

    font-size: 15px;
}

.form-group textarea {
    min-height: 120px;
}


/* =====================================================
   ERROR / SUCCESS
   ===================================================== */

.error {
    background: #fee2e2;

    color: #991b1b;

    padding: 10px;

    border-radius: 8px;

    margin: 12px 0;
}

.success {
    background: #dcfce7;

    color: #166534;

    padding: 10px;

    border-radius: 8px;

    margin: 12px 0;
}


/* =====================================================
   OTHER PROJECT ELEMENTS
   ===================================================== */

.search {
    margin: 20px 0;
}

.search input {
    width: 100%;

    padding: 11px;

    border: 1px solid #cbd5e1;

    border-radius: 8px;

    font-size: 15px;
}

.two-col {
    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 24px;
}

.detail {
    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 28px;

    background: #fff;

    padding: 24px;

    border-radius: 16px;
}

.detail img {
    width: 100%;

    max-height: 430px;

    object-fit: cover;

    border-radius: 12px;
}

.table-wrap {
    overflow: auto;

    background: #fff;

    border-radius: 12px;
}

.table {
    width: 100%;

    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px;

    border-bottom:
        1px solid #e5e7eb;

    text-align: left;
}

.small-btn {
    padding: 6px 9px;

    border-radius: 6px;

    text-decoration: none;

    background: #e5e7eb;

    color: #111827;
}

.danger {
    background: #dc2626;

    color: #fff;
}

.badge {
    display: inline-block;

    padding: 5px 9px;

    background: #e0e7ff;

    border-radius: 20px;

    font-size: 12px;
}

.practical-list {
    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 12px;
}

.practical-list a {
    display: block;

    background: #fff;

    padding: 15px;

    border-radius: 10px;

    text-decoration: none;

    color: #1e3a8a;

    border: 1px solid #e5e7eb;
}

</style>


<!-- =====================================================
     CAMPUSCART PAGE CONTENT
     ===================================================== -->

<main class="container page">

    <!-- HERO -->

    <section class="hero">

        <div class="hero-content">

            <span class="hero-label">
                🎓 Student Marketplace
            </span>

            <h1>
                Buy, Sell &
                <span>Connect</span>
                on Campus
            </h1>

            <p>
                CampusCart helps students buy and sell useful
                products within their campus. Find books,
                electronics, cycles and other items from
                fellow students.
            </p>

            <div class="actions">

                <a href="index.php" class="btn">
                    Browse Products
                </a>


                <?php if (isset($_SESSION["user_id"])): ?>

                    <a href="add_product.php"
                       class="btn secondary">
                        Sell an Item
                    </a>

                <?php else: ?>

                    <a href="register.php"
                       class="btn secondary">
                        Join CampusCart
                    </a>

                <?php endif; ?>

            </div>

        </div>

    </section>


    <!-- HOW CAMPUSCART WORKS -->

    <section class="section">

        <div class="section-title">

            <h2>
                How CampusCart Works
            </h2>

            <p>
                Three simple steps for buying and selling.
            </p>

        </div>


        <div class="steps">

            <div class="step">

                <div class="step-number">
                    1
                </div>

                <h3>
                    Find a Product
                </h3>

                <p>
                    Browse products posted by students
                    and find something useful.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    2
                </div>

                <h3>
                    Check Details
                </h3>

                <p>
                    View the product image, price,
                    description and seller information.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    3
                </div>

                <h3>
                    Contact the Seller
                </h3>

                <p>
                    Send a message to the seller
                    and discuss the product.
                </p>

            </div>

        </div>

    </section>


    <!-- LATEST PRODUCTS -->

    <section class="section">

        <div class="section-title">

            <h2>
                Latest Products
            </h2>

            <p>
                Recently added products from students.
            </p>

        </div>


        <?php if (count($products) > 0): ?>

            <div class="grid">

                <?php foreach ($products as $p): ?>

                    <article class="product-card">


                        <?php if (!empty($p["image"])): ?>

                            <img
                                src="uploads/<?= h($p["image"]) ?>"
                                alt="<?= h($p["name"]) ?>"
                                class="product-image"
                            >

                        <?php else: ?>

                            <div
                                class="product-image"
                                style="
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    color:#6b7280;
                                "
                            >
                                No Image
                            </div>

                        <?php endif; ?>


                        <div class="product-info">

                            <span class="product-tag">
                                Campus Item
                            </span>


                            <h3>
                                <?= h($p["name"]) ?>
                            </h3>


                            <div class="product-description">

                                <?= h($p["description"]) ?>

                            </div>


                            <div
                                style="
                                    margin-top:15px;
                                    display:flex;
                                    justify-content:space-between;
                                    align-items:center;
                                "
                            >

                                <div>

                                    <div class="price">
                                        ₹<?= number_format(
                                            $p["price"],
                                            2
                                        ) ?>
                                    </div>

                                    <div class="muted">
                                        Seller:
                                        <?= h($p["seller"]) ?>
                                    </div>

                                </div>

                            </div>


                            <a
                                href="product.php?id=<?= (int)$p["id"] ?>"
                                class="view-product"
                            >
                                View Product →
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


        <?php else: ?>

            <div class="notice">

                No products have been added yet.

                <?php if (isset($_SESSION["user_id"])): ?>

                    <br><br>

                    <a
                        href="add_product.php"
                        class="btn"
                    >
                        Add First Product
                    </a>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </section>

</main>


<?php include "includes/footer.php"; ?>