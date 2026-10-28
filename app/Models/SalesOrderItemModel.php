<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesOrderItemModel extends Model
{
    protected $table            = 'sales_order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sales_order_id', 'sl_no', 'material_id', 'description', 
        'specification', 'hsn_code', 'quantity', 'dispatched_qty', 
        'unit', 'rate', 'amount', 'gst_rate', 'status', 'remarks'
    ];
    protected $useTimestamps = false;
}
