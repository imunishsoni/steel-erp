<?php

namespace App\Models;

use CodeIgniter\Model;

class DispatchModel extends Model
{
    protected $table            = 'dispatches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'dc_no', 'dc_date', 'sales_order_id', 'client_id', 'delivery_address', 
        'transporter', 'vehicle_no', 'driver_name', 'driver_phone', 
        'lr_no', 'lr_date', 'eway_bill_no', 'total_weight', 'no_of_packages', 
        'remarks', 'status', 'delivered_date', 'created_by'
    ];

    protected $useTimestamps = true;

    public function getWithRelations()
    {
        return $this->select('dispatches.*, clients.company_name as client_company, sales_orders.so_no')
                    ->join('clients', 'clients.id = dispatches.client_id')
                    ->join('sales_orders', 'sales_orders.id = dispatches.sales_order_id', 'left')
                    ->orderBy('dispatches.id', 'DESC')
                    ->findAll();
    }

    public function getDetails($id)
    {
        $dc = $this->select('dispatches.*, clients.company_name as client_name, clients.contact_person, clients.phone as client_phone, clients.gstin as client_gstin, sales_orders.so_no, sales_orders.client_po_no')
                   ->join('clients', 'clients.id = dispatches.client_id')
                   ->join('sales_orders', 'sales_orders.id = dispatches.sales_order_id', 'left')
                   ->where('dispatches.id', $id)
                   ->first();

        if ($dc) {
            $db = \Config\Database::connect();
            $dc['items'] = $db->table('dispatch_items')
                              ->where('dispatch_id', $id)
                              ->get()->getResultArray();
        }

        return $dc;
    }
}
