<?php

namespace App\Controllers;

use App\Models\SalesOrderModel;
use App\Models\SalesOrderItemModel;
use App\Models\ClientModel;
use App\Models\MaterialModel;

class SalesOrders extends BaseController
{
    public function index()
    {
        $soModel = new SalesOrderModel();
        $orders = $soModel->getWithRelations();

        return $this->render('sales_orders/index', [
            'pageTitle'   => 'Confirmed Sales Orders (SO)',
            'salesOrders' => $orders,
        ]);
    }

    public function new()
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->getActiveClients();

        $matModel = new MaterialModel();
        $materials = $matModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        return $this->render('sales_orders/form', [
            'pageTitle'  => 'Create Sales Order (Client PO Confirmation)',
            'order'      => [],
            'items'      => [],
            'clients'    => $clients,
            'materials'  => $materials,
            'isEdit'     => false,
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $soModel = new SalesOrderModel();
        $itemModel = new SalesOrderItemModel();

        $soNo = trim((string)$this->request->getPost('so_no'));
        if (empty($soNo)) {
            $count = $soModel->countAllResults() + 1;
            $soNo = 'SO-2627-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $grandTotal = (float)$this->request->getPost('grand_total');

        $data = [
            'so_no'            => $soNo,
            'so_date'          => $this->request->getPost('so_date') ?: date('Y-m-d'),
            'quotation_id'     => !empty($this->request->getPost('quotation_id')) ? (int)$this->request->getPost('quotation_id') : null,
            'client_id'        => (int)$this->request->getPost('client_id'),
            'client_po_no'     => $this->request->getPost('client_po_no'),
            'client_po_date'   => $this->request->getPost('client_po_date') ?: date('Y-m-d'),
            'project_name'     => $this->request->getPost('project_name'),
            'delivery_address' => $this->request->getPost('delivery_address'),
            'payment_terms'    => $this->request->getPost('payment_terms'),
            'delivery_date'    => $this->request->getPost('delivery_date'),
            'subtotal'         => (float)$this->request->getPost('subtotal'),
            'discount_amt'     => (float)$this->request->getPost('discount_amt'),
            'taxable_amount'   => (float)$this->request->getPost('taxable_amount'),
            'cgst_amount'      => (float)$this->request->getPost('cgst_amount'),
            'sgst_amount'      => (float)$this->request->getPost('sgst_amount'),
            'igst_amount'      => (float)$this->request->getPost('igst_amount'),
            'total_tax'        => (float)$this->request->getPost('total_tax'),
            'grand_total'      => $grandTotal,
            'amount_in_words'  => $this->request->getPost('amount_in_words') ?: number_to_words_indian($grandTotal),
            'status'           => $this->request->getPost('status') ?: 'confirmed',
            'remarks'          => $this->request->getPost('remarks'),
            'created_by'       => $this->currentUser['id'] ?? 1,
        ];

        $db->transStart();

        $soModel->insert($data);
        $soId = $soModel->getInsertID();

        $items = $this->request->getPost('items') ?? [];
        $sl = 1;
        foreach ($items as $item) {
            if (empty($item['description']) && empty($item['material_id'])) {
                continue;
            }
            $itemModel->insert([
                'sales_order_id' => $soId,
                'sl_no'          => $sl++,
                'material_id'    => !empty($item['material_id']) ? (int)$item['material_id'] : null,
                'description'    => $item['description'],
                'specification'  => $item['specification'] ?? null,
                'hsn_code'       => $item['hsn_code'] ?? '73089090',
                'quantity'       => (float)($item['quantity'] ?? 0),
                'dispatched_qty' => 0,
                'unit'           => $item['unit'] ?? 'kg',
                'rate'           => (float)($item['rate'] ?? 0),
                'amount'         => (float)($item['amount'] ?? 0),
                'gst_rate'       => (float)($item['gst_rate'] ?? 18),
                'status'         => 'pending',
                'remarks'        => $item['remarks'] ?? null,
            ]);
        }

        $db->transComplete();

        log_activity('Sales Orders', 'Create', $soId, "Registered Sales Order {$soNo} for {$data['project_name']}");

        return redirect()->to('/sales-orders/view/' . $soId)->with('success', "Sales Order '{$soNo}' created successfully.");
    }

    public function view($id)
    {
        $soModel = new SalesOrderModel();
        $order = $soModel->getDetails($id);

        if (!$order) {
            return redirect()->to('/sales-orders')->with('error', 'Sales Order not found.');
        }

        $db = \Config\Database::connect();
        $jobs = $db->table('production_jobs')->where('sales_order_id', $id)->get()->getResultArray();
        $dispatches = $db->table('dispatches')->where('sales_order_id', $id)->get()->getResultArray();
        $invoices = $db->table('invoices')->where('sales_order_id', $id)->get()->getResultArray();

        return $this->render('sales_orders/view', [
            'pageTitle'  => $order['so_no'] . ' - Sales Order',
            'order'      => $order,
            'jobs'       => $jobs,
            'dispatches' => $dispatches,
            'invoices'   => $invoices,
        ]);
    }

    public function printDoc($id)
    {
        $soModel = new SalesOrderModel();
        $order = $soModel->getDetails($id);

        if (!$order) {
            return redirect()->to('/sales-orders')->with('error', 'Sales Order not found.');
        }

        $company = company_settings();

        return view('sales_orders/print', [
            'docTitle' => 'Order Confirmation - ' . $order['so_no'],
            'order'    => $order,
            'company'  => $company,
        ]);
    }

    public function delete($id)
    {
        $soModel = new SalesOrderModel();
        $order = $soModel->find($id);

        if ($order) {
            $soModel->delete($id);
            log_activity('Sales Orders', 'Delete', $id, "Deleted Sales Order {$order['so_no']}");
            return redirect()->to('/sales-orders')->with('success', 'Sales Order deleted.');
        }

        return redirect()->to('/sales-orders')->with('error', 'Sales Order not found.');
    }
}
