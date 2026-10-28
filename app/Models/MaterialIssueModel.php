<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialIssueModel extends Model
{
    protected $table            = 'material_issues';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'issue_no', 'issue_date', 'job_id', 'material_id', 
        'quantity', 'unit', 'rate', 'remarks', 'issued_by'
    ];
    protected $useTimestamps = false;

    public function getWithRelations()
    {
        return $this->select('material_issues.*, production_jobs.job_no, production_jobs.project_name, materials.material_code, materials.name as material_name, users.full_name as issuer_name')
                    ->join('production_jobs', 'production_jobs.id = material_issues.job_id')
                    ->join('materials', 'materials.id = material_issues.material_id')
                    ->join('users', 'users.id = material_issues.issued_by', 'left')
                    ->orderBy('material_issues.id', 'DESC')
                    ->findAll();
    }
}
