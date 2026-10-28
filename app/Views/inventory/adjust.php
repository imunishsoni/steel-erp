<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Manual Stock Adjustment</h3>
        <p class="text-muted small mb-0">Record physical stock audit reconciliation, yard scrap write-offs, or initial opening balance</p>
    </div>
    <a href="<?= base_url('inventory') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

<div class="steel-card" style="max-width: 700px; margin: 0 auto;">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-sliders me-2"></i>Stock Adjustment Voucher</span>
    </div>
    <div class="p-4">
        <form action="<?= base_url('inventory/adjust') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Material / Steel Grade <span class="text-danger">*</span></label>
                <select name="material_id" class="form-select select2" required>
                    <option value="">Select Material</option>
                    <?php foreach ($materials as $m): ?>
                        <option value="<?= $m['id'] ?>">
                            <?= esc($m['material_code']) ?> - <?= esc($m['name']) ?> (<?= esc($m['unit']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                    <select name="adjustment_type" class="form-select" required>
                        <option value="adjustment_in">Stock Increase (+ Physical Gain / Inward)</option>
                        <option value="adjustment_out">Stock Decrease (- Scrap / Damage / Shrinkage)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" name="quantity" class="form-control font-mono" required placeholder="Quantity">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Warehouse / Yard Location</label>
                <select name="warehouse" class="form-select">
                    <option value="Yard-A (Plates)">Yard-A (Plates)</option>
                    <option value="Yard-B (Boiler Quality)">Yard-B (Boiler Quality SA516)</option>
                    <option value="Structural Bay 1">Structural Bay 1 (Heavy Beams)</option>
                    <option value="Structural Bay 2">Structural Bay 2 (Channels & Angles)</option>
                    <option value="Pipes Yard">Pipes Yard</option>
                    <option value="Consumables Store">Consumables Store</option>
                    <option value="Chemical & Paint Bay">Chemical & Paint Bay</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Reason / Remarks <span class="text-danger">*</span></label>
                <textarea name="remarks" rows="3" class="form-control" required placeholder="e.g. Annual physical weighment variance, offcut salvage, damaged consumable disposal"></textarea>
            </div>

            <div class="text-end">
                <a href="<?= base_url('inventory') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check2-circle me-1"></i> Apply Adjustment
                </button>
            </div>
        </form>
    </div>
</div>
