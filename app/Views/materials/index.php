<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-stack text-primary me-2"></i>Steel Catalog & Material Specifications</h3>
        <p class="text-muted small mb-0">Structural steel plates, sections, SA516 boiler plates, seamless pipes, and welding consumables</p>
    </div>
    <a href="<?= base_url('materials/new') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add New Material
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-table me-2"></i>Master Material Database</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Material Name</th>
                        <th>Category</th>
                        <th>Grade / Standard</th>
                        <th>HSN Code</th>
                        <th>Unit</th>
                        <th>In Stock</th>
                        <th>Avg / Purchase Rate</th>
                        <th>GST %</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materials as $m): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary"><?= esc($m['material_code']) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($m['name']) ?></div>
                                <small class="text-muted"><?= esc($m['specification'] ?: '') ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= esc($m['category_name']) ?></span></td>
                            <td><span class="badge bg-secondary"><?= esc($m['grade'] ?: '-') ?></span></td>
                            <td class="font-mono small"><?= esc($m['hsn_code']) ?></td>
                            <td class="text-uppercase"><?= esc($m['unit']) ?></td>
                            <td>
                                <?php $stock = (float)($m['in_stock'] ?? 0); ?>
                                <span class="fw-bold <?= ($m['reorder_level'] > 0 && $stock <= $m['reorder_level']) ? 'text-danger' : 'text-success' ?>">
                                    <?= number_format($stock, 1) ?>
                                </span>
                                <?php if ($m['reorder_level'] > 0 && $stock <= $m['reorder_level']): ?>
                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Below Reorder Level"></i>
                                <?php endif; ?>
                            </td>
                            <td class="font-mono"><?= format_inr($m['last_purchase_rate']) ?></td>
                            <td class="font-mono"><?= (int)$m['gst_rate'] ?>%</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('materials/edit/' . $m['id']) ?>" class="btn btn-light" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= base_url('materials/delete/' . $m['id']) ?>" class="btn btn-light text-danger confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
