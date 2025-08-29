<?php
// لا تعرض HTML عند وجود خطأ، فقط JSON
ini_set('display_errors', 0); 
error_reporting(E_ALL); 

// **CORS headers يجب أن تكون أول شيء**
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// التعامل مع preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// تضمين المتحكمات
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/ProjectController.php';

$userController = new UserController();
$projectController = new ProjectController();

// قراءة request و id بأمان
$request = $_GET['request'] ?? '';
$id = $_GET['id'] ?? null;

// دالة لإرجاع خطأ JSON
function sendError($message, $code = 400){
    http_response_code($code);
    echo json_encode(['success'=>false, 'message'=>$message]);
    exit();
}

// التعامل مع الأخطاء غير المتوقعة
set_exception_handler(function($e){
    sendError($e->getMessage(), 500);
});

set_error_handler(function($errno, $errstr, $errfile, $errline){
    // يمكنك تسجيل الخطأ بدلاً من عرضه للمستخدم في بيئة الإنتاج
    error_log("خطأ PHP: $errstr في $errfile على السطر $errline", 0);
    sendError("حدث خطأ غير متوقع.", 500);
});

try {
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if($request === 'users' && !$id){
                $userController->getUsers();
            } elseif ($request === 'projects' && !$id){
                $projectController->getProjects();
            } elseif ($request === 'projects' && $id){
                $projectController->getProjects($id);
            } else sendError('Endpoint غير موجود', 404);
            break;

        case 'POST':
            if($request === 'users'){
                $data = json_decode(file_get_contents('php://input'), true);
                if(!$data) sendError('بيانات غير صحيحة');
                $userController->createUser($data);
            } elseif($request === 'projects'){
                // للتعامل مع FormData
                $data = $_POST;
                $projectController->createProject($data);
            } else sendError('Endpoint غير موجود', 404);
            break;

        case 'PUT':
            if($request === 'users' && $id){
                $data = json_decode(file_get_contents('php://input'), true);
                if(!$data) sendError('بيانات غير صحيحة');
                $userController->updateUser($id, $data);
            } elseif($request === 'projects' && $id){
                // للتعامل مع البيانات المرسلة بدون ملفات
                $data = json_decode(file_get_contents('php://input'), true);
                if(!$data) sendError('بيانات غير صحيحة');
                $projectController->updateProject($id, $data);
            } else sendError('Endpoint أو ID غير موجود', 404);
            break;

        case 'DELETE':
            if($request === 'users' && $id){
                $userController->deleteUser($id);
            } elseif($request === 'projects' && $id){
                $projectController->deleteProject($id);
            } else sendError('Endpoint أو ID غير موجود', 404);
            break;

        default:
            sendError('Method not allowed', 405);
    }
} catch(Exception $e){
    sendError($e->getMessage(), 500);
}