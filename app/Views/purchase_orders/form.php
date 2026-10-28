<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?= $isEdit ? 'Edit Purchase Order' : 'Create Purchase Order (Procurement)' ?></h3>
        <p class="text-muted small mb-0">Procure structural steel plates, beams, seamless pipes, and consumables</p>
    </div>
    <a href="<?= base_url('purchase-orders') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Purchase Orders
    </a>
</div>

<form action="<?= $isEdit ? base_url('purchase-orders/update/' . $order['id']) : base_url('purchase-orders/create') ?>" method="post" id="poForm">
    <?= csrf_field() ?>

    <input type="hidden" id="company_state" value="<?= esc($company['state'] ?? 'Rajasthan') ?>">

    <div class="steel-card mb-4">
        <div class="steel-card-header">
            <span class="fw-bold"><i class="bi bi-file-earmark-plus me-2"></i>PO Header</span>
        </div>
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">PO Number</label>
                    <input type="text" name="po_no" class="form-control font-mono" value="<?= esc($order['po_no'] ?? '') ?>" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-3">
                    <label class="form-label">PO Date <span class="text-danger">*</span></label>
                    <input type="date" name="po_date" class="form-control" value="<?= esc($order['po_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Steel Mill / Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="client_select" class="form-select select2" required>
                        <option value="">Select Vendor</option>
                        <?php foreach ($vendors as $v): ?>
                            <option value="<?= $v['id'] ?>" data-state="<?= esc($v['state']) ?>" <?= (isset($order['vendor_id']) && $order['vendor_id'] == $v['id']) ? 'selected' : '' ?>>
                                <?= esc($v['company_name']) ?> (<?= esc($v['city']) ?>, <?= esc($v['state']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Linked Sales Order (Optional)</label>
                    <select name="reference_so" class="form-select select2">
                        <option value="">-- General / Yard Stock --</option>
                        <?php foreach ($salesOrders as $so): ?>
                            <option value="<?= $so['id'] ?>" <?= (isset($order['reference_so']) && $order['reference_so'] == $so['id']) ? 'selected' : '' ?>>
                                <?= esc($so['so_no']) ?> - <?= esc($so['project_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Expected Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control" value="<?= esc($order['delivery_date'] ?? date('Y-m-d', strtotime('+15 days'))) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Destination</label>
                    <input type="text" name="delivery_address" class="form-control" value="<?= esc($order['delivery_address'] ?? 'Heavy Steel Fabricators Yard, Jaipur Works') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control" value="<?= esc($order['payment_terms'] ?? '100% Advance against PI / RTGS') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="steel-card mb-4">
        <div class="steel-card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-list-ol me-2"></i>Raw Material & Consumables Order List</span>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                <i class="bi bi-plus-circle me-1"></i> Add Material Row
            </button>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 4%;">#</th>
                            <th style="width: 30%;">Steel Material Catalog</th>
                            <th style="width: 25%;">Description & Dimension</th>
                            <th style="width: 10%;">HSN</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 6%;">Unit</th>
                            <th style="width: 10%;">Rate (₹)</th>
                            <th style="width: 12%;">Amount (₹)</th>
                            <th style="width: 3%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item-row">
                            <td class="text-center sl-no">1</td>
                            <td>
                                <select name="items[1][material_id]" class="form-select form-select-sm material-select" required>
                                    <option value="">Select Material</option>
                                    <?php foreach ($materials as $m): ?>
                                        <option value="<?= $m['id'] ?>" data-code="<?= esc($m['material_code']) ?>" data-spec="<?= esc($m['specification']) ?>" data-unit="<?= esc($m['unit']) ?>" data-rate="<?= esc($m['last_purchase_rate']) ?>" data-hsn="<?= esc($m['hsn_code']) ?>">
                                            <?= esc($m['name']) ?> (<?= esc($m['grade']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="items[1][description]" class="form-control form-control-sm mb-1" placeholder="Plate / Beam Dimension">
                                <input type="text" name="items[1][specification]" class="form-control form-control-sm item-spec text-muted" placeholder="Specification">
                            </td>
                            <td>
                                <input type="text" name="items[1][hsn_code]" class="form-control form-control-sm item-hsn font-mono" value="72085100">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[1][quantity]" class="form-control form-control-sm item-qty font-mono" value="5000" required>
                            </td>
                            <td>
                                <input type="text" name="items[1][unit]" class="form-control form-control-sm item-unit text-uppercase" value="kg" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[1][rate]" class="form-control form-control-sm item-rate font-mono" value="65.00" required>
                            </td>
                            <td class="text-end font-mono fw-bold">
                                <input type="hidden" name="items[1][amount]" class="item-amount" value="325000.00">
                                <span class="item-amount-display">3,25,000.00</span>
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

    <!-- Calculations -->
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="steel-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-2"></i>Purchase Terms & Quality Conditions</h6>
                <textarea name="terms_conditions" rows="5" class="form-control small">1. Material Test Certificates (TC) with heat numbers must be sent along with consignment.
2. Steel plates must be ultrasonically tested as per ASTM A435 / A578 Level-II.
3. Dimensions and rolling tolerances strictly as per IS 1852.
4. Weighment at our Works weighbridge will be final and binding.</textarea>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="steel-card p-4 h-100 bg-light">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-calculator me-2"></i>Procurement Total</h6>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="font-mono fw-bold">₹ <span id="subtotal_display">0.00</span></span>
                    <input type="hidden" name="subtotal" id="subtotal_input" value="0.00">
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

                <div class="row align-items-center mb-3">
                    <div class="col-6"><span class="text-muted">Freight / Logistics (₹):</span></div>
                    <div class="col-6">
                        <input type="number" step="0.01" name="freight" id="freight_input" class="form-control form-control-sm font-mono text-end" value="0.00">
                    </div>
                </div>

                <div class="d-flex justify-content-between border-top border-2 border-dark pt-3 mb-2">
                    <span class="fs-5 fw-bold text-dark">Grand Total:</span>
                    <span class="fs-5 fw-bold text-primary font-mono">₹ <span id="grand_total_display">0.00</span></span>
                    <input type="hidden" name="grand_total" id="grand_total_input" value="0.00">
                </div>

                <div class="small text-muted mb-3" style="font-style: italic;">
                    <span id="amount_in_words_display">-</span>
                    <input type="hidden" name="amount_in_words" id="amount_in_words_input">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-save me-1"></i> Issue Purchase Order
                </button>
            </div>
        </div>
    </div>
</form>

<script type="text/template" id="rowTemplate">
    <tr class="item-row">
        <td class="text-center sl-no">{INDEX}</td>
        <td>
            <select name="items[{INDEX}][material_id]" class="form-select form-select-sm material-select" required>
                <option value="">Select Material</option>
                <?php foreach ($materials as $m): ?>
                    <option value="<?= $m['id'] ?>" data-code="<?= esc($m['material_code']) ?>" data-spec="<?= esc($m['specification']) ?>" data-unit="<?= esc($m['unit']) ?>" data-rate="<?= esc($m['last_purchase_rate']) ?>" data-hsn="<?= esc($m['hsn_code']) ?>">
                        <?= esc($m['name']) ?> (<?= esc($m['grade']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="text" name="items[{INDEX}][description]" class="form-control form-control-sm mb-1" placeholder="Dimension">
            <input type="text" name="items[{INDEX}][specification]" class="form-control form-control-sm item-spec text-muted" placeholder="Specification">
        </td>
        <td>
            <input type="text" name="items[{INDEX}][hsn_code]" class="form-control form-control-sm item-hsn font-mono" value="72085100">
        </td>
        <td>
            <input type="number" step="0.01" name="items[{INDEX}][quantity]" class="form-control form-control-sm item-qty font-mono" value="1000" required>
        </td>
        <td>
            <input type="text" name="items[{INDEX}][unit]" class="form-control form-control-sm item-unit text-uppercase" value="kg" required>
        </td>
        <td>
            <input type="number" step="0.01" name="items[{INDEX}][rate]" class="form-control form-control-sm item-rate font-mono" value="65.00" required>
        </td>
        <td class="text-end font-mono fw-bold">
            <input type="hidden" name="items[{INDEX}][amount]" class="item-amount" value="65000.00">
            <span class="item-amount-display">65,000.00</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-light text-danger remove-row-btn p-0 px-1"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</script>
