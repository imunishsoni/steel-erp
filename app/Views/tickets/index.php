<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h4 class="mb-0"><i class="bi bi-ticket-perforated me-2 text-primary"></i>In-Built Industrial Ticketing &amp; CAPA System</h4>
        <small class="text-muted">Native issue tracking, weld defect NCRs, engineering RFIs, machinery breakdowns &amp; corrective actions</small>
    </div>
    <div class="col-md-5 text-md-end mt-2 mt-md-0">
        <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalQuickTicket">
            <i class="bi bi-lightning-charge me-1"></i> Quick Log
        </button>
        <a href="<?= base_url('tickets/new') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Raise Formal Ticket
        </a>
    </div>
</div>

<!-- KPI SUMMARY STATS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Issues Logged</div>
                        <h3 class="mb-0 fw-bold"><?= $stats['total'] ?></h3>
                    </div>
                    <div class="fs-1 text-primary opacity-50"><i class="bi bi-ticket-perforated"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Active / Open</div>
                        <h3 class="mb-0 fw-bold text-warning"><?= $stats['open'] ?></h3>
                    </div>
                    <div class="fs-1 text-warning opacity-50"><i class="bi bi-hourglass-split"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-danger h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Urgent / Critical</div>
                        <h3 class="mb-0 fw-bold text-danger"><?= $stats['urgent_critical'] ?></h3>
                    </div>
                    <div class="fs-1 text-danger opacity-50"><i class="bi bi-radioactive"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Resolved &amp; Closed</div>
                        <h3 class="mb-0 fw-bold text-success"><?= $stats['resolved_closed'] ?> <small class="fs-6 text-muted fw-normal">(<?= $stats['resolution_rate'] ?>%)</small></h3>
                    </div>
                    <div class="fs-1 text-success opacity-50"><i class="bi bi-check2-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FILTERS & CATEGORY PILLS -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <!-- CATEGORY PILLS -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="<?= base_url('tickets') ?>" class="btn btn-sm <?= ($filters['category'] === 'all' && empty($filters['status']) || $filters['status'] === 'all' && $filters['category'] === 'all') ? 'btn-dark' : 'btn-outline-secondary' ?>">
                All Categories (<?= $stats['total'] ?>)
            </a>
            <a href="<?= base_url('tickets?category=quality_ncr') ?>" class="btn btn-sm <?= $filters['category'] === 'quality_ncr' ? 'btn-danger' : 'btn-outline-danger' ?>">
                <i class="bi bi-shield-x me-1"></i> Quality NCRs
            </a>
            <a href="<?= base_url('tickets?category=drawing_rfi') ?>" class="btn btn-sm <?= $filters['category'] === 'drawing_rfi' ? 'btn-primary' : 'btn-outline-primary' ?>">
                <i class="bi bi-pencil-ruler me-1"></i> Drawing RFIs
            </a>
            <a href="<?= base_url('tickets?category=machine_breakdown') ?>" class="btn btn-sm <?= $filters['category'] === 'machine_breakdown' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' ?>">
                <i class="bi bi-wrench-adjustable me-1"></i> Machine Breakdowns
            </a>
            <a href="<?= base_url('tickets?category=safety_hazard') ?>" class="btn btn-sm <?= $filters['category'] === 'safety_hazard' ? 'btn-danger' : 'btn-outline-danger' ?>">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Safety &amp; HSE
            </a>
            <a href="<?= base_url('tickets?category=client_punch') ?>" class="btn btn-sm <?= $filters['category'] === 'client_punch' ? 'btn-info text-white' : 'btn-outline-info' ?>">
                <i class="bi bi-card-checklist me-1"></i> Client Punch Points
            </a>
        </div>

        <!-- SEARCH AND MULTI-FILTER FORM -->
        <form method="get" action="<?= base_url('tickets') ?>" class="row g-2 align-items-center">
            <input type="hidden" name="category" value="<?= esc($filters['category']) ?>">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search ticket #, defect, summary..." value="<?= esc($filters['search']) ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="all">-- All Statuses --</option>
                    <option value="open" <?= $filters['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= $filters['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="under_review" <?= $filters['status'] === 'under_review' ? 'selected' : '' ?>>Under Review</option>
                    <option value="resolved" <?= $filters['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="closed" <?= $filters['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="all">-- All Priorities --</option>
                    <option value="critical" <?= $filters['priority'] === 'critical' ? 'selected' : '' ?>>Critical</option>
                    <option value="urgent" <?= $filters['priority'] === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    <option value="high" <?= $filters['priority'] === 'high' ? 'selected' : '' ?>>High</option>
                    <option value="medium" <?= $filters['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="low" <?= $filters['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="department" class="form-select form-select-sm">
                    <option value="all">-- All Departments --</option>
                    <option value="qa_qc" <?= $filters['department'] === 'qa_qc' ? 'selected' : '' ?>>QA / QC Quality</option>
                    <option value="production_shopfloor" <?= $filters['department'] === 'production_shopfloor' ? 'selected' : '' ?>>Shopfloor Manufacturing</option>
                    <option value="design_engineering" <?= $filters['department'] === 'design_engineering' ? 'selected' : '' ?>>Design &amp; Engineering</option>
                    <option value="maintenance" <?= $filters['department'] === 'maintenance' ? 'selected' : '' ?>>Plant Maintenance</option>
                    <option value="safety_hse" <?= $filters['department'] === 'safety_hse' ? 'selected' : '' ?>>Safety &amp; HSE</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
                <a href="<?= base_url('tickets') ?>" class="btn btn-outline-secondary btn-sm" title="Clear Filters"><i class="bi bi-x-circle"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- TICKETS TABLE -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th style="width: 130px;">Ticket No</th>
                        <th style="width: 100px;">Priority</th>
                        <th style="width: 130px;">Category</th>
                        <th>Subject &amp; Observation</th>
                        <th>Job Ref / Client</th>
                        <th>Department &amp; Assigned</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 100px;">Target Date</th>
                        <th class="text-end" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-ticket-detailed fs-1 d-block mb-2 text-secondary"></i>
                                <strong>No tickets found matching current filters.</strong>
                                <div class="small mt-1">Raise a new defect or engineering query using the button above.</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $t): ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('tickets/view/' . $t['id']) ?>" class="fw-bold font-monospace text-decoration-none">
                                        <?= esc($t['ticket_no']) ?>
                                    </a>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        <i class="bi bi-clock"></i> <?= date('d M, H:i', strtotime($t['created_at'])) ?>
                                    </div>
                                </td>
                                <td><?= ticket_priority_badge($t['priority']) ?></td>
                                <td><?= ticket_category_badge($t['category']) ?></td>
                                <td>
                                    <a href="<?= base_url('tickets/view/' . $t['id']) ?>" class="text-dark fw-bold text-decoration-none d-block mb-1">
                                        <?= esc($t['title']) ?>
                                    </a>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 380px;">
                                        <?= esc(substr($t['description'], 0, 110)) ?>...
                                    </small>
                                </td>
                                <td>
                                    <?php if (!empty($t['job_no'])): ?>
                                        <span class="badge bg-light text-dark border font-monospace"><?= esc($t['job_no']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($t['client_name'])): ?>
                                        <div class="small text-muted text-truncate" style="max-width: 180px;">
                                            <i class="bi bi-building"></i> <?= esc($t['client_name']) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="small text-muted">-</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold small"><?= esc(ucwords(str_replace('_', ' ', $t['department']))) ?></div>
                                    <div class="text-muted small">
                                        <i class="bi bi-person"></i> <?= esc($t['assigned_name'] ?? 'Unassigned') ?>
                                    </div>
                                </td>
                                <td><?= status_badge($t['status']) ?></td>
                                <td>
                                    <?php if (!empty($t['target_date'])): ?>
                                        <small class="font-monospace text-muted"><?= date('d M Y', strtotime($t['target_date'])) ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">-</small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?= base_url('tickets/view/' . $t['id']) ?>" class="btn btn-outline-primary btn-sm py-1 px-2" title="Open Traveler">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================== MODAL: QUICK RAISE TICKET ==================== -->
<div class="modal fade" id="modalQuickTicket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-lightning-charge me-2"></i>Quick Log Shop Floor Issue / Defect</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('tickets/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Ticket Number</label>
                            <input type="text" name="ticket_no" class="form-control font-monospace" value="<?= esc($nextTicketNo) ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold required">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="quality_ncr">Quality NCR (Weld / Plate Defect)</option>
                                <option value="drawing_rfi">Drawing RFI / Change Order</option>
                                <option value="machine_breakdown">Machinery Breakdown</option>
                                <option value="safety_hazard">Safety &amp; HSE Incident</option>
                                <option value="client_punch">Client Pre-Dispatch Punch Point</option>
                                <option value="production_delay">Production Bottleneck</option>
                                <option value="general">General Issue</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold required">Priority</label>
                            <select name="priority" class="form-select font-monospace" required>
                                <option value="critical">🔴 Critical (Plant Stoppage)</option>
                                <option value="urgent">🟠 Urgent (TPI / Weld Defect)</option>
                                <option value="high" selected>🟡 High (Schedule Risk)</option>
                                <option value="medium">🔵 Medium (Normal)</option>
                                <option value="low">⚪ Low (Observation)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Related Fabrication Job Order</label>
                            <select name="job_id" class="form-select" id="quickJobSelect">
                                <option value="">-- Optional: Link to Active Job --</option>
                                <?php foreach ($jobs as $job): ?>
                                    <option value="<?= $job['id'] ?>"><?= esc($job['job_no']) ?> - <?= esc($job['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold required">Target Department</label>
                            <select name="department" class="form-select" required>
                                <option value="qa_qc">QA / QC Quality Assurance</option>
                                <option value="production_shopfloor">Shopfloor Fabrication Bays</option>
                                <option value="design_engineering">Design &amp; Engineering</option>
                                <option value="maintenance">Plant Machinery Maintenance</option>
                                <option value="safety_hse">Safety &amp; HSE</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold required">Subject / Observation</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Ultrasonic Testing Slag Defect on Bottom Flange Weld Joint WJ-04" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold required">Technical Description</label>
                            <textarea name="description" class="form-control font-monospace" rows="4" placeholder="Detail the joint ID, plate thickness, heat number, inspection standard (AWS D1.1 / ASME Sec IX), inspector observations, and immediate shop floor containment action." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Log Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
