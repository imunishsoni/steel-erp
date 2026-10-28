<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Material Inward Receipt (GRN)</h3>
        <p class="text-muted small mb-0">Record mill invoice / delivery challan, physical weighbridge inspection, and accepted tonnage</p>
    </div>
    <a href="<?= base_url('grn') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to GRNs
    </a>
</div>

<form action="<?= base_url('grn/create') ?>" method="post">
    <?= csrf_field() ?>

    <div class="steel-card mb-4">
        <div class="steel-card-header">
            <span class="fw-bold"><i class="bi bi-truck me-2"></i>Inward Gate Entry & Vendor Details</span>
        </div>
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">GRN Number</label>
                    <input type="text" name="grn_no" class="form-control font-mono" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-3">
                    <label class="form-label">GRN Date <span class="text-danger">*</span></label>
                    <input type="date" name="grn_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Purchase Order (PO)</label>
                    <select name="purchase_order_id" class="form-select select2" onchange="if(this.value) window.location.href='<?= base_url('grn/new?po_id=') ?>' + this.value;">
                        <option value="">-- Direct Receipt / Select PO --</option>
                        <?php foreach ($poList as $po): ?>
                            <option value="<?= $po['id'] ?>" <?= ($selectedPo && $selectedPo['id'] == $po['id']) ? 'selected' : '' ?>>
                                <?= esc($po['po_no']) ?> (₹ <?= number_format($po['grand_total'], 0) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vendor / Mill <span class="text-danger">*</span></label>
                    <select name="vendor_id" class="form-select select2" required>
                        <option value="">Select Vendor</option>
                        <?php foreach ($vendors as $v): ?>
                            <option value="<?= $v['id'] ?>" <?= ($selectedPo && $selectedPo['vendor_id'] == $v['id']) ? 'selected' : '' ?>>
                                <?= esc($v['company_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Vendor Challan / Invoice No <span class="text-danger">*</span></label>
                    <input type="text" name="challan_no" class="form-control font-mono" required placeholder="e.g. SAIL/JPR/INV/1044">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vendor Challan Date</label>
                    <input type="date" name="challan_date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vehicle / Heavy Trailer No <span class="text-danger">*</span></label>
                    <input type="text" name="vehicle_no" class="form-control font-mono text-uppercase" required placeholder="e.g. RJ-14-GH-4589">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Inspection Status</label>
                    <select name="status" class="form-select">
                        <option value="accepted">Accepted in Full</option>
                        <option value="partial">Partial Acceptance</option>
                        <option value="inspected">Inspected / Quality Check</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="mb-0">
                <label class="form-label">QC Inspector Notes & Heat Numbers</label>
                <textarea name="remarks" rows="2" class="form-control" placeholder="Weighment verification done at plant weighbridge. Mill TC verified by QC Inspector. Record Heat/Cast numbers.">Weighment at Plant Weighbridge done. Mill TC verified by QC.</textarea>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="steel-card mb-4">
        <div class="steel-card-header">
            <span class="fw-bold"><i class="bi bi-list-check me-2"></i>Physical Material Verification & Acceptance</span>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 4%;">#</th>
                            <th style="width: 32%;">Material Description</th>
                            <th style="width: 12%;">Ordered Qty</th>
                            <th style="width: 14%;">Received Qty</th>
                            <th style="width: 14%;">Accepted Qty</th>
                            <th style="width: 12%;">Rejected Qty</th>
                            <th style="width: 12%;">Heat No / Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($selectedPo && !empty($selectedPo['items'])): ?>
                            <?php foreach ($selectedPo['items'] as $idx => $it): ?>
                                <?php $bal = max(0, (float)$it['quantity'] - (float)$it['received_qty']); ?>
                                <tr>
                                    <td class="text-center"><?= $idx + 1 ?></td>
                                    <td>
                                        <input type="hidden" name="items[<?= $idx ?>][po_item_id]" value="<?= $it['id'] ?>">
                                        <input type="hidden" name="items[<?= $idx ?>][material_id]" value="<?= $it['material_id'] ?>">
                                        <input type="hidden" name="items[<?= $idx ?>][rate]" value="<?= $it['rate'] ?>">
                                        <div class="fw-bold"><?= esc($it['material_name'] ?: $it['description']) ?></div>
                                        <small class="text-muted font-mono"><?= esc($it['material_code']) ?> &bull; <?= esc($it['specification']) ?></small>
                                    </td>
                                    <td class="font-mono">
                                        <input type="hidden" name="items[<?= $idx ?>][ordered_qty]" value="<?= $it['quantity'] ?>">
                                        <?= number_format($it['quantity'], 2) ?> <?= esc($it['unit']) ?>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[<?= $idx ?>][received_qty]" class="form-control form-control-sm font-mono" value="<?= $bal ?>">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[<?= $idx ?>][accepted_qty]" class="form-control form-control-sm font-mono text-success fw-bold" value="<?= $bal ?>">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[<?= $idx ?>][rejected_qty]" class="form-control form-control-sm font-mono text-danger" value="0.00">
                                    </td>
                                    <td>
                                        <input type="text" name="items[<?= $idx ?>][remarks]" class="form-control form-control-sm" placeholder="Heat No: SAIL-K-4921" value="Heat OK">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                    Please select a Purchase Order (PO) from the dropdown above to load the ordered steel items.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-end">
        <a href="<?= base_url('grn') ?>" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-4 fw-semibold" <?= (!$selectedPo) ? 'disabled' : '' ?>>
            <i class="bi bi-check2-circle me-1"></i> Accept Materials & Inward to Yard
        </button>
    </div>
</form>
