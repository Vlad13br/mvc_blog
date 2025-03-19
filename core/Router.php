<?php
class Router
{
    public function direct($uri)
    {
        require '../controllers/AuthController.php';
        require '../controllers/HomeController.php';
        require '../controllers/PostController.php';

        $authController = new AuthController();
        $homeController = new HomeController();
        $postController = new PostController();

        if ($uri == '/' or $uri == '/home') {
            $homeController->index();
        } elseif ($uri == '/register') {
            $authController->showRegisterForm();
        } elseif ($uri == '/register/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } elseif ($uri == '/login') {
            $authController->showLoginForm();
        } elseif ($uri == '/login/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } elseif ($uri == '/logout') {
            $authController->logout();
        } elseif ($uri == '/create-post') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $postController->createPost();
            } else {
                $postController->showCreatePostForm();
            }
        } elseif (preg_match('/^\/post\/(\d+)$/', $uri, $matches)) {
            $postController->showPost($matches[1]);
        } elseif ($uri == '/comment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $postController->addComment();
        } elseif ($uri == '/like' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $postController->addLike();
        } else {
            echo "404 - Сторінка не знайдена";
        }
    }
}