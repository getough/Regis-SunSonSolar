<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditAndCheckins extends Migration
{
    public function up(): void
    {
        // Who did what (account creation, deactivation, password changes, lockouts...)
        $this->forge->addField([
            'log_id'         => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'actor_user_id'  => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'action'         => ['type' => 'VARCHAR', 'constraint' => 60],
            'target_user_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'details'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('log_id', true);
        $this->forge->addForeignKey('actor_user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('target_user_id', 'users', 'user_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('audit_log', true, ['ENGINE' => 'InnoDB']);

        // Technician attendance. Coordinates are recorded only when the button is pressed.
        $this->forge->addField([
            'checkin_id'     => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'checked_in_at'  => ['type' => 'DATETIME'],
            'in_latitude'    => ['type' => 'DECIMAL', 'constraint' => '9,6'],
            'in_longitude'   => ['type' => 'DECIMAL', 'constraint' => '9,6'],
            'checked_out_at' => ['type' => 'DATETIME', 'null' => true],
            'out_latitude'   => ['type' => 'DECIMAL', 'constraint' => '9,6', 'null' => true],
            'out_longitude'  => ['type' => 'DECIMAL', 'constraint' => '9,6', 'null' => true],
        ]);
        $this->forge->addKey('checkin_id', true);
        $this->forge->addKey(['user_id', 'checked_in_at']);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('technician_checkins', true, ['ENGINE' => 'InnoDB']);
        // booking_id gets added once the bookings table exists.
    }

    public function down(): void
    {
        $this->forge->dropTable('technician_checkins', true);
        $this->forge->dropTable('audit_log', true);
    }
}
