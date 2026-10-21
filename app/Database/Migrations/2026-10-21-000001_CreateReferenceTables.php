<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReferenceTables extends Migration
{
    public function up(): void
    {
        // roles
        $this->forge->addField([
            'role_id'   => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'auto_increment' => true],
            'role_name' => ['type' => 'VARCHAR', 'constraint' => 30],
        ]);
        $this->forge->addKey('role_id', true);
        $this->forge->addUniqueKey('role_name');
        $this->forge->createTable('roles', true, ['ENGINE' => 'InnoDB']);

        // departments
        $this->forge->addField([
            'department_id'   => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'auto_increment' => true],
            'department_name' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('department_id', true);
        $this->forge->addUniqueKey('department_name');
        $this->forge->createTable('departments', true, ['ENGINE' => 'InnoDB']);

        // Reference data
        $this->db->table('roles')->insertBatch(array_map(
            static fn (string $r) => ['role_name' => $r],
            ['customer', 'admin', 'hr', 'dispatcher', 'technician', 'staff']
        ));

        // Department list exactly as given by Kat and Sol in the interview.
        $this->db->table('departments')->insertBatch(array_map(
            static fn (string $d) => ['department_name' => $d],
            ['Administration', 'IT', 'Dispatch', 'Accounting', 'HR', 'Marketing', 'Sales', 'Customer Service']
        ));
    }

    public function down(): void
    {
        $this->forge->dropTable('departments', true);
        $this->forge->dropTable('roles', true);
    }
}
