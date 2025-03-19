<?php

require_once '../models/User.php';
require_once '../models/Post.php';

class HomeController {
    public function index() {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        $postModel = new Post();
        $posts = $postModel->getAllPosts();
        require '../views/home.php';
    }
}
