<?php
namespace App\Core;

/**
 * CyberShield CSRF Protection Service
 * Provides cryptographic tokens and validation against Cross-Site Request Forgery
 */
class CSRF {
    private const SESSION_KEY = 'cybershield_csrf_token';

    public static function getToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::SESSION_KEY];
    }

    public static function validate(?string $token = null): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($token === null) {
            $token = $_POST['csrf_token'] 
                ?? $_SERVER['HTTP_X_CSRF_TOKEN'] 
                ?? null;
        }

        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';

        if (empty($token) || empty($sessionToken)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function field(): string {
        $token = htmlspecialchars(self::getToken(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    public static function regenerate(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        return $_SESSION[self::SESSION_KEY];
    }
}
