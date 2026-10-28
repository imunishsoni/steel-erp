<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationModel extends Model
{
    protected $table            = 'quotations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quotation_no', 'quotation_date', 'valid_until', 'revision', 
        'enquiry_id', 'client_id', 'project_name', 'subject', 
        'payment_terms', 'delivery_terms', 'delivery_period', 
        'freight_charges', 'taxes_note', 'subtotal', 'discount_pct', 
        'discount_amt', 'taxable_amount', 'cgst_amount', 'sgst_amount', 
        'igst_amount', 'total_tax', 'grand_total', 'amount_in_words', 
        'terms_conditions', 'notes', 'status', 'created_by', 'approved_by'
    ];

    protected $useTimestamps = true;

    public function getWithRelations()
    {
        return $this->select('quotations.*, clients.company_name as client_company, clients.state as client_state, clients.gstin as client_gstin, users.full_name as creator_name')
                    ->join('clients', 'clients.id = quotations.client_id')
                    ->join('users', 'users.id = quotations.created_by', 'left')
                    ->orderBy('quotations.id', 'DESC')
                    ->findAll();
    }

    public function getDetails($id)
    {
        $q = $this->select('quotations.*, clients.company_name as client_name, clients.contact_person, clients.email as client_email, clients.phone as client_phone, clients.address_line1, clients.address_line2, clients.city, clients.state as client_state, clients.pincode, clients.gstin as client_gstin, clients.pan as client_pan, users.full_name as creator_name')
                  ->join('clients', 'clients.id = quotations.client_id')
                  ->join('users', 'users.id = quotations.created_by', 'left')
                  ->where('quotations.id', $id)
                  ->first();

        if ($q) {
            $db = \Config\Database::connect();
            $q['items'] = $db->table('quotation_items')
                             ->select('quotation_items.*, materials.material_code, materials.name as material_name')
                             ->join('materials', 'materials.id = quotation_items.material_id', 'left')
                             ->where('quotation_id', $id)
                             ->orderBy('sl_no', 'ASC')
                             ->get()->getResultArray();
        }

        return $q;
    }
}
