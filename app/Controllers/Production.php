<?php

namespace App\Controllers;

use App\Models\ProductionJobModel;
use App\Models\ProductionJobItemModel;
use App\Models\MaterialIssueModel;
use App\Models\SalesOrderModel;
use App\Models\MaterialModel;
use App\Models\InventoryModel;
use App\Models\StockTransactionModel;
use App\Models\UserModel;

class Production extends BaseController
{
    protected $jobModel;
    protected $itemModel;
    protected $issueModel;

    public function __construct()
    {
        $this->jobModel = new ProductionJobModel();
        $this->itemModel = new ProductionJobItemModel();
        $this->issueModel = new MaterialIssueModel();
    }

    public function index()
    {
        $jobs = $this->jobModel->getWithRelations();

        $stats = [
            'total_jobs'    => count($jobs),
            'in_progress'   => count(array_filter($jobs, fn($j) => $j['status'] === 'in_progress')),
            'total_weight'  => array_sum(array_column($jobs, 'total_weight')),
            'completed'     => count(array_filter($jobs, fn($j) => $j['status'] === 'completed')),
        ];

        return $this->render('production/index', [
            'pageTitle' => 'Shop Floor Production & Job Tracking',
            'jobs'      => $jobs,
            'stats'     => $stats,
        ]);
    }

    public function create()
    {
        $soModel = new SalesOrderModel();
        $salesOrders = $soModel->whereIn('status', ['confirmed', 'in_production'])->findAll();

        $userModel = new UserModel();
        $engineers = $userModel->where('is_active', 1)->findAll();

        // Generate next Job number: JOB-YYMM-001
        $prefix = 'JOB-' . date('ym') . '-';
        $latest = $this->jobModel->like('job_no', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $nextSeq = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['job_no'], $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        }
        $autoJobNo = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $this->render('production/form', [
            'pageTitle'   => 'Create Fabrication Job Order',
            'job'         => null,
            'salesOrders' => $salesOrders,
            'engineers'   => $engineers,
            'autoJobNo'   => $autoJobNo,
        ]);
    }

