<?php

require_once "includes/db.php";
require_once "includes/auth.php";

$pageTitle = "CampusCart - Student Marketplace";

/*
|--------------------------------------------------------------------------
| Get latest products
|--------------------------------------------------------------------------
|
| products table:
| id
| user_id
| title
| category
| price
| description
| image
|
| users table:
| id
| name
|
*/

$stmt = $pdo->query("
    SELECT
        p.*,
        u.name AS seller
    FROM products p
    JOIN users u
        ON u.id = p.user_id
    ORDER BY p.created_at DESC
    LIMIT 6
");

$products = $stmt->fetchAll();

include "includes/header.php";

?>

<style>

/* =========================================================
   CAMPUSCART HOME PAGE
   INTERNAL CSS
   ========================================================= */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #f5f7fb;
    color: #172033;
}

.container {
    width: min(1120px, 92%);
    margin: auto;
}

.page {
    padding: 30px 0 60px;
}


/* =========================================================
   HERO BANNER
   ========================================================= */

.hero-banner {
    position: relative;

    min-height: 480px;

    border-radius: 24px;

    overflow: hidden;

    display: flex;

    align-items: center;

    padding: 55px;

    margin-bottom: 45px;

    background: #172554;

    box-shadow:
        0 18px 45px rgba(15, 23, 42, 0.20);
}


/* =========================================================
   ROTATING BACKGROUND IMAGE 1
   Campus Image
   ========================================================= */

.banner-image-one {
    position: absolute;

    inset: 0;

    width: 100%;
    height: 100%;

    background-image:
        linear-gradient(
            rgba(10, 35, 70, 0.70),
            rgba(10, 35, 70, 0.70)
        ),
        url("assets/campus.jpg");

    background-size: cover;

    background-position: center;

    background-repeat: no-repeat;

    animation: campusImage 10s infinite;

    z-index: 1;
}


/* =========================================================
   ROTATING BACKGROUND IMAGE 2
   Calculator Image
   ========================================================= */

.banner-image-two {
    position: absolute;

    inset: 0;

    width: 100%;
    height: 100%;

    background-image:
        linear-gradient(
            rgba(10, 35, 70, 0.70),
            rgba(10, 35, 70, 0.70)
        ),
        url("assets/calculator.jpg");

    background-size: cover;

    background-position: center;

    background-repeat: no-repeat;

    animation: calculatorImage 10s infinite;

    z-index: 2;
}


/* =========================================================
   CAMPUS IMAGE ANIMATION
   ========================================================= */

@keyframes campusImage {

    0% {
        opacity: 1;
        transform: scale(1);
    }

    40% {
        opacity: 1;
        transform: scale(1.05);
    }

    50% {
        opacity: 0;
        transform: scale(1.08);
    }

    90% {
        opacity: 0;
    }

    100% {
        opacity: 1;
        transform: scale(1);
    }
}


/* =========================================================
   CALCULATOR IMAGE ANIMATION
   ========================================================= */

@keyframes calculatorImage {

    0% {
        opacity: 0;
        transform: scale(1.08);
    }

    40% {
        opacity: 0;
    }

    50% {
        opacity: 1;
        transform: scale(1);
    }

    90% {
        opacity: 1;
        transform: scale(1.05);
    }

    100% {
        opacity: 0;
        transform: scale(1.08);
    }
}


/* =========================================================
   HERO CONTENT
   ========================================================= */

.hero-content {
    position: relative;

    z-index: 5;

    max-width: 690px;

    color: #ffffff;

    animation: heroAppear 1s ease;
}


.hero-label {
    display: inline-block;

    padding: 8px 15px;

    background: rgba(255, 255, 255, 0.16);

    border: 1px solid rgba(255, 255, 255, 0.30);

    border-radius: 30px;

    font-size: 13px;

    font-weight: bold;

    margin-bottom: 18px;

    backdrop-filter: blur(5px);

    animation: labelAnimation 3s infinite;
}


.hero-content h1 {
    margin: 0 0 18px;

    font-size: 52px;

    line-height: 1.08;

    letter-spacing: -1px;
}


.hero-content h1 span {
    color: #93c5fd;
}


