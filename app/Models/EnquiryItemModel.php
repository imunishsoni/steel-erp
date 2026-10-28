<?php

namespace App\Models;

use CodeIgniter\Model;

class EnquiryItemModel extends Model
{
    protected $table            = 'enquiry_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'enquiry_id', 'material_id', 'description', 'specification', 'quantity', 'unit', 'remarks'
    ];
    protected $useTimestamps = false;
}
