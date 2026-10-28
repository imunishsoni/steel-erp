<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table            = 'vendors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'vendor_code', 'company_name', 'contact_person', 'email', 
        'phone', 'mobile', 'vendor_type', 'address_line1', 
        'address_line2', 'city', 'state', 'pincode', 'country', 
        'gstin', 'pan', 'payment_terms', 'bank_name', 'bank_account_no', 
        'bank_ifsc', 'notes', 'is_active', 'rating', 'created_by'
    ];

    protected $useTimestamps = true;

    public function getActiveVendors()
    {
        return $this->where('is_active', 1)->orderBy('company_name', 'ASC')->findAll();
    }
}
