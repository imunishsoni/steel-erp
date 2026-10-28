<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderItemModel extends Model
{
    protected $table            = 'purchase_order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'purchase_order_id', 'sl_no', 'material_id', 'description', 
        'specification', 'hsn_code', 'quantity', 'received_qty', 
        'unit', 'rate', 'amount', 'gst_rate', 'remarks'
    ];
    protected $useTimestamps = false;
}
