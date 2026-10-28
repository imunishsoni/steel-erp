<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?= $isEdit ? 'Edit Sales Order' : 'Create Sales Order (Contract Confirmation)' ?></h3>
        <p class="text-muted small mb-0">Record formal client Purchase Order (PO), delivery schedule, payment terms, and items</p>
    </div>
    <a href="<?= base_url('sales-orders') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Sales Orders
    </a>
</div>

<form action="<?= $isEdit ? base_url('sales-orders/update/' . $order['id']) : base_url('sales-orders/create') ?>" method="post" id="soForm">
    <?= csrf_field() ?>

    <input type="hidden" id="company_state" value="<?= esc($company['state'] ?? 'Rajasthan') ?>">
    <input type="hidden" name="quotation_id" value="<?= esc($order['quotation_id'] ?? '') ?>">

    <div class="steel-card mb-4">
        <div class="steel-card-header">
            <span class="fw-bold"><i class="bi bi-file-earmark-check me-2"></i>Sales Order Header</span>
        </div>
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Sales Order No</label>
                    <input type="text" name="so_no" class="form-control font-mono" value="<?= esc($order['so_no'] ?? '') ?>" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Order Date <span class="text-danger">*</span></label>
                    <input type="date" name="so_date" class="form-control" value="<?= esc($order['so_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Client PO No <span class="text-danger">*</span></label>
                    <input type="text" name="client_po_no" class="form-control font-mono" value="<?= esc($order['client_po_no'] ?? '') ?>" required placeholder="e.g. NTPC/RAM/PUR/2026/884">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Client PO Date</label>
                    <input type="date" name="client_po_date" class="form-control" value="<?= esc($order['client_po_date'] ?? date('Y-m-d')) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Client / Industrial Plant <span class="text-danger">*</span></label>
                    <select name="client_id" id="client_select" class="form-select select2" required>
                        <option value="">Select Client Plant</option>
                        <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id'] ?>" data-state="<?= esc($c['state']) ?>" <?= (isset($order['client_id']) && $order['client_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= esc($c['company_name']) ?> (<?= esc($c['city']) ?>, <?= esc($c['state']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Project Name <span class="text-danger">*</span></label>
                    <input type="text" name="project_name" class="form-control fw-bold" value="<?= esc($order['project_name'] ?? '') ?>" required placeholder="e.g. 800MW Stage-IV Boiler Structure & ESP Ducting">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Site Delivery Address</label>
                    <textarea name="delivery_address" rows="2" class="form-control" placeholder="Plant erection site or central store address"><?= esc($order['delivery_address'] ?? '') ?></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Scheduled Target Delivery</label>
                    <input type="date" name="delivery_date" class="form-control" value="<?= esc($order['delivery_date'] ?? date('Y-m-d', strtotime('+45 days'))) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control" value="<?= esc($order['payment_terms'] ?? '15% Advance, 75% Proforma, 10% on fitment') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="steel-card mb-4">
        <div class="steel-card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-list-ol me-2"></i>Scope of Supply & Order Quantities</span>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                <i class="bi bi-plus-circle me-1"></i> Add Line Item
            </button>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 4%;">#</th>
                            <th style="width: 25%;">Material Catalog Ref</th>
                            <th style="width: 28%;">Description & Scope</th>
                            <th style="width: 10%;">HSN</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 8%;">Unit</th>
                            <th style="width: 10%;">Rate (₹)</th>
                            <th style="width: 12%;">Amount (₹)</th>
                            <th style="width: 3%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item-row">
                            <td class="text-center sl-no">1</td>
                            <td>
                                <select name="items[1][material_id]" class="form-select form-select-sm material-select">
                                    <option value="">-- Custom Scope --</option>
                                    <?php foreach ($materials as $m): ?>
                                        <option value="<?= $m['id'] ?>" data-code="<?= esc($m['material_code']) ?>" data-spec="<?= esc($m['specification']) ?>" data-unit="<?= esc($m['unit']) ?>" data-rate="<?= esc($m['last_purchase_rate']) ?>" data-hsn="<?= esc($m['hsn_code']) ?>">
                                            <?= esc($m['name']) ?> (<?= esc($m['grade']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="items[1][description]" class="form-control form-control-sm mb-1" required placeholder="Description" value="Fabricated Heavy Structural Steel Girders">
                                <input type="text" name="items[1][specification]" class="form-control form-control-sm item-spec text-muted" placeholder="Drawing ref" value="IS 2062 Grade E250 / SA 516">
                            </td>
                            <td>
                                <input type="text" name="items[1][hsn_code]" class="form-control form-control-sm item-hsn font-mono" value="73089090">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[1][quantity]" class="form-control form-control-sm item-qty font-mono" value="10000" required>
                            </td>
                            <td>
                                <input type="text" name="items[1][unit]" class="form-control form-control-sm item-unit text-uppercase" value="kg" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[1][rate]" class="form-control form-control-sm item-rate font-mono" value="90.00" required>
                            </td>
                            <td class="text-end font-mono fw-bold">
                                <input type="hidden" name="items[1][amount]" class="item-amount" value="900000.00">
                                <span class="item-amount-display">9,00,000.00</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-light text-danger remove-row-btn p-0 px-1"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Calculations and Save -->
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="steel-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-2"></i>Execution Notes & Quality Milestones</h6>
                <textarea name="remarks" rows="5" class="form-control" placeholder="Inspection calls to be raised 7 days in advance. E-Way bill compliance. Mill TC verification required."><?= esc($order['remarks'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="steel-card p-4 h-100 bg-light">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-calculator me-2"></i>Financial Summary</h6>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="font-mono fw-bold">₹ <span id="subtotal_display">0.00</span></span>
                    <input type="hidden" name="subtotal" id="subtotal_input" value="0.00">
                </div>

                <div class="row align-items-center mb-2">
                    <div class="col-6"><span class="text-muted">Discount (%):</span></div>
                    <div class="col-6">
                        <input type="number" step="0.01" name="discount_pct" id="discount_pct_input" class="form-control form-control-sm font-mono text-end" value="0.00">
                        <input type="hidden" name="discount_amt" id="discount_amt_input" value="0.00">
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-3 border-top pt-2">
                    <span class="fw-semibold">Taxable Amount:</span>
                    <span class="font-mono fw-bold">₹ <span id="taxable_display">0.00</span></span>
                    <input type="hidden" name="taxable_amount" id="taxable_amount_input" value="0.00">
                </div>

                <div class="bg-white p-3 rounded border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-bold">Tax Split:</span>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="is_igst_checkbox">
                            <label class="form-check-label small" for="is_igst_checkbox">Inter-State (IGST 18%)</label>
                        </div>
                    </div>
                    <div id="cgst_row" class="d-flex justify-content-between small text-muted mb-1">
                        <span>CGST (9%):</span>
                        <span class="font-mono">₹ <span id="cgst_display">0.00</span></span>
                        <input type="hidden" name="cgst_amount" id="cgst_amount_input" value="0.00">
                    </div>
                    <div id="sgst_row" class="d-flex justify-content-between small text-muted mb-1">
                        <span>SGST (9%):</span>
                        <span class="font-mono">₹ <span id="sgst_display">0.00</span></span>
                        <input type="hidden" name="sgst_amount" id="sgst_amount_input" value="0.00">
                    </div>
                    <div id="igst_row" class="d-flex justify-content-between small text-muted mb-1" style="display: none;">
                        <span>IGST (18%):</span>
                        <span class="font-mono">₹ <span id="igst_display">0.00</span></span>
                        <input type="hidden" name="igst_amount" id="igst_amount_input" value="0.00">
                    </div>
                    <input type="hidden" name="total_tax" id="total_tax_input" value="0.00">
                </div>

                <div class="d-flex justify-content-between border-top border-2 border-dark pt-3 mb-2">
                    <span class="fs-5 fw-bold text-dark">Contract Total:</span>
                    <span class="fs-5 fw-bold text-primary font-mono">₹ <span id="grand_total_display">0.00</span></span>
                    <input type="hidden" name="grand_total" id="grand_total_input" value="0.00">
                </div>

                <div class="small text-muted mb-3" style="font-style: italic;">
                    <span id="amount_in_words_display">-</span>
                    <input type="hidden" name="amount_in_words" id="amount_in_words_input">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-check2-circle me-1"></i> Confirm & Save Sales Order
                </button>
            </div>
        </div>
    </div>
</form>

<script type="text/template" id="rowTemplate">
    <tr class="item-row">
        <td class="text-center sl-no">{INDEX}</td>
        <td>
            <select name="items[{INDEX}][material_id]" class="form-select form-select-sm material-select">
                <option value="">-- Custom Scope --</option>
                <?php foreach ($materials as $m): ?>
                    <option value="<?= $m['id'] ?>" data-code="<?= esc($m['material_code']) ?>" data-spec="<?= esc($m['specification']) ?>" data-unit="<?= esc($m['unit']) ?>" data-rate="<?= esc($m['last_purchase_rate']) ?>" data-hsn="<?= esc($m['hsn_code']) ?>">
                        <?= esc($m['name']) ?> (<?= esc($m['grade']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="text" name="items[{INDEX}][description]" class="form-control form-control-sm mb-1" required placeholder="Description">
            <input type="text" name="items[{INDEX}][specification]" class="form-control form-control-sm item-spec text-muted" placeholder="Drawing ref">
        </td>
        <td>
            <input type="text" name="items[{INDEX}][hsn_code]" class="form-control form-control-sm item-hsn font-mono" value="73089090">
        </td>
        <td>
            <input type="number" step="0.01" name="items[{INDEX}][quantity]" class="form-control form-control-sm item-qty font-mono" value="1000" required>
        </td>
        <td>
            <input type="text" name="items[{INDEX}][unit]" class="form-control form-control-sm item-unit text-uppercase" value="kg" required>
        </td>
        <td>
            <input type="number" step="0.01" name="items[{INDEX}][rate]" class="form-control form-control-sm item-rate font-mono" value="90.00" required>
        </td>
        <td class="text-end font-mono fw-bold">
            <input type="hidden" name="items[{INDEX}][amount]" class="item-amount" value="90000.00">
            <span class="item-amount-display">90,000.00</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-light text-danger remove-row-btn p-0 px-1"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</script>
