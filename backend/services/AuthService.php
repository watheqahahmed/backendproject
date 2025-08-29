<?php
class AuthService {

    // ✅ بدء السيشن بشكل آمن
    private static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ✅ تسجيل الدخول
    public static function login($userId, $role = 'user') {
        self::startSession();
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
        return true;
    }

    // ✅ تسجيل الخروج
    public static function logout() {
        self::startSession();
        session_unset();
        session_destroy();
        return true;
    }

    // ✅ التحقق من تسجيل الدخول
    public static function checkLogin() {
        self::startSession();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Unauthorized. Please login first."
            ]);
            exit;
        }
    }

    // ✅ التحقق من أن المستخدم أدمن
    public static function checkAdmin() {
        self::startSession();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                "success" => false,
                "message" => "Forbidden. Admins only."
            ]);
            exit;
        }
    }

    // ✅ جلب بيانات المستخدم الحالي
    public static function currentUser() {
        self::startSession();
        return isset($_SESSION['user_id']) ? [
            "id" => $_SESSION['user_id'],
            "role" => $_SESSION['role'] ?? 'user'
        ] : null;
    }
}
