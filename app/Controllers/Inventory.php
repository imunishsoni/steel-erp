<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\MaterialModel;
use App\Models\StockTransactionModel;

class Inventory extends BaseController
{
    public function index()
    {
        $invModel = new InventoryModel();
        $inventory = $invModel->getFullInventory();

        $totalValuation = 0;
        $totalWeightKg = 0;

        foreach ($inventory as $item) {
            $totalValuation += ((float)$item['quantity'] * (float)$item['avg_rate']);
            if ($item['unit'] === 'kg') {
                $totalWeightKg += (float)$item['quantity'];
            } elseif ($item['unit'] === 'mt') {
                $totalWeightKg += ((float)$item['quantity'] * 1000);
            }
        }

        return $this->render('inventory/index', [
            'pageTitle'      => 'Warehouse & Steel Stockyard Inventory',
            'inventory'      => $inventory,
            'totalValuation' => $totalValuation,
            'totalWeightMT'  => $totalWeightKg / 1000,
        ]);
    }

    public function adjust()
    {
        $matModel = new MaterialModel();
        $materials = $matModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        return $this->render('inventory/adjust', [
            'pageTitle' => 'Stock Adjustment / Physical Reconciliation',
            'materials' => $materials,
        ]);
    }

    public function saveAdjustment()
    {
        $materialId = (int)$this->request->getPost('material_id');
        $adjustmentType = $this->request->getPost('adjustment_type'); // 'adjustment_in' or 'adjustment_out'
        $quantity = abs((float)$this->request->getPost('quantity'));
        $warehouse = $this->request->getPost('warehouse') ?? 'Yard-A (Plates)';
        $remarks = $this->request->getPost('remarks');

        if ($materialId <= 0 || $quantity <= 0) {
            return redirect()->back()->with('error', 'Valid material and quantity are required.');
        }

        $invModel = new InventoryModel();
        $txModel = new StockTransactionModel();

        $delta = ($adjustmentType === 'adjustment_in') ? $quantity : -$quantity;
        $invModel->updateStock($materialId, $delta, $warehouse);

        $txModel->insert([
            'material_id'      => $materialId,
            'transaction_type' => $adjustmentType,
            'reference_type'   => 'Manual Adjustment',
            'reference_id'     => null,
            'quantity'         => $quantity,
            'rate'             => 0,
            'warehouse'        => $warehouse,
            'remarks'          => $remarks,
            'created_by'       => $this->currentUser['id'] ?? 1,
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        log_activity('Inventory', 'Adjust', $materialId, "Stock adjustment: {$adjustmentType} {$quantity} in {$warehouse}");

        return redirect()->to('/inventory')->with('success', 'Stock adjustment recorded successfully.');
    }

    public function transactions()
    {
        $txModel = new StockTransactionModel();
        $transactions = $txModel->getRecentTransactions(100);

        return $this->render('inventory/transactions', [
            'pageTitle'    => 'Stock Transaction Audit Ledger',
            'transactions' => $transactions,
        ]);
    }
}
