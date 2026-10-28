<?php

namespace App\Models;

use CodeIgniter\Model;

class GrnItemModel extends Model
{
    protected $table            = 'grn_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'grn_id', 'po_item_id', 'material_id', 'ordered_qty', 
        'received_qty', 'accepted_qty', 'rejected_qty', 'rate', 'remarks'
    ];
    protected $useTimestamps = false;
}
