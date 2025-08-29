<?php
require_once __DIR__ . '/../config/Database.php';

class Blog {
    private $conn;
    private $table = 'blog_posts';
    private $uploadDir = __DIR__ . '/../uploads/blog/';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM " . $this->table . " ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $content, $imageFile) {
        $imagePath = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($imageFile);
        }

        $sql = "INSERT INTO " . $this->table . " (title, content, image) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        $result = $stmt->execute([$title, $content, $imagePath]);
        
        return $result ? $this->conn->lastInsertId() : false;
    }

    public function update($id, $title, $content, $imageFile) {
        $imagePath = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($imageFile);
        }
        
        $sql = "UPDATE " . $this->table . " SET title = ?, content = ?, image = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([$title, $content, $imagePath, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function handleImageUpload($file) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $ext;
        $targetFile = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return 'uploads/blog/' . $fileName;
        }

        return null;
    }
}