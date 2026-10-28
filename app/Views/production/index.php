<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-gear-wide-connected me-2 text-primary"></i>Shop Floor Production & Job Cards</h4>
        <small class="text-muted">Track heavy fabrication orders, stage-wise processes, and steel tonnage</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('production/issues') ?>" class="btn btn-outline-primary btn-sm me-2">
            <i class="bi bi-box-arrow-right me-1"></i>Material Issues Log
        </a>
        <a href="<?= base_url('production/issue-material') ?>" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-plus-circle me-1"></i>Issue Steel
        </a>
        <a href="<?= base_url('production/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>New Fabrication Job
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-primary">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Job Cards</div>
                <div class="fs-3 fw-bold"><?= $stats['total_jobs'] ?></div>
                <div class="text-muted small">Registered in system</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-warning">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">In Progress (Bays)</div>
                <div class="fs-3 fw-bold text-warning"><?= $stats['in_progress'] ?></div>
                <div class="text-muted small">Active on shop floor</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-info">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Under Fabrication</div>
                <div class="fs-3 fw-bold text-info"><?= number_format($stats['total_weight'], 1) ?> <span class="fs-6">MT</span></div>
                <div class="text-muted small">Steel structural weight</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-success">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Completed Jobs</div>
                <div class="fs-3 fw-bold text-success"><?= $stats['completed'] ?></div>
                <div class="text-muted small">Ready for dispatch</div>
            </div>
        </div>
    </div>
</div>

<!-- Jobs Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-bold"><i class="bi bi-list-task me-2"></i>Fabrication Job Orders</span>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable w-100">
            <thead class="table-light small">
                <tr>
                    <th>Job No</th>
                    <th>Project & Client</th>
                    <th>Ref SO</th>
                    <th>Weight (MT)</th>
                    <th>Timeline</th>
                    <th>Progress</th>
                    <th>Engineer</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jobs)): foreach ($jobs as $job): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('production/view/' . $job['id']) ?>" class="fw-bold text-primary text-decoration-none">
                            <?= esc($job['job_no']) ?>
                        </a>
                        <div class="small text-muted"><?= date('d-m-Y', strtotime($job['job_date'])) ?></div>
                    </td>
                    <td>
                        <div class="fw-semibold"><?= esc($job['project_name']) ?></div>
                        <small class="text-muted"><?= esc($job['client_company'] ?: 'Internal Order') ?></small>
                    </td>
                    <td>
                        <?php if ($job['so_no']): ?>
                            <span class="badge bg-light text-dark border"><?= esc($job['so_no']) ?></span>
                        <?php else: ?>
                            <span class="text-muted small">&mdash;</span>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold"><?= number_format($job['total_weight'], 2) ?></td>
                    <td>
                        <div class="small">Target: <strong><?= date('d M Y', strtotime($job['target_date'])) ?></strong></div>
                        <div class="small text-muted">Start: <?= date('d M', strtotime($job['start_date'])) ?></div>
                    </td>
                    <td style="min-width: 140px;">
                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                            <span><?= number_format($job['completion_pct'], 0) ?>%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-<?= $job['completion_pct'] >= 100 ? 'success' : ($job['completion_pct'] >= 50 ? 'primary' : 'warning') ?>" 
                                 role="progressbar" 
                                 style="width: <?= $job['completion_pct'] ?>%">
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="small"><?= esc($job['assigned_engineer'] ?: 'Unassigned') ?></span>
                    </td>
                    <td><?= status_badge($job['status']) ?></td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="<?= base_url('production/view/' . $job['id']) ?>" class="btn btn-outline-secondary" title="View Job Card">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= base_url('production/issue-material/' . $job['id']) ?>" class="btn btn-outline-primary" title="Issue Steel Material">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
