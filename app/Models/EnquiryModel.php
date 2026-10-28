<?php

namespace App\Models;

use CodeIgniter\Model;

class EnquiryModel extends Model
{
    protected $table            = 'enquiries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'enquiry_no', 'enquiry_date', 'client_id', 'client_name', 
        'contact_person', 'contact_phone', 'contact_email', 'source', 
        'project_name', 'plant_type', 'description', 'estimated_value', 
        'expected_date', 'priority', 'status', 'assigned_to', 'remarks', 'created_by'
    ];

    protected $useTimestamps = true;

    public function getWithRelations()
    {
        return $this->select('enquiries.*, clients.client_code, clients.company_name as client_company, users.full_name as assigned_user')
                    ->join('clients', 'clients.id = enquiries.client_id', 'left')
                    ->join('users', 'users.id = enquiries.assigned_to', 'left')
                    ->orderBy('enquiries.id', 'DESC')
                    ->findAll();
    }
}
