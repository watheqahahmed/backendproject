<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/AuthService.php';

class UserController {
    private $model;
    private $auth;

    public function __construct() {
        $this->model = new User();
        $this->auth = new AuthService();
    }

    // =================== Users CRUD ===================
    public function getUsers() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAll());
    }

    public function createUser($data) {
        header('Content-Type: application/json');

        if(empty($data['name']) || empty($data['email']) || empty($data['password'])){
            echo json_encode(['success'=>false,'message'=>'الحقول مطلوبة']);
            return;
        }

        $result = $this->model->create(
            $data['name'], 
            $data['email'], 
            $data['password'], 
            $data['role'] ?? 'viewer'
        );
        echo json_encode($result);
    }

    public function updateUser($id, $data) {
        header('Content-Type: application/json');

        if(!$id){
            echo json_encode(['success'=>false,'message'=>'المعرف مطلوب']);
            return;
        }

        $result = $this->model->update(
            $id, 
            $data['role'] ?? 'viewer', 
            $data['feedback'] ?? ''
        );
        echo json_encode($result);
    }

    public function deleteUser($id) {
        header('Content-Type: application/json');

        if(!$id){
            echo json_encode(['success'=>false,'message'=>'المعرف مطلوب']);
            return;
        }

        $result = $this->model->delete($id);
        echo json_encode($result);
    }

    // =================== Register ===================
    public function register($data) {
        header('Content-Type: application/json');

        if(empty($data['name']) || empty($data['email']) || empty($data['password'])){
            echo json_encode(['success'=>false,'message'=>'الحقول مطلوبة']);
            return;
        }

        $result = $this->model->create(
            $data['name'], 
            $data['email'], 
            $data['password'], 
            'viewer'
        );

        if($result['success']){
            $user = $this->model->getByEmail($data['email']);
            $this->auth->login($user);
        }

        echo json_encode($result);
    }

    // =================== Login ===================
    public function login($data) {
        header('Content-Type: application/json');

        $result = $this->model->login($data['email'], $data['password']);
        if($result['success']){
            $this->auth->login($result['user']);
        }
        echo json_encode($result);
    }

    // =================== Logout ===================
    public function logout() {
        header('Content-Type: application/json');

        $this->auth->logout();
        echo json_encode(['success'=>true,'message'=>'تم تسجيل الخروج']);
    }
}
?>
