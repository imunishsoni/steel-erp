<?php

namespace App\Controllers;

use App\Models\GrnModel;
use App\Models\GrnItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\VendorModel;
use App\Models\InventoryModel;
use App\Models\StockTransactionModel;

class Grn extends BaseController
{
    public function index()
    {
        $grnModel = new GrnModel();
        $grns = $grnModel->getWithRelations();

        return $this->render('grn/index', [
            'pageTitle' => 'Goods Receipt Notes (GRN) & Material Inward',
            'grns'      => $grns,
        ]);
    }

    public function new()
    {
        $poId = $this->request->getGet('po_id');
        $poModel = new PurchaseOrderModel();
        $poList = $poModel->whereNotIn('status', ['completed', 'cancelled'])->orderBy('id', 'DESC')->findAll();

        $selectedPo = null;
        if (!empty($poId)) {
            $selectedPo = $poModel->getDetails($poId);
        }

        $vendorModel = new VendorModel();
        $vendors = $vendorModel->getActiveVendors();

        return $this->render('grn/form', [
            'pageTitle'  => 'Create Goods Receipt Note (GRN)',
            'grn'        => [],
            'selectedPo' => $selectedPo,
            'poList'     => $poList,
            'vendors'    => $vendors,
            'isEdit'     => false,
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $grnModel = new GrnModel();
        $grnItemModel = new GrnItemModel();
        $invModel = new InventoryModel();
        $txModel = new StockTransactionModel();

        $grnNo = trim((string)$this->request->getPost('grn_no'));
        if (empty($grnNo)) {
            $count = $grnModel->countAllResults() + 1;
            $grnNo = 'GRN-2627-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $poId = !empty($this->request->getPost('purchase_order_id')) ? (int)$this->request->getPost('purchase_order_id') : null;
        $vendorId = (int)$this->request->getPost('vendor_id');

        $data = [
            'grn_no'            => $grnNo,
            'grn_date'          => $this->request->getPost('grn_date') ?: date('Y-m-d'),
            'purchase_order_id' => $poId,
            'vendor_id'         => $vendorId,
            'challan_no'        => $this->request->getPost('challan_no'),
            'challan_date'      => $this->request->getPost('challan_date') ?: date('Y-m-d'),
            'vehicle_no'        => strtoupper(trim((string)$this->request->getPost('vehicle_no'))),
            'remarks'           => $this->request->getPost('remarks'),
            'status'            => $this->request->getPost('status') ?: 'accepted',
            'created_by'        => $this->currentUser['id'] ?? 1,
        ];

        $db->transStart();

        $grnModel->insert($data);
        $grnId = $grnModel->getInsertID();

        $items = $this->request->getPost('items') ?? [];
        foreach ($items as $item) {
            $materialId = (int)($item['material_id'] ?? 0);
            $acceptedQty = (float)($item['accepted_qty'] ?? 0);
            $receivedQty = (float)($item['received_qty'] ?? 0);
            $rate = (float)($item['rate'] ?? 0);

            if ($materialId <= 0 || $receivedQty <= 0) {
                continue;
            }

            $grnItemModel->insert([
                'grn_id'       => $grnId,
                'po_item_id'   => !empty($item['po_item_id']) ? (int)$item['po_item_id'] : null,
                'material_id'  => $materialId,
                'ordered_qty'  => (float)($item['ordered_qty'] ?? 0),
                'received_qty' => $receivedQty,
                'accepted_qty' => $acceptedQty,
                'rejected_qty' => (float)($item['rejected_qty'] ?? 0),
                'rate'         => $rate,
                'remarks'      => $item['remarks'] ?? null,
            ]);

            // Update inventory with accepted qty
            if ($acceptedQty > 0) {
                $invModel->updateStock($materialId, $acceptedQty, 'Yard-A (Plates)', $rate);

                // Transaction ledger
                $txModel->insert([
                    'material_id'      => $materialId,
                    'transaction_type' => 'purchase_in',
                    'reference_type'   => 'GRN',
                    'reference_id'     => $grnId,
                    'quantity'         => $acceptedQty,
                    'rate'             => $rate,
                    'warehouse'        => 'Yard-A (Plates)',
                    'remarks'          => "Inward via GRN {$grnNo} (Vehicle: {$data['vehicle_no']})",
                    'created_by'       => $this->currentUser['id'] ?? 1,
                    'created_at'       => date('Y-m-d H:i:s'),
                ]);
            }

            // Update PO item received_qty if linked
            if (!empty($item['po_item_id'])) {
                $db->query("UPDATE purchase_order_items SET received_qty = received_qty + ? WHERE id = ?", [$acceptedQty, (int)$item['po_item_id']]);
            }
        }

        // Update PO status to received / partially_received
        if ($poId) {
            $db->table('purchase_orders')->where('id', $poId)->update(['status' => 'received']);
        }

        $db->transComplete();

        log_activity('GRN', 'Create', $grnId, "Recorded inward GRN {$grnNo} (Challan: {$data['challan_no']})");

        return redirect()->to('/grn/view/' . $grnId)->with('success', "GRN '{$grnNo}' generated and yard inventory updated.");
    }

    public function view($id)
    {
        $grnModel = new GrnModel();
        $grn = $grnModel->getDetails($id);

        if (!$grn) {
            return redirect()->to('/grn')->with('error', 'GRN record not found.');
        }

        return $this->render('grn/view', [
            'pageTitle' => $grn['grn_no'] . ' - Goods Receipt Note',
            'grn'       => $grn,
        ]);
    }
}
