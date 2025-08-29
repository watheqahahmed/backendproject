<?php
// ------------------------
// تضمين المتحكمات (Controllers)
// ------------------------
require_once __DIR__ . '/../controllers/ProjectController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/BlogController.php';
require_once __DIR__ . '/../controllers/SubmissionController.php';

// ------------------------
// تفعيل عرض الأخطاء للتطوير
// ------------------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ------------------------
// دالة مساعدة لإرسال الأخطاء بصيغة JSON
// ------------------------
function sendError(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

// ------------------------
// استقبال الطلبات
// ------------------------
$request = $_GET['request'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$method = $_SERVER['REQUEST_METHOD'];

// ------------------------
// منطق المشاريع
// ------------------------
if ($request === 'projects') {
    $controller = new ProjectController();
    try {
        switch ($method) {
            case 'GET':
                $id ? $controller->getProjects($id) : $controller->getProjects();
                break;
            case 'POST':
                $data = $_POST;
                empty($data['name']) || empty($data['description']) ? sendError('يرجى ملء جميع الحقول المطلوبة.', 400) : null;
                $controller->createProject($data);
                break;
            case 'PUT':
                parse_str(file_get_contents("php://input"), $put_vars);
                $data = $put_vars;
                !$id ? sendError('معرف المشروع مفقود.', 400) : null;
                empty($data['name']) || empty($data['description']) ? sendError('يرجى ملء جميع الحقول المطلوبة.', 400) : null;
                $controller->updateProject($id, $data);
                break;
            case 'DELETE':
                !$id ? sendError('معرف المشروع مفقود.', 400) : null;
                $controller->deleteProject($id);
                break;
            default:
                sendError('الأسلوب غير مسموح به.', 405);
        }
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

// ------------------------
// منطق المستخدمين
// ------------------------
elseif ($request === 'users') {
    $controller = new UserController();
    try {
        switch ($method) {
            case 'GET':
                (isset($_GET['count']) && $_GET['count'] === 'true') ? $controller->getUsersCount() : $controller->getUsers();
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                !$data ? sendError('بيانات غير صحيحة', 400) : null;
                $controller->createUser($data);
                break;
            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                !$data ? sendError('بيانات غير صحيحة', 400) : null;
                !$id ? sendError('ID غير موجود', 400) : null;
                $controller->updateUser($id, $data);
                break;
            case 'DELETE':
                !$id ? sendError('ID غير موجود', 400) : null;
                $controller->deleteUser($id);
                break;
            default:
                sendError('Method not allowed', 405);
        }
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

// ------------------------
// منطق المدونة (Blog)
// ------------------------
elseif ($request === 'blog') {
    $controller = new BlogController();
    try {
        switch ($method) {
            case 'GET':
                $id ? $controller->getBlogPosts($id) : $controller->getBlogPosts();
                break;
            case 'POST':
                $data = $_POST;
                !$id ? $controller->createBlogPost($data) : $controller->updateBlogPost($id, $data);
                break;
            case 'PUT':
                parse_str(file_get_contents("php://input"), $put_vars);
                $data = $put_vars;
                !$id ? sendError('معرف المقال مفقود.', 400) : null;
                $controller->updateBlogPost($id, $data);
                break;
            case 'DELETE':
                !$id ? sendError('معرف المقال مفقود.', 400) : null;
                $controller->deleteBlogPost($id);
                break;
            default:
                sendError('الأسلوب غير مسموح به.', 405);
        }
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

// ------------------------
// منطق الرسائل (Submissions)
// ------------------------
elseif ($request === 'submissions') {
    $controller = new SubmissionController();
    try {
        switch ($method) {
            case 'GET':
                $controller->getSubmissions();
                break;
            case 'POST':
                $data = $_POST;
                $controller->createSubmission($data);
                break;
            case 'DELETE':
                !$id ? sendError('معرف الرسالة مفقود.', 400) : null;
                $controller->deleteSubmission($id);
                break;
            default:
                sendError('الأسلوب غير مسموح به للرسائل.', 405);
        }
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

// ------------------------
// أي Endpoint غير معروف
// ------------------------
else {
    sendError('Endpoint غير موجود', 404);
}
?>
