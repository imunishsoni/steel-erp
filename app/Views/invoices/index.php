<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-receipt me-2 text-primary"></i>GST Tax Invoices & Receivables</h4>
        <small class="text-muted">GST tax invoices, HSN 7308 billing, and payment reconciliations</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('payments/received') ?>" class="btn btn-outline-success btn-sm me-2">
            <i class="bi bi-cash-stack me-1"></i>Payment Receipts
        </a>
        <a href="<?= base_url('invoices/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>New Tax Invoice
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-primary">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Invoices</div>
                <div class="fs-3 fw-bold"><?= $stats['total_invoices'] ?></div>
                <div class="text-muted small">Generated in system</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-info">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Billed</div>
                <div class="fs-3 fw-bold text-dark"><?= format_inr($stats['total_billed']) ?></div>
                <div class="text-muted small">Inclusive of GST</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-success">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Collections Received</div>
                <div class="fs-3 fw-bold text-success"><?= format_inr($stats['total_paid']) ?></div>
                <div class="text-muted small">Cleared in bank</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-danger">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Outstanding Receivables</div>
                <div class="fs-3 fw-bold text-danger"><?= format_inr($stats['total_due']) ?></div>
                <div class="text-muted small">Pending payment</div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-bold"><i class="bi bi-list-check me-2"></i>Sales Invoices Register</span>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable w-100">
            <thead class="table-light small">
                <tr>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Client / Plant</th>
                    <th>GSTIN</th>
                    <th>Ref SO / DC</th>
                    <th class="text-end">Taxable (₹)</th>
                    <th class="text-end">GST (₹)</th>
                    <th class="text-end">Grand Total</th>
                    <th class="text-end">Balance Due</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoices)): foreach ($invoices as $inv): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('invoices/view/' . $inv['id']) ?>" class="fw-bold text-primary text-decoration-none">
                            <?= esc($inv['invoice_no']) ?>
                        </a>
                    </td>
                    <td><?= date('d-m-Y', strtotime($inv['invoice_date'])) ?></td>
                    <td>
                        <div class="fw-semibold"><?= esc($inv['client_company']) ?></div>
                        <small class="text-muted">Place: <?= esc($inv['place_of_supply'] ?: 'Local') ?></small>
                    </td>
                    <td><code><?= esc($inv['client_gstin'] ?: 'N/A') ?></code></td>
                    <td>
                        <?php if ($inv['so_no']): ?>
                            <span class="badge bg-light text-dark border"><?= esc($inv['so_no']) ?></span>
                        <?php endif; ?>
                        <?php if ($inv['dc_no']): ?>
                            <div class="small text-muted">DC: <?= esc($inv['dc_no']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-end"><?= format_inr($inv['taxable_amount']) ?></td>
                    <td class="text-end">
                        <small><?= $inv['is_igst'] ? 'IGST' : 'CGST+SGST' ?></small><br>
                        <?= format_inr($inv['total_tax']) ?>
                    </td>
                    <td class="text-end fw-bold"><?= format_inr($inv['grand_total']) ?></td>
                    <td class="text-end fw-bold <?= $inv['balance_amount'] > 0 ? 'text-danger' : 'text-success' ?>">
                        <?= format_inr($inv['balance_amount']) ?>
                    </td>
                    <td><?= status_badge($inv['status']) ?></td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="<?= base_url('invoices/view/' . $inv['id']) ?>" class="btn btn-outline-secondary" title="View Invoice">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= base_url('invoices/print/' . $inv['id']) ?>" target="_blank" class="btn btn-outline-primary" title="Print GST Invoice">
                                <i class="bi bi-printer"></i>
                            </a>
                            <?php if ($inv['balance_amount'] > 0): ?>
                            <a href="<?= base_url('payments/record-receipt?invoice_id=' . $inv['id']) ?>" class="btn btn-outline-success" title="Record Receipt">
                                <i class="bi bi-cash"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
