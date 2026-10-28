<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'client_code', 'company_name', 'contact_person', 'email', 
        'phone', 'mobile', 'industry_type', 'address_line1', 
        'address_line2', 'city', 'state', 'pincode', 'country', 
        'gstin', 'pan', 'credit_limit', 'credit_days', 'notes', 
        'is_active', 'created_by'
    ];

    protected $useTimestamps = true;

    public function getActiveClients()
    {
        return $this->where('is_active', 1)->orderBy('company_name', 'ASC')->findAll();
    }

    public function getByIndustry($type)
    {
        return $this->where('industry_type', $type)->findAll();
    }
}
