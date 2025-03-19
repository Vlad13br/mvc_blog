<?php
$title = "Реєстрація";

ob_start();
?>

<h1>Реєстрація</h1>

<p id="error-message" style="color: red; display: none;"></p>

<form id="registerForm" method="post" class="auth-container">
    <input type="text" name="name" id="name" placeholder="Ім'я" required>
    <br>

    <input type="email" name="email" id="email" placeholder="Email" required>
    <br>

    <input type="password" name="password" id="password" placeholder="Пароль" required>
    <br>

    <button type="submit">Зареєструватися</button>
</form>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
