<?php
namespace App\Core;

/**
 * CyberShield Audit and Security Logger
 * Records administrative actions and security events with zero credential logging
 */
class Logger {
    public static function log(string $action, string $entityType, ?string $entityId = null, array $details = []): void {
        try {
            // Strip any sensitive keys if accidentally passed
            unset($details['password'], $details['password_hash'], $details['csrf_token'], $details['secret']);

            $user = Auth::user();
            $userId = $user['id'] ?? null;
            $username = $user['username'] ?? 'system_or_guest';

            $ip = self::getClientIp();
            $detailsJson = !empty($details) ? json_encode($details, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;

            Database::query(
                "INSERT INTO audit_logs (user_id, username, action, entity_type, entity_id, details_json, ip_address, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [$userId, $username, $action, $entityType, $entityId, $detailsJson, $ip]
            );

            // Also record to storage/logs/audit.log
            $logFile = __DIR__ . '/../../storage/logs/audit.log';
            $logEntry = sprintf(
                "[%s] IP:%s USER:%s ACTION:%s ENTITY:%s #%s DATA:%s\n",
                date('Y-m-d H:i:s'),
                $ip,
                $username,
                $action,
                $entityType,
                $entityId ?? '-',
                $detailsJson ?? '{}'
            );
            @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            // Fail silently or record to file so user workflow is not halted
            error_log("CyberShield Audit Log Error: " . $e->getMessage());
        }
    }

    public static function getClientIp(): string {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        // Handle proxies if configured, but validate format
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $candidate = trim($parts[0]);
            if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                $ip = $candidate;
            }
        }
        return substr($ip, 0, 45);
    }
}
