<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\MaterialCategoryModel;

class Materials extends BaseController
{
    public function index()
    {
        $materialModel = new MaterialModel();
        $materials = $materialModel->getMaterialsWithCategory();

        $categoryModel = new MaterialCategoryModel();
        $categories = $categoryModel->orderBy('name', 'ASC')->findAll();

        return $this->render('materials/index', [
            'pageTitle'  => 'Steel Materials Catalog & Specifications',
            'materials'   => $materials,
            'categories' => $categories,
        ]);
    }

    public function new()
    {
        $categoryModel = new MaterialCategoryModel();
        $categories = $categoryModel->orderBy('name', 'ASC')->findAll();

        return $this->render('materials/form', [
            'pageTitle'  => 'Add New Material / Steel Specification',
            'material'   => [],
            'categories' => $categories,
            'isEdit'     => false,
        ]);
    }

    public function create()
    {
        $materialModel = new MaterialModel();

        $data = [
            'material_code'      => strtoupper(trim((string)$this->request->getPost('material_code'))),
            'name'               => $this->request->getPost('name'),
            'category_id'        => (int)$this->request->getPost('category_id'),
            'hsn_code'           => $this->request->getPost('hsn_code'),
            'unit'               => $this->request->getPost('unit'),
            'grade'              => $this->request->getPost('grade'),
            'specification'      => $this->request->getPost('specification'),
            'thickness'          => $this->request->getPost('thickness'),
            'width'              => $this->request->getPost('width'),
            'length'             => $this->request->getPost('length'),
            'min_stock'          => (float)$this->request->getPost('min_stock'),
            'reorder_level'      => (float)$this->request->getPost('reorder_level'),
            'last_purchase_rate' => (float)$this->request->getPost('last_purchase_rate'),
            'gst_rate'           => (float)$this->request->getPost('gst_rate'),
            'is_active'          => 1,
        ];

        $materialModel->insert($data);
        $insertId = $materialModel->getInsertID();

        // Create initial zero inventory entry if not exists
        $db = \Config\Database::connect();
        $db->table('inventory')->insert([
            'material_id'  => $insertId,
            'warehouse'    => 'Yard-A (Plates)',
            'quantity'     => 0,
            'reserved_qty' => 0,
            'avg_rate'     => $data['last_purchase_rate'],
            'last_updated' => date('Y-m-d H:i:s'),
        ]);

        log_activity('Materials', 'Create', $insertId, "Added material {$data['name']} ({$data['material_code']})");

        return redirect()->to('/materials')->with('success', "Material '{$data['name']}' added to catalog.");
    }

    public function edit($id)
    {
        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if (!$material) {
            return redirect()->to('/materials')->with('error', 'Material not found.');
        }

        $categoryModel = new MaterialCategoryModel();
        $categories = $categoryModel->orderBy('name', 'ASC')->findAll();

        return $this->render('materials/form', [
            'pageTitle'  => 'Edit Material - ' . $material['name'],
            'material'   => $material,
            'categories' => $categories,
            'isEdit'     => true,
        ]);
    }

    public function update($id)
    {
        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if (!$material) {
            return redirect()->to('/materials')->with('error', 'Material not found.');
        }

        $data = [
            'material_code'      => strtoupper(trim((string)$this->request->getPost('material_code'))),
            'name'               => $this->request->getPost('name'),
            'category_id'        => (int)$this->request->getPost('category_id'),
            'hsn_code'           => $this->request->getPost('hsn_code'),
            'unit'               => $this->request->getPost('unit'),
            'grade'              => $this->request->getPost('grade'),
            'specification'      => $this->request->getPost('specification'),
            'thickness'          => $this->request->getPost('thickness'),
            'min_stock'          => (float)$this->request->getPost('min_stock'),
            'reorder_level'      => (float)$this->request->getPost('reorder_level'),
            'last_purchase_rate' => (float)$this->request->getPost('last_purchase_rate'),
            'gst_rate'           => (float)$this->request->getPost('gst_rate'),
            'is_active'          => (int)$this->request->getPost('is_active'),
        ];

        $materialModel->update($id, $data);

        log_activity('Materials', 'Update', $id, "Updated material {$data['name']}");

        return redirect()->to('/materials')->with('success', "Material '{$data['name']}' updated.");
    }

    public function delete($id)
    {
        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if ($material) {
            $materialModel->delete($id);
            log_activity('Materials', 'Delete', $id, "Deleted material {$material['name']}");
            return redirect()->to('/materials')->with('success', 'Material removed from catalog.');
        }

        return redirect()->to('/materials')->with('error', 'Material not found.');
    }
}
