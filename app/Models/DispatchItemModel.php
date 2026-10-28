<?php

namespace App\Models;

use CodeIgniter\Model;

class DispatchItemModel extends Model
{
    protected $table            = 'dispatch_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'dispatch_id', 'so_item_id', 'description', 'quantity', 'unit', 'weight', 'remarks'
    ];
    protected $useTimestamps = false;
}
