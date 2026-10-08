<?php

require_once "includes/db.php";
require_once "includes/auth.php";

requireLogin();

$stmt = $pdo->prepare("
    SELECT 
        m.*,
        p.title,
        s.name AS sender_name,
        s.phone AS sender_phone
    FROM messages m
    JOIN products p ON p.id = m.product_id
    JOIN users s ON s.id = m.sender_id
    WHERE m.receiver_id = ?
    ORDER BY m.created_at DESC
");

$stmt->execute([$_SESSION["user_id"]]);

$messages = $stmt->fetchAll();

$pageTitle = "Messages";

include "includes/header.php";

?>

<h1>Messages From Students</h1>

<?php if (!$messages): ?>

    <p class="notice">
        No messages yet. When another student contacts you,
        messages will appear here.
    </p>

<?php endif; ?>


<div class="grid">

<?php foreach ($messages as $m): ?>

    <div class="card">

        <h3>
            <?= h($m["title"]) ?>
        </h3>

        <p>
            <strong>From:</strong>
            <?= h($m["sender_name"]) ?>
        </p>

        <p>
            <strong>Phone:</strong>
            
            <?php if (!empty($m["sender_phone"])): ?>

                <a href="tel:<?= h($m["sender_phone"]) ?>">
                    <?= h($m["sender_phone"]) ?>
                </a>

            <?php else: ?>

                Phone number not provided

            <?php endif; ?>

        </p>

        <p>
            <strong>Message:</strong><br>
            <?= nl2br(h($m["message"])) ?>
        </p>

        <p class="muted">
            <?= h($m["created_at"]) ?>
        </p>

    </div>

<?php endforeach; ?>

</div>

<?php include "includes/footer.php"; ?>