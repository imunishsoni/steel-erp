<?php

namespace App\Controllers;

use App\Models\DispatchModel;
use App\Models\DispatchItemModel;
use App\Models\SalesOrderModel;
use App\Models\ClientModel;

class Dispatches extends BaseController
{
    protected $dispatchModel;
    protected $itemModel;

    public function __construct()
    {
        $this->dispatchModel = new DispatchModel();
        $this->itemModel = new DispatchItemModel();
    }

    public function index()
    {
        $dispatches = $this->dispatchModel->getWithRelations();

        $stats = [
            'total'       => count($dispatches),
            'dispatched'  => count(array_filter($dispatches, fn($d) => in_array($d['status'], ['dispatched', 'in_transit']))),
            'delivered'   => count(array_filter($dispatches, fn($d) => $d['status'] === 'delivered')),
            'total_mt'    => array_sum(array_column($dispatches, 'total_weight')),
        ];

        return $this->render('dispatches/index', [
            'pageTitle'  => 'Delivery Challans & Dispatch Logistics',
            'dispatches' => $dispatches,
            'stats'      => $stats,
        ]);
    }

    public function create($soId = null)
    {
        $soId = $soId ?: $this->request->getGet('so_id');
        $soModel = new SalesOrderModel();
        $salesOrders = $soModel->whereIn('status', ['confirmed', 'in_production', 'ready_for_dispatch', 'completed'])->findAll();

        $clientModel = new ClientModel();
        $clients = $clientModel->where('is_active', 1)->orderBy('company_name', 'ASC')->findAll();

        $selectedSO = null;
        if ($soId) {
            $selectedSO = $soModel->getDetails($soId);
        }

        // Auto DC number: DC-YYMM-001
        $prefix = 'DC-' . date('ym') . '-';
        $latest = $this->dispatchModel->like('dc_no', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $nextSeq = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['dc_no'], $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        }
        $autoDcNo = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $this->render('dispatches/form', [
            'pageTitle'   => 'New Delivery Challan (DC) & Gate Pass',
            'salesOrders' => $salesOrders,
            'clients'     => $clients,
            'selectedSO'  => $selectedSO,
            'autoDcNo'    => $autoDcNo,
        ]);
    }

    public function new($soId = null)
    {
        return $this->create($soId);
    }

    public function store()
    {
        $rules = [
            'dc_no'      => 'required|is_unique[dispatches.dc_no]',
            'dc_date'    => 'required|valid_date',
            'client_id'  => 'required|is_not_unique[clients.id]',
            'vehicle_no' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $totalWeight = floatval($this->request->getPost('total_weight') ?? 0);

        $dispatchData = [
            'dc_no'            => $this->request->getPost('dc_no'),
            'dc_date'          => $this->request->getPost('dc_date'),
            'sales_order_id'   => $this->request->getPost('sales_order_id') ?: null,
            'client_id'        => $this->request->getPost('client_id'),
            'delivery_address' => $this->request->getPost('delivery_address'),
            'transporter'      => $this->request->getPost('transporter'),
            'vehicle_no'       => strtoupper(trim($this->request->getPost('vehicle_no'))),
            'driver_name'      => $this->request->getPost('driver_name'),
            'driver_phone'     => $this->request->getPost('driver_phone'),
            'lr_no'            => $this->request->getPost('lr_no'),
            'lr_date'          => $this->request->getPost('lr_date') ?: null,
            'eway_bill_no'     => $this->request->getPost('eway_bill_no'),
            'total_weight'     => $totalWeight,
            'no_of_packages'   => intval($this->request->getPost('no_of_packages') ?? 1),
            'remarks'          => $this->request->getPost('remarks'),
            'status'           => 'dispatched',
            'created_by'       => session()->get('user_id'),
        ];

        $this->dispatchModel->insert($dispatchData);
        $dispatchId = $this->dispatchModel->getInsertID();

        // Insert items
        $descriptions = $this->request->getPost('item_description') ?? [];
        $quantities = $this->request->getPost('item_quantity') ?? [];
        $units = $this->request->getPost('item_unit') ?? [];
        $weights = $this->request->getPost('item_weight') ?? [];
        $itemRemarks = $this->request->getPost('item_remarks') ?? [];

        foreach ($descriptions as $idx => $desc) {
            if (!empty(trim($desc))) {
                $this->itemModel->insert([
                    'dispatch_id' => $dispatchId,
                    'description' => trim($desc),
                    'quantity'    => floatval($quantities[$idx] ?? 1),
                    'unit'        => $units[$idx] ?? 'MT',
                    'weight'      => floatval($weights[$idx] ?? 0),
                    'remarks'     => $itemRemarks[$idx] ?? '',
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to generate Delivery Challan.');
        }

        log_activity('Dispatch', 'Generated Delivery Challan ' . $dispatchData['dc_no']);
        return redirect()->to('/dispatches/view/' . $dispatchId)->with('success', 'Delivery Challan ' . $dispatchData['dc_no'] . ' created successfully.');
    }

    public function view($id)
    {
        $dispatch = $this->dispatchModel->getDetails($id);
        if (!$dispatch) {
            return redirect()->to('/dispatches')->with('error', 'Delivery Challan not found.');
        }

        return $this->render('dispatches/view', [
            'pageTitle' => $dispatch['dc_no'] . ' - Delivery Challan',
            'dispatch'  => $dispatch,
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $update = ['status' => $status];
        if ($status === 'delivered') {
            $update['delivered_date'] = date('Y-m-d');
        }

        $this->dispatchModel->update($id, $update);
        log_activity('Dispatch', "Updated Dispatch ID {$id} status to {$status}");

        return redirect()->to('/dispatches/view/' . $id)->with('success', 'Dispatch status updated.');
    }

    public function print($id)
    {
        $dispatch = $this->dispatchModel->getDetails($id);
        if (!$dispatch) {
            return redirect()->to('/dispatches')->with('error', 'Delivery Challan not found.');
        }

        return view('dispatches/print', [
            'pageTitle' => 'Print Delivery Challan - ' . $dispatch['dc_no'],
            'dispatch'  => $dispatch,
        ]);
    }
}
