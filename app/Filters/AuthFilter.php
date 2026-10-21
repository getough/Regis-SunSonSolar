<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Usage in Routes.php:
 *   ['filter' => 'auth']            any logged-in, active user
 *   ['filter' => 'auth:admin,hr']   only these roles
 *
 * The user's role and active flag are re-read from the database on EVERY request,
 * so deactivating an account or changing a role takes effect immediately.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $sessionUser = $session->get('user');

        if (! $sessionUser || empty($sessionUser['user_id'])) {
            return redirect()->to('/login')->with('error', 'Please log in to continue.');
        }

        $user = model(UserModel::class)->findWithRole((int) $sessionUser['user_id']);
        if (! $user || (int) $user['is_active'] !== 1) {
            $session->destroy();

            return redirect()->to('/login')->with('error', 'Your session has ended. Please log in again.');
        }

        // Keep the session copy fresh.
        $session->set('user', [
            'user_id'              => (int) $user['user_id'],
            'name'                 => trim($user['first_name'] . ' ' . $user['last_name']),
            'role'                 => $user['role_name'],
            'department'           => $user['department_name'],
            'must_change_password' => (int) $user['must_change_password'],
        ]);

        // Temporary password: nothing else is reachable until it is changed.
        if ((int) $user['must_change_password'] === 1 && $request->getPath() !== 'change-password') {
            return redirect()->to('/change-password')->with('error', 'Please change your temporary password to continue.');
        }

        if (! empty($arguments) && ! in_array($user['role_name'], $arguments, true)) {
            return redirect()->to('/dashboard')->with('error', 'You do not have access to that page.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
