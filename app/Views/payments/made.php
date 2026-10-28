<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-wallet2 me-2 text-danger"></i>Vendor Payments & Steel Mill Disbursals</h4>
        <small class="text-muted">Outward payments to steel mills, welding electrode suppliers and transport contractors</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('purchase-orders') ?>" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-cart3 me-1"></i>Purchase Orders</a>
        <a href="<?= base_url('payments/record-payment') ?>" class="btn btn-danger btn-sm">
            <i class="bi bi-plus-lg me-1"></i>New Payment Voucher
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-start-danger">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Disbursals</div>
                <div class="fs-3 fw-bold text-danger"><?= format_inr($stats['total_paid']) ?></div>
                <div class="text-muted small">Paid to vendors & mills</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-start-warning">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">TDS Deducted (Sec 194C / 194Q)</div>
                <div class="fs-3 fw-bold text-warning"><?= format_inr($stats['total_tds']) ?></div>
                <div class="text-muted small">Deposited to IT Department</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-start-primary">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Vouchers Processed</div>
                <div class="fs-3 fw-bold"><?= $stats['count'] ?></div>
                <div class="text-muted small">Total vendor remittances</div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-bold"><i class="bi bi-journal-text me-2"></i>Vendor Disbursals Register</span>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable w-100">
            <thead class="table-light small">
                <tr>
                    <th>Voucher No</th>
                    <th>Date</th>
                    <th>Vendor / Mill</th>
                    <th>Ref PO</th>
                    <th>Mode</th>
                    <th>Bank & UTR No</th>
                    <th class="text-end">TDS (₹)</th>
                    <th class="text-end">Paid Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payments)): foreach ($payments as $p): ?>
                <tr>
                    <td><code><?= esc($p['payment_no']) ?></code></td>
                    <td><?= date('d-m-Y', strtotime($p['payment_date'])) ?></td>
                    <td><strong><?= esc($p['vendor_company']) ?></strong></td>
                    <td>
                        <?php if ($p['po_no']): ?>
                            <a href="<?= base_url('purchase-orders/view/' . $p['purchase_order_id']) ?>" class="badge bg-light text-primary border text-decoration-none">
                                <?= esc($p['po_no']) ?>
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border">Direct Expense / Advance</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-secondary"><?= strtoupper($p['payment_mode']) ?></span></td>
                    <td>
                        <div class="small fw-semibold"><?= esc($p['bank_name'] ?: 'State Bank of India') ?></div>
                        <small class="text-muted">Ref: <?= esc($p['reference_no'] ?: 'Direct Transfer') ?></small>
                    </td>
                    <td class="text-end text-muted"><?= format_inr($p['tds_amount']) ?></td>
                    <td class="text-end fw-bold text-danger"><?= format_inr($p['amount']) ?></td>
                    <td><?= status_badge($p['status']) ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
