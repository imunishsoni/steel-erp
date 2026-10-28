<?php

namespace App\Models;

use CodeIgniter\Model;

class StockTransactionModel extends Model
{
    protected $table            = 'stock_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'material_id', 'transaction_type', 'reference_type', 'reference_id', 
        'quantity', 'rate', 'warehouse', 'remarks', 'created_by', 'created_at'
    ];

    protected $useTimestamps = false;

    public function getRecentTransactions($limit = 50)
    {
        return $this->select('stock_transactions.*, materials.material_code, materials.name as material_name, materials.unit, users.full_name as user_name')
                    ->join('materials', 'materials.id = stock_transactions.material_id')
                    ->join('users', 'users.id = stock_transactions.created_by', 'left')
                    ->orderBy('stock_transactions.id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
