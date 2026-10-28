<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-box-arrow-in-down text-primary me-2"></i>Goods Receipt Notes (GRN) & Inwards</h3>
        <p class="text-muted small mb-0">Record material gate entry, physical weighbridge check, mill test certificates (TC), and stock entry</p>
    </div>
    <a href="<?= base_url('grn/new') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Material Inward (GRN)
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-receipt me-2"></i>Inward Material Register</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>GRN No</th>
                        <th>GRN Date</th>
                        <th>Vendor / Mill Name</th>
                        <th>PO Ref</th>
                        <th>Vendor Challan</th>
                        <th>Vehicle / Trailer No</th>
                        <th>Received By</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grns as $g): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary">
                                <a href="<?= base_url('grn/view/' . $g['id']) ?>" class="text-decoration-none">
                                    <?= esc($g['grn_no']) ?>
                                </a>
                            </td>
                            <td><?= format_date($g['grn_date']) ?></td>
                            <td class="fw-bold text-dark"><?= esc($g['vendor_company']) ?></td>
                            <td class="font-mono"><?= esc($g['po_no'] ?: 'Direct Purchase') ?></td>
                            <td class="font-mono small">
                                <div><?= esc($g['challan_no']) ?></div>
                                <small class="text-muted"><?= format_date($g['challan_date']) ?></small>
                            </td>
                            <td class="font-mono"><?= esc($g['vehicle_no']) ?></td>
                            <td><?= esc($g['receiver_name'] ?? 'Store Incharge') ?></td>
                            <td><?= status_badge($g['status']) ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('grn/view/' . $g['id']) ?>" class="btn btn-sm btn-light" title="View GRN Details"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
