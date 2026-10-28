<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\SalesOrderModel;
use App\Models\ProductionJobModel;
use App\Models\DispatchModel;
use App\Models\InvoiceModel;
use App\Models\InventoryModel;
use App\Models\EnquiryModel;
use App\Models\ActivityLogModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Total Sales Order Value
        $salesOrderValue = $db->table('sales_orders')
                              ->selectSum('grand_total')
                              ->whereNotIn('status', ['cancelled'])
                              ->get()->getRow()->grand_total ?? 0;

        // 2. Active Fabrication Jobs & Tonnage
        $activeJobsCount = $db->table('production_jobs')
                             ->whereNotIn('status', ['completed', 'cancelled'])
                             ->countAllResults();

        $activeJobTonnageKg = $db->table('production_jobs')
                                ->selectSum('total_weight')
                                ->whereNotIn('status', ['completed', 'cancelled'])
                                ->get()->getRow()->total_weight ?? 0;
        $activeJobTonnageMT = $activeJobTonnageKg / 1000;

        // 3. Total Dispatched Tonnage
        $dispatchedKg = $db->table('dispatches')
                           ->selectSum('total_weight')
                           ->whereNotIn('status', ['returned', 'draft'])
                           ->get()->getRow()->total_weight ?? 0;
        $dispatchedMT = $dispatchedKg / 1000;

        // 4. Raw Steel Inventory Valuation
        $inventoryValue = $db->table('inventory')
                             ->select('SUM(quantity * avg_rate) as total_val')
                             ->get()->getRow()->total_val ?? 0;

        // 5. Total Receivables Outstanding
        $receivables = $db->table('invoices')
                          ->selectSum('balance_amount')
                          ->whereNotIn('status', ['paid', 'cancelled', 'draft'])
                          ->get()->getRow()->balance_amount ?? 0;

        // 6. Enquiries Count
        $openEnquiries = $db->table('enquiries')
                            ->whereIn('status', ['new', 'in_review', 'quoted'])
                            ->countAllResults();

        // 7. Industry Breakdown (Power vs Sugar vs Cement)
        $sectorStats = $db->query("
            SELECT 
                c.industry_type,
                COUNT(so.id) as order_count,
                COALESCE(SUM(so.grand_total), 0) as total_value
            FROM clients c
            LEFT JOIN sales_orders so ON so.client_id = c.id AND so.status != 'cancelled'
            GROUP BY c.industry_type
        ")->getResultArray();

        // 8. Active Fabrication Jobs with Items
        $activeJobs = $db->table('production_jobs pj')
                         ->select('pj.*, so.so_no, c.company_name as client_name, c.industry_type')
                         ->join('sales_orders so', 'so.id = pj.sales_order_id', 'left')
                         ->join('clients c', 'c.id = so.client_id', 'left')
                         ->whereNotIn('pj.status', ['completed', 'cancelled'])
                         ->orderBy('pj.id', 'DESC')
                         ->limit(5)
                         ->get()->getResultArray();

        // 9. Low Stock Materials
        $lowStock = $db->query("
            SELECT m.material_code, m.name, m.unit, m.reorder_level, COALESCE(i.quantity, 0) as current_qty
            FROM materials m
            LEFT JOIN inventory i ON i.material_id = m.id
            WHERE m.reorder_level > 0 AND COALESCE(i.quantity, 0) <= m.reorder_level
            ORDER BY (COALESCE(i.quantity, 0) / m.reorder_level) ASC
            LIMIT 5
        ")->getResultArray();

        // 10. Recent Dispatches
        $recentDispatches = $db->table('dispatches d')
                               ->select('d.*, c.company_name as client_name, so.so_no')
                               ->join('clients c', 'c.id = d.client_id')
                               ->join('sales_orders so', 'so.id = d.sales_order_id', 'left')
                               ->orderBy('d.id', 'DESC')
                               ->limit(5)
                               ->get()->getResultArray();

        // 11. Recent Audit Logs
        $activityModel = new ActivityLogModel();
        $recentLogs = $activityModel->getRecent(8);

        return $this->render('dashboard/index', [
            'pageTitle'         => 'Executive Dashboard',
            'salesOrderValue'   => $salesOrderValue,
            'activeJobsCount'   => $activeJobsCount,
            'activeJobTonnageMT'=> $activeJobTonnageMT,
            'dispatchedMT'      => $dispatchedMT,
            'inventoryValue'    => $inventoryValue,
            'receivables'       => $receivables,
            'openEnquiries'     => $openEnquiries,
            'sectorStats'       => $sectorStats,
            'activeJobs'        => $activeJobs,
            'lowStock'          => $lowStock,
            'recentDispatches'  => $recentDispatches,
            'recentLogs'        => $recentLogs,
        ]);
    }
}
