<?php
require_once __DIR__ . '/../repositories/BlogRepository.php';
require_once __DIR__ . '/../utils/Response.php';

class BlogController
{
    private BlogRepository $blogRepo;
    private string $uploadDir;

    public function __construct()
    {
        $this->blogRepo = new BlogRepository();
        $this->uploadDir = __DIR__ . '/../uploads/blog/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function getBlogPosts(?int $id = null): void
    {
        if ($id) {
            $post = $this->blogRepo->getById($id);
            if ($post) Response::json(['success' => true, 'blogPost' => $post]);
            else Response::error('المقال غير موجود.', 404);
        } else {
            $posts = $this->blogRepo->getAll();
            Response::json(['success' => true, 'blogPosts' => $posts]);
        }
    }

    public function createBlogPost(array $data): void
    {
        $title = $data['title'] ?? null;
        $content = $data['content'] ?? null;

        if (!$title || !$content) Response::error('يرجى ملء جميع الحقول المطلوبة.', 400);

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
        }

        $newId = $this->blogRepo->create($title, $content, $imagePath);
        if ($newId) {
            $newPost = $this->blogRepo->getById($newId);
            Response::json(['success' => true, 'message' => 'تم إنشاء المقال بنجاح.', 'blogPost' => $newPost]);
        } else {
            Response::error('فشل في إنشاء المقال.', 500);
        }
    }

    public function updateBlogPost(int $id, array $data): void
    {
        $post = $this->blogRepo->getById($id);
        if (!$post) Response::error('المقال غير موجود.', 404);

        $title = $data['title'] ?? $post['title'];
        $content = $data['content'] ?? $post['content'];
        $imagePath = $post['image'] ?? null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)) unlink(__DIR__ . '/../' . $imagePath);
            $imagePath = $this->handleImageUpload($_FILES['image']);
        }

        $result = $this->blogRepo->update($id, $title, $content, $imagePath);
        if ($result) {
            $updatedPost = $this->blogRepo->getById($id);
            Response::json(['success' => true, 'message' => 'تم تحديث المقال بنجاح.', 'blogPost' => $updatedPost]);
        } else {
            Response::error('فشل في تحديث المقال.', 500);
        }
    }

    public function deleteBlogPost(int $id): void
    {
        $post = $this->blogRepo->getById($id);
        if (!$post) Response::error('المقال غير موجود.', 404);

        if ($post['image'] && file_exists(__DIR__ . '/../' . $post['image'])) {
            unlink(__DIR__ . '/../' . $post['image']);
        }

        if ($this->blogRepo->delete($id)) {
            Response::json(['success' => true, 'message' => 'تم حذف المقال بنجاح.']);
        } else {
            Response::error('فشل في حذف المقال.', 500);
        }
    }

    private function handleImageUpload(array $file): ?string
    {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $ext;
        $targetFile = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return 'uploads/blog/' . $fileName;
        }
        return null;
    }
}
