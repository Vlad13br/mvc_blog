<?php
require_once '../models/Post.php';
require_once '../models/Comment.php';
require_once '../models/Like.php';
require_once '../core/Database.php';
class PostController
{
    private $postModel;
    private $commentModel;
    private $likeModel;

    public function __construct()
    {
        $db = Database::getInstance()->getConnection();
        $this->postModel = new Post($db);
        $this->commentModel = new Comment($db);
        $this->likeModel = new Like($db);
    }

    public function showCreatePostForm($errorMessage = '')
    {
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
            $title = $_POST['title'] ;
            $description = $_POST['description'] ;
            $short_description = $_POST['short_description'] ;

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

        if ($post) {
            require '../views/post-detail.php';
        } else {
            echo "Пост не знайдений!";
        }
    }
    public function addComment()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $postId = $_POST['post_id'];
            $content = trim($_POST['content']);

            if (!empty($content)) {
                $this->commentModel->addComment($userId, $postId, $content);
            }
        }
        header("Location: /post/$postId");
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
