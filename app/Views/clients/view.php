<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="mb-0 fw-bold"><?= esc($client['company_name']) ?></h3>
            <?= plant_type_badge($client['industry_type']) ?>
        </div>
        <p class="text-muted small mb-0 font-mono">Code: <?= esc($client['client_code']) ?> &bull; GSTIN: <?= esc($client['gstin'] ?: 'Unregistered') ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('quotations/new?client_id=' . $client['id']) ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark-plus me-1"></i>New Quotation</a>
        <a href="<?= base_url('clients/edit/' . $client['id']) ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit Client</a>
        <a href="<?= base_url('clients') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Plant Information</h6>
            <div class="small">
                <div class="mb-2 text-muted">Contact Person: <strong class="text-dark"><?= esc($client['contact_person'] ?: '-') ?></strong></div>
                <div class="mb-2 text-muted">Email: <strong class="text-dark"><?= esc($client['email'] ?: '-') ?></strong></div>
                <div class="mb-2 text-muted">Phone: <strong class="text-dark"><?= esc($client['mobile'] ?: $client['phone'] ?: '-') ?></strong></div>
                <div class="mb-2 text-muted">Sector: <?= plant_type_badge($client['industry_type']) ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Plant Site & Address</h6>
            <div class="small">
                <div class="mb-1 text-dark fw-semibold"><?= esc($client['address_line1'] ?: '-') ?></div>
                <div class="mb-1 text-muted"><?= esc($client['address_line2'] ?: '') ?></div>
                <div class="mb-1 text-muted"><?= esc($client['city']) ?>, <?= esc($client['state']) ?> - <?= esc($client['pincode']) ?></div>
                <div class="mt-2 pt-2 border-top">
                    <span class="badge bg-light text-dark border">
                        Tax Supply: <?= (strcasecmp($client['state'], 'Rajasthan') === 0) ? 'Intra-State (CGST+SGST)' : 'Inter-State (IGST 18%)' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-cash-coin me-2 text-primary"></i>Commercial & Credit Terms</h6>
            <div class="small">
                <div class="mb-2 text-muted">Credit Limit: <strong class="text-dark"><?= format_inr($client['credit_limit']) ?></strong></div>
                <div class="mb-2 text-muted">Credit Period: <strong class="text-dark"><?= esc($client['credit_days']) ?> Days</strong></div>
                <div class="mb-2 text-muted">PAN: <strong class="font-mono text-dark"><?= esc($client['pan'] ?: '-') ?></strong></div>
                <div>Status: <?= ($client['is_active']) ? '<span class="badge bg-success">Active Account</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs for Orders, Quotations, Invoices -->
<div class="steel-card">
    <div class="steel-card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs border-0" id="clientTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active px-4 py-3 fw-bold" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                    <i class="bi bi-cart-check me-2"></i>Sales Orders (<?= count($orders) ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-4 py-3 fw-bold" id="quotations-tab" data-bs-toggle="tab" data-bs-target="#quotations" type="button" role="tab">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Quotations (<?= count($quotations) ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-4 py-3 fw-bold" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">
                    <i class="bi bi-receipt me-2"></i>Tax Invoices (<?= count($invoices) ?>)
                </button>
            </li>
        </ul>
    </div>
    <div class="p-4">
        <div class="tab-content" id="clientTabsContent">
            <!-- Orders -->
            <div class="tab-pane fade show active" id="orders" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-steel">
                        <thead>
                            <tr>
                                <th>SO No</th>
                                <th>Date</th>
                                <th>Project Name</th>
                                <th>Client PO No</th>
                                <th>Grand Total</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-3">No sales orders found for this client</td></tr>
                            <?php else: ?>
                                <?php foreach ($orders as $so): ?>
                                    <tr>
                                        <td class="font-mono fw-bold text-primary"><?= esc($so['so_no']) ?></td>
                                        <td><?= format_date($so['so_date']) ?></td>
                                        <td class="fw-semibold"><?= esc($so['project_name']) ?></td>
                                        <td class="font-mono"><?= esc($so['client_po_no'] ?: '-') ?></td>
                                        <td class="font-mono fw-bold"><?= format_inr($so['grand_total']) ?></td>
                                        <td><?= format_date($so['delivery_date']) ?></td>
                                        <td><?= status_badge($so['status']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('sales-orders/view/' . $so['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quotations -->
            <div class="tab-pane fade" id="quotations" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-steel">
                        <thead>
                            <tr>
                                <th>Quote No</th>
                                <th>Date</th>
                                <th>Project Name</th>
                                <th>Taxable Amount</th>
                                <th>Tax</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($quotations)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-3">No quotations found</td></tr>
                            <?php else: ?>
                                <?php foreach ($quotations as $q): ?>
                                    <tr>
                                        <td class="font-mono fw-bold"><?= esc($q['quotation_no']) ?></td>
                                        <td><?= format_date($q['quotation_date']) ?></td>
                                        <td><?= esc($q['project_name']) ?></td>
                                        <td class="font-mono"><?= format_inr($q['taxable_amount']) ?></td>
                                        <td class="font-mono"><?= format_inr($q['total_tax']) ?></td>
                                        <td class="font-mono fw-bold text-success"><?= format_inr($q['grand_total']) ?></td>
                                        <td><?= status_badge($q['status']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('quotations/view/' . $q['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoices -->
            <div class="tab-pane fade" id="invoices" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-steel">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Grand Total</th>
                                <th>Paid Amount</th>
                                <th>Balance Due</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($invoices)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-3">No invoices generated yet</td></tr>
                            <?php else: ?>
                                <?php foreach ($invoices as $inv): ?>
                                    <tr>
                                        <td class="font-mono fw-bold text-primary"><?= esc($inv['invoice_no']) ?></td>
                                        <td><?= format_date($inv['invoice_date']) ?></td>
                                        <td class="font-mono"><?= format_inr($inv['grand_total']) ?></td>
                                        <td class="font-mono text-success"><?= format_inr($inv['paid_amount']) ?></td>
                                        <td class="font-mono fw-bold text-danger"><?= format_inr($inv['balance_amount']) ?></td>
                                        <td><?= status_badge($inv['status']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('invoices/view/' . $inv['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
