<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Сторінка' ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<div class="header">
    <a href="/">Головна</a>
    <div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/create-post">Створити пост</a>
            <a href="/logout">Вийти</a>
        <?php else: ?>
            <a href="/login">Увійти</a>
            <a href="/register">Реєстрація</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <?= $content ?? '' ?>
</div>

<script src="/scripts/app.js"></script>
<?php if (isset($additionalScripts)): ?>
    <script src="<?= $additionalScripts; ?>"></script>
<?php endif; ?>
</body>
</html>
