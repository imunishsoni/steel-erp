<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-cash-stack me-2 text-success"></i>Record Client Payment Receipt</h4>
        <div class="text-muted small">Log inward bank transfer / RTGS against outstanding GST tax invoices</div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= base_url('payments/received') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Receipts</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-receipt-cutoff me-2"></i>Bank Receipt Voucher</span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('payments/store-receipt') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Receipt Voucher No</label>
                            <input type="text" name="receipt_no" class="form-control font-monospace fw-bold" value="<?= old('receipt_no', $autoReceiptNo) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Receipt Date (Credit Date)</label>
                            <input type="date" name="receipt_date" class="form-control" value="<?= old('receipt_date', date('Y-m-d')) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Client / Remitter</label>
                            <select name="client_id" class="form-select select2" id="client-select" required>
                                <option value="">-- Select Client --</option>
                                <?php foreach ($clients as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= (old('client_id', $selectedInv['client_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                                        <?= esc($c['company_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Against Tax Invoice (Optional)</label>
                            <select name="invoice_id" class="form-select select2" id="invoice-select">
                                <option value="">-- On Account / Advance Payment --</option>
                                <?php foreach ($invoices as $inv): ?>
                                    <option value="<?= $inv['id'] ?>" 
                                            data-client-id="<?= $inv['client_id'] ?>"
                                            data-balance="<?= $inv['balance_amount'] ?>"
                                            <?= (old('invoice_id', $selectedInv['id'] ?? '') == $inv['id']) ? 'selected' : '' ?>>
                                        <?= esc($inv['invoice_no']) ?> &bull; Due: ₹<?= number_format($inv['balance_amount'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Amount Credited in Bank (₹)</label>
                            <input type="number" step="0.01" name="amount" id="amount-input" class="form-control fw-bold fs-5 text-success" placeholder="0.00" value="<?= old('amount', $selectedInv['balance_amount'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">TDS Deducted by Client (₹)</label>
                            <input type="number" step="0.01" name="tds_amount" class="form-control" placeholder="0.00" value="<?= old('tds_amount', '0.00') ?>">
                            <div class="small text-muted">Sec 194Q / 194C TDS credit will be adjusted against invoice balance</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Payment Mode</label>
                            <select name="payment_mode" class="form-select">
                                <option value="rtgs" selected>RTGS / NEFT</option>
                                <option value="cheque">Cheque / Demand Draft</option>
                                <option value="lc">Letter of Credit (LC)</option>
                                <option value="bank_transfer">Bank Transfer / IMPS</option>
                                <option value="cash">Cash (Minor)</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UTR No / Cheque No</label>
                            <input type="text" name="reference_no" class="form-control font-monospace" placeholder="e.g. SBIN02948571" value="<?= old('reference_no') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Deposited in Bank Account</label>
                            <input type="text" name="bank_name" class="form-control" value="<?= old('bank_name', 'State Bank of India - A/c 39485720194') ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Voucher Narration / Remarks</label>
                            <input type="text" name="remarks" class="form-control" placeholder="e.g. Cleared towards supply of structural steel girder batch #1" value="<?= old('remarks') ?>">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?= base_url('payments/received') ?>" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Record & Reconcile Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const invSelect = document.getElementById('invoice-select');
    const clientSelect = document.getElementById('client-select');
    const amtInput = document.getElementById('amount-input');

    if (invSelect) {
        invSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                const clientId = opt.getAttribute('data-client-id');
                const balance = opt.getAttribute('data-balance');
                if (clientId && clientSelect) {
                    clientSelect.value = clientId;
                    $(clientSelect).trigger('change');
                }
                if (balance && !amtInput.value) {
                    amtInput.value = balance;
                }
            }
        });
    }
});
</script>
