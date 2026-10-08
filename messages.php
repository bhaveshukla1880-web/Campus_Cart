<?php

require_once "includes/db.php";
require_once "includes/auth.php";

requireLogin();

$currentUserId = (int)$_SESSION["user_id"];

$error = "";
$success = "";


/* =========================================================
   SEND REPLY
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $productId = (int)($_POST["product_id"] ?? 0);

    $receiverId = (int)($_POST["receiver_id"] ?? 0);

    $message = trim($_POST["message"] ?? "");


    if ($productId <= 0 || $receiverId <= 0) {

        $error = "Invalid conversation.";

    } elseif ($receiverId === $currentUserId) {

        $error = "You cannot send a message to yourself.";

    } elseif ($message === "") {

        $error = "Please enter a message.";

    } elseif (strlen($message) > 2000) {

        $error = "Message is too long.";

    } else {

        /*
         * Make sure the receiver actually exists.
         */

        $userCheck = $pdo->prepare("
            SELECT id
            FROM users
            WHERE id = ?
        ");

        $userCheck->execute([$receiverId]);

        if (!$userCheck->fetch()) {

            $error = "The selected student does not exist.";

        } else {

            /*
             * Make sure the product exists.
             */

            $productCheck = $pdo->prepare("
                SELECT id
                FROM products
                WHERE id = ?
            ");

            $productCheck->execute([$productId]);

            if (!$productCheck->fetch()) {

                $error = "Product not found.";

            } else {

                /*
                 * Insert the new message.
                 */

                $insert = $pdo->prepare("
                    INSERT INTO messages
                    (
                        product_id,
                        sender_id,
                        receiver_id,
                        message
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?
                    )
                ");

                $insert->execute([
                    $productId,
                    $currentUserId,
                    $receiverId,
                    $message
                ]);

                $success = "Message sent successfully.";
            }
        }
    }
}


/* =========================================================
   GET ALL CONVERSATION MESSAGES
   ========================================================= */

$stmt = $pdo->prepare("
    SELECT
        m.id,
        m.product_id,
        m.sender_id,
        m.receiver_id,
        m.message,
        m.created_at,

        p.title AS product_title,

        sender.name AS sender_name,
        sender.phone AS sender_phone,

        receiver.name AS receiver_name,
        receiver.phone AS receiver_phone

    FROM messages m

    JOIN products p
        ON p.id = m.product_id

    JOIN users sender
        ON sender.id = m.sender_id

    JOIN users receiver
        ON receiver.id = m.receiver_id

    WHERE
        m.sender_id = ?
        OR
        m.receiver_id = ?

    ORDER BY
        m.created_at ASC
");

$stmt->execute([
    $currentUserId,
    $currentUserId
]);

$allMessages = $stmt->fetchAll();


/* =========================================================
   GROUP MESSAGES BY PRODUCT + OTHER USER
   ========================================================= */

$conversations = [];

foreach ($allMessages as $m) {

    /*
     * Find the other person in the conversation.
     */

    if ((int)$m["sender_id"] === $currentUserId) {

        $otherUserId = (int)$m["receiver_id"];

        $otherName = $m["receiver_name"];

        $otherPhone = $m["receiver_phone"];

    } else {

        $otherUserId = (int)$m["sender_id"];

        $otherName = $m["sender_name"];

        $otherPhone = $m["sender_phone"];
    }


    /*
     * Each product + person combination
     * becomes one conversation.
     */

    $conversationKey =
        $m["product_id"] . "_" . $otherUserId;


    if (!isset($conversations[$conversationKey])) {

        $conversations[$conversationKey] = [

            "product_id" =>
                (int)$m["product_id"],

            "product_title" =>
                $m["product_title"],

            "other_user_id" =>
                $otherUserId,

            "other_name" =>
                $otherName,

            "other_phone" =>
                $otherPhone,

            "messages" =>
                []

        ];
    }


    $conversations[$conversationKey]["messages"][] = $m;
}


$pageTitle = "Messages";

include "includes/header.php";

?>


<style>

/* =========================================================
   MESSAGES PAGE
   ========================================================= */

.messages-page {
    max-width: 1050px;

    margin: 35px auto 60px;

    padding: 0 20px;
}


.messages-heading {
    margin-bottom: 25px;
}


.messages-heading h1 {
    margin: 0 0 8px;

    font-size: 32px;

    color: #172033;
}


.messages-heading p {
    margin: 0;

    color: #6b7280;
}


/* =========================================================
   ALERTS
   ========================================================= */

.message-success {
    background: #dcfce7;

    color: #166534;

    border: 1px solid #bbf7d0;

    padding: 13px 16px;

    border-radius: 10px;

    margin-bottom: 20px;
}


.message-error {
    background: #fee2e2;

    color: #991b1b;

    border: 1px solid #fecaca;

    padding: 13px 16px;

    border-radius: 10px;

    margin-bottom: 20px;
}


/* =========================================================
   CONVERSATION
   ========================================================= */

.conversation {
    background: #ffffff;

    border: 1px solid #e2e8f0;

    border-radius: 18px;

    margin-bottom: 25px;

    overflow: hidden;

    box-shadow:
        0 8px 25px rgba(15, 23, 42, 0.06);
}


/* =========================================================
   CONVERSATION HEADER
   ========================================================= */

.conversation-header {
    padding: 18px 20px;

    background: #f8fafc;

    border-bottom: 1px solid #e2e8f0;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    flex-wrap: wrap;
}


.conversation-header h2 {
    margin: 0 0 5px;

    font-size: 20px;

    color: #172033;
}


.conversation-header p {
    margin: 0;

    color: #64748b;

    font-size: 13px;
}


.person-info {
    text-align: right;
}


.person-name {
    font-weight: bold;

    color: #1d4ed8;
}


.phone-link {
    display: inline-block;

    margin-top: 5px;

    color: #166534;

    text-decoration: none;

    font-size: 13px;
}


.phone-link:hover {
    text-decoration: underline;
}


/* =========================================================
   CHAT AREA
   ========================================================= */

.chat-area {
    padding: 20px;

    max-height: 450px;

    overflow-y: auto;

    background: #f8fafc;
}


.chat-message {
    display: flex;

    margin-bottom: 15px;
}


.chat-message:last-child {
    margin-bottom: 0;
}


.chat-message.mine {
    justify-content: flex-end;
}


.chat-message.theirs {
    justify-content: flex-start;
}


.message-bubble {
    max-width: 75%;

    padding: 12px 15px;

    border-radius: 15px;

    line-height: 1.5;

    font-size: 14px;
}


.mine .message-bubble {
    background: #2563eb;

    color: #ffffff;

    border-bottom-right-radius: 4px;
}


.theirs .message-bubble {
    background: #ffffff;

    color: #172033;

    border: 1px solid #e2e8f0;

    border-bottom-left-radius: 4px;
}


.message-sender {
    font-size: 11px;

    font-weight: bold;

    margin-bottom: 5px;

    opacity: 0.75;
}


.message-time {
    display: block;

    margin-top: 7px;

    font-size: 10px;

    opacity: 0.7;
}


/* =========================================================
   REPLY AREA
   ========================================================= */

.reply-area {
    padding: 18px 20px;

    border-top: 1px solid #e2e8f0;

    background: #ffffff;
}


.reply-form {
    display: flex;

    gap: 10px;

    align-items: flex-end;
}


.reply-form textarea {
    flex: 1;

    min-height: 50px;

    max-height: 130px;

    resize: vertical;

    border: 1px solid #cbd5e1;

    border-radius: 10px;

    padding: 12px;

    font-family: inherit;

    font-size: 14px;

    outline: none;
}


.reply-form textarea:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.10);
}


