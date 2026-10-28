<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?= $isEdit ? 'Edit Project Enquiry' : 'Register New Project Enquiry / Tender' ?></h3>
        <p class="text-muted small mb-0">Record technical scope, target sectors, client contact, and estimation priority</p>
    </div>
    <a href="<?= base_url('enquiries') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Enquiries
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-clipboard-plus me-2"></i>Enquiry Technical Scope</span>
    </div>
    <div class="p-4">
        <form action="<?= $isEdit ? base_url('enquiries/update/' . $enquiry['id']) : base_url('enquiries/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Enquiry No</label>
                    <input type="text" name="enquiry_no" class="form-control font-mono" value="<?= esc($enquiry['enquiry_no'] ?? '') ?>" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Enquiry Date <span class="text-danger">*</span></label>
                    <input type="date" name="enquiry_date" class="form-control" value="<?= esc($enquiry['enquiry_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Plant Sector <span class="text-danger">*</span></label>
                    <select name="plant_type" class="form-select" required>
                        <option value="power" <?= (isset($enquiry['plant_type']) && $enquiry['plant_type'] === 'power') ? 'selected' : '' ?>>Power Plant</option>
                        <option value="sugar" <?= (isset($enquiry['plant_type']) && $enquiry['plant_type'] === 'sugar') ? 'selected' : '' ?>>Sugar Mill</option>
                        <option value="cement" <?= (isset($enquiry['plant_type']) && $enquiry['plant_type'] === 'cement') ? 'selected' : '' ?>>Cement Plant</option>
                        <option value="steel" <?= (isset($enquiry['plant_type']) && $enquiry['plant_type'] === 'steel') ? 'selected' : '' ?>>Steel Plant</option>
                        <option value="chemical" <?= (isset($enquiry['plant_type']) && $enquiry['plant_type'] === 'chemical') ? 'selected' : '' ?>>Chemical / Fertilizer</option>
                        <option value="other" <?= (isset($enquiry['plant_type']) && $enquiry['plant_type'] === 'other') ? 'selected' : '' ?>>General Engineering</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Lead Source</label>
                    <select name="source" class="form-select">
                        <option value="direct" <?= (isset($enquiry['source']) && $enquiry['source'] === 'direct') ? 'selected' : '' ?>>Direct Inquiry</option>
                        <option value="tender" <?= (isset($enquiry['source']) && $enquiry['source'] === 'tender') ? 'selected' : '' ?>>E-Tender Portal</option>
                        <option value="reference" <?= (isset($enquiry['source']) && $enquiry['source'] === 'reference') ? 'selected' : '' ?>>Client Reference</option>
                        <option value="email" <?= (isset($enquiry['source']) && $enquiry['source'] === 'email') ? 'selected' : '' ?>>Email RFQ</option>
                        <option value="website" <?= (isset($enquiry['source']) && $enquiry['source'] === 'website') ? 'selected' : '' ?>>Company Website</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Select Registered Client / Plant</label>
                    <select name="client_id" class="form-select select2">
                        <option value="">-- Non-Registered / Walk-in Client --</option>
                        <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (isset($enquiry['client_id']) && $enquiry['client_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= esc($c['company_name']) ?> (<?= esc($c['city']) ?>, <?= esc($c['state']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Client Name (if not in list)</label>
                    <input type="text" name="client_name" class="form-control" value="<?= esc($enquiry['client_name'] ?? '') ?>" placeholder="Client Organization Name">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="<?= esc($enquiry['contact_person'] ?? '') ?>" placeholder="Engr Name">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="<?= esc($enquiry['contact_phone'] ?? '') ?>" placeholder="Mobile">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" class="form-control" value="<?= esc($enquiry['contact_email'] ?? '') ?>" placeholder="email@client.com">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Project / Work Scope Name <span class="text-danger">*</span></label>
                <input type="text" name="project_name" class="form-control fw-bold" value="<?= esc($enquiry['project_name'] ?? '') ?>" required placeholder="e.g. 800MW Stage-IV Boiler Structure & ESP Ducting">
            </div>

            <div class="mb-3">
                <label class="form-label">Technical Description & Scope of Fabrication</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Specify structural steel details, drawings provided, NDT testing scope, radiography, blast cleaning SA 2.5, primer paint specs..."><?= esc($enquiry['description'] ?? '') ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Estimated Value (₹)</label>
                    <input type="number" step="0.01" name="estimated_value" class="form-control font-mono" value="<?= esc($enquiry['estimated_value'] ?? '0.00') ?>" placeholder="₹ Amount">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Expected Submission Date</label>
                    <input type="date" name="expected_date" class="form-control" value="<?= esc($enquiry['expected_date'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="low" <?= (isset($enquiry['priority']) && $enquiry['priority'] === 'low') ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= (!isset($enquiry['priority']) || $enquiry['priority'] === 'medium') ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= (isset($enquiry['priority']) && $enquiry['priority'] === 'high') ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= (isset($enquiry['priority']) && $enquiry['priority'] === 'urgent') ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="new" <?= (!isset($enquiry['status']) || $enquiry['status'] === 'new') ? 'selected' : '' ?>>New</option>
                        <option value="in_review" <?= (isset($enquiry['status']) && $enquiry['status'] === 'in_review') ? 'selected' : '' ?>>In Review / Costing</option>
                        <option value="quoted" <?= (isset($enquiry['status']) && $enquiry['status'] === 'quoted') ? 'selected' : '' ?>>Quoted / Proposal Sent</option>
                        <option value="converted" <?= (isset($enquiry['status']) && $enquiry['status'] === 'converted') ? 'selected' : '' ?>>Converted to Order</option>
                        <option value="lost" <?= (isset($enquiry['status']) && $enquiry['status'] === 'lost') ? 'selected' : '' ?>>Lost</option>
                        <option value="cancelled" <?= (isset($enquiry['status']) && $enquiry['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Assigned Estimation Engineer</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">-- Unassigned --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= (isset($enquiry['assigned_to']) && $enquiry['assigned_to'] == $u['id']) ? 'selected' : '' ?>>
                                <?= esc($u['full_name']) ?> (<?= esc($u['role']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Internal Remarks</label>
                    <input type="text" name="remarks" class="form-control" value="<?= esc($enquiry['remarks'] ?? '') ?>" placeholder="Internal notes or comments">
                </div>
            </div>

            <div class="text-end">
                <a href="<?= base_url('enquiries') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-save me-1"></i> Save Enquiry
                </button>
            </div>
        </form>
    </div>
</div>
