<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditModel extends Model
{
    protected $table         = 'audit_log';
    protected $primaryKey    = 'log_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['actor_user_id', 'action', 'target_user_id', 'details'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function record(?int $actorId, string $action, ?int $targetId = null, ?string $details = null): void
    {
        $this->insert([
            'actor_user_id'  => $actorId,
            'action'         => $action,
            'target_user_id' => $targetId,
            'details'        => $details,
        ]);
    }
}
