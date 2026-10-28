<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationItemModel extends Model
{
    protected $table            = 'quotation_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quotation_id', 'sl_no', 'material_id', 'description', 
        'specification', 'hsn_code', 'quantity', 'unit', 'rate', 'amount', 'gst_rate', 'remarks'
    ];
    protected $useTimestamps = false;
}
