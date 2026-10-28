<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanySettingModel extends Model
{
    protected $table            = 'company_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'company_name', 'tagline', 'address_line1', 'address_line2', 
        'city', 'state', 'pincode', 'country', 'phone', 'email', 
        'website', 'gstin', 'pan', 'cin', 'bank_name', 'bank_account_no', 
        'bank_ifsc', 'bank_branch', 'logo', 'financial_year', 
        'invoice_prefix', 'quotation_prefix', 'po_prefix', 'so_prefix', 'dc_prefix'
    ];

    protected $useTimestamps = true;
}
