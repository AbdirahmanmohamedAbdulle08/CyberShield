<?php
/**
 * CyberShield — One-Time Setup and Installation Wizard
 * Initializes database, imports schema, sets up initial Super Admin, and self-locks.
 */

declare(strict_types=1);

$lockFile = __DIR__ . '/install.lock';
$isLocked = file_exists($lockFile);

$message = '';
$messageType = '';

if ($isLocked && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    die("Installation is permanently locked. Remove install.lock manually to re-run setup.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isLocked) {
    $dbHost     = trim($_POST['db_host'] ?? '127.0.0.1');
    $dbPort     = trim($_POST['db_port'] ?? '3306');
    $dbName     = trim($_POST['db_name'] ?? 'cybershield_db');
    $dbUser     = trim($_POST['db_user'] ?? 'root');
    $dbPass     = (string)($_POST['db_pass'] ?? '');

    $adminUser  = trim($_POST['admin_user'] ?? 'superadmin');
    $adminEmail = trim($_POST['admin_email'] ?? 'admin@cybershield.hu.edu.so');
    $adminName  = trim($_POST['admin_name'] ?? 'Dr. Abdirahman Omar (SOC Lead)');
    $adminPass  = (string)($_POST['admin_pass'] ?? '');
    $installDemo= !empty($_POST['install_demo']);

    if (empty($adminUser) || empty($adminEmail) || strlen($adminPass) < 8) {
        $message = "Please provide valid admin credentials (password minimum 8 characters).";
        $messageType = "danger";
    } else {
        try {
            // 1. Connect to MySQL server
            $dsnRoot = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
            $pdo = new PDO($dsnRoot, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // 2. Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `{$dbName}`;");

            // 3. Import schema.sql
            $schemaFile = __DIR__ . '/database/schema.sql';
            if (!file_exists($schemaFile)) {
                throw new RuntimeException("schema.sql not found at {$schemaFile}");
            }
            $schemaSql = file_get_contents($schemaFile);
            $pdo->exec($schemaSql);

            // 4. Optionally import seed_demo.sql
            if ($installDemo) {
                $seedFile = __DIR__ . '/database/seed_demo.sql';
                if (file_exists($seedFile)) {
                    $seedSql = file_get_contents($seedFile);
                    $pdo->exec($seedSql);
                }
            }

            // 5. Create or update Super Admin account
            $passwordHash = password_hash($adminPass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password_hash, full_name, role, status, created_at)
                VALUES (?, ?, ?, ?, 'super_admin', 'active', NOW())
                ON DUPLICATE KEY UPDATE 
                    password_hash = VALUES(password_hash),
                    full_name = VALUES(full_name),
                    role = 'super_admin',
                    status = 'active'
            ");
            $stmt->execute([$adminUser, $adminEmail, $passwordHash, $adminName]);

            // 6. Create install.lock
            $lockContent = json_encode([
                'installed_at' => date('Y-m-d H:i:s'),
                'database'     => $dbName,
                'admin'        => $adminUser,
                'environment'  => 'Windows_XAMPP',
                'signature'    => hash('sha256', uniqid((string)mt_rand(), true)),
            ], JSON_PRETTY_PRINT);
            file_put_contents($lockFile, $lockContent);

            $isLocked = true;
            $message = "Installation completed successfully! CyberShield has been locked against re-execution.";
            $messageType = "success";
        } catch (Throwable $e) {
            $message = "Installation error: " . $e->getMessage();
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberShield Installation & Setup Wizard</title>
    <link rel="stylesheet" href="public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="public/assets/css/cybershield.css">
</head>
<body class="bg-navy text-light py-5">
<div class="container" style="max-width: 680px;">
    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="public/assets/images/logo.svg" alt="CyberShield Logo" style="height: 54px;">
        <div class="text-secondary small mt-2">
            One-Time Setup Wizard · <span class="text-cyan fw-bold">Hormuud University CS & Cyber Security Lab</span>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?> bg-surface-card border-<?= $messageType ?> text-<?= $messageType ?> p-3 mb-4 rounded d-flex align-items-center gap-2">
            <i class="bi bi-<?= ($messageType === 'success') ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> fs-5"></i>
            <div><?= htmlspecialchars($message) ?></div>
        </div>
    <?php endif; ?>

    <?php if ($isLocked): ?>
        <div class="card card-soc border-cyan text-center p-5 shadow-lg">
            <div class="display-3 text-cyan mb-3"><i class="bi bi-lock-fill"></i></div>
            <h1 class="h4 fw-bold text-light mb-2">Installation Guard Active</h1>
            <p class="text-secondary small mb-4">
                CyberShield is already initialized and protected by <code>install.lock</code>. The setup wizard is permanently locked against unauthorized re-execution.
            </p>
            <div class="d-flex justify-content-center gap-2">
                <a href="login" class="btn btn-cyan px-4 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Proceed to Login Portal
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card card-soc shadow-lg">
            <div class="card-header border-bottom border-navy p-3 text-center">
                <h1 class="h5 fw-bold text-light mb-0">CyberShield System Initialization</h1>
                <p class="text-secondary small mb-0">Configure database and initial Super Admin credentials</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="setup.php">
                    <h2 class="h6 fw-bold text-cyan mb-3"><i class="bi bi-database me-1"></i> 1. MySQL Connection (XAMPP Default)</h2>
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label text-secondary small fw-semibold">Database Host</label>
                            <input type="text" class="form-control form-control-soc" name="db_host" value="127.0.0.1" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-secondary small fw-semibold">Port</label>
                            <input type="text" class="form-control form-control-soc" name="db_port" value="3306" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-semibold">Database Name</label>
                            <input type="text" class="form-control form-control-soc" name="db_name" value="cybershield_db" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-semibold">Database Username</label>
                            <input type="text" class="form-control form-control-soc" name="db_user" value="root" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-semibold">Database Password</label>
                        <input type="password" class="form-control form-control-soc" name="db_pass" placeholder="(Leave blank for XAMPP default)">
                    </div>

                    <h2 class="h6 fw-bold text-cyan mb-3"><i class="bi bi-person-badge me-1"></i> 2. Initial Super Admin Account</h2>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-semibold">Username</label>
                            <input type="text" class="form-control form-control-soc" name="admin_user" value="superadmin" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-semibold">Full Name</label>
                            <input type="text" class="form-control form-control-soc" name="admin_name" value="Dr. Abdirahman Omar (SOC Lead)" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Institutional Email</label>
                        <input type="email" class="form-control form-control-soc" name="admin_email" value="admin@cybershield.hu.edu.so" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-semibold">Super Admin Password (Min 8 chars)</label>
                        <input type="password" class="form-control form-control-soc" name="admin_pass" value="CyberShield@2026!" required>
                    </div>

                    <h2 class="h6 fw-bold text-cyan mb-3"><i class="bi bi-mortarboard me-1"></i> 3. Educational Demo Data</h2>
                    <div class="p-3 rounded bg-surface border border-navy mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="install_demo" id="installDemo" value="1" checked>
                            <label class="form-check-label text-light small fw-semibold" for="installDemo">
                                Install Hormuud University CS Cyber Security Demo Dataset (Cohorts, Scenarios, 10 Modules, Quizzes, Sample Telemetry)
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-cyan w-100 py-2 fw-semibold">
                        <i class="bi bi-shield-check me-1"></i> Initialize CyberShield Platform
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
