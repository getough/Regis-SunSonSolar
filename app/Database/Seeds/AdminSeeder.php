<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Creates the first two admins (Kat and Sol) from the interviews.
 * Run ONCE:   php spark db:seed AdminSeeder
 * Each gets a random temporary password, printed once here, and must change it at first login.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        helper('account');
        $users = model(UserModel::class);

        // TODO confirm with Sol: email domain, full name and username. The interview gave his email as
        // "sol.solis," (incomplete) and his name as both "Sol Solis" and "Sol Sun Solis".
        // Gender was not stated for either person. Office address is a placeholder.
        $admins = [
            [
                'first_name' => 'Katherine', 'middle_name' => 'Olap', 'last_name' => 'Sinagaraw',
                'birthdate' => '1990-07-01', 'gender' => null,
                'email' => 'katherine.sinagaraw@sunsonsolar.com',
                'phone_number' => '09291230983',
                'address' => 'Sun Son Solar Office, Pasig City',
                'username' => 'KittyKat16', 'department' => 'Administration',
            ],
            [
                'first_name' => 'Sol', 'middle_name' => 'Sun', 'last_name' => 'Solis',
                'birthdate' => '1967-01-08', 'gender' => null,
                'email' => 'sol.solis@sunsonsolar.com',
                'phone_number' => '09291230983',
                'address' => 'Sun Son Solar Office, Pasig City',
                'username' => 'admin', 'department' => 'IT',
            ],
        ];

        foreach ($admins as $a) {
            $exists = $users->where('username', $a['username'])->orWhere('email', $a['email'])->first();
            if ($exists) {
                CLI::write("SKIPPED  {$a['username']} (already exists, password untouched)", 'yellow');
                continue;
            }

            $dept = $this->db->table('departments')->where('department_name', $a['department'])->get()->getRowArray();
            unset($a['department']);

            $plain = temp_password();
            $users->createAccount($a + [
                'password_hash'        => password_hash($plain, PASSWORD_DEFAULT),
                'is_active'            => 1,
                'must_change_password' => 1,
            ], 'admin', (int) $dept['department_id']);

            CLI::write("CREATED  {$a['username']}   temporary password: {$plain}", 'green');
        }

        CLI::write("\nGive each person their temporary password privately. They must change it at first login.");
    }
}
