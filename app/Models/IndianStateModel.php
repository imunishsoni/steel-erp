<?php

namespace App\Models;

use CodeIgniter\Model;

class IndianStateModel extends Model
{
    protected $table            = 'indian_states';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['state_code', 'state_name', 'is_ut'];
    protected $useTimestamps    = false;
}
