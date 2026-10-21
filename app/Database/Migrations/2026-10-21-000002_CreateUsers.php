<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up(): void
    {
        // One table for customers AND employees: user_id is the primary key and
        // usernames/emails are unique across everybody.
        $this->forge->addField([
            'user_id'               => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'role_id'               => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
            'first_name'            => ['type' => 'VARCHAR', 'constraint' => 50],
            'middle_name'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'last_name'             => ['type' => 'VARCHAR', 'constraint' => 50],
            'birthdate'             => ['type' => 'DATE'],
            'gender'                => ['type' => 'ENUM', 'constraint' => ['Male', 'Female', 'Other', 'Prefer not to say'], 'null' => true],
            'email'                 => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone_number'          => ['type' => 'VARCHAR', 'constraint' => 15],
            'address'               => ['type' => 'TEXT'],
            'username'              => ['type' => 'VARCHAR', 'constraint' => 50],
            'password_hash'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_active'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'must_change_password'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'failed_login_attempts' => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'default' => 0],
            'locked_until'          => ['type' => 'DATETIME', 'null' => true],
            'last_login'            => ['type' => 'DATETIME', 'null' => true],
            'privacy_consent_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('username');
        $this->forge->addForeignKey('role_id', 'roles', 'role_id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('users', true, ['ENGINE' => 'InnoDB']);

        // Employees only: their department.
        $this->forge->addField([
            'user_id'       => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'department_id' => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('department_id', 'departments', 'department_id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('employee_profiles', true, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('employee_profiles', true);
        $this->forge->dropTable('users', true);
    }
}
