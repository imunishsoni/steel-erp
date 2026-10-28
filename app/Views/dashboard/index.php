<!-- Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold text-dark">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Executive Plant Dashboard
        </h3>
        <p class="text-muted small mb-0">Heavy Steel Fabrication Operations &bull; Financial Year 2026-27 &bull; Jaipur Works</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="<?= base_url('enquiries/new') ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New Enquiry</a>
        <a href="<?= base_url('quotations/new') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-earmark-plus me-1"></i>New Quotation</a>
        <a href="<?= base_url('production/new') ?>" class="btn btn-primary btn-sm"><i class="bi bi-gear-fill me-1"></i>New Job Order</a>
    </div>
</div>

<!-- TOP KPI WIDGETS -->
<div class="row g-3 mb-4">
    <!-- Total Sales Book -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-widget border-left-steel">
            <i class="bi bi-currency-rupee stat-icon text-primary"></i>
            <div class="stat-title">Active Order Book (SO)</div>
            <div class="stat-value text-primary"><?= format_inr($salesOrderValue, true, 0) ?></div>
            <div class="stat-subtitle text-muted">Confirmed fabrication contracts</div>
        </div>
    </div>

    <!-- Active Production Tonnage -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-widget border-left-power">
            <i class="bi bi-hammer stat-icon text-danger"></i>
            <div class="stat-title">Shop Floor In-Progress</div>
            <div class="stat-value text-danger"><?= number_format($activeJobTonnageMT, 1) ?> <span style="font-size: 1rem; font-weight: normal;">MT</span></div>
            <div class="stat-subtitle text-muted"><?= $activeJobsCount ?> fabrication jobs on shop floor</div>
        </div>
    </div>

    <!-- Total Dispatched Tonnage -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-widget border-left-sugar">
            <i class="bi bi-truck stat-icon text-success"></i>
            <div class="stat-title">Dispatched Consignments</div>
            <div class="stat-value text-success"><?= number_format($dispatchedMT, 1) ?> <span style="font-size: 1rem; font-weight: normal;">MT</span></div>
            <div class="stat-subtitle text-muted">Supplied to plant erection sites</div>
        </div>
    </div>

    <!-- Inventory Valuation -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-widget border-left-cement">
            <i class="bi bi-boxes stat-icon text-warning"></i>
            <div class="stat-title">Steel Yard Stock Valuation</div>
            <div class="stat-value text-warning-emphasis"><?= format_inr($inventoryValue, true, 0) ?></div>
            <div class="stat-subtitle text-muted">Plates, Beams, Pipes & Electrodes</div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-3 mb-4">
    <!-- Industry Breakdown Donut Chart -->
    <div class="col-lg-5">
        <div class="steel-card h-100">
            <div class="steel-card-header">
                <span class="fw-bold"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Order Book by Plant Sector</span>
                <span class="badge bg-light text-dark">Power / Sugar / Cement</span>
            </div>
            <div class="p-3">
                <div style="height: 250px;">
                    <canvas id="sectorChart"></canvas>
                </div>
                <div class="mt-3 pt-3 border-top small">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-muted"><i class="bi bi-lightning-charge-fill text-danger me-1"></i>Power</div>
                            <strong class="text-dark">₹ 90.2 L (66%)</strong>
                        </div>
                        <div class="col-4">
                            <div class="text-muted"><i class="bi bi-buildings-fill text-warning me-1"></i>Cement</div>
                            <strong class="text-dark">₹ 46.0 L (34%)</strong>
                        </div>
                        <div class="col-4">
                            <div class="text-muted"><i class="bi bi-cup-hot-fill text-success me-1"></i>Sugar</div>
                            <strong class="text-dark">₹ 41.3 L (Pipeline)</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Fabrication & Dispatch Bar Chart -->
    <div class="col-lg-7">
        <div class="steel-card h-100">
            <div class="steel-card-header">
                <span class="fw-bold"><i class="bi bi-bar-chart-fill me-2 text-success"></i>Monthly Dispatch & Fabrication Tonnage (MT)</span>
                <span class="badge bg-primary">FY 2026-27</span>
            </div>
            <div class="p-3">
                <div style="height: 250px;">
                    <canvas id="tonnageChart"></canvas>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between small text-muted">
                    <span><i class="bi bi-dot text-primary fs-5"></i>Planned Production Target: <strong>120 MT/month</strong></span>
                    <span><i class="bi bi-dot text-success fs-5"></i>Current Capacity Utilization: <strong>82%</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JOBS AND STOCK ALERTS ROW -->
