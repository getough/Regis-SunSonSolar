<?php

namespace App\Controllers;

use App\Models\AuditModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;

    public function login()
    {
        if (session('user')) {
            return redirect()->to('/dashboard');
        }

        return view('login', ['title' => 'Log in']);
    }

    public function attempt()
    {
        if (! $this->validate([
            'username' => 'required|max_length[50]',
            'password' => 'required|max_length[72]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Please enter your username and password.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        $users    = model(UserModel::class);
        $audit    = model(AuditModel::class);
        $user     = $users->findForLogin($username);
        $generic  = 'Invalid username or password.';

        if (! $user) {
            password_hash($password, PASSWORD_DEFAULT); // keep timing similar for unknown usernames

            return redirect()->back()->withInput()->with('error', $generic);
        }

        if (! empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
            return redirect()->back()->withInput()->with('error', 'Too many failed attempts. Please try again in a few minutes.');
        }

        if (! password_verify($password, $user['password_hash'])) {
            $attempts = (int) $user['failed_login_attempts'] + 1;
            $update   = ['failed_login_attempts' => $attempts];
            if ($attempts >= self::MAX_ATTEMPTS) {
                $update['locked_until']          = date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60);
                $update['failed_login_attempts'] = 0;
                $audit->record((int) $user['user_id'], 'account_locked', (int) $user['user_id'], 'Too many failed logins');
            }
            $users->update($user['user_id'], $update);

            return redirect()->back()->withInput()->with('error', $generic);
        }

        // Correct password from here on.
        if ((int) $user['is_active'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'This account is deactivated. Please contact an administrator.');
        }

        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            $users->update($user['user_id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }
        $users->update($user['user_id'], [
            'failed_login_attempts' => 0,
            'locked_until'          => null,
            'last_login'            => date('Y-m-d H:i:s'),
        ]);

        $session = session();
        $session->regenerate(true); // new session id after login
        $session->set('user', [
            'user_id'              => (int) $user['user_id'],
            'name'                 => trim($user['first_name'] . ' ' . $user['last_name']),
            'role'                 => $user['role_name'],
            'department'           => $user['department_name'],
            'must_change_password' => (int) $user['must_change_password'],
        ]);
        $audit->record((int) $user['user_id'], 'login', (int) $user['user_id']);

        if ((int) $user['must_change_password'] === 1) {
            return redirect()->to('/change-password');
        }

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    public function changePassword()
    {
        return view('change_password', ['title' => 'Change password']);
    }

    public function updatePassword()
    {
        $rules = [
            'current_password' => ['label' => 'Current password', 'rules' => 'required'],
            'new_password'     => UserModel::passwordRule('New password'),
            'confirm_password' => ['label' => 'Confirm new password', 'rules' => 'required|matches[new_password]'],
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $users   = model(UserModel::class);
        $userId  = (int) ((session('user') ?? [])['user_id'] ?? 0);
        $user    = $users->find($userId);
        $current = (string) $this->request->getPost('current_password');
        $new     = (string) $this->request->getPost('new_password');

        if (! $user || ! password_verify($current, $user['password_hash'])) {
            return redirect()->back()->with('errors', ['current_password' => 'Your current password is incorrect.']);
        }
        if ($current === $new) {
            return redirect()->back()->with('errors', ['new_password' => 'Your new password must be different from the current one.']);
        }

        $users->update($userId, [
            'password_hash'        => password_hash($new, PASSWORD_DEFAULT),
            'must_change_password' => 0,
        ]);
        model(AuditModel::class)->record($userId, 'password_changed', $userId);
        session()->regenerate(true);

        return redirect()->to('/dashboard')->with('success', 'Your password has been changed.');
    }
}
