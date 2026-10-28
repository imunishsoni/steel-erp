<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMadeModel extends Model
{
    protected $table            = 'payments_made';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'payment_no', 'payment_date', 'vendor_id', 'purchase_order_id', 
        'amount', 'payment_mode', 'reference_no', 'bank_name', 
        'tds_amount', 'remarks', 'status', 'created_by'
    ];
    protected $useTimestamps = false;

    public function getWithRelations()
    {
        return $this->select('payments_made.*, vendors.company_name as vendor_company, purchase_orders.po_no')
                    ->join('vendors', 'vendors.id = payments_made.vendor_id')
                    ->join('purchase_orders', 'purchase_orders.id = payments_made.purchase_order_id', 'left')
                    ->orderBy('payments_made.id', 'DESC')
                    ->findAll();
    }
}
