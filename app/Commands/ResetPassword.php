<?php

namespace App\Commands;

use App\Models\AuditModel;
use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Usage:  php spark auth:reset-password <username>
 * Gives the user a new random temporary password, clears any lockout,
 * and forces a password change at the next login.
 */
class ResetPassword extends BaseCommand
{
    protected $group       = 'Sun Son Solar';
    protected $name        = 'auth:reset-password';
    protected $description = 'Gives a user a new temporary password (must be changed at next login).';
    protected $usage       = 'auth:reset-password <username>';
    protected $arguments   = ['username' => 'The username to reset'];

    public function run(array $params)
    {
        helper('account');

        $username = $params[0] ?? CLI::prompt('Username');
        $users    = model(UserModel::class);
        $user     = $users->where('username', $username)->first();

        if (! $user) {
            CLI::error("No user named '{$username}'.");

            return;
        }

        $plain = temp_password();
        $users->update($user['user_id'], [
            'password_hash'         => password_hash($plain, PASSWORD_DEFAULT),
            'must_change_password'  => 1,
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ]);
        model(AuditModel::class)->record(null, 'password_reset_cli', (int) $user['user_id']);

        CLI::write("RESET    {$username}   temporary password: {$plain}", 'green');
        CLI::write('Give it to the person privately. They must change it at first login.');
    }
}
