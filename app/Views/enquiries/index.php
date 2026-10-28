<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-chat-left-quote text-primary me-2"></i>Inquiries & Tender Estimations</h3>
        <p class="text-muted small mb-0">Track RFQs from power plants, sugar mills, and cement project authorities</p>
    </div>
    <a href="<?= base_url('enquiries/new') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Project Enquiry
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-task me-2"></i>Active Enquiries Pipeline</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>Enquiry No</th>
                        <th>Date</th>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Plant Sector</th>
                        <th>Est. Value</th>
                        <th>Priority</th>
                        <th>Assigned Engineer</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enquiries as $enq): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary">
                                <a href="<?= base_url('enquiries/view/' . $enq['id']) ?>" class="text-decoration-none">
                                    <?= esc($enq['enquiry_no']) ?>
                                </a>
                            </td>
                            <td><?= format_date($enq['enquiry_date']) ?></td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 220px;"><?= esc($enq['project_name']) ?></div>
                                <small class="text-muted text-truncate d-block" style="max-width: 220px;"><?= esc($enq['description']) ?></small>
                            </td>
                            <td><?= esc($enq['client_company'] ?: $enq['client_name']) ?></td>
                            <td><?= plant_type_badge($enq['plant_type']) ?></td>
                            <td class="font-mono fw-bold"><?= format_inr($enq['estimated_value'], true, 0) ?></td>
                            <td>
                                <?php if ($enq['priority'] === 'urgent'): ?>
                                    <span class="badge bg-danger"><i class="bi bi-fire me-1"></i>Urgent</span>
                                <?php elseif ($enq['priority'] === 'high'): ?>
                                    <span class="badge bg-warning text-dark">High</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Medium</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($enq['assigned_user'] ?: 'Unassigned') ?></td>
                            <td><?= status_badge($enq['status']) ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('enquiries/view/' . $enq['id']) ?>" class="btn btn-light" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="<?= base_url('quotations/new?enquiry_id=' . $enq['id']) ?>" class="btn btn-light text-primary" title="Create Quotation"><i class="bi bi-file-earmark-plus"></i></a>
                                    <a href="<?= base_url('enquiries/edit/' . $enq['id']) ?>" class="btn btn-light" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= base_url('enquiries/delete/' . $enq['id']) ?>" class="btn btn-light text-danger confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
