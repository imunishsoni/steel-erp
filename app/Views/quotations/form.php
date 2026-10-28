<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?= $isEdit ? 'Edit Quotation' : 'Create Quotation / Fabrication Cost Estimation' ?></h3>
        <p class="text-muted small mb-0">Build itemized scope with plate rolling, submerged arc welding, blast SA 2.5, and GST auto-splitting</p>
    </div>
    <a href="<?= base_url('quotations') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Quotations
    </a>
</div>

<form action="<?= $isEdit ? base_url('quotations/update/' . $quotation['id']) : base_url('quotations/create') ?>" method="post" id="quotationForm">
    <?= csrf_field() ?>

    <input type="hidden" id="company_state" value="<?= esc($company['state'] ?? 'Rajasthan') ?>">
    <input type="hidden" name="enquiry_id" value="<?= esc($quotation['enquiry_id'] ?? '') ?>">

    <!-- Top Card: Header info -->
    <div class="steel-card mb-4">
        <div class="steel-card-header">
            <span class="fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Proposal Header</span>
        </div>
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Quotation No</label>
                    <input type="text" name="quotation_no" class="form-control font-mono" value="<?= esc($quotation['quotation_no'] ?? '') ?>" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quotation Date <span class="text-danger">*</span></label>
                    <input type="date" name="quotation_date" class="form-control" value="<?= esc($quotation['quotation_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Validity Period (Days)</label>
                    <input type="date" name="valid_until" class="form-control" value="<?= esc($quotation['valid_until'] ?? date('Y-m-d', strtotime('+30 days'))) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= (isset($quotation['status']) && $quotation['status'] === 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="sent" <?= (!isset($quotation['status']) || $quotation['status'] === 'sent') ? 'selected' : '' ?>>Sent to Client</option>
                        <option value="accepted" <?= (isset($quotation['status']) && $quotation['status'] === 'accepted') ? 'selected' : '' ?>>Accepted / Confirmed</option>
                        <option value="rejected" <?= (isset($quotation['status']) && $quotation['status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Client / Industrial Plant <span class="text-danger">*</span></label>
                    <select name="client_id" id="client_select" class="form-select select2" required>
                        <option value="">Select Client Plant</option>
                        <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id'] ?>" data-state="<?= esc($c['state']) ?>" <?= (isset($quotation['client_id']) && $quotation['client_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= esc($c['company_name']) ?> (<?= esc($c['city']) ?>, <?= esc($c['state']) ?>) - <?= plant_type_badge($c['industry_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Project Name <span class="text-danger">*</span></label>
                    <input type="text" name="project_name" class="form-control fw-bold" value="<?= esc($quotation['project_name'] ?? '') ?>" required placeholder="e.g. 800MW Stage-IV Boiler Structure & ESP Ducting">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" value="<?= esc($quotation['subject'] ?? 'Quotation for Heavy Steel Fabrication and Supply') ?>" placeholder="Quotation subject line">
            </div>
        </div>
    </div>

    <!-- Items Grid Card -->
    <div class="steel-card mb-4">
        <div class="steel-card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-list-ol me-2"></i>Itemized Scope & Bill of Quantities (BOQ)</span>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                <i class="bi bi-plus-circle me-1"></i> Add Item Line
            </button>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 4%;">#</th>
                            <th style="width: 25%;">Material Catalog Ref</th>
                            <th style="width: 28%;">Item Description & Scope</th>
                            <th style="width: 10%;">HSN Code</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 8%;">Unit</th>
                            <th style="width: 10%;">Rate (₹)</th>
                            <th style="width: 12%;">Amount (₹)</th>
                            <th style="width: 3%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Initial Rows or Prefilled -->
                        <tr class="item-row">
                            <td class="text-center sl-no">1</td>
                            <td>
                                <select name="items[1][material_id]" class="form-select form-select-sm material-select">
                                    <option value="">-- Custom / Built-up Scope --</option>
                                    <?php foreach ($materials as $m): ?>
                                        <option value="<?= $m['id'] ?>" data-code="<?= esc($m['material_code']) ?>" data-spec="<?= esc($m['specification']) ?>" data-unit="<?= esc($m['unit']) ?>" data-rate="<?= esc($m['last_purchase_rate']) ?>" data-hsn="<?= esc($m['hsn_code']) ?>">
                                            <?= esc($m['name']) ?> (<?= esc($m['grade']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="items[1][description]" class="form-control form-control-sm mb-1" required placeholder="Fabricated Shell / Structure Description" value="ESP Casing Shell Panels with Stiffeners">
                                <input type="text" name="items[1][specification]" class="form-control form-control-sm item-spec text-muted" placeholder="Drawing ref / material spec" value="Fabricated from 16mm IS2062 E250 plates with ISA 75x75x6 stiffeners">
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
                                <input type="number" step="0.01" name="items[1][rate]" class="form-control form-control-sm item-rate font-mono" value="88.00" required>
                            </td>
                            <td class="text-end font-mono fw-bold">
                                <input type="hidden" name="items[1][amount]" class="item-amount" value="880000.00">
                                <span class="item-amount-display">8,80,000.00</span>
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

    <!-- Bottom Row: Commercials & Taxes Summary -->
    <div class="row g-3 mb-4">
        <!-- Terms & Notes -->
        <div class="col-lg-7">
            <div class="steel-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2 text-primary"></i>Commercial Terms & Conditions</h6>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Payment Terms</label>
                        <input type="text" name="payment_terms" class="form-control" value="<?= esc($quotation['payment_terms'] ?? '15% Advance, 75% Proforma, 10% on fitment') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Delivery Terms</label>
                        <input type="text" name="delivery_terms" class="form-control" value="<?= esc($quotation['delivery_terms'] ?? 'Ex-Works Jaipur / Transportation by Trailer') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Delivery Period</label>
                        <input type="text" name="delivery_period" class="form-control" value="<?= esc($quotation['delivery_period'] ?? '6 to 8 Weeks from Drawing Approval') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Taxes Note</label>
                        <input type="text" name="taxes_note" class="form-control" value="<?= esc($quotation['taxes_note'] ?? 'GST @ 18% extra as applicable') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Detailed Terms & Quality Inspection</label>
                    <textarea name="terms_conditions" rows="4" class="form-control small">1. Material Mill Test Certificates (TC) provided with supply.
2. Third-Party Inspection (TPI) by NTPC/EIL/TUV at our Works prior to dispatch.
3. Shot blasting to Swedish Standard SA 2.5 and Red Oxide primer application included.
4. All fabrication strictly as per IS 800:2007 and AWS D1.1 structural welding code.</textarea>
                </div>
            </div>
        </div>

        <!-- Calculation Panel -->
        <div class="col-lg-5">
            <div class="steel-card p-4 h-100 bg-light">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-calculator me-2 text-primary"></i>Price & GST Summary</h6>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Item Subtotal:</span>
                    <span class="font-mono fw-bold">₹ <span id="subtotal_display">0.00</span></span>
                    <input type="hidden" name="subtotal" id="subtotal_input" value="0.00">
                </div>

                <div class="row align-items-center mb-2">
                    <div class="col-6">
                        <span class="text-muted">Discount (%):</span>
                    </div>
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

                <!-- GST Section -->
                <div class="bg-white p-3 rounded border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-bold">GST Tax Classification:</span>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="is_igst_checkbox">
                            <label class="form-check-label small" for="is_igst_checkbox">Inter-State (IGST)</label>
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
                    <div class="col-6">
                        <span class="text-muted">Freight / Logistics (₹):</span>
                    </div>
                    <div class="col-6">
                        <input type="number" step="0.01" name="freight_charges" id="freight_input" class="form-control form-control-sm font-mono text-end" value="0.00">
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
                    <i class="bi bi-file-earmark-check me-1"></i> Save Formal Quotation
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Row Template for Add Row -->
<script type="text/template" id="rowTemplate">
    <tr class="item-row">
        <td class="text-center sl-no">{INDEX}</td>
        <td>
            <select name="items[{INDEX}][material_id]" class="form-select form-select-sm material-select">
                <option value="">-- Custom / Built-up Scope --</option>
                <?php foreach ($materials as $m): ?>
                    <option value="<?= $m['id'] ?>" data-code="<?= esc($m['material_code']) ?>" data-spec="<?= esc($m['specification']) ?>" data-unit="<?= esc($m['unit']) ?>" data-rate="<?= esc($m['last_purchase_rate']) ?>" data-hsn="<?= esc($m['hsn_code']) ?>">
                        <?= esc($m['name']) ?> (<?= esc($m['grade']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="text" name="items[{INDEX}][description]" class="form-control form-control-sm mb-1" required placeholder="Fabricated Shell / Structure Description">
            <input type="text" name="items[{INDEX}][specification]" class="form-control form-control-sm item-spec text-muted" placeholder="Drawing ref / material spec">
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
            <input type="number" step="0.01" name="items[{INDEX}][rate]" class="form-control form-control-sm item-rate font-mono" value="85.00" required>
        </td>
        <td class="text-end font-mono fw-bold">
            <input type="hidden" name="items[{INDEX}][amount]" class="item-amount" value="85000.00">
            <span class="item-amount-display">85,000.00</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-light text-danger remove-row-btn p-0 px-1"><i class="bi bi-trash"></i></button>
        </td>
    </tr>
</script>
