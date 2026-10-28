<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesOrderModel extends Model
{
    protected $table            = 'sales_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'so_no', 'so_date', 'quotation_id', 'client_id', 'client_po_no', 
        'client_po_date', 'project_name', 'delivery_address', 'payment_terms', 
        'delivery_date', 'subtotal', 'discount_amt', 'taxable_amount', 
        'cgst_amount', 'sgst_amount', 'igst_amount', 'total_tax', 
        'grand_total', 'amount_in_words', 'status', 'remarks', 'created_by'
    ];

    protected $useTimestamps = true;

    public function getWithRelations()
    {
        return $this->select('sales_orders.*, clients.company_name as client_company, clients.industry_type, users.full_name as creator_name')
                    ->join('clients', 'clients.id = sales_orders.client_id')
                    ->join('users', 'users.id = sales_orders.created_by', 'left')
                    ->orderBy('sales_orders.id', 'DESC')
                    ->findAll();
    }

    public function getDetails($id)
    {
        $so = $this->select('sales_orders.*, clients.company_name as client_name, clients.contact_person, clients.email as client_email, clients.phone as client_phone, clients.gstin as client_gstin, clients.state as client_state')
                   ->join('clients', 'clients.id = sales_orders.client_id')
                   ->where('sales_orders.id', $id)
                   ->first();

        if ($so) {
            $db = \Config\Database::connect();
            $so['items'] = $db->table('sales_order_items')
                              ->select('sales_order_items.*, materials.material_code, materials.name as material_name')
                              ->join('materials', 'materials.id = sales_order_items.material_id', 'left')
                              ->where('sales_order_id', $id)
                              ->orderBy('sl_no', 'ASC')
                              ->get()->getResultArray();
        }

        return $so;
    }
}
