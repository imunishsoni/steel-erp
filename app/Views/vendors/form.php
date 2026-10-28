<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?= $isEdit ? 'Edit Vendor / Supplier' : 'Register Approved Vendor / Steel Mill' ?></h3>
        <p class="text-muted small mb-0">Record mill accreditation, statutory GSTIN, payment terms, and bank RTGS coordinates</p>
    </div>
    <a href="<?= base_url('vendors') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Vendors
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-building-gear me-2"></i>Vendor Profile</span>
    </div>
    <div class="p-4">
        <form action="<?= $isEdit ? base_url('vendors/update/' . $vendor['id']) : base_url('vendors/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Vendor Code</label>
                    <input type="text" name="vendor_code" class="form-control font-mono" value="<?= esc($vendor['vendor_code'] ?? '') ?>" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company / Mill Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" value="<?= esc($vendor['company_name'] ?? '') ?>" required placeholder="e.g. Steel Authority of India Ltd (SAIL)">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vendor Type <span class="text-danger">*</span></label>
                    <select name="vendor_type" class="form-select" required>
                        <option value="raw_material" <?= (isset($vendor['vendor_type']) && $vendor['vendor_type'] === 'raw_material') ? 'selected' : '' ?>>Raw Material (Plates & Sections)</option>
                        <option value="consumables" <?= (isset($vendor['vendor_type']) && $vendor['vendor_type'] === 'consumables') ? 'selected' : '' ?>>Consumables & Hardware</option>
                        <option value="services" <?= (isset($vendor['vendor_type']) && $vendor['vendor_type'] === 'services') ? 'selected' : '' ?>>Machining & NDT Services</option>
                        <option value="transport" <?= (isset($vendor['vendor_type']) && $vendor['vendor_type'] === 'transport') ? 'selected' : '' ?>>Heavy Trailer Logistics</option>
                        <option value="other" <?= (isset($vendor['vendor_type']) && $vendor['vendor_type'] === 'other') ? 'selected' : '' ?>>Other Supplies</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="<?= esc($vendor['contact_person'] ?? '') ?>" placeholder="Branch Manager / Key Account Executive">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($vendor['email'] ?? '') ?>" placeholder="sales@mill.com">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="<?= esc($vendor['mobile'] ?? '') ?>" placeholder="10-digit mobile">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= esc($vendor['phone'] ?? '') ?>" placeholder="Landline">
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Branch / Stockyard Address</h6>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Address Line 1</label>
                    <input type="text" name="address_line1" class="form-control" value="<?= esc($vendor['address_line1'] ?? '') ?>" placeholder="Office / Warehouse / Yard Address">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address Line 2</label>
                    <input type="text" name="address_line2" class="form-control" value="<?= esc($vendor['address_line2'] ?? '') ?>" placeholder="Industrial Area / Landmark">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= esc($vendor['city'] ?? '') ?>" placeholder="City">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State <span class="text-danger">*</span></label>
                    <select name="state" class="form-select" required>
                        <option value="">Select Indian State</option>
                        <?php foreach ($states as $s): ?>
                            <option value="<?= esc($s['state_name']) ?>" <?= (isset($vendor['state']) && strcasecmp($vendor['state'], $s['state_name']) === 0) ? 'selected' : '' ?>>
                                <?= esc($s['state_name']) ?> (<?= esc($s['state_code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" value="<?= esc($vendor['pincode'] ?? '') ?>" placeholder="PIN">
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-bank me-2 text-primary"></i>Statutory Taxation, Banking & Commercials</h6>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">GSTIN (15 Digits)</label>
                    <input type="text" name="gstin" class="form-control font-mono text-uppercase" maxlength="15" value="<?= esc($vendor['gstin'] ?? '') ?>" placeholder="08AAACS4115G1ZS">
                </div>
                <div class="col-md-3">
                    <label class="form-label">PAN (10 Digits)</label>
                    <input type="text" name="pan" class="form-control font-mono text-uppercase" maxlength="10" value="<?= esc($vendor['pan'] ?? '') ?>" placeholder="AAACS4115G">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control" value="<?= esc($vendor['payment_terms'] ?? '100% Advance against PI') ?>" placeholder="e.g. 30 Days Credit, 100% Advance">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vendor Rating</label>
                    <select name="rating" class="form-select">
                        <option value="5" <?= (isset($vendor['rating']) && $vendor['rating'] == 5) ? 'selected' : '' ?>>★★★★★ (Prime Producer)</option>
                        <option value="4" <?= (isset($vendor['rating']) && $vendor['rating'] == 4) ? 'selected' : '' ?>>★★★★☆ (Tier-1 Supplier)</option>
                        <option value="3" <?= (isset($vendor['rating']) && $vendor['rating'] == 3) ? 'selected' : '' ?>>★★★☆☆ (Standard)</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" value="<?= esc($vendor['bank_name'] ?? '') ?>" placeholder="e.g. State Bank of India">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bank Account Number</label>
                    <input type="text" name="bank_account_no" class="form-control font-mono" value="<?= esc($vendor['bank_account_no'] ?? '') ?>" placeholder="Account No">
                </div>
                <div class="col-md-4">
                    <label class="form-label">RTGS / IFSC Code</label>
                    <input type="text" name="bank_ifsc" class="form-control font-mono text-uppercase" value="<?= esc($vendor['bank_ifsc'] ?? '') ?>" placeholder="SBIN0004128">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Procurement & Quality Notes</label>
                <textarea name="notes" rows="2" class="form-control" placeholder="Test certificates format (TC), ultrasonic tested plates ASTM A435, delivery logistics"><?= esc($vendor['notes'] ?? '') ?></textarea>
            </div>

            <?php if ($isEdit): ?>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?= (!empty($vendor['is_active'])) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="is_active">Active Vendor</label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-end">
                <a href="<?= base_url('vendors') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-save me-1"></i> Save Vendor Record
                </button>
            </div>
        </form>
    </div>
</div>
