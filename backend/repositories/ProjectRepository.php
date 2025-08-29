<?php
// repositories/ProjectRepository.php
require_once __DIR__ . '/../config/Database.php';

class ProjectRepository {
    private $db;
    private $uploadDir;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->uploadDir = __DIR__ . '/../uploads/projects/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM projects ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $description, $imageFile = null) {
        $imagePath = null;
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($imageFile);
        }

        $stmt = $this->db->prepare("INSERT INTO projects (name, description, image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $imagePath]);
        return $this->db->lastInsertId();
    }

    public function update($id, $name, $description, $imageFile = null) {
        $project = $this->getById($id);
        if (!$project) return false;

        $imagePath = $project['image'];
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)) {
                unlink(__DIR__ . '/../' . $imagePath);
            }
            $imagePath = $this->handleImageUpload($imageFile);
        }

        $stmt = $this->db->prepare("UPDATE projects SET name = ?, description = ?, image = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$name, $description, $imagePath, $id]);
    }

    public function delete($id) {
        $project = $this->getById($id);
        if (!$project) return false;

        // حذف الصورة إذا موجودة
        if ($project['image'] && file_exists(__DIR__ . '/../' . $project['image'])) {
            unlink(__DIR__ . '/../' . $project['image']);
        }

        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function handleImageUpload($file) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $ext;
        $targetFile = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return 'uploads/projects/' . $fileName;
        }

        return null;
    }
}