.hero-content p {
    margin: 0 0 28px;

    max-width: 650px;

    color: #e5efff;

    font-size: 17px;

    line-height: 1.7;
}


/* =========================================================
   HERO BUTTONS
   ========================================================= */

.hero-buttons {
    display: flex;

    gap: 12px;

    flex-wrap: wrap;
}


.hero-btn {
    display: inline-block;

    padding: 13px 22px;

    border-radius: 10px;

    text-decoration: none;

    font-weight: bold;

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        background 0.25s ease;
}


.hero-btn-primary {
    background: #ffffff;

    color: #1d4ed8;
}


.hero-btn-primary:hover {
    transform: translateY(-4px);

    box-shadow:
        0 10px 25px rgba(0, 0, 0, 0.25);
}


.hero-btn-secondary {
    background: rgba(255, 255, 255, 0.12);

    color: #ffffff;

    border: 1px solid rgba(255, 255, 255, 0.45);
}


.hero-btn-secondary:hover {
    background: #ffffff;

    color: #1d4ed8;

    transform: translateY(-4px);
}


/* =========================================================
   FLOATING CARD
   ========================================================= */

.floating-card {
    position: absolute;

    right: 45px;

    bottom: 40px;

    z-index: 6;

    width: 235px;

    padding: 20px;

    border-radius: 18px;

    background: rgba(255, 255, 255, 0.95);

    color: #172033;

    box-shadow:
        0 15px 35px rgba(0, 0, 0, 0.25);

    animation: floatingCard 4s ease-in-out infinite;
}


.floating-icon {
    width: 48px;

    height: 48px;

    border-radius: 13px;

    background: #dbeafe;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 25px;

    margin-bottom: 12px;
}


.floating-card h3 {
    margin: 0 0 7px;

    font-size: 19px;
}


.floating-card p {
    margin: 0;

    color: #64748b;

    font-size: 13px;

    line-height: 1.5;
}


/* =========================================================
   BANNER DOTS
   ========================================================= */

.banner-indicator {
    position: absolute;

    z-index: 8;

    right: 30px;

    top: 25px;

    display: flex;

    gap: 7px;
}


.banner-dot {
    width: 9px;

    height: 9px;

    border-radius: 50%;

    background: rgba(255, 255, 255, 0.55);

    animation: dotOne 10s infinite;
}


.banner-dot:nth-child(2) {
    animation: dotTwo 10s infinite;
}


/* =========================================================
   SECTION
   ========================================================= */

.section {
    margin: 55px 0;
}


.section-heading {
    margin-bottom: 23px;
}


.section-heading h2 {
    margin: 0 0 6px;

    font-size: 29px;
}


.section-heading p {
    margin: 0;

    color: #6b7280;
}


/* =========================================================
   HOW IT WORKS
   ========================================================= */

.steps {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;
}


.step {
    background: #ffffff;

    border: 1px solid #e3e8f0;

    border-radius: 17px;

    padding: 25px;

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}


.step:hover {
    transform: translateY(-8px);

    box-shadow:
        0 15px 35px rgba(30, 41, 59, 0.10);
}


.step-number {
    width: 42px;

    height: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #dbeafe;

    color: #2563eb;

    font-weight: bold;

    margin-bottom: 16px;
}


.step h3 {
    margin: 0 0 8px;
}


.step p {
    margin: 0;

    color: #687386;

    line-height: 1.6;
}


/* =========================================================
   PRODUCTS
   ========================================================= */

.products {
    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 22px;
}


.product-card {
    background: #ffffff;

    border: 1px solid #e3e8f0;

    border-radius: 17px;

    overflow: hidden;

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}


.product-card:hover {
    transform: translateY(-8px);

    box-shadow:
        0 18px 35px rgba(30, 41, 59, 0.13);
}


/* =========================================================
   PRODUCT IMAGE
   ========================================================= */

.product-image {
    width: 100%;

    height: 205px;

    object-fit: cover;

    display: block;

    background: #e5e7eb;

    transition:
        transform 0.4s ease;
}


.product-card:hover .product-image {
    transform: scale(1.04);
}


.no-image {
    width: 100%;

    height: 205px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #e5e7eb;

    color: #6b7280;
}


