<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-box-arrow-right me-2 text-primary"></i>Store Requisition / Steel Material Issue</h4>
        <div class="text-muted small">Issue raw steel plates, rolled sections, or welding consumables from yard to shop floor job card</div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= base_url('production') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Jobs</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-file-earmark-check me-2"></i>Steel Material Issue Slip</span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('production/store-material-issue') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Issue Voucher No</label>
                            <input type="text" name="issue_no" class="form-control font-monospace fw-bold" value="<?= old('issue_no', $autoIssueNo) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Issue Date</label>
                            <input type="date" name="issue_date" class="form-control" value="<?= old('issue_date', date('Y-m-d')) ?>" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label required">Target Fabrication Job Order</label>
                            <select name="job_id" class="form-select select2" required>
                                <option value="">-- Select Active Job Order --</option>
                                <?php foreach ($jobs as $job): ?>
                                    <option value="<?= $job['id'] ?>" <?= (old('job_id') == $job['id'] || $selectedJob == $job['id']) ? 'selected' : '' ?>>
                                        <?= esc($job['job_no']) ?> &mdash; <?= esc($job['project_name']) ?> (<?= number_format($job['total_weight'], 1) ?> MT)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label required">Raw Steel Material from Yard / Store</label>
                            <select name="material_id" class="form-select select2" id="material-select" required>
                                <option value="">-- Select Steel Grade / Item --</option>
                                <?php foreach ($materials as $mat): ?>
                                    <option value="<?= $mat['id'] ?>" 
                                            data-unit="<?= esc($mat['unit']) ?>" 
                                            data-stock="<?= esc($mat['current_stock'] ?? 0) ?>"
                                            <?= old('material_id') == $mat['id'] ? 'selected' : '' ?>>
                                        <?= esc($mat['name']) ?> (<?= esc($mat['material_code']) ?>) &bull; Avail: <?= number_format($mat['current_stock'] ?? 0, 3) ?> <?= esc($mat['unit']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Quantity to Issue (<span id="unit-label">MT</span>)</label>
                            <input type="number" step="0.001" name="quantity" id="quantity-input" class="form-control fw-bold" placeholder="0.000" value="<?= old('quantity') ?>" required>
                            <div class="small text-muted mt-1" id="stock-feedback"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Heat Number / Mother Plate Reference / Purpose</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Plate #JSPL-2024-H102 issued for bottom shell rolling. Cut remnants to be returned to Bay 2."><?= old('remarks') ?></textarea>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?= base_url('production') ?>" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-right me-1"></i>Issue & Deduct from Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const matSelect = document.getElementById('material-select');
    const unitLabel = document.getElementById('unit-label');
    const stockFeedback = document.getElementById('stock-feedback');

    matSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (opt.value) {
            const unit = opt.getAttribute('data-unit') || 'MT';
            const stock = parseFloat(opt.getAttribute('data-stock') || 0);
            unitLabel.textContent = unit;
            stockFeedback.innerHTML = `<span class="badge bg-info text-dark">Yard Stock Available: ${stock.toFixed(3)} ${unit}</span>`;
        } else {
            stockFeedback.innerHTML = '';
        }
    });
});
</script>
