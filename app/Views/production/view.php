<div class="row mb-3">
    <div class="col-md-7">
        <h4 class="mb-1">
            <i class="bi bi-gear-wide-connected me-2 text-primary"></i>Job Card: <?= esc($job['job_no']) ?>
        </h4>
        <div class="text-muted small">
            Project: <strong><?= esc($job['project_name']) ?></strong> &bull; 
            Client: <strong><?= esc($job['client_name'] ?: 'Direct Fabrication') ?></strong>
            <?php if (!empty($job['so_no'])): ?>
                &bull; SO: <a href="<?= base_url('sales-orders/view/' . $job['sales_order_id']) ?>" class="fw-bold"><?= esc($job['so_no']) ?></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-5 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('production') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="<?= base_url('production/issue-material/' . $job['id']) ?>" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Issue Steel</a>
        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#progressModal">
            <i class="bi bi-sliders me-1"></i>Update Progress
        </button>
        <button onclick="window.print()" class="btn btn-outline-dark btn-sm"><i class="bi bi-printer me-1"></i>Print</button>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Main Info Card -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-card-checklist me-2"></i>Fabrication Order Specifications</span>
                <div>
                    <?= status_badge($job['status']) ?>
                    <span class="badge bg-secondary ms-1">Priority: <?= strtoupper($job['priority']) ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Start Date</small>
                        <strong><?= date('d M Y', strtotime($job['start_date'])) ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Target Dispatch Date</small>
                        <strong class="text-danger"><?= date('d M Y', strtotime($job['target_date'])) ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Overall Progress</small>
                        <strong class="text-primary fs-5"><?= number_format($job['completion_pct'], 0) ?>%</strong>
                    </div>
                    <div class="col-12">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-<?= $job['completion_pct'] >= 100 ? 'success' : 'primary' ?>" 
                                 role="progressbar" 
                                 style="width: <?= $job['completion_pct'] ?>%">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Total Estimated Steel Tonnage</small>
                        <span class="fs-5 fw-bold"><?= number_format($job['total_weight'], 3) ?> MT</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Lead Engineer / Incharge</small>
                        <span><?= esc($job['assigned_engineer'] ?: 'Unassigned') ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Actual Completion Date</small>
                        <span><?= $job['actual_end_date'] ? date('d M Y', strtotime($job['actual_end_date'])) : 'In Progress' ?></span>
                    </div>
                    <?php if (!empty($job['description'])): ?>
                    <div class="col-12">
                        <small class="text-muted d-block">Scope & Specifications</small>
                        <p class="mb-0 small"><?= nl2br(esc($job['description'])) ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($job['remarks'])): ?>
                    <div class="col-12">
                        <div class="alert alert-light border mb-0 py-2 small">
                            <strong>Quality & Welding Notes:</strong> <?= nl2br(esc($job['remarks'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Assembly & Process Items -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-diagram-3 me-2"></i>Sub-Assemblies & Process Stages</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>#</th>
                            <th>Sub-Assembly / Part</th>
                            <th>Drawing No</th>
                            <th>Primary Process</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Weight (MT)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($job['items'])): foreach ($job['items'] as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong><?= esc($item['description']) ?></strong></td>
                            <td><code><?= esc($item['drawing_no'] ?: 'N/A') ?></code></td>
                            <td><span class="badge bg-light text-dark border"><?= esc($item['process']) ?></span></td>
                            <td class="text-end"><?= $item['completed_qty'] ?> / <?= $item['quantity'] ?> <?= esc($item['unit']) ?></td>
                            <td class="text-end"><?= number_format($item['total_weight'], 3) ?></td>
                            <td><?= status_badge($item['status']) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center py-3 text-muted">No process line items recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Materials Issued Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-box-seam me-2"></i>Raw Steel Materials Issued to this Job</span>
                <a href="<?= base_url('production/issue-material/' . $job['id']) ?>" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-plus me-1"></i>Issue Steel
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Issue Slip</th>
                            <th>Date</th>
                            <th>Steel Material</th>
                            <th>Code</th>
                            <th class="text-end">Issued Qty</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalIssuedQty = 0;
                        if (!empty($job['issues'])): foreach ($job['issues'] as $issue): 
                            $totalIssuedQty += $issue['quantity'];
                        ?>
                        <tr>
                            <td><code><?= esc($issue['issue_no']) ?></code></td>
                            <td><?= date('d-m-Y', strtotime($issue['issue_date'])) ?></td>
                            <td><strong><?= esc($issue['material_name']) ?></strong></td>
                            <td><span class="badge bg-light text-secondary"><?= esc($issue['material_code']) ?></span></td>
                            <td class="text-end fw-bold"><?= number_format($issue['quantity'], 3) ?> <?= esc($issue['unit']) ?></td>
                            <td class="small text-muted"><?= esc($issue['remarks'] ?: '&mdash;') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-light fw-bold">
                            <td colspan="4" class="text-end">Total Steel Issued:</td>
                            <td class="text-end text-primary"><?= number_format($totalIssuedQty, 3) ?> MT</td>
                            <td></td>
                        </tr>
                        <?php else: ?>
                        <tr><td colspan="6" class="text-center py-3 text-muted">No steel material issued yet for this job card.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Shop Floor QA & Inspection Stages -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-check-all me-2"></i>Shop Floor Quality Inspection Stages</span>
            </div>
            <div class="card-body small">
                <div class="d-flex align-items-start mb-3">
                    <div class="badge bg-success rounded-pill me-2 p-2">01</div>
                    <div>
                        <div class="fw-bold">Raw Material Identification</div>
                        <div class="text-muted">Heat number transfer, ultrasonic laminations test.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-3">
                    <div class="badge bg-primary rounded-pill me-2 p-2">02</div>
                    <div>
                        <div class="fw-bold">Fit-Up & Root Gap Inspection</div>
                        <div class="text-muted">Bevel angle check, root face alignment as per WPS.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-3">
                    <div class="badge bg-primary rounded-pill me-2 p-2">03</div>
                    <div>
                        <div class="fw-bold">Non-Destructive Testing (NDT)</div>
                        <div class="text-muted">100% DP testing on root run, Ultrasonic / Radiography test.</div>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-3">
                    <div class="badge bg-primary rounded-pill me-2 p-2">04</div>
                    <div>
                        <div class="fw-bold">Post Weld Heat Treatment (PWHT)</div>
                        <div class="text-muted">If mandated by client code (ASME Sec VIII / IBR).</div>
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <div class="badge bg-secondary rounded-pill me-2 p-2">05</div>
                    <div>
                        <div class="fw-bold">Surface Preparation & Painting</div>
                        <div class="text-muted">SA 2.5 grit blasting, DFT measurement of epoxy & polyurethane.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-printer me-2"></i>Fabrication Protocols</span>
            </div>
            <div class="card-body small text-muted">
                <p class="mb-2">&bull; All welding performed by ASME Sec IX certified welders only.</p>
                <p class="mb-2">&bull; Steel plate trace-ability strictly linked to SAIL/JSPL Mill Certificates.</p>
                <p class="mb-0">&bull; Dimensional tolerance per IS 7215 / client QAP guidelines.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Progress -->
<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('production/update-status/' . $job['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Fabrication Progress</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Job Status</label>
                        <select name="status" class="form-select">
                            <option value="planned" <?= $job['status'] === 'planned' ? 'selected' : '' ?>>Planned</option>
                            <option value="in_progress" <?= $job['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="on_hold" <?= $job['status'] === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                            <option value="completed" <?= $job['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Completion Percentage (%)</label>
                        <input type="number" name="completion_pct" class="form-control" min="0" max="100" value="<?= $job['completion_pct'] ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Progress</button>
                </div>
            </div>
        </form>
    </div>
</div>
