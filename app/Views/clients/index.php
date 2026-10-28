<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-building text-primary me-2"></i>Clients & Industrial Plants</h3>
        <p class="text-muted small mb-0">Power stations, sugar complexes, and cement manufacturing plants</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('clients/new') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add New Client / Plant
        </a>
    </div>
</div>

<!-- Sector Filter Pills -->
<div class="mb-3 d-flex flex-wrap gap-2">
    <a href="<?= base_url('clients') ?>" class="btn btn-sm <?= ($selectedIndustry === 'all') ? 'btn-dark' : 'btn-outline-secondary' ?>">
        All Sectors (<?= count($clients) ?>)
    </a>
    <a href="<?= base_url('clients?industry=power_plant') ?>" class="btn btn-sm <?= ($selectedIndustry === 'power_plant') ? 'btn-danger' : 'btn-outline-danger' ?>">
        <i class="bi bi-lightning-charge me-1"></i> Power Plants
    </a>
    <a href="<?= base_url('clients?industry=sugar_mill') ?>" class="btn btn-sm <?= ($selectedIndustry === 'sugar_mill') ? 'btn-success' : 'btn-outline-success' ?>">
        <i class="bi bi-cup-hot me-1"></i> Sugar Mills
    </a>
    <a href="<?= base_url('clients?industry=cement_plant') ?>" class="btn btn-sm <?= ($selectedIndustry === 'cement_plant') ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' ?>">
        <i class="bi bi-buildings me-1"></i> Cement Plants
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-ul me-2"></i>Client Directory</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Client / Plant Name</th>
                        <th>Sector</th>
                        <th>Location</th>
                        <th>Contact Person</th>
                        <th>GSTIN</th>
                        <th>Credit Terms</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary"><?= esc($client['client_code']) ?></td>
                            <td>
                                <a href="<?= base_url('clients/view/' . $client['id']) ?>" class="fw-bold text-dark text-decoration-none">
                                    <?= esc($client['company_name']) ?>
                                </a>
                            </td>
                            <td><?= plant_type_badge($client['industry_type']) ?></td>
                            <td>
                                <div><?= esc($client['city']) ?></div>
                                <small class="text-muted"><?= esc($client['state']) ?></small>
                            </td>
                            <td>
                                <div><?= esc($client['contact_person']) ?></div>
                                <small class="text-muted"><?= esc($client['mobile'] ?: $client['phone']) ?></small>
                            </td>
                            <td class="font-mono small"><?= esc($client['gstin'] ?: '-') ?></td>
                            <td>
                                <div><?= format_inr($client['credit_limit'], true, 0) ?></div>
                                <small class="text-muted"><?= esc($client['credit_days']) ?> Days</small>
                            </td>
                            <td>
                                <?php if ($client['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('clients/view/' . $client['id']) ?>" class="btn btn-light" title="View Details"><i class="bi bi-eye"></i></a>
                                    <a href="<?= base_url('clients/edit/' . $client['id']) ?>" class="btn btn-light" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= base_url('clients/delete/' . $client['id']) ?>" class="btn btn-light text-danger confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
