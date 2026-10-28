<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-boxes text-primary me-2"></i>Steel Stockyard & Consumables Inventory</h3>
        <p class="text-muted small mb-0">Real-time balances across Yard-A (Plates), Yard-B (SA516), Structural Bays & Chemical Store</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('inventory/adjust') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-sliders me-1"></i> Stock Adjustment
        </a>
        <a href="<?= base_url('inventory/transactions') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-clock-history me-1"></i> Transaction Ledger
        </a>
        <a href="<?= base_url('materials/new') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Material
        </a>
    </div>
</div>

<!-- Stock Highlights -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-widget border-left-steel">
            <i class="bi bi-currency-rupee stat-icon text-primary"></i>
            <div class="stat-title">Total Inventory Valuation</div>
            <div class="stat-value text-primary"><?= format_inr($totalValuation, true, 0) ?></div>
            <div class="stat-subtitle text-muted">Valued at moving weighted average rate</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-widget border-left-power">
            <i class="bi bi-speedometer2 stat-icon text-danger"></i>
            <div class="stat-title">Total Steel Weight in Stock</div>
            <div class="stat-value text-danger"><?= number_format($totalWeightMT, 1) ?> <span style="font-size: 1rem;">MT</span></div>
            <div class="stat-subtitle text-muted">Plates, Beams, Channels & Angles</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-widget border-left-sugar">
            <i class="bi bi-check2-circle stat-icon text-success"></i>
            <div class="stat-title">Active SKUs / Grades</div>
            <div class="stat-value text-success"><?= count($inventory) ?></div>
            <div class="stat-subtitle text-muted">IS 2062, SA 516, ISMB, Seamless Pipes</div>
        </div>
    </div>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-table me-2"></i>Live Warehouse Balances</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>Material Code</th>
                        <th>Material Name</th>
                        <th>Warehouse / Location</th>
                        <th>Rack / Bay</th>
                        <th>Unit</th>
                        <th>Total Stock</th>
                        <th>Reserved</th>
                        <th>Available</th>
                        <th>Avg Rate</th>
                        <th>Stock Value</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $row): ?>
                        <?php 
                            $qty = (float)$row['quantity'];
                            $reserved = (float)$row['reserved_qty'];
                            $available = (float)($row['available_qty'] ?? ($qty - $reserved));
                            $rate = (float)$row['avg_rate'];
                            $val = $qty * $rate;
                            $reorder = (float)$row['reorder_level'];
                            $isLow = ($reorder > 0 && $qty <= $reorder);
                        ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary"><?= esc($row['material_code']) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($row['material_name']) ?></div>
                                <small class="text-muted"><?= esc($row['grade'] ?? '') ?> &bull; <?= esc($row['thickness'] ?? '') ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= esc($row['warehouse'] ?: 'Main Yard') ?></span></td>
                            <td class="font-mono small"><?= esc($row['location_rack'] ?: '-') ?></td>
                            <td class="text-uppercase"><?= esc($row['unit']) ?></td>
                            <td class="font-mono fw-bold <?= $isLow ? 'text-danger' : 'text-dark' ?>">
                                <?= number_format($qty, 1) ?>
                            </td>
                            <td class="font-mono text-muted"><?= number_format($reserved, 1) ?></td>
                            <td class="font-mono fw-bold text-success"><?= number_format($available, 1) ?></td>
                            <td class="font-mono"><?= format_inr($rate) ?></td>
                            <td class="font-mono fw-bold"><?= format_inr($val, true, 0) ?></td>
                            <td>
                                <?php if ($isLow): ?>
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Low Stock</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Adequate</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
