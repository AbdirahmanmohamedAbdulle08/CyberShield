<?php
/**
 * CyberShield Application Configuration
 */

// Determine base URL dynamically
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = rtrim($scriptDir, '/');
if ($basePath === '') {
    $basePath = '';
}

return [
    'name'           => 'CyberShield',
    'tagline'        => 'Detect. Educate. Defend.',
    'version'        => '1.0.0',
    'institution'    => 'Hormuud University',
    'department'     => 'Faculty of Computer Science & IT — Cyber Security Lab',
    'base_path'      => $basePath,
    'session_name'   => 'CYBERSHIELD_SESSID',
    'csrf_token_key' => 'cybershield_csrf_token',
    'timezone'       => 'Africa/Mogadishu',
    'roles'          => [
        'super_admin'      => 'Super Admin',
        'campaign_manager' => 'Campaign Manager',
        'instructor'       => 'Instructor',
        'student'          => 'Student / Participant',
    ],
];
