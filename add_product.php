<?php

require_once "includes/db.php";
require_once "includes/auth.php";

requireLogin();

$error = "";


/* =====================================================
   CHECK WHETHER LOGGED-IN USER STILL EXISTS
   ===================================================== */

$userId = (int)($_SESSION["user_id"] ?? 0);

$userStmt = $pdo->prepare("
    SELECT id, name
    FROM users
    WHERE id = ?
");

$userStmt->execute([$userId]);

$currentUser = $userStmt->fetch();


/*
   If the session contains an invalid user ID,
   log the user out and ask them to login again.
*/

if (!$currentUser) {

    $_SESSION = [];

    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            "",
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    header("Location: login.php?error=session_expired");
    exit;
}


/* =====================================================
   PRODUCT FORM
   ===================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");

    $category = trim($_POST["category"] ?? "");

    $price = (float)($_POST["price"] ?? 0);

    $description = trim($_POST["description"] ?? "");

    $imageName = null;


    /* ---------------------------------------------
       BASIC VALIDATION
       --------------------------------------------- */

    if (
        $title === "" ||
        $category === "" ||
        $description === "" ||
        $price <= 0
    ) {

        $error = "Please enter valid product details.";

    }


    /* ---------------------------------------------
       IMAGE UPLOAD
       --------------------------------------------- */

    elseif (!empty($_FILES["image"]["name"])) {

        $allowed = [
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp"
        ];

        $ext = strtolower(
            pathinfo(
                $_FILES["image"]["name"],
                PATHINFO_EXTENSION
            )
        );


        if (
            !in_array($ext, $allowed, true) ||
            $_FILES["image"]["size"] > 3 * 1024 * 1024
        ) {

            $error =
                "Use JPG, PNG, GIF or WEBP image up to 3 MB.";

        }

        else {

            $imageName =
                uniqid("product_", true) . "." . $ext;


            $uploadPath =
                __DIR__ . "/uploads/" . $imageName;


            if (
                !move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    $uploadPath
                )
            ) {

                $error = "Image upload failed.";

                $imageName = null;
            }
        }
    }


    /* =================================================
       INSERT PRODUCT
       ================================================= */

    if ($error === "") {

        try {

            /*
             * IMPORTANT:
             * Use the verified user ID from the database.
             */

            $stmt = $pdo->prepare("
                INSERT INTO products
                (
                    user_id,
                    title,
                    category,
                    price,
                    description,
                    image
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
            ");


            $stmt->execute([
                $currentUser["id"],
                $title,
                $category,
                $price,
                $description,
                $imageName
            ]);


            header(
                "Location: my_products.php?success=" .
                urlencode("Product uploaded successfully.")
            );

            exit;


        } catch (PDOException $e) {

            /*
             * If database insertion fails after
             * uploading the image, remove the image.
             */

            if (
                $imageName !== null &&
                file_exists(__DIR__ . "/uploads/" . $imageName)
            ) {

                unlink(
                    __DIR__ . "/uploads/" . $imageName
                );
            }


            $error =
                "Product could not be added. " .
                "Please login again and try.";
        }
    }
}


$pageTitle = "Sell Product";

include "includes/header.php";

?>


<div class="form-card">

    <h2>Upload Product</h2>

    <p class="muted">
        Add an image and clear details so another
        student can understand what you are selling.
    </p>


    <?php if ($error): ?>

        <div class="error">
            <?= h($error) ?>
        </div>

    <?php endif; ?>


    <form
        method="post"
        enctype="multipart/form-data"
        data-validate
    >


        <!-- PRODUCT IMAGE -->

        <div class="form-group">

            <label>
                Product Image
            </label>

            <input
                id="image"
                type="file"
                name="image"
                accept="image/*"
            >

            <img
                id="imagePreview"
                style="
                    display:none;
                    max-width:220px;
                    margin-top:10px;
                    border-radius:8px;
                "
            >

        </div>


        <!-- TITLE -->

        <div class="form-group">

            <label>
                Title
            </label>

            <input
                name="title"
                required
                placeholder="Scientific Calculator"
            >

        </div>


        <!-- CATEGORY -->

        <div class="form-group">

            <label>
                Category
            </label>

            <select
                name="category"
                required
            >

                <option value="">
                    Select Category
                </option>

                <option>
                    Books
                </option>

                <option>
                    Electronics
                </option>

                <option>
                    Furniture
                </option>

                <option>
                    Cycles
                </option>

                <option>
                    Clothing
                </option>

                <option>
                    Other
                </option>

            </select>

        </div>


        <!-- PRICE -->

        <div class="form-group">

            <label>
                Price (₹)
            </label>

            <input
                type="number"
                step="0.01"
                min="1"
                name="price"
                required
            >

        </div>


        <!-- DESCRIPTION -->

        <div class="form-group">

            <label>
                Description
            </label>

            <textarea
                name="description"
                required
                placeholder="Condition, features, pickup details..."
            ></textarea>

        </div>


        <button
            type="submit"
            class="btn"
        >
            Upload Product
        </button>


    </form>

</div>


<?php include "includes/footer.php"; ?>