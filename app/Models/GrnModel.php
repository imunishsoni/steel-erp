<?php

namespace App\Models;

use CodeIgniter\Model;

class GrnModel extends Model
{
    protected $table            = 'grn';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'grn_no', 'grn_date', 'purchase_order_id', 'vendor_id', 
        'challan_no', 'challan_date', 'vehicle_no', 'remarks', 'status', 'created_by'
    ];

    protected $useTimestamps = false;

    public function getWithRelations()
    {
        return $this->select('grn.*, vendors.company_name as vendor_company, purchase_orders.po_no, users.full_name as receiver_name')
                    ->join('vendors', 'vendors.id = grn.vendor_id')
                    ->join('purchase_orders', 'purchase_orders.id = grn.purchase_order_id', 'left')
                    ->join('users', 'users.id = grn.created_by', 'left')
                    ->orderBy('grn.id', 'DESC')
                    ->findAll();
    }

    public function getDetails($id)
    {
        $grn = $this->select('grn.*, vendors.company_name as vendor_company, vendors.company_name as vendor_name, vendors.gstin as vendor_gstin, purchase_orders.po_no, purchase_orders.po_no as po_number, users.full_name as receiver_name, users.full_name as received_by_name')
                    ->join('vendors', 'vendors.id = grn.vendor_id')
                    ->join('purchase_orders', 'purchase_orders.id = grn.purchase_order_id', 'left')
                    ->join('users', 'users.id = grn.created_by', 'left')
                    ->where('grn.id', $id)
                    ->first();

        if ($grn) {
            $db = \Config\Database::connect();
            $grn['items'] = $db->table('grn_items')
                               ->select('grn_items.*, materials.material_code, materials.name as material_name, materials.unit')
                               ->join('materials', 'materials.id = grn_items.material_id')
                               ->where('grn_id', $id)
                               ->get()->getResultArray();
        }

        return $grn;
    }
}
