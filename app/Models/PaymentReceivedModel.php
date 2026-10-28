<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentReceivedModel extends Model
{
    protected $table            = 'payments_received';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'receipt_no', 'receipt_date', 'client_id', 'invoice_id', 
        'amount', 'payment_mode', 'reference_no', 'bank_name', 
        'tds_amount', 'remarks', 'status', 'created_by'
    ];
    protected $useTimestamps = false;

    public function getWithRelations()
    {
        return $this->select('payments_received.*, clients.company_name as client_company, invoices.invoice_no')
                    ->join('clients', 'clients.id = payments_received.client_id')
                    ->join('invoices', 'invoices.id = payments_received.invoice_id', 'left')
                    ->orderBy('payments_received.id', 'DESC')
                    ->findAll();
    }
}
