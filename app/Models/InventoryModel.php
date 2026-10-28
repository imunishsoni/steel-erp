<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table            = 'inventory';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'material_id', 'warehouse', 'location_rack', 'quantity', 
        'reserved_qty', 'avg_rate', 'last_updated'
    ];

    protected $useTimestamps = false;

    public function getFullInventory()
    {
        return $this->select('inventory.*, materials.material_code, materials.name as material_name, materials.unit, materials.grade, materials.thickness, materials.reorder_level, material_categories.name as category_name')
                    ->join('materials', 'materials.id = inventory.material_id')
                    ->join('material_categories', 'material_categories.id = materials.category_id', 'left')
                    ->orderBy('materials.name', 'ASC')
                    ->findAll();
    }

    public function getByMaterial($material_id)
    {
        return $this->where('material_id', $material_id)->first();
    }

    public function updateStock($material_id, $qty_change, $warehouse = 'main', $rate = 0)
    {
        $existing = $this->where('material_id', $material_id)->first();
        if ($existing) {
            $new_qty = max(0, (float)$existing['quantity'] + (float)$qty_change);
            $this->update($existing['id'], [
                'quantity'     => $new_qty,
                'avg_rate'     => $rate > 0 ? $rate : $existing['avg_rate'],
                'last_updated' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->insert([
                'material_id'   => $material_id,
                'warehouse'     => $warehouse,
                'quantity'      => max(0, (float)$qty_change),
                'reserved_qty'  => 0,
                'avg_rate'      => $rate,
                'last_updated'  => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
