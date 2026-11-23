<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketCommentModel extends Model
{
    protected $table            = 'ticket_comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_id',
        'user_id',
        'user_name',
        'action_type',
        'comment',
        'created_at',
    ];

    protected $useTimestamps = false; // We use created_at default CURRENT_TIMESTAMP
}