    public function store()
    {
        $rules = [
            'job_no'       => 'required|min_length[3]|is_unique[production_jobs.job_no]',
            'project_name' => 'required|min_length[3]',
            'target_date'  => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $totalWeight = floatval($this->request->getPost('total_weight') ?? 0);

        $jobData = [
            'job_no'         => $this->request->getPost('job_no'),
            'job_date'       => $this->request->getPost('job_date') ?: date('Y-m-d'),
            'sales_order_id' => $this->request->getPost('sales_order_id') ?: null,
            'project_name'   => $this->request->getPost('project_name'),
            'description'    => $this->request->getPost('description'),
            'start_date'     => $this->request->getPost('start_date') ?: date('Y-m-d'),
            'target_date'    => $this->request->getPost('target_date'),
            'priority'       => $this->request->getPost('priority') ?: 'medium',
            'status'         => 'planned',
            'total_weight'   => $totalWeight,
            'completion_pct' => 0,
            'assigned_to'    => $this->request->getPost('assigned_to') ?: null,
            'remarks'        => $this->request->getPost('remarks'),
            'created_by'     => session()->get('user_id'),
        ];

        $this->jobModel->insert($jobData);
        $jobId = $this->jobModel->getInsertID();

        // Insert job items / sub-assemblies if provided
        $descriptions = $this->request->getPost('item_description') ?? [];
        $drawings = $this->request->getPost('item_drawing_no') ?? [];
        $quantities = $this->request->getPost('item_quantity') ?? [];
        $weights = $this->request->getPost('item_weight_each') ?? [];
        $processes = $this->request->getPost('item_process') ?? [];

        foreach ($descriptions as $idx => $desc) {
            if (!empty(trim($desc))) {
                $qty = floatval($quantities[$idx] ?? 1);
                $wEach = floatval($weights[$idx] ?? 0);
                $this->itemModel->insert([
                    'job_id'        => $jobId,
                    'description'   => trim($desc),
                    'drawing_no'    => $drawings[$idx] ?? '',
                    'quantity'      => $qty,
                    'unit'          => 'PCS',
                    'weight_each'   => $wEach,
                    'total_weight'  => $qty * $wEach,
                    'process'       => $processes[$idx] ?? 'Cutting',
                    'status'        => 'pending',
                    'completed_qty' => 0,
                ]);
            }
        }

        // If sales order linked, update SO status to in_production
        if (!empty($jobData['sales_order_id'])) {
            $soModel = new SalesOrderModel();
            $soModel->update($jobData['sales_order_id'], ['status' => 'in_production']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create Job Order. Please check inputs.');
        }

        log_activity('Production', 'Created Fabrication Job Order ' . $jobData['job_no']);
        return redirect()->to('/production/view/' . $jobId)->with('success', 'Job Order ' . $jobData['job_no'] . ' created successfully.');
    }

    public function view($id)
    {
        $job = $this->jobModel->getDetails($id);
        if (!$job) {
            return redirect()->to('/production')->with('error', 'Job Order not found.');
        }

        $userModel = new UserModel();
        $engineers = $userModel->where('is_active', 1)->findAll();

        return $this->render('production/view', [
            'pageTitle' => $job['job_no'] . ' - Fabrication Job Card',
            'job'       => $job,
            'engineers' => $engineers,
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $completionPct = floatval($this->request->getPost('completion_pct') ?? 0);

        $updateData = [
            'status'         => $status,
            'completion_pct' => min(100, max(0, $completionPct)),
        ];

        if ($status === 'completed') {
            $updateData['actual_end_date'] = date('Y-m-d');
            $updateData['completion_pct'] = 100;
        }

        $this->jobModel->update($id, $updateData);
        log_activity('Production', "Updated Job ID {$id} status to {$status} ({$completionPct}%)");

        return redirect()->to('/production/view/' . $id)->with('success', 'Job status and progress updated.');
    }

    public function updateItemProgress($itemId)
    {
        $item = $this->itemModel->find($itemId);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $completedQty = floatval($this->request->getPost('completed_qty'));
        $processStatus = $this->request->getPost('status');

        $this->itemModel->update($itemId, [
            'completed_qty' => $completedQty,
            'status'        => $processStatus,
        ]);

        return redirect()->to('/production/view/' . $item['job_id'])->with('success', 'Process item progress updated.');
    }

    public function issues()
    {
        $issues = $this->issueModel->getWithRelations();

        return $this->render('production/issues_list', [
            'pageTitle' => 'Raw Steel Material Requisitions & Issues',
            'issues'    => $issues,
        ]);
    }

    public function issueMaterial($jobId = null)
    {
        $materialModel = new MaterialModel();
        $materials = $materialModel->getWithCategory();

        $jobs = $this->jobModel->whereIn('status', ['planned', 'in_progress'])->findAll();

        // Auto issue number: ISS-YYMM-001
        $prefix = 'ISS-' . date('ym') . '-';
        $latest = $this->issueModel->like('issue_no', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $nextSeq = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['issue_no'], $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        }
        $autoIssueNo = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $this->render('production/issue_material', [
            'pageTitle'   => 'Issue Raw Material to Job Order',
            'selectedJob' => $jobId,
            'jobs'        => $jobs,
            'materials'   => $materials,
            'autoIssueNo' => $autoIssueNo,
        ]);
    }

    public function storeMaterialIssue()
    {
        $rules = [
            'issue_no'    => 'required',
            'job_id'      => 'required|is_not_unique[production_jobs.id]',
            'material_id' => 'required|is_not_unique[materials.id]',
            'quantity'    => 'required|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $materialId = $this->request->getPost('material_id');
        $qty = floatval($this->request->getPost('quantity'));
        $jobId = $this->request->getPost('job_id');

        $matModel = new MaterialModel();
        $material = $matModel->find($materialId);

        $invModel = new InventoryModel();
        $inv = $invModel->where('material_id', $materialId)->first();

        if (!$inv || $inv['quantity'] < $qty) {
            $currQty = $inv ? $inv['quantity'] : 0;
            return redirect()->back()->withInput()->with('error', "Insufficient stock for {$material['name']}. Available in yard: {$currQty} {$material['unit']}. Requested: {$qty}");
        }

        // Insert issue record
        $issueData = [
            'issue_no'    => $this->request->getPost('issue_no'),
            'issue_date'  => $this->request->getPost('issue_date') ?: date('Y-m-d'),
            'job_id'      => $jobId,
            'material_id' => $materialId,
            'quantity'    => $qty,
            'unit'        => $material['unit'] ?? 'MT',
            'rate'        => $material['last_purchase_rate'] ?? 0,
            'remarks'     => $this->request->getPost('remarks'),
            'issued_by'   => session()->get('user_id'),
        ];
        $this->issueModel->insert($issueData);

        // Deduct inventory
        $newQty = $inv['quantity'] - $qty;
        $invModel->update($inv['id'], ['quantity' => $newQty]);

        // Record stock transaction
        $txModel = new StockTransactionModel();
        $txModel->insert([
            'material_id'      => $materialId,
            'transaction_type' => 'issue',
            'quantity'         => $qty,
            'unit_rate'        => $material['last_purchase_rate'] ?? 0,
            'total_value'      => $qty * ($material['last_purchase_rate'] ?? 0),
            'reference_type'   => 'production_job',
            'reference_id'     => $jobId,
            'notes'            => 'Issued to ' . $issueData['issue_no'] . ' for Job #' . $jobId,
            'created_by'       => session()->get('user_id'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Database transaction failed while issuing material.');
        }

        log_activity('Production', "Issued {$qty} {$material['unit']} of {$material['name']} to Job #{$jobId}");
        return redirect()->to('/production/view/' . $jobId)->with('success', "Issued {$qty} {$material['unit']} of steel to Job Card.");
    }
}
