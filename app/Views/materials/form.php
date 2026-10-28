<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><?= $isEdit ? 'Edit Material Specification' : 'Add New Material to Catalog' ?></h3>
        <p class="text-muted small mb-0">Define grade, standard specs (IS 2062, ASME SA516), HSN code, and reorder levels</p>
    </div>
    <a href="<?= base_url('materials') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Catalog
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-box-seam me-2"></i>Material Technical Specification</span>
    </div>
    <div class="p-4">
        <form action="<?= $isEdit ? base_url('materials/update/' . $material['id']) : base_url('materials/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Material Code <span class="text-danger">*</span></label>
                    <input type="text" name="material_code" class="form-control font-mono text-uppercase" value="<?= esc($material['material_code'] ?? '') ?>" required placeholder="e.g. MS-PLT-32-SA516">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Material Name & Description <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= esc($material['name'] ?? '') ?>" required placeholder="e.g. MS Plate 32mm SA516 Gr70 Boiler Quality">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (isset($material['category_id']) && $material['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= esc($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Steel Grade / Standard</label>
                    <input type="text" name="grade" class="form-control" value="<?= esc($material['grade'] ?? '') ?>" placeholder="e.g. IS2062 E250, SA516 Gr70">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Thickness / Section</label>
                    <input type="text" name="thickness" class="form-control" value="<?= esc($material['thickness'] ?? '') ?>" placeholder="e.g. 16mm, 20mm, 200mm">
                </div>
                <div class="col-md-3">
                    <label class="form-label">HSN Code <span class="text-danger">*</span></label>
                    <input type="text" name="hsn_code" class="form-control font-mono" value="<?= esc($material['hsn_code'] ?? '72085100') ?>" required placeholder="72085100">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select" required>
                        <option value="kg" <?= (isset($material['unit']) && $material['unit'] === 'kg') ? 'selected' : '' ?>>kg (Kilograms)</option>
                        <option value="mt" <?= (isset($material['unit']) && $material['unit'] === 'mt') ? 'selected' : '' ?>>mt (Metric Tonnes)</option>
                        <option value="nos" <?= (isset($material['unit']) && $material['unit'] === 'nos') ? 'selected' : '' ?>>nos (Numbers)</option>
                        <option value="mtr" <?= (isset($material['unit']) && $material['unit'] === 'mtr') ? 'selected' : '' ?>>mtr (Meters)</option>
                        <option value="ltr" <?= (isset($material['unit']) && $material['unit'] === 'ltr') ? 'selected' : '' ?>>ltr (Liters)</option>
                        <option value="set" <?= (isset($material['unit']) && $material['unit'] === 'set') ? 'selected' : '' ?>>set (Set)</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Standard Purchase Rate (₹)</label>
                    <input type="number" step="0.01" name="last_purchase_rate" class="form-control font-mono" value="<?= esc($material['last_purchase_rate'] ?? '65.00') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">GST Tax Rate (%)</label>
                    <select name="gst_rate" class="form-select font-mono">
                        <option value="18.00" <?= (!isset($material['gst_rate']) || $material['gst_rate'] == 18) ? 'selected' : '' ?>>18% (Standard Industrial Steel)</option>
                        <option value="12.00" <?= (isset($material['gst_rate']) && $material['gst_rate'] == 12) ? 'selected' : '' ?>>12%</option>
                        <option value="28.00" <?= (isset($material['gst_rate']) && $material['gst_rate'] == 28) ? 'selected' : '' ?>>28%</option>
                        <option value="5.00" <?= (isset($material['gst_rate']) && $material['gst_rate'] == 5) ? 'selected' : '' ?>>5%</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min Stock Threshold</label>
                    <input type="number" step="0.01" name="min_stock" class="form-control font-mono" value="<?= esc($material['min_stock'] ?? '5000') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Reorder Level Threshold</label>
                    <input type="number" step="0.01" name="reorder_level" class="form-control font-mono" value="<?= esc($material['reorder_level'] ?? '10000') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Technical Specification Details</label>
                <textarea name="specification" rows="2" class="form-control" placeholder="Chemical composition, tensile strength, Charpy impact test specs as per IS 2062:2011 / ASME Section II"><?= esc($material['specification'] ?? '') ?></textarea>
            </div>

            <?php if ($isEdit): ?>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?= (!empty($material['is_active'])) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="is_active">Active Catalog Item</label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-end">
                <a href="<?= base_url('materials') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-save me-1"></i> Save Material
                </button>
            </div>
        </form>
    </div>
</div>
