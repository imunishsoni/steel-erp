<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'module', 'action', 'record_id', 'description', 'ip_address', 'user_agent', 'created_at'
    ];
    protected $useTimestamps = false;

    public function getRecent($limit = 30)
    {
        return $this->select('activity_log.*, users.full_name as user_name, users.username')
                    ->join('users', 'users.id = activity_log.user_id', 'left')
                    ->orderBy('activity_log.id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
