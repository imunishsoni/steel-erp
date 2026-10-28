<div class="row mb-3 align-items-center">
    <div class="col-md-8">
        <h4 class="mb-0"><i class="bi bi-gear me-2 text-primary"></i>Company Profile & ERP Configuration</h4>
        <small class="text-muted">Statutory GSTIN, PAN, bank remittance details, and document numbering prefixes</small>
    </div>
</div>

<form action="<?= base_url('settings/update') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-4">
        <!-- Company Identity -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><i class="bi bi-building me-2"></i>Legal Identity & Works Address</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label required">Company Legal Name</label>
                            <input type="text" name="company_name" class="form-control fw-bold" value="<?= old('company_name', $settings['company_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Company Tagline / Subtitle</label>
                            <input type="text" name="tagline" class="form-control" value="<?= old('tagline', $settings['tagline'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Works Address Line 1</label>
                            <input type="text" name="address_line1" class="form-control" value="<?= old('address_line1', $settings['address_line1'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Works Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control" value="<?= old('address_line2', $settings['address_line2'] ?? '') ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="<?= old('city', $settings['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" value="<?= old('state', $settings['state'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">PIN Code</label>
                            <input type="text" name="pincode" class="form-control" value="<?= old('pincode', $settings['pincode'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= old('phone', $settings['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email', $settings['email'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GST & Statutory -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><i class="bi bi-file-earmark-check me-2"></i>Statutory & GST Details</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">GSTIN (15 Digits)</label>
                            <input type="text" name="gstin" class="form-control font-monospace text-uppercase fw-bold" maxlength="15" value="<?= old('gstin', $settings['gstin'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">PAN (10 Digits)</label>
                            <input type="text" name="pan" class="form-control font-monospace text-uppercase fw-bold" maxlength="10" value="<?= old('pan', $settings['pan'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Corporate Identification Number (CIN)</label>
                            <input type="text" name="cin" class="form-control font-monospace text-uppercase" value="<?= old('cin', $settings['cin'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Accounts -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><i class="bi bi-bank me-2"></i>Bank Remittance Details (For Tax Invoices)</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" value="<?= old('bank_name', $settings['bank_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Account No</label>
                            <input type="text" name="bank_account_no" class="form-control font-monospace" value="<?= old('bank_account_no', $settings['bank_account_no'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">IFSC Code</label>
                            <input type="text" name="bank_ifsc" class="form-control font-monospace text-uppercase" value="<?= old('bank_ifsc', $settings['bank_ifsc'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Branch Name</label>
                            <input type="text" name="bank_branch" class="form-control" value="<?= old('bank_branch', $settings['bank_branch'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Prefixes -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><i class="bi bi-hash me-2"></i>Document Numbering Prefixes</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Quotation</label>
                            <input type="text" name="quotation_prefix" class="form-control font-monospace" value="<?= old('quotation_prefix', $settings['quotation_prefix'] ?? 'QTN-') ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sales Order</label>
                            <input type="text" name="so_prefix" class="form-control font-monospace" value="<?= old('so_prefix', $settings['so_prefix'] ?? 'SO-') ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Purchase Order</label>
                            <input type="text" name="po_prefix" class="form-control font-monospace" value="<?= old('po_prefix', $settings['po_prefix'] ?? 'PO-') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Delivery Challan</label>
                            <input type="text" name="dc_prefix" class="form-control font-monospace" value="<?= old('dc_prefix', $settings['dc_prefix'] ?? 'DC-') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">GST Tax Invoice</label>
                            <input type="text" name="invoice_prefix" class="form-control font-monospace" value="<?= old('invoice_prefix', $settings['invoice_prefix'] ?? 'INV-') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4 mb-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Company Settings</button>
    </div>
</form>
