<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?= $isEdit ? 'Edit Client / Industrial Plant' : 'Add New Client / Industrial Plant' ?></h3>
        <p class="text-muted small mb-0">Record plant location, statutory GSTIN, credit limits, and contact details</p>
    </div>
    <a href="<?= base_url('clients') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Clients
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-building-add me-2"></i>Plant & Client Details</span>
    </div>
    <div class="p-4">
        <form action="<?= $isEdit ? base_url('clients/update/' . $client['id']) : base_url('clients/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Client Code</label>
                    <input type="text" name="client_code" class="form-control font-mono" value="<?= esc($client['client_code'] ?? '') ?>" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company / Organization Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" value="<?= esc($client['company_name'] ?? '') ?>" required placeholder="e.g. NTPC Limited, UltraTech Cement Ltd">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Industry Plant Sector <span class="text-danger">*</span></label>
                    <select name="industry_type" class="form-select" required>
                        <option value="power_plant" <?= (isset($client['industry_type']) && $client['industry_type'] === 'power_plant') ? 'selected' : '' ?>>Power Plant</option>
                        <option value="sugar_mill" <?= (isset($client['industry_type']) && $client['industry_type'] === 'sugar_mill') ? 'selected' : '' ?>>Sugar Mill</option>
                        <option value="cement_plant" <?= (isset($client['industry_type']) && $client['industry_type'] === 'cement_plant') ? 'selected' : '' ?>>Cement Plant</option>
                        <option value="steel_plant" <?= (isset($client['industry_type']) && $client['industry_type'] === 'steel_plant') ? 'selected' : '' ?>>Steel Plant</option>
                        <option value="chemical" <?= (isset($client['industry_type']) && $client['industry_type'] === 'chemical') ? 'selected' : '' ?>>Chemical / Petrochem</option>
                        <option value="infrastructure" <?= (isset($client['industry_type']) && $client['industry_type'] === 'infrastructure') ? 'selected' : '' ?>>Infrastructure</option>
                        <option value="other" <?= (isset($client['industry_type']) && $client['industry_type'] === 'other') ? 'selected' : '' ?>>General Engineering / Other</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="<?= esc($client['contact_person'] ?? '') ?>" placeholder="Chief Engineer / Procurement Manager">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($client['email'] ?? '') ?>" placeholder="procurement@client.com">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="<?= esc($client['mobile'] ?? '') ?>" placeholder="10-digit mobile">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Office Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= esc($client['phone'] ?? '') ?>" placeholder="Landline">
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Plant Address & State (Crucial for CGST/SGST vs IGST)</h6>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Address Line 1 (Plant / Unit)</label>
                    <input type="text" name="address_line1" class="form-control" value="<?= esc($client['address_line1'] ?? '') ?>" placeholder="Plot / Plant Complex / Village">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address Line 2 (Tehsil / Area)</label>
                    <input type="text" name="address_line2" class="form-control" value="<?= esc($client['address_line2'] ?? '') ?>" placeholder="Post / Landmark / Industrial Area">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">City / District</label>
                    <input type="text" name="city" class="form-control" value="<?= esc($client['city'] ?? '') ?>" placeholder="e.g. Jodhpur, Ramagundam">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State <span class="text-danger">*</span></label>
                    <select name="state" class="form-select" required>
                        <option value="">Select Indian State</option>
                        <?php foreach ($states as $s): ?>
                            <option value="<?= esc($s['state_name']) ?>" <?= (isset($client['state']) && strcasecmp($client['state'], $s['state_name']) === 0) ? 'selected' : '' ?>>
                                <?= esc($s['state_name']) ?> (<?= esc($s['state_code']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" value="<?= esc($client['pincode'] ?? '') ?>" placeholder="6-digit PIN">
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Statutory Taxation & Credit Terms</h6>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">GSTIN (15 Digits)</label>
                    <input type="text" name="gstin" class="form-control font-mono text-uppercase" maxlength="15" value="<?= esc($client['gstin'] ?? '') ?>" placeholder="08AABCU9603R1ZM">
                </div>
                <div class="col-md-3">
                    <label class="form-label">PAN (10 Digits)</label>
                    <input type="text" name="pan" class="form-control font-mono text-uppercase" maxlength="10" value="<?= esc($client['pan'] ?? '') ?>" placeholder="AABCU9603R">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Credit Limit (INR ₹)</label>
                    <input type="number" step="0.01" name="credit_limit" class="form-control font-mono" value="<?= esc($client['credit_limit'] ?? '1000000') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Credit Days</label>
                    <input type="number" name="credit_days" class="form-control font-mono" value="<?= esc($client['credit_days'] ?? '30') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Technical Notes & Plant Requirements</label>
                <textarea name="notes" rows="3" class="form-control" placeholder="Specific metallurgy, ASME/IS standards, third-party inspection agency (e.g. EIL, TUV, BV)"><?= esc($client['notes'] ?? '') ?></textarea>
            </div>

            <?php if ($isEdit): ?>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?= (!empty($client['is_active'])) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="is_active">Active Account</label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-end">
                <a href="<?= base_url('clients') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-save me-1"></i> Save Client Record
                </button>
            </div>
        </form>
    </div>
</div>
