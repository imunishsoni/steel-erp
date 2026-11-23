<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="<?= base_url('tickets') ?>" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Back to Tickets">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="mb-0 font-monospace text-primary"><?= esc($ticket['ticket_no']) ?></h4>
            <?= ticket_priority_badge($ticket['priority']) ?>
            <?= ticket_category_badge($ticket['category']) ?>
            <?= status_badge($ticket['status']) ?>
        </div>
        <h5 class="fw-bold mb-0 text-dark"><?= esc($ticket['title']) ?></h5>
    </div>
    <div class="col-md-5 text-md-end mt-2 mt-md-0">
        <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalUpdateStatus">
            <i class="bi bi-arrow-repeat me-1"></i> Update Status / CAPA
        </button>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print Traveler
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT COLUMN: TECHNICAL DETAILS & DISCUSSION TIMELINE -->
    <div class="col-lg-8">
        <!-- TICKET TECHNICAL DETAILS -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-card-text me-2 text-primary"></i>Technical Defect Description &amp; Scope</span>
                <span class="text-muted small">Logged on <?= date('d M Y, H:i', strtotime($ticket['created_at'])) ?> by <?= esc($ticket['creator_name'] ?? 'System') ?></span>
            </div>
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-2">Observation &amp; Inspection Findings:</h6>
                <div class="p-3 bg-light rounded font-monospace small mb-3 border">
                    <?= nl2br(esc($ticket['description'])) ?>
                </div>

                <?php if (!empty($ticket['root_cause'])): ?>
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-search me-1 text-danger"></i>Root Cause Analysis (5-Why):</h6>
                    <div class="p-3 bg-light rounded small mb-3 border border-danger border-opacity-25">
                        <?= nl2br(esc($ticket['root_cause'])) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ticket['resolution_notes'])): ?>
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-shield-check me-1 text-success"></i>Corrective &amp; Preventive Action (CAPA):</h6>
                    <div class="p-3 bg-success bg-opacity-10 rounded small border border-success border-opacity-25">
                        <?= nl2br(esc($ticket['resolution_notes'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ACTIVITY TIMELINE & DISCUSSION THREAD -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-chat-left-dots me-2 text-primary"></i>Investigation Timeline &amp; Engineer Remarks</span>
            </div>
            <div class="card-body p-4">
                <div class="timeline">
                    <?php if (empty($ticket['comments'])): ?>
                        <div class="text-muted small py-3 text-center">No timeline activity recorded yet.</div>
                    <?php else: ?>
                        <div class="list-group list-group-flush mb-4">
                            <?php foreach ($ticket['comments'] as $c): ?>
                                <div class="list-group-item px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-bold text-dark small">
                                            <i class="bi bi-person-circle me-1 text-primary"></i> <?= esc($c['user_name']) ?>
                                            <?php if ($c['action_type'] === 'status_change'): ?>
                                                <span class="badge bg-warning text-dark ms-2 font-monospace" style="font-size: 0.65rem;">STATUS CHANGE</span>
                                            <?php elseif ($c['action_type'] === 'capa_update'): ?>
                                                <span class="badge bg-success ms-2 font-monospace" style="font-size: 0.65rem;">CAPA ACTION</span>
                                            <?php elseif ($c['action_type'] === 'assignment'): ?>
                                                <span class="badge bg-info text-white ms-2 font-monospace" style="font-size: 0.65rem;">ASSIGNMENT</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary ms-2 font-monospace" style="font-size: 0.65rem;">NOTE</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted font-monospace" style="font-size: 0.72rem;">
                                            <?= date('d M Y, H:i', strtotime($c['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="small text-secondary ps-4 font-monospace">
                                        <?= nl2br(esc($c['comment'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ADD COMMENT FORM -->
                <form action="<?= base_url('tickets/add-comment/' . $ticket['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Post Investigation Observation / Engineering Remark</label>
                        <textarea name="comment" class="form-control font-monospace" rows="3" placeholder="Enter metallurgical observation, NDT film reference, welder heat input record, or action taken..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-send me-1"></i> Post Remark
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: METADATA & LINKED RECORDS -->
    <div class="col-lg-4">
        <!-- TICKET SUMMARY CARD -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-info-circle me-2 text-info"></i>Ticket Metadata</span>
            </div>
            <div class="card-body p-3 small">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 120px;">Category:</td>
                        <td><?= ticket_category_badge($ticket['category']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Priority:</td>
                        <td><?= ticket_priority_badge($ticket['priority']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status:</td>
                        <td><?= status_badge($ticket['status']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Department:</td>
                        <td class="fw-bold text-dark"><?= esc(ucwords(str_replace('_', ' ', $ticket['department']))) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Assigned To:</td>
                        <td class="fw-bold text-primary">
                            <?= esc($ticket['assigned_name'] ?? 'Unassigned') ?>
                            <a href="#" class="ms-1 small text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalReassign" title="Reassign Engineer">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Target Date:</td>
                        <td class="font-monospace fw-bold text-danger">
                            <?= !empty($ticket['target_date']) ? date('d M Y', strtotime($ticket['target_date'])) : 'Not set' ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Logged At:</td>
                        <td class="font-monospace"><?= date('d M Y, H:i', strtotime($ticket['created_at'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Resolved At:</td>
                        <td class="font-monospace text-success">
                            <?= !empty($ticket['resolved_at']) ? date('d M Y, H:i', strtotime($ticket['resolved_at'])) : 'Pending' ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- LINKED FABRICATION JOB ORDER -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-gear-wide-connected me-2 text-primary"></i>Linked Fabrication Job</span>
            </div>
            <div class="card-body p-3 small">
                <?php if (!empty($ticket['job_no'])): ?>
                    <div class="fw-bold fs-6 font-monospace mb-1">
                        <a href="<?= base_url('production/view/' . $ticket['job_id']) ?>" class="text-decoration-none">
                            <?= esc($ticket['job_no']) ?>
                        </a>
                    </div>
                    <div class="text-dark fw-bold mb-1"><?= esc($ticket['job_title']) ?></div>
                    <div class="text-muted mb-2"><i class="bi bi-file-earmark-code"></i> Drawing: <code><?= esc($ticket['drawing_no']) ?></code></div>
                    <div class="badge bg-secondary font-monospace"><?= esc($ticket['total_tonnage']) ?> MT Allocated</div>
                <?php else: ?>
                    <p class="text-muted mb-0">No specific job order linked to this ticket (General / Yard Issue).</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- LINKED PLANT CLIENT -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-building me-2 text-success"></i>Plant Client</span>
            </div>
            <div class="card-body p-3 small">
                <?php if (!empty($ticket['client_name'])): ?>
                    <div class="fw-bold text-dark mb-1"><?= esc($ticket['client_name']) ?></div>
                    <?= plant_type_badge($ticket['industry_type'] ?? '') ?>
                    <?php if (!empty($ticket['client_gstin'])): ?>
                        <div class="text-muted mt-2 font-monospace" style="font-size: 0.72rem;">GSTIN: <?= esc($ticket['client_gstin']) ?></div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">Internal works / no external client punch.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL: UPDATE STATUS & CAPA ==================== -->
<div class="modal fade" id="modalUpdateStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-arrow-repeat me-2"></i>Update Status &amp; Corrective Action (CAPA)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('tickets/update-status/' . $ticket['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold required">Advance Ticket Status</label>
                            <select name="status" class="form-select font-monospace" required>
                                <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>OPEN (Investigating)</option>
                                <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>IN PROGRESS (Shop Floor Containment / Gouging)</option>
                                <option value="under_review" <?= $ticket['status'] === 'under_review' ? 'selected' : '' ?>>UNDER REVIEW (TPI / Quality Review)</option>
                                <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>RESOLVED (Reweld / Rectification Verified)</option>
                                <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>CLOSED (Client / TPI Sign-off Complete)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Root Cause Analysis (5-Why Method)</label>
                            <textarea name="root_cause" class="form-control font-monospace" rows="3" placeholder="Why did the defect occur? (e.g. Inadequate preheat, flux humidity, drawing typo)..."><?= esc($ticket['root_cause'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Corrective &amp; Preventive Action (CAPA) Implementation Notes</label>
                            <textarea name="resolution_notes" class="form-control font-monospace" rows="4" placeholder="Detail the rectification protocol completed, re-inspection NDT report numbers, welder retraining, or tooling calibration."><?= esc($ticket['resolution_notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL: REASSIGN ENGINEER ==================== -->
<div class="modal fade" id="modalReassign" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="bi bi-person-gear me-2"></i>Reassign Ticket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('tickets/reassign/' . $ticket['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold required">Select Responsible Engineer / Supervisor</label>
                        <select name="assigned_to" class="form-select" required>
                            <option value="">-- Choose Staff Member --</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>" <?= ($ticket['assigned_to'] == $emp['id']) ? 'selected' : '' ?>>
                                    <?= esc($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= esc($emp['designation']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>
