<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="mb-0 fw-bold"><?= esc($vendor['company_name']) ?></h3>
            <span class="badge bg-secondary"><?= ucwords(str_replace('_', ' ', $vendor['vendor_type'])) ?></span>
        </div>
        <p class="text-muted small mb-0 font-mono">Code: <?= esc($vendor['vendor_code']) ?> &bull; GSTIN: <?= esc($vendor['gstin'] ?: 'Unregistered') ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('purchase-orders/new?vendor_id=' . $vendor['id']) ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-cart-plus me-1"></i>New Purchase Order</a>
        <a href="<?= base_url('vendors/edit/' . $vendor['id']) ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit Vendor</a>
        <a href="<?= base_url('vendors') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Vendor Info</h6>
            <div class="small">
                <div class="mb-2 text-muted">Contact: <strong class="text-dark"><?= esc($vendor['contact_person'] ?: '-') ?></strong></div>
                <div class="mb-2 text-muted">Email: <strong class="text-dark"><?= esc($vendor['email'] ?: '-') ?></strong></div>
                <div class="mb-2 text-muted">Phone: <strong class="text-dark"><?= esc($vendor['mobile'] ?: $vendor['phone'] ?: '-') ?></strong></div>
                <div>Rating:
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem; <?= $i > $vendor['rating'] ? 'opacity: 0.2;' : '' ?>"></i>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Office / Mill Address</h6>
            <div class="small">
                <div class="mb-1 text-dark fw-semibold"><?= esc($vendor['address_line1'] ?: '-') ?></div>
                <div class="mb-1 text-muted"><?= esc($vendor['address_line2'] ?: '') ?></div>
                <div class="mb-1 text-muted"><?= esc($vendor['city']) ?>, <?= esc($vendor['state']) ?> - <?= esc($vendor['pincode']) ?></div>
                <div class="mt-2 pt-2 border-top">
                    <span class="badge bg-light text-dark border">
                        Tax Supply: <?= (strcasecmp($vendor['state'], 'Rajasthan') === 0) ? 'Intra-State (CGST+SGST)' : 'Inter-State (IGST 18%)' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-bank me-2 text-primary"></i>Banking & Payment Terms</h6>
            <div class="small">
                <div class="mb-2 text-muted">Terms: <strong class="text-dark"><?= esc($vendor['payment_terms'] ?: '-') ?></strong></div>
                <div class="mb-2 text-muted">Bank: <strong class="text-dark"><?= esc($vendor['bank_name'] ?: '-') ?></strong></div>
                <div class="mb-2 text-muted">A/C No: <strong class="font-mono text-dark"><?= esc($vendor['bank_account_no'] ?: '-') ?></strong></div>
                <div>IFSC: <strong class="font-mono text-primary"><?= esc($vendor['bank_ifsc'] ?: '-') ?></strong></div>
            </div>
        </div>
    </div>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-bag-check me-2"></i>Purchase Orders & Deliveries</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel">
                <thead>
                    <tr>
                        <th>PO No</th>
                        <th>Date</th>
                        <th>Delivery Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-3">No purchase orders found for this vendor</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $po): ?>
                            <tr>
                                <td class="font-mono fw-bold text-primary"><?= esc($po['po_no']) ?></td>
                                <td><?= format_date($po['po_date']) ?></td>
                                <td><?= format_date($po['delivery_date']) ?></td>
                                <td class="font-mono fw-bold"><?= format_inr($po['grand_total']) ?></td>
                                <td><?= status_badge($po['status']) ?></td>
                                <td class="text-end">
                                    <a href="<?= base_url('purchase-orders/view/' . $po['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
