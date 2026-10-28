<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-file-earmark-spreadsheet text-primary me-2"></i>Cost Estimations & Formal Quotations</h3>
        <p class="text-muted small mb-0">Structural steel fabrication estimations with automatic CGST+SGST / IGST calculation</p>
    </div>
    <a href="<?= base_url('quotations/new') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Create New Quotation
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-columns-reverse me-2"></i>Quotations Register</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>Quote No</th>
                        <th>Date</th>
                        <th>Client / Plant</th>
                        <th>Project Name</th>
                        <th>Taxable Amt</th>
                        <th>Tax (GST)</th>
                        <th>Grand Total</th>
                        <th>Valid Until</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotations as $q): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary">
                                <a href="<?= base_url('quotations/view/' . $q['id']) ?>" class="text-decoration-none">
                                    <?= esc($q['quotation_no']) ?>
                                </a>
                            </td>
                            <td><?= format_date($q['quotation_date']) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($q['client_company']) ?></div>
                                <small class="text-muted"><?= esc($q['client_state']) ?></small>
                            </td>
                            <td class="fw-semibold text-truncate" style="max-width: 200px;"><?= esc($q['project_name']) ?></td>
                            <td class="font-mono"><?= format_inr($q['taxable_amount']) ?></td>
                            <td class="font-mono"><?= format_inr($q['total_tax']) ?></td>
                            <td class="font-mono fw-bold text-success"><?= format_inr($q['grand_total']) ?></td>
                            <td><?= format_date($q['valid_until']) ?></td>
                            <td><?= status_badge($q['status']) ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('quotations/view/' . $q['id']) ?>" class="btn btn-light" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="<?= base_url('quotations/print/' . $q['id']) ?>" target="_blank" class="btn btn-light" title="Print Proposal"><i class="bi bi-printer"></i></a>
                                    <?php if ($q['status'] !== 'converted'): ?>
                                        <a href="<?= base_url('quotations/convert-so/' . $q['id']) ?>" class="btn btn-light text-success" title="Convert to Sales Order" onclick="return confirm('Convert this quotation to an active Sales Order?');"><i class="bi bi-cart-check"></i></a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('quotations/delete/' . $q['id']) ?>" class="btn btn-light text-danger confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