/* =========================================================
   PRODUCT INFORMATION
   ========================================================= */

.product-info {
    padding: 18px;
}


.product-tag {
    display: inline-block;

    padding: 5px 9px;

    background: #eff6ff;

    color: #2563eb;

    border-radius: 6px;

    font-size: 12px;

    margin-bottom: 10px;
}


.product-info h3 {
    margin: 0 0 8px;

    font-size: 19px;
}


.product-description {
    color: #6b7280;

    font-size: 14px;

    line-height: 1.5;

    min-height: 42px;
}


.product-bottom {
    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 17px;

    padding-top: 14px;

    border-top: 1px solid #edf0f4;
}


.price {
    color: #166534;

    font-size: 18px;

    font-weight: bold;
}


.seller {
    color: #737b89;

    font-size: 12px;

    margin-top: 3px;
}


.view-button {
    display: inline-block;

    margin-top: 15px;

    color: #2563eb;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;

    transition: 0.2s;
}


.view-button:hover {
    padding-left: 5px;
}


/* =========================================================
   EMPTY PRODUCTS
   ========================================================= */

.empty-products {
    background: #ffffff;

    border: 1px dashed #cbd5e1;

    border-radius: 16px;

    padding: 45px 20px;

    text-align: center;
}


.empty-products h3 {
    margin-top: 0;
}


.empty-products p {
    color: #6b7280;

    margin-bottom: 20px;
}


/* =========================================================
   ANIMATIONS
   ========================================================= */

@keyframes heroAppear {

    from {
        opacity: 0;

        transform: translateY(25px);
    }

    to {
        opacity: 1;

        transform: translateY(0);
    }

}


@keyframes floatingCard {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-12px);
    }

}


@keyframes labelAnimation {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.05);
    }

}


@keyframes dotOne {

    0%,
    45% {
        background: #ffffff;

        transform: scale(1.25);
    }

    50%,
    100% {
        background: rgba(255, 255, 255, 0.55);

        transform: scale(1);
    }

}


@keyframes dotTwo {

    0%,
    45% {
        background: rgba(255, 255, 255, 0.55);

        transform: scale(1);
    }

    50%,
    95% {
        background: #ffffff;

        transform: scale(1.25);
    }

    100% {
        background: rgba(255, 255, 255, 0.55);

        transform: scale(1);
    }

}


/* =========================================================
   RESPONSIVE DESIGN
   ========================================================= */

