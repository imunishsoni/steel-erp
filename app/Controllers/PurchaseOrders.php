<?php

namespace App\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\VendorModel;
use App\Models\MaterialModel;
use App\Models\SalesOrderModel;

class PurchaseOrders extends BaseController
{
    public function index()
    {
        $poModel = new PurchaseOrderModel();
        $orders = $poModel->getWithRelations();

        return $this->render('purchase_orders/index', [
            'pageTitle'      => 'Procurement & Purchase Orders (PO)',
            'purchaseOrders' => $orders,
        ]);
    }

    public function new()
    {
        $vendorModel = new VendorModel();
        $vendors = $vendorModel->getActiveVendors();

        $matModel = new MaterialModel();
        $materials = $matModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        $soModel = new SalesOrderModel();
        $salesOrders = $soModel->whereNotIn('status', ['cancelled'])->orderBy('id', 'DESC')->findAll();

        $vendorId = $this->request->getGet('vendor_id');
        $prefill = [];
        if (!empty($vendorId)) {
            $prefill['vendor_id'] = $vendorId;
        }

        return $this->render('purchase_orders/form', [
            'pageTitle'   => 'Create Purchase Order (Steel Mills / Suppliers)',
            'order'       => $prefill,
            'items'       => [],
            'vendors'     => $vendors,
            'materials'   => $materials,
            'salesOrders' => $salesOrders,
            'isEdit'      => false,
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $poModel = new PurchaseOrderModel();
        $itemModel = new PurchaseOrderItemModel();

        $poNo = trim((string)$this->request->getPost('po_no'));
        if (empty($poNo)) {
            $count = $poModel->countAllResults() + 1;
            $poNo = 'PO-2627-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $grandTotal = (float)$this->request->getPost('grand_total');

        $data = [
            'po_no'            => $poNo,
            'po_date'          => $this->request->getPost('po_date') ?: date('Y-m-d'),
            'vendor_id'        => (int)$this->request->getPost('vendor_id'),
            'delivery_date'    => $this->request->getPost('delivery_date') ?: date('Y-m-d', strtotime('+15 days')),
            'delivery_address' => $this->request->getPost('delivery_address') ?: 'Heavy Steel Fabricators Yard, Jaipur Works',
            'reference_so'     => !empty($this->request->getPost('reference_so')) ? (int)$this->request->getPost('reference_so') : null,
            'payment_terms'    => $this->request->getPost('payment_terms'),
            'subtotal'         => (float)$this->request->getPost('subtotal'),
            'discount_amt'     => (float)$this->request->getPost('discount_amt'),
            'taxable_amount'   => (float)$this->request->getPost('taxable_amount'),
            'cgst_amount'      => (float)$this->request->getPost('cgst_amount'),
            'sgst_amount'      => (float)$this->request->getPost('sgst_amount'),
            'igst_amount'      => (float)$this->request->getPost('igst_amount'),
            'total_tax'        => (float)$this->request->getPost('total_tax'),
            'freight'          => (float)$this->request->getPost('freight'),
            'grand_total'      => $grandTotal,
            'amount_in_words'  => $this->request->getPost('amount_in_words') ?: number_to_words_indian($grandTotal),
            'terms_conditions' => $this->request->getPost('terms_conditions'),
            'status'           => $this->request->getPost('status') ?: 'sent',
            'remarks'          => $this->request->getPost('remarks'),
            'created_by'       => $this->currentUser['id'] ?? 1,
            'approved_by'      => $this->currentUser['id'] ?? 1,
        ];

        $db->transStart();

        $poModel->insert($data);
        $poId = $poModel->getInsertID();

        $items = $this->request->getPost('items') ?? [];
        $sl = 1;
        foreach ($items as $item) {
            if (empty($item['description']) && empty($item['material_id'])) {
                continue;
            }
            $itemModel->insert([
                'purchase_order_id' => $poId,
                'sl_no'             => $sl++,
                'material_id'       => !empty($item['material_id']) ? (int)$item['material_id'] : null,
                'description'       => $item['description'],
                'specification'     => $item['specification'] ?? null,
                'hsn_code'          => $item['hsn_code'] ?? '72085100',
                'quantity'          => (float)($item['quantity'] ?? 0),
                'received_qty'      => 0,
                'unit'              => $item['unit'] ?? 'kg',
                'rate'              => (float)($item['rate'] ?? 0),
                'amount'            => (float)($item['amount'] ?? 0),
                'gst_rate'          => (float)($item['gst_rate'] ?? 18),
                'remarks'           => $item['remarks'] ?? null,
            ]);
        }

        $db->transComplete();

        log_activity('Purchase Orders', 'Create', $poId, "Created PO {$poNo}");

        return redirect()->to('/purchase-orders/view/' . $poId)->with('success', "Purchase Order '{$poNo}' created successfully.");
    }

    public function view($id)
    {
        $poModel = new PurchaseOrderModel();
        $order = $poModel->getDetails($id);

        if (!$order) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order not found.');
        }

        $db = \Config\Database::connect();
        $grns = $db->table('grn')->where('purchase_order_id', $id)->get()->getResultArray();
        $payments = $db->table('payments_made')->where('purchase_order_id', $id)->get()->getResultArray();

        return $this->render('purchase_orders/view', [
            'pageTitle' => $order['po_no'] . ' - Purchase Order',
            'order'     => $order,
            'grns'      => $grns,
            'payments'  => $payments,
        ]);
    }

    public function printDoc($id)
    {
        $poModel = new PurchaseOrderModel();
        $order = $poModel->getDetails($id);

        if (!$order) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase Order not found.');
        }

        $company = company_settings();

        return view('purchase_orders/print', [
            'docTitle' => 'Purchase Order - ' . $order['po_no'],
            'order'    => $order,
            'company'  => $company,
        ]);
    }

    public function delete($id)
    {
        $poModel = new PurchaseOrderModel();
        $order = $poModel->find($id);

        if ($order) {
            $poModel->delete($id);
            log_activity('Purchase Orders', 'Delete', $id, "Deleted PO {$order['po_no']}");
            return redirect()->to('/purchase-orders')->with('success', 'Purchase Order deleted.');
        }

        return redirect()->to('/purchase-orders')->with('error', 'Purchase Order not found.');
    }
}
