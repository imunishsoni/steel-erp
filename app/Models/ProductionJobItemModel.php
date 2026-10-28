<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductionJobItemModel extends Model
{
    protected $table            = 'production_job_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_id', 'description', 'drawing_no', 'quantity', 'unit', 
        'weight_each', 'total_weight', 'process', 'status', 'completed_qty', 'remarks'
    ];
    protected $useTimestamps = false;
}
