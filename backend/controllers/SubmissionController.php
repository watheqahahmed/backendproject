<?php
// controllers/SubmissionController.php
require_once __DIR__ . '/../repositories/SubmissionRepository.php';
require_once __DIR__ . '/../utils/Response.php';

class SubmissionController {
    private $submissionRepo;

    public function __construct() {
        $this->submissionRepo = new SubmissionRepository();
    }

    public function getSubmissions() {
        $submissions = $this->submissionRepo->getAll();
        Response::json(['success' => true, 'submissions' => $submissions]);
    }

    public function createSubmission($data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            Response::error('يرجى ملء جميع الحقول المطلوبة.', 400);
            return;
        }

        $newId = $this->submissionRepo->create($data['name'], $data['email'], $data['message']);

        if ($newId) {
            $submission = $this->submissionRepo->getById($newId);
            Response::json([
                'success' => true,
                'message' => 'تم استلام رسالتك بنجاح.',
                'submission' => $submission
            ]);
        } else {
            Response::error('فشل في إرسال الرسالة.', 500);
        }
    }

    public function deleteSubmission($id) {
        $submission = $this->submissionRepo->getById($id);
        if (!$submission) {
            Response::error('الرسالة غير موجودة.', 404);
            return;
        }

        $result = $this->submissionRepo->delete($id);

        if ($result) {
            Response::json(['success' => true, 'message' => 'تم حذف الرسالة بنجاح.']);
        } else {
            Response::error('فشل في حذف الرسالة.', 500);
        }
    }
}
