<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="mb-0 fw-bold"><?= esc($enquiry['enquiry_no']) ?></h3>
            <?= plant_type_badge($enquiry['plant_type']) ?>
            <?= status_badge($enquiry['status']) ?>
        </div>
        <p class="text-muted small mb-0"><?= esc($enquiry['project_name']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('quotations/new?enquiry_id=' . $enquiry['id']) ?>" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Convert to Formal Quotation
        </a>
        <a href="<?= base_url('enquiries/edit/' . $enquiry['id']) ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="<?= base_url('enquiries') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-building me-2 text-primary"></i>Client / Plant Info</h6>
            <div class="small">
                <div class="mb-2"><strong>Company:</strong> <?= esc($enquiry['client_company'] ?: $enquiry['client_name']) ?></div>
                <div class="mb-2"><strong>Location:</strong> <?= esc($enquiry['client_city'] ?? '-') ?>, <?= esc($enquiry['client_state'] ?? '-') ?></div>
                <div class="mb-2"><strong>Contact Person:</strong> <?= esc($enquiry['contact_person'] ?: '-') ?></div>
                <div class="mb-2"><strong>Phone:</strong> <?= esc($enquiry['contact_phone'] ?: '-') ?></div>
                <div><strong>Email:</strong> <?= esc($enquiry['contact_email'] ?: '-') ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-tag me-2 text-primary"></i>Tender / Project Details</h6>
            <div class="small">
                <div class="mb-2"><strong>Enquiry Date:</strong> <?= format_date($enquiry['enquiry_date']) ?></div>
                <div class="mb-2"><strong>Target Date:</strong> <?= format_date($enquiry['expected_date']) ?></div>
                <div class="mb-2"><strong>Lead Source:</strong> <?= ucwords(esc($enquiry['source'])) ?></div>
                <div class="mb-2"><strong>Priority:</strong> <span class="badge bg-warning text-dark text-uppercase"><?= esc($enquiry['priority']) ?></span></div>
                <div><strong>Assigned To:</strong> <?= esc($enquiry['assigned_name'] ?: 'Unassigned') ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-cash me-2 text-primary"></i>Commercials</h6>
            <div class="small">
                <div class="mb-2">Estimated Value:</div>
                <div class="fs-4 fw-bold text-primary font-mono mb-2"><?= format_inr($enquiry['estimated_value']) ?></div>
                <div class="text-muted"><?= number_to_words_indian($enquiry['estimated_value']) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-card-text me-2"></i>Technical Fabrication Description & Specifications</span>
    </div>
    <div class="p-4">
        <div class="bg-light p-3 rounded border mb-3" style="white-space: pre-wrap; font-size: 0.95rem;"><?= esc($enquiry['description']) ?></div>
        <?php if (!empty($enquiry['remarks'])): ?>
            <div class="text-muted small"><strong>Internal Remarks:</strong> <?= esc($enquiry['remarks']) ?></div>
        <?php endif; ?>
    </div>
</div>
