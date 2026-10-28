<div class="row mb-3">
    <div class="col-md-7">
        <h4 class="mb-1"><i class="bi bi-truck me-2 text-primary"></i>Delivery Challan #<?= esc($dispatch['dc_no']) ?></h4>
        <div class="text-muted small">
            Date: <strong><?= date('d M Y', strtotime($dispatch['dc_date'])) ?></strong> &bull; 
            Client: <strong><?= esc($dispatch['client_name']) ?></strong>
            <?php if (!empty($dispatch['so_no'])): ?>
                &bull; SO: <a href="<?= base_url('sales-orders/view/' . $dispatch['sales_order_id']) ?>" class="fw-bold"><?= esc($dispatch['so_no']) ?></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-5 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('dispatches') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal">
            <i class="bi bi-pencil-square me-1"></i>Update Status
        </button>
        <a href="<?= base_url('dispatches/print/' . $dispatch['id']) ?>" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i>Print DC & Gate Pass
        </a>
    </div>
</div>

<div class="row g-3">
    <!-- Main Logistics Info -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-geo-alt me-2"></i>Consignee & Site Destination</span>
                <?= status_badge($dispatch['status']) ?>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Client / Consignee</small>
                        <h6 class="mb-1 fw-bold"><?= esc($dispatch['client_name']) ?></h6>
                        <div class="small text-muted">GSTIN: <strong><?= esc($dispatch['client_gstin'] ?: 'N/A') ?></strong></div>
                        <div class="small text-muted">Contact: <?= esc($dispatch['contact_person'] ?: 'N/A') ?> (<?= esc($dispatch['client_phone'] ?: '') ?>)</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Delivery Site / Unloading Address</small>
                        <p class="mb-0 fw-semibold"><?= nl2br(esc($dispatch['delivery_address'])) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-truck-flatbed me-2"></i>Transporter & Vehicle Identification</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Vehicle / Trailer No</small>
                        <span class="badge bg-dark fs-6 font-monospace"><?= esc($dispatch['vehicle_no']) ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Transporter Agency</small>
                        <strong><?= esc($dispatch['transporter'] ?: 'Dedicated Fleet') ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Government E-Way Bill No</small>
                        <code class="fs-6 text-success fw-bold"><?= esc($dispatch['eway_bill_no'] ?: 'Not Issued') ?></code>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Lorry Receipt (LR) No</small>
                        <strong><?= esc($dispatch['lr_no'] ?: 'Direct Handover') ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">LR Date</small>
                        <span><?= $dispatch['lr_date'] ? date('d-m-Y', strtotime($dispatch['lr_date'])) : '&mdash;' ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Packages / Bundles</small>
                        <span><?= $dispatch['no_of_packages'] ?> Packages</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Driver Name & Phone</small>
                        <div><?= esc($dispatch['driver_name'] ?: 'N/A') ?></div>
                        <small class="text-muted"><?= esc($dispatch['driver_phone'] ?: '') ?></small>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Total Net Weight</small>
                        <span class="fs-5 fw-bold text-primary"><?= number_format($dispatch['total_weight'], 3) ?> MT</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Delivered Date</small>
                        <span><?= $dispatch['delivered_date'] ? date('d-m-Y', strtotime($dispatch['delivered_date'])) : 'In Transit' ?></span>
                    </div>
                </div>

                <?php if (!empty($dispatch['remarks'])): ?>
                <div class="alert alert-light border mt-3 mb-0 py-2 small">
                    <strong>Dispatch Remarks:</strong> <?= esc($dispatch['remarks']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Dispatched Items Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-stack me-2"></i>Dispatched Fabricated Structures & Assemblies</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>#</th>
                            <th>Description of Goods</th>
                            <th class="text-end">Quantity</th>
                            <th class="text-end">Weight (MT)</th>
                            <th>Remarks / Identification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dispatch['items'])): foreach ($dispatch['items'] as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong class="text-dark"><?= esc($item['description']) ?></strong></td>
                            <td class="text-end"><?= number_format($item['quantity'], 2) ?> <?= esc($item['unit']) ?></td>
                            <td class="text-end fw-bold"><?= number_format($item['weight'], 3) ?></td>
                            <td class="small text-muted"><?= esc($item['remarks'] ?: '&mdash;') ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-3 text-muted">No items in this dispatch.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Side Security Gate Pass Summary -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-shield-lock me-2"></i>Factory Gate Pass Security Check</span>
            </div>
            <div class="card-body small">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Weighbridge Tare & Gross weight slip attached</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Physical lashings & trailer tie-down verified</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Driver DL & Vehicle RC/Insurance verified</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>E-Way Bill active & QR code readable</li>
                    <li><i class="bi bi-check-circle-fill text-success me-2"></i>Security out-time logged in register</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Next Step: Invoicing</span>
            </div>
            <div class="card-body small">
                <p class="text-muted mb-3">Generate GST Tax Invoice against this Delivery Challan once site acknowledgment is verified.</p>
                <a href="<?= base_url('invoices/create?client_id=' . $dispatch['client_id'] . '&so_id=' . ($dispatch['sales_order_id'] ?? '')) ?>" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-receipt me-1"></i>Generate Tax Invoice
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('dispatches/update-status/' . $dispatch['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Dispatch Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Consignment Status</label>
                        <select name="status" class="form-select">
                            <option value="prepared" <?= $dispatch['status'] === 'prepared' ? 'selected' : '' ?>>Prepared at Factory</option>
                            <option value="dispatched" <?= $dispatch['status'] === 'dispatched' ? 'selected' : '' ?>>Dispatched from Gate</option>
                            <option value="in_transit" <?= $dispatch['status'] === 'in_transit' ? 'selected' : '' ?>>In Transit (On Highway)</option>
                            <option value="delivered" <?= $dispatch['status'] === 'delivered' ? 'selected' : '' ?>>Delivered at Site</option>
                            <option value="returned" <?= $dispatch['status'] === 'returned' ? 'selected' : '' ?>>Returned</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>
