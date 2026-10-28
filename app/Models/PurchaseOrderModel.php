<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table            = 'purchase_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'po_no', 'po_date', 'vendor_id', 'delivery_date', 'delivery_address', 
        'reference_so', 'payment_terms', 'subtotal', 'discount_amt', 
        'taxable_amount', 'cgst_amount', 'sgst_amount', 'igst_amount', 
        'total_tax', 'freight', 'grand_total', 'amount_in_words', 
        'terms_conditions', 'status', 'remarks', 'created_by', 'approved_by'
    ];

    protected $useTimestamps = true;

    public function getWithRelations()
    {
        return $this->select('purchase_orders.*, vendors.company_name as vendor_company, vendors.vendor_type, sales_orders.so_no as linked_so')
                    ->join('vendors', 'vendors.id = purchase_orders.vendor_id')
                    ->join('sales_orders', 'sales_orders.id = purchase_orders.reference_so', 'left')
                    ->orderBy('purchase_orders.id', 'DESC')
                    ->findAll();
    }

    public function getDetails($id)
    {
        $po = $this->select('purchase_orders.*, vendors.company_name as vendor_name, vendors.contact_person, vendors.email as vendor_email, vendors.phone as vendor_phone, vendors.address_line1, vendors.address_line2, vendors.city, vendors.state as vendor_state, vendors.pincode, vendors.gstin as vendor_gstin, vendors.pan as vendor_pan, vendors.bank_name, vendors.bank_account_no, vendors.bank_ifsc')
                   ->join('vendors', 'vendors.id = purchase_orders.vendor_id')
                   ->where('purchase_orders.id', $id)
                   ->first();

        if ($po) {
            $db = \Config\Database::connect();
            $po['items'] = $db->table('purchase_order_items')
                              ->select('purchase_order_items.*, materials.material_code, materials.name as material_name')
                              ->join('materials', 'materials.id = purchase_order_items.material_id', 'left')
                              ->where('purchase_order_id', $id)
                              ->orderBy('sl_no', 'ASC')
                              ->get()->getResultArray();
        }

        return $po;
    }
}
