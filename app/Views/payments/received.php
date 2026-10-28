<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-cash-stack me-2 text-success"></i>Client Collections & Payment Receipts</h4>
        <small class="text-muted">Inward RTGS, NEFT, Cheque and LC receipts from clients</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('invoices') ?>" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-receipt me-1"></i>Invoices</a>
        <a href="<?= base_url('payments/record-receipt') ?>" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Record New Receipt
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-start-success">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Collections</div>
                <div class="fs-3 fw-bold text-success"><?= format_inr($stats['total_received']) ?></div>
                <div class="text-muted small">Realized in bank account</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-start-info">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">TDS Deductions (Sec 194C / 194Q)</div>
                <div class="fs-3 fw-bold text-info"><?= format_inr($stats['total_tds']) ?></div>
                <div class="text-muted small">Claimable in Form 26AS</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-start-primary">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Receipt Vouchers</div>
                <div class="fs-3 fw-bold"><?= $stats['count'] ?></div>
                <div class="text-muted small">Total transactions logged</div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-bold"><i class="bi bi-journal-check me-2"></i>Collections Register</span>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable w-100">
            <thead class="table-light small">
                <tr>
                    <th>Receipt No</th>
                    <th>Date</th>
                    <th>Client / Plant</th>
                    <th>Against Invoice</th>
                    <th>Mode</th>
                    <th>Bank & UTR No</th>
                    <th class="text-end">TDS (₹)</th>
                    <th class="text-end">Amount Cleared</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($receipts)): foreach ($receipts as $r): ?>
                <tr>
                    <td><code><?= esc($r['receipt_no']) ?></code></td>
                    <td><?= date('d-m-Y', strtotime($r['receipt_date'])) ?></td>
                    <td><strong><?= esc($r['client_company']) ?></strong></td>
                    <td>
                        <?php if ($r['invoice_no']): ?>
                            <a href="<?= base_url('invoices/view/' . $r['invoice_id']) ?>" class="badge bg-light text-primary border text-decoration-none">
                                <?= esc($r['invoice_no']) ?>
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border">On Account / Advance</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-secondary"><?= strtoupper($r['payment_mode']) ?></span></td>
                    <td>
                        <div class="small fw-semibold"><?= esc($r['bank_name'] ?: 'State Bank of India') ?></div>
                        <small class="text-muted">Ref: <?= esc($r['reference_no'] ?: 'Direct Transfer') ?></small>
                    </td>
                    <td class="text-end text-muted"><?= format_inr($r['tds_amount']) ?></td>
                    <td class="text-end fw-bold text-success"><?= format_inr($r['amount']) ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
