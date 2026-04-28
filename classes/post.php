<?php
class Post {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Retrieves all posts with associated user data
    public function getAll() {
        $query = "SELECT 
                    posts.id,
                    posts.caption,
                    posts.image,
                    posts.created_at,
                    users.id as user_id, 
                    users.username, 
                    users.photo as user_photo 
                  FROM posts 
                  JOIN users ON posts.user_id = users.id 
                  ORDER BY posts.created_at DESC";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Inserts a new post
    public function create($user_id, $caption, $image_path= null) {
        $sql = "INSERT INTO posts (user_id, caption, image, created_at) VALUES (?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
           return false;
        }

        $stmt->bind_param("iss", $user_id, $caption, $image_path);
        return $stmt->execute();
    }

    public function delete($post_id) {
        $stmt = $this->conn->prepare("SELECT image FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    // 2. Jika ada gambar, hapus file fisiknya dari folder
    if ($post && !empty($post['image'])) {
        // Gabungkan path untuk mendapatkan lokasi file yang benar
        // Asumsi struktur file: classes/post.php, maka root adalah ../
        $filePath = __DIR__ . '/../' . $post['image']; 
        
        if (file_exists($filePath)) {
            unlink($filePath); // Perintah PHP untuk hapus file
        }
    }
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $post_id);
        return $stmt->execute();
    }

    public function edit($post_id, $user_id, $caption, $image_path) {
        $sql = "UPDATE posts SET caption = ?, image = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
         if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ssii", $caption, $image_path, $post_id, $user_id);
        return $stmt->execute();
    }

    public function getById($post_id, $user_id) {
    $stmt = $this->conn->prepare("
        SELECT * FROM posts WHERE id = ? AND user_id = ?
    ");

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
}