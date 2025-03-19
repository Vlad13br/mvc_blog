<?php
$title = 'Створити пост';
ob_start();
?>
<?php if (isset($errorMessage) && !empty($errorMessage)): ?>
    <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
<?php endif; ?>
<h1>Написати пост</h1>
<form method="POST" action="/create-post" class="post-form">
    <input type="text" name="title" placeholder="Заголовок" required>
    <textarea name="description" placeholder="Опис..." required></textarea>
    <input type="text" name="short_description" placeholder="Короткий опис статті" required>
    <button type="submit" class="btn">Опублікувати</button>
</form>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
