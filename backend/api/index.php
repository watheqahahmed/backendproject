<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../controllers/UserController.php';

$controller = new UserController();

// جلب نوع الطلب من URL
$request = $_GET['request'] ?? '';

$input = json_decode(file_get_contents('php://input'), true);

switch($request){
    // =================== Users CRUD ===================
    case 'users':
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $controller->getUsers();
        } elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            // فقط admin يمكنه إنشاء مستخدم جديد
            if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
                http_response_code(403);
                echo json_encode(['success'=>false,'message'=>'غير مسموح']);
                exit;
            }
            $controller->createUser($input);
        } elseif($_SERVER['REQUEST_METHOD'] === 'PUT'){
            $id = $_GET['id'] ?? null;
            if(!$id){
                echo json_encode(['success'=>false,'message'=>'المعرف مطلوب']);
                exit;
            }
            // السماح لكل المستخدمين بتحديث المستخدمين
            $controller->updateUser($id, $input);
        } elseif($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            $id = $_GET['id'] ?? null;
            if(!$id){
                echo json_encode(['success'=>false,'message'=>'المعرف مطلوب']);
                exit;
            }
            // السماح لكل المستخدمين بحذف المستخدمين
            $controller->deleteUser($id);
        } else{
            http_response_code(405);
            echo json_encode(['success'=>false,'message'=>'Method Not Allowed']);
        }
        break;

    // =================== Register ===================
    case 'register':
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $controller->register($input);
        } else{
            http_response_code(405);
            echo json_encode(['success'=>false,'message'=>'Method Not Allowed']);
        }
        break;

    // =================== Login ===================
    case 'login':
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $controller->login($input);
        } else{
            http_response_code(405);
            echo json_encode(['success'=>false,'message'=>'Method Not Allowed']);
        }
        break;

    // =================== Logout ===================
    case 'logout':
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_destroy();
            echo json_encode(['success'=>true,'message'=>'تم تسجيل الخروج']);
        } else{
            http_response_code(405);
            echo json_encode(['success'=>false,'message'=>'Method Not Allowed']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['success'=>false,'message'=>'API endpoint غير موجود']);
        break;
}
?>
