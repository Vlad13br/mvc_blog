<?php

require_once '../vendor/autoload.php';
require_once '../models/Post.php';
require_once '../models/Comment.php';
require_once '../models/Like.php';
require_once '../models/User.php';
require_once '../core/Database.php';

class PostController
{
    private $postModel;
    private $commentModel;
    private $likeModel;
    private $userModel;
    private $parsedown;

    public function __construct()
    {
        $db = Database::getInstance()->getConnection();
        $this->postModel = new Post($db);
        $this->commentModel = new Comment($db);
        $this->likeModel = new Like($db);
        $this->userModel = new User($db);
        $this->parsedown = new Parsedown();
    }

    public function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function showCreatePostForm($errorMessage = '')
    {
        $csrfToken = $this->generateCsrfToken();
        require '../views/create-post.php';
    }

    public function createPost()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo "Невірний CSRF токен!";
                http_response_code(403);
                exit;
            }

            $title = $_POST['title'];
            $description = $_POST['description'];
            $short_description = $_POST['short_description'];

            if (empty($title) || empty($description) || empty($short_description)) {
                $errorMessage = "Будь ласка, заповніть всі поля!";
                $this->showCreatePostForm($errorMessage);
                return;
            }

            $postCreated = $this->postModel->create($_SESSION['user_id'], $title, $description, $short_description);

            if ($postCreated) {
                header("Location: /home");
                exit;
            } else {
                $errorMessage = "Сталася помилка при створенні поста. Спробуйте ще раз!";
                $this->showCreatePostForm($errorMessage);
            }
        }
    }

    public function showPost($id)
    {
        $post = $this->postModel->getById($id);
        $comments = $this->commentModel->getCommentsByPostId($id);
        $likeCount = $this->likeModel->getLikeCount($id);

        $additionalScripts = '/scripts/comments.js';
        if ($post) {
            $post['description'] = $this->parsedown->text($post['description']);
            require '../views/post-detail.php';
        } else {
            echo "Пост не знайдений!";
        }
    }
    public function addComment()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Користувач не авторизований']);
            http_response_code(401);
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'message' => 'Невірний CSRF токен']);
            http_response_code(403);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $postId = $_POST['post_id'];
            $content = trim($_POST['content']);

            if (!empty($content)) {
                $commentId = $this->commentModel->addComment($userId, $postId, $content);

                $user = $this->userModel->getUserById($userId);

                echo json_encode([
                    'success' => true,
                    'comment' => [
                        'id' => $commentId,
                        'name' => htmlspecialchars($user['name']),
                        'content' => htmlspecialchars($content)
                    ]
                ]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Коментар не може бути порожнім']);
                http_response_code(400);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Неправильний метод запиту']);
        http_response_code(405);
    }

    public function addLike()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['post_id'])) {
                $userId = $_SESSION['user_id'];
                $postId = $data['post_id'];

                $this->likeModel->addLike($userId, $postId);

                echo json_encode(['success' => true, 'likes' => $this->likeModel->getLikeCount($postId)]);
                exit;
            }
        }
    }

}
