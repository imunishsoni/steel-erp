<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductionJobModel extends Model
{
    protected $table            = 'production_jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_no', 'job_date', 'sales_order_id', 'project_name', 
        'description', 'start_date', 'target_date', 'actual_end_date', 
        'priority', 'status', 'total_weight', 'completion_pct', 
        'assigned_to', 'remarks', 'created_by'
    ];

    protected $useTimestamps = true;

    public function getWithRelations()
    {
        return $this->select('production_jobs.*, sales_orders.so_no, clients.company_name as client_company, clients.industry_type, users.full_name as assigned_engineer')
                    ->join('sales_orders', 'sales_orders.id = production_jobs.sales_order_id', 'left')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = production_jobs.assigned_to', 'left')
                    ->orderBy('production_jobs.id', 'DESC')
                    ->findAll();
    }

    public function getDetails($id)
    {
        $job = $this->select('production_jobs.*, sales_orders.so_no, clients.company_name as client_name, users.full_name as assigned_engineer')
                    ->join('sales_orders', 'sales_orders.id = production_jobs.sales_order_id', 'left')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = production_jobs.assigned_to', 'left')
                    ->where('production_jobs.id', $id)
                    ->first();

        if ($job) {
            $db = \Config\Database::connect();
            $job['items'] = $db->table('production_job_items')
                               ->where('job_id', $id)
                               ->get()->getResultArray();

            $job['issues'] = $db->table('material_issues')
                                ->select('material_issues.*, materials.material_code, materials.name as material_name')
                                ->join('materials', 'materials.id = material_issues.material_id')
                                ->where('job_id', $id)
                                ->get()->getResultArray();
        }

        return $job;
    }
}
