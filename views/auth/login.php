<?php
$title = "Увійти";

ob_start();
?>

<h1>Увійти</h1>

<p id="error-message" style="color: red; display: none;"></p>

<form id="loginForm" method="post" class="auth-container">
    <input type="email" name="email" id="email" placeholder="Email" required>
    <br>

    <input type="password" name="password" id="password" placeholder="Пароль" required>
    <br>

    <button type="submit">Увійти</button>
</form>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
