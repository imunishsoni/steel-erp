<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-wallet2 me-2 text-danger"></i>Record Vendor Payment Voucher</h4>
        <div class="text-muted small">Disburse outward payments to SAIL/JSPL steel mills, raw material suppliers & vendors</div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= base_url('payments/made') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Payments</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-cash-coin me-2"></i>Vendor Disbursal Voucher</span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('payments/store-payment') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Payment Voucher No</label>
                            <input type="text" name="payment_no" class="form-control font-monospace fw-bold" value="<?= old('payment_no', $autoVoucherNo) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Voucher Date</label>
                            <input type="date" name="payment_date" class="form-control" value="<?= old('payment_date', date('Y-m-d')) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Vendor / Supplier (Steel Mill)</label>
                            <select name="vendor_id" class="form-select select2" required>
                                <option value="">-- Select Vendor --</option>
                                <?php foreach ($vendors as $v): ?>
                                    <option value="<?= $v['id'] ?>" <?= old('vendor_id') == $v['id'] ? 'selected' : '' ?>>
                                        <?= esc($v['company_name']) ?> (<?= esc($v['city'] ?: $v['state']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Against Purchase Order (PO)</label>
                            <select name="purchase_order_id" class="form-select select2">
                                <option value="">-- Direct Payment / LC Settlement --</option>
                                <?php foreach ($pos as $po): ?>
                                    <option value="<?= $po['id'] ?>" <?= old('purchase_order_id') == $po['id'] ? 'selected' : '' ?>>
                                        <?= esc($po['po_no']) ?> &bull; Total: ₹<?= number_format($po['grand_total'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Amount Disbursed (₹)</label>
                            <input type="number" step="0.01" name="amount" class="form-control fw-bold fs-5 text-danger" placeholder="0.00" value="<?= old('amount') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">TDS Deducted (₹)</label>
                            <input type="number" step="0.01" name="tds_amount" class="form-control" placeholder="0.00" value="<?= old('tds_amount', '0.00') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Payment Mode</label>
                            <select name="payment_mode" class="form-select">
                                <option value="rtgs" selected>RTGS / NEFT</option>
                                <option value="cheque">Cheque</option>
                                <option value="lc">Letter of Credit (LC)</option>
                                <option value="bank_transfer">Direct Bank Transfer</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">UTR Ref / Cheque No</label>
                            <input type="text" name="reference_no" class="form-control font-monospace" placeholder="e.g. UTR-9827361" value="<?= old('reference_no') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Paid From Bank Account</label>
                            <input type="text" name="bank_name" class="form-control" value="<?= old('bank_name', 'State Bank of India - Current A/c') ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Payment Narration</label>
                            <input type="text" name="remarks" class="form-control" placeholder="e.g. Advance 30% against SAIL plate order for thermal project" value="<?= old('remarks') ?>">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?= base_url('payments/made') ?>" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Authorize & Record Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
