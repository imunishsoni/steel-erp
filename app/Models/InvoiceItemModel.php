<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemModel extends Model
{
    protected $table            = 'invoice_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_id', 'sl_no', 'so_item_id', 'description', 
        'hsn_code', 'quantity', 'unit', 'rate', 'amount', 
        'gst_rate', 'cgst_amt', 'sgst_amt', 'igst_amt', 'total_amount'
    ];
    protected $useTimestamps = false;
}
