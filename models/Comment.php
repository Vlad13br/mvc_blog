<?php
require_once '../core/Database.php';
class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addComment($userId, $postId, $content)
    {
        $stmt = $this->db->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $postId, $content]);
    }

    public function getCommentsByPostId($postId)
    {
        $stmt = $this->db->prepare("SELECT comments.*, users.name FROM comments 
                                    JOIN users ON comments.user_id = users.id 
                                    WHERE post_id = ? ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
