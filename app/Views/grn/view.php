<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-box-arrow-in-down me-2 text-primary"></i>GRN #<?= esc($grn['grn_no']) ?></h4>
        <div class="text-muted small">
            PO: <strong><?= esc($grn['po_no'] ?? 'Direct Receipt') ?></strong> &bull; 
            Vendor: <strong><?= esc($grn['vendor_company'] ?? $grn['vendor_name'] ?? 'Supplier') ?></strong> &bull; 
            Date: <?= date('d M Y', strtotime($grn['grn_date'] ?? date('Y-m-d'))) ?>
        </div>
    </div>
    <div class="col-md-4 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('grn') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to GRNs</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i>Print GRN Slip</button>
    </div>
</div>

<div class="row g-3">
    <!-- Summary Card -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-info-circle me-2"></i>Receipt & Consignment Details</span>
                <span>
                    <?= status_badge($grn['status']) ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Vendor / Supplier</small>
                        <strong><?= esc($grn['vendor_company'] ?? $grn['vendor_name'] ?? '') ?></strong>
                        <div class="small text-muted">GSTIN: <?= esc($grn['vendor_gstin'] ?? 'N/A') ?></div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Supplier Invoice / Challan No</small>
                        <strong><?= esc($grn['challan_no'] ?: 'Direct Handover') ?></strong>
                        <div class="small text-muted"><?= !empty($grn['challan_date']) ? date('d-M-Y', strtotime($grn['challan_date'])) : '' ?></div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Vehicle / Trailer No</small>
                        <span class="badge bg-dark fs-6"><?= esc($grn['vehicle_no'] ?: 'Ex-Works') ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Associated PO</small>
                        <?php if (!empty($grn['purchase_order_id'])): ?>
                            <a href="<?= base_url('purchase-orders/view/' . $grn['purchase_order_id']) ?>" class="fw-bold text-decoration-none">
                                <?= esc($grn['po_no'] ?? 'PO #' . $grn['purchase_order_id']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">&mdash;</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Received By</small>
                        <div><?= esc($grn['receiver_name'] ?? 'Store Incharge') ?></div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Receipt Date</small>
                        <div><?= date('d M Y', strtotime($grn['grn_date'] ?? date('Y-m-d'))) ?></div>
                    </div>
                </div>

                <?php if (!empty($grn['remarks'])): ?>
                <div class="alert alert-light border mt-3 mb-0 py-2 small">
                    <strong>Remarks / QC Notes:</strong> <?= esc($grn['remarks']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Material Items Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-stack me-2"></i>Received Steel Materials & Mill TC Verification</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>#</th>
                            <th>Steel Material & Code</th>
                            <th class="text-end">Ordered Qty</th>
                            <th class="text-end">Received Qty</th>
                            <th class="text-end">Accepted Qty</th>
                            <th class="text-end">Rejected Qty</th>
                            <th class="text-end">Rate (₹)</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($grn['items'])): foreach ($grn['items'] as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-bold"><?= esc($item['material_name'] ?? 'Steel Component') ?></div>
                                <small class="text-muted"><code><?= esc($item['material_code'] ?? '') ?></code></small>
                            </td>
                            <td class="text-end"><?= number_format($item['ordered_qty'] ?? 0, 3) ?> <small><?= esc($item['unit'] ?? 'MT') ?></small></td>
                            <td class="text-end fw-bold"><?= number_format($item['received_qty'] ?? 0, 3) ?> <small><?= esc($item['unit'] ?? 'MT') ?></small></td>
                            <td class="text-end text-success fw-bold"><?= number_format($item['accepted_qty'] ?? 0, 3) ?></td>
                            <td class="text-end text-danger"><?= number_format($item['rejected_qty'] ?? 0, 3) ?></td>
                            <td class="text-end"><?= format_inr($item['rate'] ?? 0) ?></td>
                            <td class="small text-muted"><?= esc($item['remarks'] ?? '&mdash;') ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No line items in this GRN.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Side Info -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-shield-check me-2"></i>Quality Assurance Checklist</span>
            </div>
            <div class="card-body small">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Mill Test Certificate (MTC) 3.1 / 3.2 verified</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Dimensional check (Thickness, Width, Length)</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Visual check (Surface defects, laminations, rust)</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Weighbridge gross & tare reconciliation</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i>Material color coding & yard bay tagging</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-link-45deg me-2"></i>Audit Trail</span>
            </div>
            <div class="card-body small">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Store Receiver:</span>
                    <span class="fw-semibold"><?= esc($grn['receiver_name'] ?? 'Store Incharge') ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Created Date:</span>
                    <span><?= date('d-m-Y H:i', strtotime($grn['created_at'] ?? 'now')) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
