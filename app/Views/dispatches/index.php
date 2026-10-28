<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-truck me-2 text-primary"></i>Delivery Challans & Dispatch Logistics</h4>
        <small class="text-muted">Heavy structural steel consignments, trailer dispatches, and E-Way bills</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('dispatches/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>New Delivery Challan
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-primary">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Consignments</div>
                <div class="fs-3 fw-bold"><?= $stats['total'] ?></div>
                <div class="text-muted small">Generated Delivery Challans</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-warning">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">On Road / In Transit</div>
                <div class="fs-3 fw-bold text-warning"><?= $stats['dispatched'] ?></div>
                <div class="text-muted small">En route to plant sites</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-success">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Delivered at Site</div>
                <div class="fs-3 fw-bold text-success"><?= $stats['delivered'] ?></div>
                <div class="text-muted small">Site MRC received</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start-info">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-semibold">Total Tonnage Dispatched</div>
                <div class="fs-3 fw-bold text-info"><?= number_format($stats['total_mt'], 1) ?> <span class="fs-6">MT</span></div>
                <div class="text-muted small">Heavy fabricated steel</div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-bold"><i class="bi bi-list-check me-2"></i>Outward Consignment Register</span>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable w-100">
            <thead class="table-light small">
                <tr>
                    <th>DC No</th>
                    <th>Date</th>
                    <th>Client / Project</th>
                    <th>Ref SO</th>
                    <th>Vehicle / Trailer</th>
                    <th>Transporter & LR</th>
                    <th>E-Way Bill</th>
                    <th class="text-end">Weight (MT)</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dispatches)): foreach ($dispatches as $dc): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('dispatches/view/' . $dc['id']) ?>" class="fw-bold text-primary text-decoration-none">
                            <?= esc($dc['dc_no']) ?>
                        </a>
                    </td>
                    <td><?= date('d-m-Y', strtotime($dc['dc_date'])) ?></td>
                    <td>
                        <div class="fw-semibold"><?= esc($dc['client_company']) ?></div>
                    </td>
                    <td>
                        <?php if ($dc['so_no']): ?>
                            <span class="badge bg-light text-dark border"><?= esc($dc['so_no']) ?></span>
                        <?php else: ?>
                            <span class="text-muted small">&mdash;</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-dark font-monospace"><?= esc($dc['vehicle_no']) ?></span>
                    </td>
                    <td>
                        <div class="small fw-semibold"><?= esc($dc['transporter'] ?: 'N/A') ?></div>
                        <small class="text-muted">LR: <?= esc($dc['lr_no'] ?: '&mdash;') ?></small>
                    </td>
                    <td>
                        <?php if ($dc['eway_bill_no']): ?>
                            <code class="small text-success"><?= esc($dc['eway_bill_no']) ?></code>
                        <?php else: ?>
                            <span class="text-muted small">&mdash;</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end fw-bold"><?= number_format($dc['total_weight'], 3) ?></td>
                    <td><?= status_badge($dc['status']) ?></td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="<?= base_url('dispatches/view/' . $dc['id']) ?>" class="btn btn-outline-secondary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= base_url('dispatches/print/' . $dc['id']) ?>" target="_blank" class="btn btn-outline-primary" title="Print Delivery Challan & Gate Pass">
                                <i class="bi bi-printer"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
