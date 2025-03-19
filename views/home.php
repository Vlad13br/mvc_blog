<?php
$title = 'Головна';
ob_start();
?>

<h1>Medium clone</h1>

<?php foreach ($posts as $post): ?>
    <a href="/post/<?= $post['id']; ?>" class="post-link">
        <div class="post">
            <p class="post-title"> <?= htmlspecialchars($post['title']) ?></p>
            <p class="post-author">Автор: <?= htmlspecialchars($post['name']) ?></p>
            <p class="post-content"> <?= htmlspecialchars($post['short_description']) ?></p>
        </div>
    </a>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
