<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_no', 'invoice_date', 'invoice_type', 'sales_order_id', 
        'dispatch_id', 'client_id', 'billing_address', 'shipping_address', 
        'place_of_supply', 'is_igst', 'subtotal', 'discount_amt', 
        'taxable_amount', 'cgst_amount', 'sgst_amount', 'igst_amount', 
        'total_tax', 'round_off', 'grand_total', 'amount_in_words', 
        'payment_terms', 'due_date', 'bank_details', 'terms_conditions', 
        'notes', 'status', 'paid_amount', 'balance_amount', 'irn', 'qr_code', 'created_by'
    ];

    protected $useTimestamps = true;

    public function getWithRelations()
    {
        return $this->select('invoices.*, clients.company_name as client_company, clients.gstin as client_gstin, sales_orders.so_no, dispatches.dc_no')
                    ->join('clients', 'clients.id = invoices.client_id')
                    ->join('sales_orders', 'sales_orders.id = invoices.sales_order_id', 'left')
                    ->join('dispatches', 'dispatches.id = invoices.dispatch_id', 'left')
                    ->orderBy('invoices.id', 'DESC')
                    ->findAll();
    }

    public function getDetails($id)
    {
        $inv = $this->select('invoices.*, clients.company_name as client_name, clients.gstin as client_gstin, clients.pan as client_pan, clients.state as client_state, clients.email as client_email, clients.phone as client_phone, sales_orders.so_no, sales_orders.client_po_no, sales_orders.client_po_date, dispatches.dc_no, dispatches.dc_date, dispatches.vehicle_no, dispatches.lr_no, dispatches.lr_date, dispatches.eway_bill_no')
                    ->join('clients', 'clients.id = invoices.client_id')
                    ->join('sales_orders', 'sales_orders.id = invoices.sales_order_id', 'left')
                    ->join('dispatches', 'dispatches.id = invoices.dispatch_id', 'left')
                    ->where('invoices.id', $id)
                    ->first();

        if ($inv) {
            $db = \Config\Database::connect();
            $inv['items'] = $db->table('invoice_items')
                               ->where('invoice_id', $id)
                               ->orderBy('sl_no', 'ASC')
                               ->get()->getResultArray();

            $inv['payments'] = $db->table('payments_received')
                                  ->where('invoice_id', $id)
                                  ->orderBy('receipt_date', 'DESC')
                                  ->get()->getResultArray();
        }

        return $inv;
    }
}
