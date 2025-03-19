<?php
require_once '../core/Database.php';
class Like
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addLike($userId, $postId)
    {
        $stmt = $this->db->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);

        if (!$stmt->fetch()) {
            $stmt = $this->db->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
            return $stmt->execute([$userId, $postId]);
        }
        return false;
    }

    public function getLikeCount($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchColumn();
    }
}
