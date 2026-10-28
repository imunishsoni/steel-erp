<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Stock Transaction Audit Ledger</h3>
        <p class="text-muted small mb-0">Complete audit trail of Goods Receipts (GRN), Production Issues, Dispatches, and Adjustments</p>
    </div>
    <a href="<?= base_url('inventory') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-check me-2"></i>Recent Stock Movements</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Material Code</th>
                        <th>Material Name</th>
                        <th>Transaction Type</th>
                        <th>Reference</th>
                        <th>Warehouse</th>
                        <th>Quantity</th>
                        <th>Remarks</th>
                        <th>Authorized By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td><?= format_date($tx['created_at'], 'd M Y, h:i A') ?></td>
                            <td class="font-mono fw-bold text-primary"><?= esc($tx['material_code']) ?></td>
                            <td><?= esc($tx['material_name']) ?></td>
                            <td>
                                <?php if ($tx['transaction_type'] === 'purchase_in' || $tx['transaction_type'] === 'adjustment_in'): ?>
                                    <span class="badge bg-success"><i class="bi bi-arrow-down-left me-1"></i>INWARD</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-arrow-up-right me-1"></i>OUTWARD</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= esc($tx['reference_type'] ?: 'Manual') ?> #<?= esc($tx['reference_id'] ?: '-') ?>
                                </span>
                            </td>
                            <td><?= esc($tx['warehouse']) ?></td>
                            <td class="font-mono fw-bold <?= ($tx['transaction_type'] === 'purchase_in' || $tx['transaction_type'] === 'adjustment_in') ? 'text-success' : 'text-danger' ?>">
                                <?= ($tx['transaction_type'] === 'purchase_in' || $tx['transaction_type'] === 'adjustment_in') ? '+' : '-' ?>
                                <?= number_format($tx['quantity'], 2) ?> <?= esc($tx['unit']) ?>
                            </td>
                            <td class="small text-muted"><?= esc($tx['remarks'] ?: '-') ?></td>
                            <td><?= esc($tx['user_name'] ?? 'System') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