.reply-button {
    border: none;

    background: #2563eb;

    color: white;

    padding: 12px 20px;

    border-radius: 10px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.2s;
}


.reply-button:hover {
    background: #1d4ed8;

    transform: translateY(-2px);
}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.empty-messages {
    background: #ffffff;

    border: 1px dashed #cbd5e1;

    border-radius: 18px;

    padding: 50px 25px;

    text-align: center;
}


.empty-icon {
    font-size: 45px;

    margin-bottom: 10px;
}


.empty-messages h2 {
    margin: 0 0 8px;
}


.empty-messages p {
    color: #64748b;

    margin: 0;
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 650px) {

    .messages-page {
        padding: 0 12px;
    }

    .messages-heading h1 {
        font-size: 27px;
    }

    .conversation-header {
        align-items: flex-start;
    }

    .person-info {
        text-align: left;
    }

    .message-bubble {
        max-width: 88%;
    }

    .reply-form {
        flex-direction: column;

        align-items: stretch;
    }

    .reply-button {
        width: 100%;
    }
}

</style>


<main class="messages-page">


    <!-- =====================================================
         PAGE HEADING
         ===================================================== -->

    <div class="messages-heading">

        <h1>
            💬 Messages
        </h1>

        <p>
            Communicate with buyers and sellers about
            CampusCart products.
        </p>

    </div>


    <!-- =====================================================
         SUCCESS MESSAGE
         ===================================================== -->

    <?php if ($success): ?>

        <div class="message-success">
            <?= h($success) ?>
        </div>

    <?php endif; ?>


    <!-- =====================================================
         ERROR MESSAGE
         ===================================================== -->

    <?php if ($error): ?>

        <div class="message-error">
            <?= h($error) ?>
        </div>

    <?php endif; ?>


    <?php if (empty($conversations)): ?>


        <!-- EMPTY -->

        <div class="empty-messages">

            <div class="empty-icon">
                💬
            </div>

            <h2>
                No conversations yet
            </h2>

            <p>
                When a student contacts you about a product,
                the conversation will appear here.
            </p>

        </div>


    <?php else: ?>


        <!-- =================================================
             CONVERSATIONS
             ================================================= -->

        <?php foreach ($conversations as $conversation): ?>


            <section class="conversation">


                <!-- =========================================
                     CONVERSATION HEADER
                     ========================================= -->

                <div class="conversation-header">


                    <div>

                        <h2>
                            <?= h(
                                $conversation["product_title"]
                            ) ?>
                        </h2>

                        <p>
                            Product conversation
                        </p>

                    </div>


                    <div class="person-info">

                        <div class="person-name">

                            <?= h(
                                $conversation["other_name"]
                            ) ?>

                        </div>


                        <?php if (
                            !empty(
                                $conversation["other_phone"]
                            )
                        ): ?>

                            <a
                                class="phone-link"
                                href="tel:<?= h(
                                    $conversation["other_phone"]
                                ) ?>"
                            >

                                📞
                                <?= h(
                                    $conversation["other_phone"]
                                ) ?>

                            </a>

                        <?php else: ?>

                            <span
                                style="
                                    color:#94a3b8;
                                    font-size:13px;
                                "
                            >
                                Phone not provided
                            </span>

                        <?php endif; ?>

                    </div>


                </div>


                <!-- =========================================
                     CHAT MESSAGES
                     ========================================= -->

                <div class="chat-area">


                    <?php foreach (
                        $conversation["messages"]
                        as $m
                    ): ?>


                        <?php

                        $isMine =
                            ((int)$m["sender_id"]
                            === $currentUserId);

                        ?>


                        <div class="chat-message
                            <?= $isMine
                                ? "mine"
                                : "theirs" ?>">


                            <div class="message-bubble">


                                <div class="message-sender">

                                    <?= $isMine
                                        ? "You"
                                        : h(
                                            $m["sender_name"]
                                        ) ?>

                                </div>


                                <?= nl2br(
                                    h($m["message"])
                                ) ?>


                                <span class="message-time">

                                    <?= h(
                                        $m["created_at"]
                                    ) ?>

                                </span>


                            </div>


                        </div>


                    <?php endforeach; ?>


                </div>


                <!-- =========================================
                     REPLY FORM
                     ========================================= -->

                <div class="reply-area">


                    <form
                        method="post"
                        class="reply-form"
                    >


                        <input
                            type="hidden"
                            name="product_id"
                            value="<?= (int)
                                $conversation["product_id"] ?>"
                        >


                        <input
                            type="hidden"
                            name="receiver_id"
                            value="<?= (int)
                                $conversation["other_user_id"] ?>"
                        >


                        <textarea
                            name="message"
                            placeholder="Write your reply..."
                            required
                            maxlength="2000"
                        ></textarea>


                        <button
                            type="submit"
                            class="reply-button"
                        >
                            Send
                        </button>


                    </form>


                </div>


            </section>


        <?php endforeach; ?>


    <?php endif; ?>


</main>


<?php include "includes/footer.php"; ?>