@media (max-width: 900px) {

    .hero-banner {
        padding: 45px 35px;
    }

    .hero-content h1 {
        font-size: 42px;
    }

    .floating-card {
        display: none;
    }

    .products {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .steps {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 600px) {

    .container {
        width: 94%;
    }

    .hero-banner {
        min-height: 450px;

        padding: 35px 23px;

        border-radius: 18px;
    }

    .hero-content h1 {
        font-size: 34px;
    }

    .hero-content p {
        font-size: 15px;
    }

    .products {
        grid-template-columns: 1fr;
    }

    .section-heading h2 {
        font-size: 25px;
    }

    .banner-indicator {
        right: 20px;

        top: 20px;
    }

}

</style>


<!-- =====================================================
     MAIN PAGE
     ===================================================== -->

<main class="container page">


    <!-- =================================================
         ROTATING HERO BANNER
         ================================================= -->

    <section class="hero-banner">


        <!-- Campus background -->

        <div class="banner-image-one"></div>


        <!-- Calculator background -->

        <div class="banner-image-two"></div>


        <!-- Banner indicators -->

        <div class="banner-indicator">

            <span class="banner-dot"></span>

            <span class="banner-dot"></span>

        </div>


        <!-- Hero content -->

        <div class="hero-content">


            <span class="hero-label">
                🎓 STUDENT MARKETPLACE
            </span>


            <h1>
                Buy, Sell &
                <span>Connect</span>
                on Campus
            </h1>


            <p>
                CampusCart makes it easy for students
                to buy and sell useful products within
                their campus. Find books, calculators,
                electronics, cycles and other useful
                items from fellow students.
            </p>


            <div class="hero-buttons">


                <a
                    href="products.php"
                    class="hero-btn hero-btn-primary"
                >
                    Browse Products
                </a>


                <?php if (isset($_SESSION["user_id"])): ?>

                    <a
                        href="add_product.php"
                        class="hero-btn hero-btn-secondary"
                    >
                        Sell an Item
                    </a>

                <?php else: ?>

                    <a
                        href="register.php"
                        class="hero-btn hero-btn-secondary"
                    >
                        Join CampusCart
                    </a>

                <?php endif; ?>


            </div>

        </div>


        <!-- Floating card -->

        <div class="floating-card">

            <div class="floating-icon">
                🛒
            </div>


            <h3>
                CampusCart
            </h3>


            <p>
                Your campus. Your students.
                Your marketplace.
            </p>

        </div>


    </section>



    <!-- =================================================
         HOW CAMPUSCART WORKS
         ================================================= -->

    <section class="section">


        <div class="section-heading">

            <h2>
                How CampusCart Works
            </h2>

            <p>
                Buy and sell products in three simple steps.
            </p>

        </div>


        <div class="steps">


            <!-- STEP 1 -->

            <div class="step">

                <div class="step-number">
                    1
                </div>


                <h3>
                    Find a Product
                </h3>


                <p>
                    Browse products uploaded by other
                    students and find something useful
                    for yourself.
                </p>

            </div>


            <!-- STEP 2 -->

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


            <!-- STEP 3 -->

            <div class="step">

                <div class="step-number">
                    3
                </div>


                <h3>
                    Contact the Seller
                </h3>


                <p>
                    Send a message to the student who
                    posted the product and discuss the
                    purchase.
                </p>

            </div>


        </div>

    </section>



    <!-- =================================================
         LATEST PRODUCTS
         ================================================= -->

    <section class="section">


        <div class="section-heading">

            <h2>
                Latest Products
            </h2>


            <p>
                Recently added products from students.
            </p>

        </div>


        <?php if (!empty($products)): ?>


            <div class="products">


                <?php foreach ($products as $p): ?>


                    <article class="product-card">


                        <!-- PRODUCT IMAGE -->

                        <?php if (!empty($p["image"])): ?>


                            <img
                                src="uploads/<?= h($p["image"]) ?>"
                                alt="<?= h($p["title"]) ?>"
                                class="product-image"
                            >


                        <?php else: ?>


                            <div class="no-image">

                                No Image Available

                            </div>


                        <?php endif; ?>


                        <!-- PRODUCT DETAILS -->

                        <div class="product-info">


                            <span class="product-tag">

                                <?= h($p["category"]) ?>

                            </span>


                            <!-- IMPORTANT:
                                 Use title, NOT name -->

                            <h3>

                                <?= h($p["title"]) ?>

                            </h3>


                            <div class="product-description">

                                <?= h($p["description"]) ?>

                            </div>


                            <div class="product-bottom">


                                <div>


                                    <div class="price">

                                        ₹<?= number_format(
                                            (float)$p["price"],
                                            2
                                        ) ?>

                                    </div>


                                    <div class="seller">

                                        Seller:
                                        <?= h($p["seller"]) ?>

                                    </div>


                                </div>


                            </div>


                            <a
                                href="product.php?id=<?= (int)$p["id"] ?>"
                                class="view-button"
                            >
                                View & Contact →
                            </a>


                        </div>


                    </article>


                <?php endforeach; ?>


            </div>


        <?php else: ?>


            <!-- NO PRODUCTS -->

            <div class="empty-products">


                <h3>
                    No products available yet
                </h3>


                <p>
                    Be the first student to add a product
                    to CampusCart.
                </p>


                <?php if (isset($_SESSION["user_id"])): ?>


                    <a
                        href="add_product.php"
                        class="hero-btn"
                        style="
                            background:#2563eb;
                            color:#ffffff;
                        "
                    >
                        Add Product
                    </a>


                <?php else: ?>


                    <a
                        href="register.php"
                        class="hero-btn"
                        style="
                            background:#2563eb;
                            color:#ffffff;
                        "
                    >
                        Create Account
                    </a>


                <?php endif; ?>


            </div>


        <?php endif; ?>


    </section>


</main>


<?php

include "includes/footer.php";

?>