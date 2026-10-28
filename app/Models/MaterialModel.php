<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table            = 'materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'material_code', 'name', 'category_id', 'hsn_code', 'unit', 
        'grade', 'specification', 'thickness', 'width', 'length', 
        'diameter', 'weight_per_unit', 'min_stock', 'max_stock', 
        'reorder_level', 'last_purchase_rate', 'gst_rate', 'is_active'
    ];

    protected $useTimestamps = true;

    public function getMaterialsWithCategory()
    {
        return $this->select('materials.*, material_categories.name as category_name, inventory.quantity as in_stock, inventory.quantity as current_stock, inventory.available_qty, inventory.warehouse')
                    ->join('material_categories', 'material_categories.id = materials.category_id', 'left')
                    ->join('inventory', 'inventory.material_id = materials.id', 'left')
                    ->orderBy('materials.name', 'ASC')
                    ->findAll();
    }

    public function getWithCategory()
    {
        return $this->getMaterialsWithCategory();
    }

    public function getLowStock()
    {
        return $this->select('materials.*, inventory.quantity as in_stock, inventory.available_qty')
                    ->join('inventory', 'inventory.material_id = materials.id', 'left')
                    ->where('inventory.quantity <= materials.reorder_level')
                    ->where('materials.reorder_level >', 0)
                    ->findAll();
    }
}
