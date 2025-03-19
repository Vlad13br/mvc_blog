<?php

require_once '../core/Database.php';

class Post
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $title, $description, $short_description)
    {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, title, description, short_description) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $title, $description, $short_description]);
    }

    public function getAllPosts()
    {
        $stmt = $this->db->query("SELECT posts.*, users.name FROM posts 
                                  JOIN users ON posts.user_id = users.id 
                                  ORDER BY posts.id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT posts.*, users.name  FROM posts 
                                JOIN users ON posts.user_id = users.id
                                WHERE posts.id = :id");

        $stmt->execute(['id' => $id]);

        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            return null;
        }

        return $post;
    }


}
