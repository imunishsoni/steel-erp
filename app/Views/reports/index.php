<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Enterprise Analytics & Compliance Reports</h4>
        <small class="text-muted">Sector breakdown (Power, Sugar, Cement), GSTR-1 register & steel stock valuation</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-printer me-1"></i>Print Report</button>
    </div>
</div>

<!-- Executive KPIs -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-primary">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Order Book Value</div>
                <div class="fs-4 fw-bold text-primary"><?= format_inr($totalOrderBookValue) ?></div>
                <div class="text-muted small">Power, Sugar & Cement orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-warning">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Order Book Tonnage</div>
                <div class="fs-4 fw-bold text-warning"><?= number_format($totalTonnageOrdered, 1) ?> <span class="fs-6">MT</span></div>
                <div class="text-muted small">Fabricated steel in pipeline</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-info">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Raw Steel Yard Stock</div>
                <div class="fs-4 fw-bold text-info"><?= format_inr($totalStockValue) ?></div>
                <div class="text-muted small">Current yard inventory valuation</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-success">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total GST Output Liability</div>
                <div class="fs-4 fw-bold text-success"><?= format_inr($totalGstBilled) ?></div>
                <div class="text-muted small">CGST + SGST + IGST billed</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs for Analytical Views -->
<ul class="nav nav-pills mb-3" id="reportsTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" id="sector-tab" data-bs-toggle="pill" data-bs-target="#sector-pane">
            <i class="bi bi-pie-chart me-1"></i>Sector Breakdown (Power / Sugar / Cement)
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="stock-tab" data-bs-toggle="pill" data-bs-target="#stock-pane">
            <i class="bi bi-boxes me-1"></i>Yard Steel Valuation
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="gstr1-tab" data-bs-toggle="pill" data-bs-target="#gstr1-pane">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>GSTR-1 Sales Register (Outward Tax)
        </button>
    </li>
</ul>

<div class="tab-content" id="reportsTabContent">
    <!-- Tab 1: Sector Breakdown -->
    <div class="tab-pane fade show active" id="sector-pane">
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-buildings me-2"></i>Order Book & Tonnage by Industry Sector</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>Target Industry Sector</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Tonnage (MT)</th>
                                    <th class="text-end">Total Contract Value</th>
                                    <th class="text-end">% Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sectorBreakdown)): foreach ($sectorBreakdown as $row): 
                                    $pct = $totalOrderBookValue > 0 ? ($row['total_val'] / $totalOrderBookValue) * 100 : 0;
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= plant_type_badge($row['industry_type']) ?></div>
                                    </td>
                                    <td class="text-center"><?= $row['order_count'] ?></td>
                                    <td class="text-end fw-bold"><?= number_format($row['total_mt'], 2) ?></td>
                                    <td class="text-end fw-bold"><?= format_inr($row['total_val']) ?></td>
                                    <td class="text-end">
                                        <div class="fw-semibold small"><?= number_format($pct, 1) ?>%</div>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-primary" style="width: <?= $pct ?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-pie-chart-fill me-2"></i>Order Share by Sector</span>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="max-height: 260px; width: 100%;">
                            <canvas id="sectorChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Stock Valuation -->
    <div class="tab-pane fade" id="stock-pane">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-stack me-2"></i>Raw Material Inventory Valuation by Category</span>
            </div>
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Category</th>
                            <th class="text-center">Stock Items</th>
                            <th class="text-end">Yard Balance (MT / Qty)</th>
                            <th class="text-end">Valuation (INR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stockValuation)): foreach ($stockValuation as $row): ?>
                        <tr>
                            <td class="fw-bold"><?= esc($row['category_name'] ?: 'General Steel') ?></td>
                            <td class="text-center"><?= $row['item_count'] ?></td>
                            <td class="text-end fw-bold"><?= number_format($row['total_qty'], 3) ?></td>
                            <td class="text-end fw-bold text-primary"><?= format_inr($row['total_value']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-light fw-bold">
                            <td>Total Inventory Valuation:</td>
                            <td class="text-center"><?= array_sum(array_column($stockValuation, 'item_count')) ?></td>
                            <td class="text-end"><?= number_format(array_sum(array_column($stockValuation, 'total_qty')), 3) ?></td>
                            <td class="text-end text-success fs-6"><?= format_inr($totalStockValue) ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 3: GSTR-1 Sales Register -->
    <div class="tab-pane fade" id="gstr1-pane">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-file-earmark-ruled me-2"></i>GSTR-1 Outward B2B Invoices Statement</span>
            </div>
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle datatable w-100">
                    <thead class="table-light small">
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Recipient Name</th>
                            <th>GSTIN</th>
                            <th>Place of Supply</th>
                            <th class="text-end">Taxable Value (₹)</th>
                            <th class="text-end">CGST (₹)</th>
                            <th class="text-end">SGST (₹)</th>
                            <th class="text-end">IGST (₹)</th>
                            <th class="text-end">Total Invoice (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($gstr1)): foreach ($gstr1 as $inv): ?>
                        <tr>
                            <td><code><?= esc($inv['invoice_no']) ?></code></td>
                            <td><?= date('d-m-Y', strtotime($inv['invoice_date'])) ?></td>
                            <td><strong><?= esc($inv['client_name']) ?></strong></td>
                            <td><code><?= esc($inv['client_gstin'] ?: 'Unregistered') ?></code></td>
                            <td><?= esc($inv['place_of_supply']) ?></td>
                            <td class="text-end"><?= format_inr($inv['taxable_amount']) ?></td>
                            <td class="text-end"><?= format_inr($inv['cgst_amount']) ?></td>
                            <td class="text-end"><?= format_inr($inv['sgst_amount']) ?></td>
                            <td class="text-end"><?= format_inr($inv['igst_amount']) ?></td>
                            <td class="text-end fw-bold"><?= format_inr($inv['grand_total']) ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('sectorChart');
    if (ctx) {
        const labels = <?= json_encode(array_map(fn($r) => ucwords(str_replace('_', ' ', $r['industry_type'])), $sectorBreakdown)) ?>;
        const data = <?= json_encode(array_map(fn($r) => floatval($r['total_val']), $sectorBreakdown)) ?>;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#0d6efd', '#ffc107', '#198754', '#0dcaf0', '#6c757d', '#6610f2'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
