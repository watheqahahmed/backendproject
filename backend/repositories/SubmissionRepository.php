<?php
// repositories/SubmissionRepository.php
require_once __DIR__ . '/../config/Database.php';

class SubmissionRepository {
    private $conn;
    private $table = 'submissions';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // جلب جميع الرسائل
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM " . $this->table . " ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // جلب رسالة واحدة حسب المعرف
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // إنشاء رسالة جديدة
    public function create($name, $email, $message) {
        $sql = "INSERT INTO " . $this->table . " (name, email, message) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$name, $email, $message]);

        return $result ? $this->conn->lastInsertId() : false;
    }

    // حذف رسالة حسب المعرف
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
