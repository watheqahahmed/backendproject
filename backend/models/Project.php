<?php
require_once __DIR__ . '/../config/Database.php';

class Project {
    private $conn;
    private $table = 'projects';
    private $uploadDir = __DIR__ . '/../uploads/';

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

    public function create($name, $description, $imageFile) {
        $imagePath = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($imageFile);
        }

        $sql = "INSERT INTO " . $this->table . " (name, description, image) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $description, $imagePath]);
    }

    public function update($id, $name, $description, $imageFile) {
        // حقل updated_at سيتم تحديثه تلقائياً بواسطة MySQL
        $sql = "UPDATE " . $this->table . " SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $description, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function handleImageUpload($file) {
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return 'uploads/' . $fileName;
        }

        return null;
    }
}