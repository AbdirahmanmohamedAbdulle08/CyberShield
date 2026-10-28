<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\CSRF;
use App\Helpers\ViewHelper;

/**
 * Authentication Controller
 */
class AuthController extends Controller {
    public function showLogin(): void {
        if (Auth::check()) {
            $user = Auth::user();
            $this->redirect(Auth::getRedirectForRole($user['role']));
        }

        $this->render('auth/login', [
            'pageTitle' => 'Sign In',
        ], 'auth');
    }

    public function login(): void {
        $this->validateCsrf();

        $identifier = trim($_POST['username'] ?? '');
        $password   = (string)($_POST['password'] ?? '');

        if (empty($identifier) || empty($password)) {
            $this->flash('error', 'Please provide both your username/email and password.');
            $this->redirect('login');
        }

        $result = Auth::attempt($identifier, $password);

        if (!$result['success']) {
            $this->flash('error', $result['error']);
            $this->redirect('login');
        }

        $this->flash('success', 'Welcome back, ' . htmlspecialchars($result['user']['full_name']) . '!');
        $this->redirect($result['redirect']);
    }

    public function logout(): void {
        Auth::logout();
        $this->flash('success', 'You have been safely signed out of CyberShield.');
        $this->redirect('login');
    }
}
