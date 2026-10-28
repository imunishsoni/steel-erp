<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-receipt me-2 text-primary"></i>Create GST Tax Invoice</h4>
        <div class="text-muted small">Standard Indian GST tax invoice with HSN classification & intra/inter-state tax auto-split</div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= base_url('invoices') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Invoices</a>
    </div>
</div>

<form action="<?= base_url('invoices/store') ?>" method="post" id="invoice-form">
    <?= csrf_field() ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <span class="fw-bold"><i class="bi bi-info-circle me-2"></i>Invoice Header & Client Details</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label required">Invoice Number</label>
                    <input type="text" name="invoice_no" class="form-control font-monospace fw-bold" value="<?= old('invoice_no', $autoInvNo) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Invoice Date</label>
                    <input type="date" name="invoice_date" class="form-control" value="<?= old('invoice_date', date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="<?= old('due_date', date('Y-m-d', strtotime('+30 days'))) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control" value="<?= old('payment_terms', '30 days net from invoice date') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label required">Billed To (Client)</label>
                    <select name="client_id" class="form-select select2" id="client-select" required>
                        <option value="">-- Select Client / Buyer --</option>
                        <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id'] ?>" 
                                    data-state="<?= esc($c['state']) ?>"
                                    data-gstin="<?= esc($c['gstin']) ?>"
                                    data-address="<?= esc(trim(($c['address_line1'] ?? '') . ', ' . ($c['address_line2'] ?? '') . ', ' . ($c['city'] ?? '') . ', ' . ($c['state'] ?? '') . ' - ' . ($c['pincode'] ?? ''), ', - ')) ?>"
                                    data-factory="<?= esc(trim(($c['address_line1'] ?? '') . ', ' . ($c['city'] ?? '') . ', ' . ($c['state'] ?? ''), ', - ')) ?>"
                                    <?= (old('client_id', $selectedClient) == $c['id']) ? 'selected' : '' ?>>
                                <?= esc($c['company_name']) ?> (<?= esc($c['state']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Linked Sales Order</label>
                    <select name="sales_order_id" class="form-select select2" id="so-select">
                        <option value="">-- Direct Invoice / None --</option>
                        <?php foreach ($salesOrders as $so): ?>
                            <option value="<?= $so['id'] ?>" <?= (old('sales_order_id', $selectedSO) == $so['id']) ? 'selected' : '' ?>>
                                <?= esc($so['so_no']) ?> &bull; <?= esc($so['client_company'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Linked Delivery Challan (DC)</label>
                    <select name="dispatch_id" class="form-select select2">
                        <option value="">-- Select Outward Challan --</option>
                        <?php foreach ($dispatches as $dc): ?>
                            <option value="<?= $dc['id'] ?>" <?= old('dispatch_id') == $dc['id'] ? 'selected' : '' ?>>
                                <?= esc($dc['dc_no']) ?> &bull; <?= esc($dc['vehicle_no']) ?> (<?= number_format($dc['total_weight'], 2) ?> MT)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label required">Buyer Billing Address & GSTIN</label>
                    <textarea name="billing_address" id="billing-address" class="form-control" rows="2" required><?= old('billing_address') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Consignee Shipping / Site Address</label>
                    <textarea name="shipping_address" id="shipping-address" class="form-control" rows="2" required><?= old('shipping_address') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label required">Place of Supply (State)</label>
                    <input type="text" name="place_of_supply" id="place_of_supply" class="form-control" value="<?= old('place_of_supply', 'Maharashtra') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">GST Tax Type</label>
                    <select name="is_igst" id="is_igst" class="form-select fw-bold">
                        <option value="0" <?= old('is_igst') == '0' ? 'selected' : '' ?>>Intra-State (CGST 9% + SGST 9%)</option>
                        <option value="1" <?= old('is_igst') == '1' ? 'selected' : '' ?>>Inter-State (IGST 18%)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Category</label>
                    <select name="invoice_type" class="form-select">
                        <option value="tax_invoice" selected>Tax Invoice (B2B)</option>
                        <option value="export">Export Invoice (LUT / Bond)</option>
                        <option value="supplementary">Supplementary / Debit Note</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-stack me-2"></i>Invoice Line Items (Fabricated Steel Structures)</span>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-item">
                <i class="bi bi-plus-circle me-1"></i>Add Item
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" id="invoice-items-table">
                <thead class="table-light small">
                    <tr>
                        <th style="width: 38%;">Description of Goods & Services</th>
                        <th style="width: 12%;">HSN / SAC</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 10%;">Unit</th>
                        <th style="width: 15%;">Rate (₹)</th>
                        <th style="width: 15%;" class="text-end">Taxable Value (₹)</th>
                        <th style="width: 5%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr>
                        <td>
                            <input type="text" name="item_description[]" class="form-control form-control-sm" placeholder="e.g. Fabricated Heavy Box Girders as per DWG-01" required>
                        </td>
                        <td>
                            <input type="text" name="item_hsn[]" class="form-control form-control-sm" value="73089090" required>
                        </td>
                        <td>
                            <input type="number" step="0.001" name="item_quantity[]" class="form-control form-control-sm item-qty text-end" value="1.000" min="0.001" required>
                        </td>
                        <td>
                            <select name="item_unit[]" class="form-select form-select-sm">
                                <option value="MT" selected>MT</option>
                                <option value="PCS">PCS</option>
                                <option value="SET">SET</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="item_rate[]" class="form-control form-control-sm item-rate text-end" value="78000.00" min="0" required>
                            <input type="hidden" name="item_gst_rate[]" class="item-gst" value="18">
                        </td>
                        <td class="text-end">
                            <input type="number" step="0.01" name="item_amount[]" class="form-control form-control-sm item-amount text-end bg-light" value="78000.00" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Calculations Footer -->
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><i class="bi bi-card-text me-2"></i>Invoice Terms & Bank Details</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small">Amount in Words (INR)</label>
                        <div class="p-2 border rounded bg-light text-primary fw-semibold small" id="amount-in-words-display">
                            Rupees Seventy-Eight Thousand Only
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Terms & Conditions</label>
                        <textarea name="terms_conditions" class="form-control small" rows="2">1. Interest @ 18% p.a. will be charged for delayed payments beyond due date.
2. Goods once sold will not be taken back.
3. Subject to Nagpur jurisdiction only.</textarea>
                    </div>
                    <div>
                        <label class="form-label small">Internal Notes</label>
                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="e.g. Milestone 2 against erection drawings approval">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><i class="bi bi-calculator me-2"></i>Tax Summary</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal (Taxable Value):</span>
                        <input type="hidden" name="subtotal" id="input-subtotal" value="78000.00">
                        <input type="hidden" name="taxable_amount" id="input-taxable" value="78000.00">
                        <strong id="display-subtotal">₹ 78,000.00</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Trade Discount:</span>
                        <div style="width: 140px;">
                            <input type="number" step="0.01" name="discount_amt" id="input-discount" class="form-control form-control-sm text-end" value="0.00">
                        </div>
                    </div>

                    <div id="intra-state-taxes">
                        <div class="d-flex justify-content-between mb-2">
                            <span>CGST (9%):</span>
                            <input type="hidden" name="cgst_amount" id="input-cgst" value="7020.00">
                            <span id="display-cgst">₹ 7,020.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>SGST (9%):</span>
                            <input type="hidden" name="sgst_amount" id="input-sgst" value="7020.00">
                            <span id="display-sgst">₹ 7,020.00</span>
                        </div>
                    </div>

                    <div id="inter-state-taxes" style="display: none;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>IGST (18%):</span>
                            <input type="hidden" name="igst_amount" id="input-igst" value="0.00">
                            <span id="display-igst">₹ 0.00</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Tax Amount:</span>
                        <input type="hidden" name="total_tax" id="input-totaltax" value="14040.00">
                        <span class="fw-semibold text-danger" id="display-totaltax">₹ 14,040.00</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Round Off:</span>
                        <div style="width: 140px;">
                            <input type="number" step="0.01" name="round_off" id="input-roundoff" class="form-control form-control-sm text-end" value="0.00" readonly>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold">Grand Total:</span>
                        <input type="hidden" name="grand_total" id="input-grandtotal" value="92040.00">
                        <span class="fs-4 fw-bold text-primary" id="display-grandtotal">₹ 92,040.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-4">
        <a href="<?= base_url('invoices') ?>" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save & Generate Tax Invoice</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('client-select');
    const isIgstSelect = document.getElementById('is_igst');
    const placeOfSupply = document.getElementById('place_of_supply');
    const billingAddress = document.getElementById('billing-address');
    const shippingAddress = document.getElementById('shipping-address');
    const intraBlock = document.getElementById('intra-state-taxes');
    const interBlock = document.getElementById('inter-state-taxes');
    const tbody = document.getElementById('items-body');
    const btnAdd = document.getElementById('btn-add-item');

    // Indian Number to words converter
    function numberToWords(num) {
        num = Math.round(num);
        if (num === 0) return 'Zero Rupees Only';
        const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        function convertGroup(n) {
            let str = '';
            if (n >= 100) {
                str += a[Math.floor(n / 100)] + ' Hundred ';
                n %= 100;
            }
            if (n >= 20) {
                str += b[Math.floor(n / 10)] + ' ';
                n %= 10;
            }
            if (n > 0) {
                str += a[n] + ' ';
            }
            return str;
        }

        let words = '';
        let crore = Math.floor(num / 10000000);
        let rem = num % 10000000;
        let lakh = Math.floor(rem / 100000);
        rem %= 100000;
        let thousand = Math.floor(rem / 1000);
        rem %= 1000;

        if (crore > 0) words += convertGroup(crore) + 'Crore ';
        if (lakh > 0) words += convertGroup(lakh) + 'Lakh ';
        if (thousand > 0) words += convertGroup(thousand) + 'Thousand ';
        if (rem > 0) words += convertGroup(rem);

        return 'Rupees ' + words.trim() + ' Only';
    }

    function calculateTotals() {
        let subtotal = 0;
        tbody.querySelectorAll('tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
            const amt = qty * rate;
            row.querySelector('.item-amount').value = amt.toFixed(2);
            subtotal += amt;
        });

        const discount = parseFloat(document.getElementById('input-discount').value) || 0;
        const taxable = Math.max(0, subtotal - discount);
        const isIgst = isIgstSelect.value === '1';

        let cgst = 0, sgst = 0, igst = 0, totalTax = 0;

        if (isIgst) {
            igst = Math.round(taxable * 0.18 * 100) / 100;
            totalTax = igst;
            intraBlock.style.display = 'none';
            interBlock.style.display = 'block';
        } else {
            cgst = Math.round(taxable * 0.09 * 100) / 100;
            sgst = Math.round(taxable * 0.09 * 100) / 100;
            totalTax = cgst + sgst;
            intraBlock.style.display = 'block';
            interBlock.style.display = 'none';
        }

        const rawGrand = taxable + totalTax;
        const roundedGrand = Math.round(rawGrand);
        const roundOff = Math.round((roundedGrand - rawGrand) * 100) / 100;

        document.getElementById('input-subtotal').value = subtotal.toFixed(2);
        document.getElementById('input-taxable').value = taxable.toFixed(2);
        document.getElementById('input-cgst').value = cgst.toFixed(2);
        document.getElementById('input-sgst').value = sgst.toFixed(2);
        document.getElementById('input-igst').value = igst.toFixed(2);
        document.getElementById('input-totaltax').value = totalTax.toFixed(2);
        document.getElementById('input-roundoff').value = roundOff.toFixed(2);
        document.getElementById('input-grandtotal').value = roundedGrand.toFixed(2);

        document.getElementById('display-subtotal').textContent = '₹ ' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('display-cgst').textContent = '₹ ' + cgst.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('display-sgst').textContent = '₹ ' + sgst.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('display-igst').textContent = '₹ ' + igst.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('display-totaltax').textContent = '₹ ' + totalTax.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('display-grandtotal').textContent = '₹ ' + roundedGrand.toLocaleString('en-IN', {minimumFractionDigits: 2});

        document.getElementById('amount-in-words-display').textContent = numberToWords(roundedGrand);
    }

    if (clientSelect) {
        clientSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                const state = opt.getAttribute('data-state') || 'Maharashtra';
                const gstin = opt.getAttribute('data-gstin') || '';
                const addr = opt.getAttribute('data-address') || '';
                const fac = opt.getAttribute('data-factory') || addr;

                billingAddress.value = `${opt.text.split('(')[0].trim()}\n${addr}\nGSTIN: ${gstin}`;
                shippingAddress.value = fac;
                placeOfSupply.value = state;

                // State code 27 is Maharashtra
                if (state.trim().toLowerCase() === 'maharashtra') {
                    isIgstSelect.value = '0';
                } else {
                    isIgstSelect.value = '1';
                }
                calculateTotals();
            }
        });
    }

    isIgstSelect.addEventListener('change', calculateTotals);
    document.getElementById('input-discount').addEventListener('input', calculateTotals);

    tbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-rate')) {
            calculateTotals();
        }
    });

    btnAdd.addEventListener('click', function() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="text" name="item_description[]" class="form-control form-control-sm" placeholder="Fabricated structure description" required>
            </td>
            <td>
                <input type="text" name="item_hsn[]" class="form-control form-control-sm" value="73089090" required>
            </td>
            <td>
                <input type="number" step="0.001" name="item_quantity[]" class="form-control form-control-sm item-qty text-end" value="1.000" min="0.001" required>
            </td>
            <td>
                <select name="item_unit[]" class="form-select form-select-sm">
                    <option value="MT" selected>MT</option>
                    <option value="PCS">PCS</option>
                    <option value="SET">SET</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" name="item_rate[]" class="form-control form-control-sm item-rate text-end" value="75000.00" min="0" required>
                <input type="hidden" name="item_gst_rate[]" class="item-gst" value="18">
            </td>
            <td class="text-end">
                <input type="number" step="0.01" name="item_amount[]" class="form-control form-control-sm item-amount text-end bg-light" value="75000.00" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        calculateTotals();
    });

    tbody.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-item')) {
            if (tbody.querySelectorAll('tr').length > 1) {
                e.target.closest('tr').remove();
                calculateTotals();
            }
        }
    });

    calculateTotals();
});
</script>
