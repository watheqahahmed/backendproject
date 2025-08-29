<?php
// controllers/ProjectController.php
require_once __DIR__ . '/../repositories/ProjectRepository.php';
require_once __DIR__ . '/../utils/Response.php';

class ProjectController {
    private $projectRepo;

    public function __construct() {
        $this->projectRepo = new ProjectRepository();
    }

    /**
     * @param int|null $id
     * يجلب مشروعاً واحداً حسب المعرف (ID) أو جميع المشاريع إذا لم يتم توفير معرف.
     */
    public function getProjects($id = null) {
        if ($id) {
            $project = $this->projectRepo->getById($id);
            if ($project) {
                Response::json(['success' => true, 'project' => $project]);
            } else {
                Response::error('المشروع غير موجود.', 404);
            }
        } else {
            $projects = $this->projectRepo->getAll();
            Response::json(['success' => true, 'projects' => $projects]);
        }
    }

    /**
     * @param array $data
     * ينشئ مشروعاً جديداً من البيانات المقدمة ويتعامل مع رفع الصورة.
     */
    public function createProject($data) {
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;

        if (!$name || !$description) {
            Response::error('يرجى ملء جميع الحقول المطلوبة.', 400);
            return;
        }

        $imageFile = $_FILES['image'] ?? null;

        $newId = $this->projectRepo->create($name, $description, $imageFile);

        if ($newId) {
            $project = $this->projectRepo->getById($newId);
            Response::json([
                'success' => true,
                'message' => 'تم إنشاء المشروع بنجاح.',
                'project' => $project
            ]);
        } else {
            Response::error('فشل في إنشاء المشروع.', 500);
        }
    }

    /**
     * @param int $id
     * @param array $data
     * يقوم بتحديث مشروع موجود.
     */
    public function updateProject($id, $data) {
        $existingProject = $this->projectRepo->getById($id);
        if (!$existingProject) {
            Response::error('المشروع غير موجود.', 404);
            return;
        }

        $name = $data['name'] ?? $existingProject['name'];
        $description = $data['description'] ?? $existingProject['description'];
        $imageFile = $_FILES['image'] ?? null;

        $result = $this->projectRepo->update($id, $name, $description, $imageFile);

        if ($result) {
            $updatedProject = $this->projectRepo->getById($id);
            Response::json([
                'success' => true,
                'message' => 'تم تحديث المشروع بنجاح.',
                'project' => $updatedProject
            ]);
        } else {
            Response::error('فشل في تحديث المشروع.', 500);
        }
    }

    /**
     * @param int $id
     * يحذف مشروعاً حسب المعرف (ID).
     */
    public function deleteProject($id) {
        $existingProject = $this->projectRepo->getById($id);
        if (!$existingProject) {
            Response::error('المشروع غير موجود.', 404);
            return;
        }

        $result = $this->projectRepo->delete($id);

        if ($result) {
            Response::json([
                'success' => true,
                'message' => 'تم حذف المشروع بنجاح.'
            ]);
        } else {
            Response::error('فشل في حذف المشروع.', 500);
        }
    }
}