<div class="row g-3 mb-4">
    <!-- Active Fabrication Jobs -->
    <div class="col-lg-8">
        <div class="steel-card h-100">
            <div class="steel-card-header">
                <span class="fw-bold"><i class="bi bi-gear-wide-connected me-2 text-primary"></i>Active Shop Floor Fabrication Jobs</span>
                <a href="<?= base_url('production') ?>" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;">View All Jobs</a>
            </div>
            <div class="table-responsive">
                <table class="table table-steel mb-0">
                    <thead>
                        <tr>
                            <th>Job Order</th>
                            <th>Project & Client</th>
                            <th>Sector</th>
                            <th>Weight</th>
                            <th>Progress</th>
                            <th>Target Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($activeJobs)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">No active jobs found</td></tr>
                        <?php else: ?>
                            <?php foreach ($activeJobs as $job): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('production/view/' . $job['id']) ?>" class="fw-bold text-decoration-none text-primary">
                                            <?= esc($job['job_no']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 200px;"><?= esc($job['project_name']) ?></div>
                                        <small class="text-muted"><?= esc($job['client_name'] ?? 'In-House Job') ?></small>
                                    </td>
                                    <td><?= plant_type_badge($job['industry_type'] ?? 'other') ?></td>
                                    <td class="font-mono"><?= number_format($job['total_weight'] / 1000, 1) ?> MT</td>
                                    <td style="min-width: 120px;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= (float)$job['completion_pct'] ?>%"></div>
                                            </div>
                                            <small class="text-muted fw-bold"><?= (int)$job['completion_pct'] ?>%</small>
                                        </div>
                                    </td>
                                    <td><?= format_date($job['target_date']) ?></td>
                                    <td><?= status_badge($job['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Low Stock Material Alerts -->
    <div class="col-lg-4">
        <div class="steel-card h-100">
            <div class="steel-card-header bg-danger bg-opacity-10 border-danger border-opacity-25">
                <span class="fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Stock Reorder Alerts</span>
                <a href="<?= base_url('inventory') ?>" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.75rem;">Yard Inventory</a>
            </div>
            <div class="p-3">
                <?php if (empty($lowStock)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle text-success fs-1 mb-2 d-block"></i>
                        All raw steel and consumables are above reorder levels.
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($lowStock as $mat): ?>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.85rem;"><?= esc($mat['name']) ?></div>
                                    <small class="text-muted font-mono"><?= esc($mat['material_code']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-danger"><?= number_format($mat['current_qty']) ?> <?= esc($mat['unit']) ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Min: <?= number_format($mat['reorder_level']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="mt-3 pt-3 border-top text-center">
                    <a href="<?= base_url('purchase-orders/new') ?>" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-cart-plus me-1"></i> Create Procurement PO
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT DISPATCHES & AUDIT TRAIL ROW -->
<div class="row g-3">
    <!-- Recent Dispatches -->
    <div class="col-lg-7">
        <div class="steel-card h-100">
            <div class="steel-card-header">
                <span class="fw-bold"><i class="bi bi-truck me-2 text-success"></i>Recent Gate Passes & Dispatches</span>
                <a href="<?= base_url('dispatches') ?>" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.75rem;">All Dispatches</a>
            </div>
            <div class="table-responsive">
                <table class="table table-steel mb-0">
                    <thead>
                        <tr>
                            <th>DC No</th>
                            <th>Client & Destination</th>
                            <th>Vehicle / Trailer</th>
                            <th>Weight</th>
                            <th>E-Way Bill</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentDispatches)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-3">No dispatches found</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentDispatches as $dc): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('dispatches/view/' . $dc['id']) ?>" class="fw-bold text-decoration-none">
                                            <?= esc($dc['dc_no']) ?>
                                        </a>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?= format_date($dc['dc_date']) ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 180px;"><?= esc($dc['client_name']) ?></div>
                                        <small class="text-muted text-truncate d-block" style="max-width: 180px;"><?= esc($dc['delivery_address']) ?></small>
                                    </td>
                                    <td class="font-mono"><?= esc($dc['vehicle_no']) ?></td>
                                    <td class="font-mono"><?= number_format($dc['total_weight'] / 1000, 1) ?> MT</td>
                                    <td class="font-mono small"><?= esc($dc['eway_bill_no']) ?></td>
                                    <td><?= status_badge($dc['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Audit Logs -->
    <div class="col-lg-5">
        <div class="steel-card h-100">
            <div class="steel-card-header">
                <span class="fw-bold"><i class="bi bi-activity me-2 text-info"></i>System Activity Trail</span>
                <span class="badge bg-light text-muted">Audit</span>
            </div>
            <div class="p-3">
                <div class="list-group list-group-flush" style="font-size: 0.82rem;">
                    <?php if (empty($recentLogs)): ?>
                        <div class="text-muted text-center py-3">No activity recorded yet</div>
                    <?php else: ?>
                        <?php foreach ($recentLogs as $log): ?>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-dark"><?= esc($log['user_name'] ?? 'System') ?></span>
                                    <span class="text-muted" style="font-size: 0.7rem;"><?= format_date($log['created_at'], 'd M, h:i A') ?></span>
                                </div>
                                <div class="text-muted">
                                    <span class="badge bg-secondary me-1"><?= esc($log['module']) ?></span>
                                    <?= esc($log['description']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Sector Chart
    const sectorCtx = document.getElementById('sectorChart').getContext('2d');
    new Chart(sectorCtx, {
        type: 'doughnut',
        data: {
            labels: ['Power Plant (NTPC/Adani)', 'Cement Plant (UltraTech/Shree)', 'Sugar Mill (Bajaj/Renuka)'],
            datasets: [{
                data: [90.2, 46.0, 41.3],
                backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            },
            cutout: '65%'
        }
    });

    // 2. Monthly Tonnage Chart
    const tonnageCtx = document.getElementById('tonnageChart').getContext('2d');
    new Chart(tonnageCtx, {
        type: 'bar',
        data: {
            labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct (Current)'],
            datasets: [
                {
                    label: 'Fabricated (MT)',
                    data: [65, 82, 95, 88, 110, 130],
                    backgroundColor: '#1e3a5f',
                    borderRadius: 4
                },
                {
                    label: 'Dispatched (MT)',
                    data: [50, 75, 80, 85, 98, 105],
                    backgroundColor: '#10b981',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Metric Tonnes (MT)' },
                    grid: { color: '#f1f5f9' }
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
});
</script>
