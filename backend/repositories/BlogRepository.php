<?php
// repositories/BlogRepository.php
require_once __DIR__ . '/../config/Database.php';

class BlogRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(string $title, string $content, ?string $imagePath): int|false {
        $stmt = $this->db->prepare("INSERT INTO blog_posts (title, content, image) VALUES (?, ?, ?)");
        $result = $stmt->execute([$title, $content, $imagePath]);
        return $result ? (int)$this->db->lastInsertId() : false;
    }

    public function update(int $id, string $title, string $content, ?string $imagePath): bool {
        $stmt = $this->db->prepare("UPDATE blog_posts SET title = ?, content = ?, image = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$title, $content, $imagePath, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM blog_posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
