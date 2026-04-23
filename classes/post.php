<?php
class Post {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Retrieves all posts with associated user data
    public function getAll() {
        $query = "SELECT posts.*, users.username, users.photo as user_photo 
                  FROM posts 
                  JOIN users ON posts.user_id = users.id 
                  ORDER BY posts.created_at DESC";
        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    // Inserts a new post
    public function create($user_id, $caption, $image_path) {
        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, caption, image, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $caption, $image_path);
        return $stmt->execute();
    }
}