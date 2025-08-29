<?php
require_once __DIR__ . '/../config/Database.php';

class UserRepository {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // جلب جميع المستخدمين
    public function getAll() {
        $stmt = $this->conn->query("SELECT id, name, email, created_at, updated_at FROM " . $this->table . " ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // جلب مستخدم حسب المعرف
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, created_at, updated_at FROM " . $this->table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // إنشاء مستخدم جديد
    public function create($name, $email, $hashedPassword) {
        $sql = "INSERT INTO " . $this->table . " (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$name, $email, $hashedPassword]);
        return $result ? $this->conn->lastInsertId() : false;
    }

    // تحديث مستخدم موجود
    public function update($id, $name, $email, $hashedPassword) {
        $sql = "UPDATE " . $this->table . " SET name = ?, email = ?, password = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $email, $hashedPassword, $id]);
    }

    // حذف مستخدم
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // تحقق من البريد الإلكتروني موجود مسبقاً (اختياري)
    public function existsByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}
?>
