<?php

namespace App\Controllers;

use App\Models\SalesOrderModel;
use App\Models\ProductionJobModel;
use App\Models\InvoiceModel;
use App\Models\InventoryModel;

class Reports extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Sector breakdown (Power, Sugar, Cement)
        $sectorBreakdown = $db->query("
            SELECT clients.industry_type, 
                   COUNT(DISTINCT sales_orders.id) as order_count, 
                   ROUND(COALESCE(SUM(CASE WHEN so_items.unit = 'kg' THEN so_items.quantity / 1000 ELSE so_items.quantity END), 0), 2) as total_mt, 
                   SUM(DISTINCT sales_orders.grand_total) as total_val
            FROM sales_orders
            JOIN clients ON clients.id = sales_orders.client_id
            LEFT JOIN sales_order_items so_items ON so_items.sales_order_id = sales_orders.id
            GROUP BY clients.industry_type
        ")->getResultArray();

        // 2. Monthly Sales Trend (Last 6 months)
        $monthlySales = $db->query("
            SELECT DATE_FORMAT(invoice_date, '%Y-%m') as ym,
                   DATE_FORMAT(invoice_date, '%b %Y') as month,
                   SUM(taxable_amount) as taxable,
                   SUM(total_tax) as tax,
                   SUM(grand_total) as grand_total
            FROM invoices
            GROUP BY DATE_FORMAT(invoice_date, '%Y-%m'), DATE_FORMAT(invoice_date, '%b %Y')
            ORDER BY ym ASC
            LIMIT 6
        ")->getResultArray();

        // 3. Raw Steel Yard Valuation
        $stockValuation = $db->query("
            SELECT material_categories.name as category_name, 
                   COUNT(inventory.id) as item_count, 
                   SUM(inventory.quantity) as total_qty, 
                   SUM(inventory.quantity * COALESCE(NULLIF(inventory.avg_rate, 0), materials.last_purchase_rate, 0)) as total_value
            FROM inventory
            JOIN materials ON materials.id = inventory.material_id
            LEFT JOIN material_categories ON material_categories.id = materials.category_id
            GROUP BY material_categories.name
        ")->getResultArray();

        // 4. GSTR-1 Sales Register
        $gstr1 = $db->table('invoices')
            ->select('invoices.*, clients.company_name as client_name, clients.gstin as client_gstin, clients.state as client_state')
            ->join('clients', 'clients.id = invoices.client_id')
            ->orderBy('invoices.invoice_date', 'DESC')
            ->get()->getResultArray();

        // Summary KPI numbers
        $totalTonnageOrdered = array_sum(array_column($sectorBreakdown, 'total_mt'));
        $totalOrderBookValue = array_sum(array_column($sectorBreakdown, 'total_val'));
        $totalStockValue = array_sum(array_column($stockValuation, 'total_value'));
        $totalGstBilled = array_sum(array_column($gstr1, 'total_tax'));

        return $this->render('reports/index', [
            'pageTitle'           => 'Enterprise Fabrication & Financial Intelligence',
            'sectorBreakdown'     => $sectorBreakdown,
            'monthlySales'        => $monthlySales,
            'stockValuation'      => $stockValuation,
            'gstr1'               => $gstr1,
            'totalTonnageOrdered' => $totalTonnageOrdered,
            'totalOrderBookValue' => $totalOrderBookValue,
            'totalStockValue'     => $totalStockValue,
            'totalGstBilled'      => $totalGstBilled,
        ]);
    }
}
