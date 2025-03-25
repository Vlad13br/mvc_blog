<?php
require '../vendor/autoload.php';
require_once '../controllers/HomeController.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/PostController.php';

class Router
{
    private $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
        $this->mapRoutes();
    }

    private function mapRoutes()
    {
        $this->router->map('GET', '/', 'HomeController#index');
        $this->router->map('GET', '/home', 'HomeController#index');
        $this->router->map('GET', '/register', 'AuthController#showRegisterForm');
        $this->router->map('POST', '/register/store', 'AuthController#register');
        $this->router->map('GET', '/login', 'AuthController#showLoginForm');
        $this->router->map('POST', '/login/store', 'AuthController#login');
        $this->router->map('GET', '/logout', 'AuthController#logout');
        $this->router->map('GET', '/create-post', 'PostController#showCreatePostForm');
        $this->router->map('POST', '/create-post', 'PostController#createPost');
        $this->router->map('GET', '/post/[i:id]', 'PostController#showPost');
        $this->router->map('POST', '/comment', 'PostController#addComment');
        $this->router->map('POST', '/like', 'PostController#addLike');
    }

    public function direct($uri)
    {
        $match = $this->router->match();

        if ($match) {
            list($controllerName, $method) = explode('#', $match['target']);

            $controller = new $controllerName();

            $controller->$method($match['params']);
        } else {
            echo "404 - Сторінка не знайдена";
        }
    }
}
