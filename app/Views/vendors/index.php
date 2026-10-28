<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-truck text-primary me-2"></i>Approved Vendors & Steel Producers</h3>
        <p class="text-muted small mb-0">Primary steel producers (SAIL, JSPL, Tata), consumables, and logistics partners</p>
    </div>
    <a href="<?= base_url('vendors/new') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Register New Vendor
    </a>
</div>

<!-- Type Filter Pills -->
<div class="mb-3 d-flex flex-wrap gap-2">
    <a href="<?= base_url('vendors') ?>" class="btn btn-sm <?= ($selectedType === 'all') ? 'btn-dark' : 'btn-outline-secondary' ?>">
        All Vendors (<?= count($vendors) ?>)
    </a>
    <a href="<?= base_url('vendors?type=raw_material') ?>" class="btn btn-sm <?= ($selectedType === 'raw_material') ? 'btn-primary' : 'btn-outline-primary' ?>">
        <i class="bi bi-layers me-1"></i> Raw Steel Mills
    </a>
    <a href="<?= base_url('vendors?type=consumables') ?>" class="btn btn-sm <?= ($selectedType === 'consumables') ? 'btn-info text-dark' : 'btn-outline-info text-dark' ?>">
        <i class="bi bi-tools me-1"></i> Consumables & Fasteners
    </a>
    <a href="<?= base_url('vendors?type=transport') ?>" class="btn btn-sm <?= ($selectedType === 'transport') ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' ?>">
        <i class="bi bi-truck me-1"></i> Heavy Trailer Logistics
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-ul me-2"></i>Approved Vendor List (AVL)</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Vendor / Mill Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Contact Person</th>
                        <th>GSTIN</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendors as $v): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary"><?= esc($v['vendor_code']) ?></td>
                            <td>
                                <a href="<?= base_url('vendors/view/' . $v['id']) ?>" class="fw-bold text-dark text-decoration-none">
                                    <?= esc($v['company_name']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= ucwords(str_replace('_', ' ', $v['vendor_type'])) ?></span>
                            </td>
                            <td>
                                <div><?= esc($v['city']) ?></div>
                                <small class="text-muted"><?= esc($v['state']) ?></small>
                            </td>
                            <td>
                                <div><?= esc($v['contact_person'] ?: '-') ?></div>
                                <small class="text-muted"><?= esc($v['mobile'] ?: $v['phone'] ?: '-') ?></small>
                            </td>
                            <td class="font-mono small"><?= esc($v['gstin'] ?: '-') ?></td>
                            <td>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-fill text-warning" style="font-size: 0.75rem; <?= $i > $v['rating'] ? 'opacity: 0.2;' : '' ?>"></i>
                                <?php endfor; ?>
                            </td>
                            <td>
                                <?= ($v['is_active']) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('vendors/view/' . $v['id']) ?>" class="btn btn-light" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="<?= base_url('vendors/edit/' . $v['id']) ?>" class="btn btn-light" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= base_url('vendors/delete/' . $v['id']) ?>" class="btn btn-light text-danger confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
