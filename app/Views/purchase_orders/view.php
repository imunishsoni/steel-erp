<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="mb-0 fw-bold"><?= esc($order['po_no']) ?></h3>
            <?= status_badge($order['status']) ?>
        </div>
        <p class="text-muted small mb-0">Procurement from <?= esc($order['vendor_name']) ?> &bull; PO Date: <?= format_date($order['po_date']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('grn/new?po_id=' . $order['id']) ?>" class="btn btn-success btn-sm">
            <i class="bi bi-box-arrow-in-down me-1"></i> Receive Material (Create GRN)
        </a>
        <a href="<?= base_url('payments/payment_form?po_id=' . $order['id']) ?>" class="btn btn-warning text-dark btn-sm">
            <i class="bi bi-credit-card me-1"></i> Record Advance / RTGS Payment
        </a>
        <a href="<?= base_url('purchase-orders/print/' . $order['id']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Print PO
        </a>
        <a href="<?= base_url('purchase-orders') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-building me-2 text-primary"></i>Supplier / Steel Mill</h6>
            <div class="small">
                <div class="fw-bold text-dark fs-6 mb-1"><?= esc($order['vendor_name']) ?></div>
                <div class="text-muted mb-1"><?= esc($order['address_line1']) ?>, <?= esc($order['city']) ?></div>
                <div class="text-muted mb-2"><?= esc($order['vendor_state']) ?> &bull; GSTIN: <span class="font-mono"><?= esc($order['vendor_gstin'] ?: '-') ?></span></div>
                <div>Attn: <strong><?= esc($order['contact_person'] ?: '-') ?></strong> (<?= esc($order['vendor_phone'] ?: '-') ?>)</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Delivery & Logistics</h6>
            <div class="small">
                <div class="mb-2">Destination: <strong><?= esc($order['delivery_address']) ?></strong></div>
                <div class="mb-2">Target Date: <strong class="text-danger"><?= format_date($order['delivery_date']) ?></strong></div>
                <div class="mb-2">Payment Terms: <strong><?= esc($order['payment_terms']) ?></strong></div>
                <div>Bank IFSC: <span class="font-mono text-primary"><?= esc($order['bank_ifsc'] ?: '-') ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100 bg-primary bg-opacity-10 border-primary border-opacity-25">
            <h6 class="fw-bold border-bottom border-primary border-opacity-25 pb-2 mb-3 text-primary"><i class="bi bi-currency-rupee me-2"></i>Total PO Value</h6>
            <div class="fs-3 fw-bold text-primary font-mono mb-1"><?= format_inr($order['grand_total']) ?></div>
            <div class="small text-muted mb-2"><?= esc($order['amount_in_words']) ?></div>
            <div class="badge bg-primary">Taxable: <?= format_inr($order['taxable_amount']) ?> | Tax: <?= format_inr($order['total_tax']) ?></div>
        </div>
    </div>
</div>

<!-- Items Table -->
<div class="steel-card mb-4">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-check me-2"></i>Ordered Materials & Inward Status</span>
    </div>
    <div class="table-responsive">
        <table class="table table-steel mb-0">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th>Material Code & Description</th>
                    <th>Specification / Dimensions</th>
                    <th>HSN</th>
                    <th class="text-end">Ordered Qty</th>
                    <th class="text-end">Received Qty</th>
                    <th class="text-end">Rate (₹)</th>
                    <th class="text-end">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td class="text-center"><?= esc($item['sl_no']) ?></td>
                        <td>
                            <div class="fw-bold font-mono text-primary"><?= esc($item['material_code']) ?></div>
                            <div><?= esc($item['description']) ?></div>
                        </td>
                        <td class="small text-muted"><?= esc($item['specification'] ?: '-') ?></td>
                        <td class="font-mono small"><?= esc($item['hsn_code']) ?></td>
                        <td class="text-end font-mono fw-bold"><?= number_format($item['quantity'], 2) ?> <?= esc($item['unit']) ?></td>
                        <td class="text-end font-mono text-success"><?= number_format($item['received_qty'], 2) ?> <?= esc($item['unit']) ?></td>
                        <td class="text-end font-mono"><?= format_inr($item['rate'], false) ?></td>
                        <td class="text-end font-mono fw-bold"><?= format_inr($item['amount']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Linked GRNs and Payments -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="steel-card h-100">
            <div class="steel-card-header">
                <span class="fw-bold"><i class="bi bi-box-arrow-in-down me-2"></i>Inward Goods Receipts (GRN)</span>
            </div>
            <div class="p-3">
                <table class="table table-steel table-sm">
                    <thead>
                        <tr>
                            <th>GRN No</th>
                            <th>Date</th>
                            <th>Challan No</th>
                            <th>Vehicle No</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($grns)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">No GRN inwards recorded yet</td></tr>
                        <?php else: ?>
                            <?php foreach ($grns as $g): ?>
                                <tr>
                                    <td><a href="<?= base_url('grn/view/' . $g['id']) ?>" class="font-mono fw-bold"><?= esc($g['grn_no']) ?></a></td>
                                    <td><?= format_date($g['grn_date']) ?></td>
                                    <td class="font-mono"><?= esc($g['challan_no']) ?></td>
                                    <td class="font-mono"><?= esc($g['vehicle_no']) ?></td>
                                    <td><?= status_badge($g['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="steel-card h-100">
            <div class="steel-card-header">
                <span class="fw-bold"><i class="bi bi-cash me-2"></i>Disbursed Payments</span>
            </div>
            <div class="p-3">
                <table class="table table-steel table-sm">
                    <thead>
                        <tr>
                            <th>Payment No</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Mode</th>
                            <th>Ref / UTR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">No payment vouchers recorded yet</td></tr>
                        <?php else: ?>
                            <?php foreach ($payments as $p): ?>
                                <tr>
                                    <td class="font-mono fw-bold"><?= esc($p['payment_no']) ?></td>
                                    <td><?= format_date($p['payment_date']) ?></td>
                                    <td class="font-mono fw-bold text-success"><?= format_inr($p['amount']) ?></td>
                                    <td class="text-uppercase"><?= esc($p['payment_mode']) ?></td>
                                    <td class="font-mono small"><?= esc($p['reference_no']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
