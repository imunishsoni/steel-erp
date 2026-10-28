<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-box-arrow-right me-2 text-primary"></i>Store Material Issues Log</h4>
        <small class="text-muted">History of steel materials issued to fabrication jobs</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('production') ?>" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-arrow-left me-1"></i>Back to Jobs</a>
        <a href="<?= base_url('production/issue-material') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New Steel Issue</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-bold"><i class="bi bi-journal-text me-2"></i>Steel Requisition & Issue History</span>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable w-100">
            <thead class="table-light small">
                <tr>
                    <th>Issue No</th>
                    <th>Date</th>
                    <th>Job Order</th>
                    <th>Material</th>
                    <th>Code</th>
                    <th class="text-end">Quantity</th>
                    <th>Issued By</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($issues)): foreach ($issues as $row): ?>
                <tr>
                    <td><code><?= esc($row['issue_no']) ?></code></td>
                    <td><?= date('d-m-Y', strtotime($row['issue_date'])) ?></td>
                    <td>
                        <a href="<?= base_url('production/view/' . $row['job_id']) ?>" class="fw-bold text-decoration-none">
                            <?= esc($row['job_no']) ?>
                        </a>
                        <div class="small text-muted"><?= esc($row['project_name']) ?></div>
                    </td>
                    <td><strong><?= esc($row['material_name']) ?></strong></td>
                    <td><span class="badge bg-light text-secondary"><?= esc($row['material_code']) ?></span></td>
                    <td class="text-end fw-bold text-primary"><?= number_format($row['quantity'], 3) ?> <?= esc($row['unit']) ?></td>
                    <td><?= esc($row['issuer_name'] ?: 'Store Incharge') ?></td>
                    <td class="small text-muted"><?= esc($row['remarks'] ?: '&mdash;') ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
