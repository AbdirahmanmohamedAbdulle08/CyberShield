<?php
namespace App\Helpers;

/**
 * CyberShield View and UI Helper Functions
 */
class ViewHelper {
    private static ?string $basePath = null;

    public static function getBasePath(): string {
        if (self::$basePath === null) {
            $config = require __DIR__ . '/../../config/app.php';
            self::$basePath = $config['base_path'];
        }
        return self::$basePath;
    }

    /**
     * Escape output to prevent Cross-Site Scripting (XSS)
     */
    public static function e($value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate application URL
     */
    public static function url(string $path = ''): string {
        $base = self::getBasePath();
        $path = ltrim($path, '/');
        if ($base === '') {
            return '/' . $path;
        }
        return $base . '/' . $path;
    }

    /**
     * Generate static asset URL
     */
    public static function asset(string $path): string {
        return self::url('public/assets/' . ltrim($path, '/'));
    }

    /**
     * Format timestamp nicely
     */
    public static function formatDate(?string $datetime, string $format = 'M d, Y · H:i'): string {
        if (!$datetime) return '—';
        $timestamp = strtotime($datetime);
        return $timestamp ? date($format, $timestamp) : '—';
    }

    /**
     * Return relative time (e.g. 5 minutes ago)
     */
    public static function timeAgo(?string $datetime): string {
        if (!$datetime) return 'Never';
        $timestamp = strtotime($datetime);
        if (!$timestamp) return '—';
        $diff = time() - $timestamp;

        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        return date('M d, Y', $timestamp);
    }

    /**
     * Role badge with SOC aesthetic colors
     */
    public static function badgeRole(string $role): string {
        $badges = [
            'super_admin'      => ['bg' => 'bg-danger-subtle text-danger border border-danger-subtle', 'label' => 'Super Admin', 'icon' => 'bi-shield-shaded'],
            'campaign_manager' => ['bg' => 'bg-info-subtle text-info border border-info-subtle', 'label' => 'Campaign Manager', 'icon' => 'bi-send'],
            'instructor'       => ['bg' => 'bg-primary-subtle text-primary border border-primary-subtle', 'label' => 'Instructor', 'icon' => 'bi-mortarboard'],
            'student'          => ['bg' => 'bg-success-subtle text-success border border-success-subtle', 'label' => 'Student', 'icon' => 'bi-person'],
        ];

        $b = $badges[$role] ?? ['bg' => 'bg-secondary text-light', 'label' => ucfirst($role), 'icon' => 'bi-person'];
        return sprintf(
            '<span class="badge %s px-2 py-1"><i class="bi %s me-1"></i>%s</span>',
            $b['bg'],
            $b['icon'],
            self::e($b['label'])
        );
    }

    /**
     * Status badge
     */
    public static function badgeStatus(string $status): string {
        $map = [
            'active'                => ['class' => 'badge-soft-success', 'label' => 'Active', 'icon' => 'bi-check-circle'],
            'running'               => ['class' => 'badge-soft-cyan', 'label' => 'Running', 'icon' => 'bi-play-circle-fill'],
            'completed'             => ['class' => 'badge-soft-success', 'label' => 'Completed', 'icon' => 'bi-check2-all'],
            'draft'                 => ['class' => 'badge-soft-secondary', 'label' => 'Draft', 'icon' => 'bi-pencil-square'],
            'pending_authorization' => ['class' => 'badge-soft-warning', 'label' => 'Pending Auth', 'icon' => 'bi-hourglass-split'],
            'authorized'            => ['class' => 'badge-soft-primary', 'label' => 'Authorized', 'icon' => 'bi-shield-check'],
            'cancelled'             => ['class' => 'badge-soft-danger', 'label' => 'Cancelled', 'icon' => 'bi-x-circle'],
            'inactive'              => ['class' => 'badge-soft-secondary', 'label' => 'Inactive', 'icon' => 'bi-dash-circle'],
            'submitted'             => ['class' => 'badge-soft-warning', 'label' => 'Submitted', 'icon' => 'bi-envelope-exclamation'],
            'under_review'          => ['class' => 'badge-soft-info', 'label' => 'Under Review', 'icon' => 'bi-eye'],
            'reviewed'              => ['class' => 'badge-soft-primary', 'label' => 'Reviewed', 'icon' => 'bi-clipboard-check'],
            'resolved'              => ['class' => 'badge-soft-success', 'label' => 'Resolved', 'icon' => 'bi-shield-fill-check'],
        ];

        $info = $map[$status] ?? ['class' => 'badge-soft-secondary', 'label' => ucfirst(str_replace('_', ' ', $status)), 'icon' => 'bi-dot'];
        return sprintf(
            '<span class="badge %s d-inline-flex align-items-center gap-1"><i class="bi %s"></i>%s</span>',
            $info['class'],
            $info['icon'],
            self::e($info['label'])
        );
    }

    public static function truncate(string $text, int $length = 100): string {
        if (mb_strlen($text) <= $length) return $text;
        return mb_substr($text, 0, $length) . '...';
    }
}
