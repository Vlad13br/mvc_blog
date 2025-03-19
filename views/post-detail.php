<?php
$title = 'Деталі поста';
ob_start();
?>

<h1><?= htmlspecialchars($post['title']) ?></h1>
<p><strong>Автор:</strong> <?= htmlspecialchars($post['name']) ?></p>
<p><strong>Опис:</strong></p>
<p><?= nl2br(htmlspecialchars($post['description'])) ?></p>

<form id="likeForm">
    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
    <button type="button" id="likeButton">👍 <span id="likeCount"><?= $likeCount ?></span></button>
</form>

<h3>Коментарі</h3>
<?php if (!empty($comments)): ?>
    <ul>
        <?php foreach ($comments as $comment): ?>
            <li><strong><?= htmlspecialchars($comment['name']) ?>:</strong> <?= htmlspecialchars($comment['content']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Коментарів поки що немає.</p>
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <form action="/comment" method="POST" id="commentForm">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <textarea class="comment_textarea" name="content" required></textarea>
        <button type="submit">Додати коментар</button>
    </form>
<?php else: ?>
    <p><a href="/login">Увійдіть</a>, щоб залишити коментар.</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
