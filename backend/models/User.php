<?php
// models/User.php
require_once __DIR__.'/../config/Database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // =================== جلب كل المستخدمين ===================
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT id, name, email, role, feedback FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =================== جلب مستخدم حسب المعرف ===================
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, role, feedback FROM users WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =================== جلب مستخدم حسب البريد الإلكتروني ===================
    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, name, email, role FROM users WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =================== إنشاء مستخدم ===================
    public function create($name, $email, $password, $role='viewer') {
        // التحقق من البريد الإلكتروني مسبقًا
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0){
            return ["success"=>false,"message"=>"البريد الإلكتروني مستخدم بالفعل"];
        }

        // تشفير كلمة المرور
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $stmt->execute([$name,$email,$hashed,$role]);

        return ["success"=>true,"message"=>"تم إنشاء المستخدم"];
    }

    // =================== تحديث مستخدم ===================
    public function update($id, $role='viewer', $feedback='') {
        $stmt = $this->conn->prepare("UPDATE users SET role=?, feedback=? WHERE id=?");
        $stmt->execute([$role,$feedback,$id]);
        return ["success"=>true,"message"=>"تم التحديث"];
    }

    // =================== حذف مستخدم ===================
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        return ["success"=>true,"message"=>"تم الحذف"];
    }

    // =================== تسجيل الدخول ===================
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, name, email, password, role FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return ["success"=>false,"message"=>"البريد الإلكتروني غير موجود"];
        }

        if(!password_verify($password, $user['password'])){
            return ["success"=>false,"message"=>"كلمة المرور غير صحيحة"];
        }

        // إزالة كلمة المرور من الرد
        unset($user['password']);

        return ["success"=>true,"message"=>"تم تسجيل الدخول بنجاح","user"=>$user];
    }

    // =================== تسجيل الخروج ===================
    public function logout() {
        if(session_status() === PHP_SESSION_ACTIVE){
            session_destroy();
        }
        return ["success"=>true,"message"=>"تم تسجيل الخروج"];
    }
}
?>
