<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-cart-check text-primary me-2"></i>Confirmed Sales Orders (SO)</h3>
        <p class="text-muted small mb-0">Track contracted order book, client PO references, fabrication dispatch progress, and balances</p>
    </div>
    <a href="<?= base_url('sales-orders/new') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Sales Order
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-task me-2"></i>Sales Orders Master Register</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>SO No</th>
                        <th>Date</th>
                        <th>Client / Plant</th>
                        <th>Project Name</th>
                        <th>Client PO Ref</th>
                        <th>Order Value</th>
                        <th>Target Delivery</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salesOrders as $so): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary">
                                <a href="<?= base_url('sales-orders/view/' . $so['id']) ?>" class="text-decoration-none">
                                    <?= esc($so['so_no']) ?>
                                </a>
                            </td>
                            <td><?= format_date($so['so_date']) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($so['client_company']) ?></div>
                                <?= plant_type_badge($so['industry_type'] ?? 'other') ?>
                            </td>
                            <td class="fw-semibold text-truncate" style="max-width: 200px;"><?= esc($so['project_name']) ?></td>
                            <td class="font-mono small">
                                <div><?= esc($so['client_po_no'] ?: '-') ?></div>
                                <small class="text-muted"><?= format_date($so['client_po_date']) ?></small>
                            </td>
                            <td class="font-mono fw-bold text-primary"><?= format_inr($so['grand_total']) ?></td>
                            <td><?= format_date($so['delivery_date']) ?></td>
                            <td><?= status_badge($so['status']) ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('sales-orders/view/' . $so['id']) ?>" class="btn btn-light" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="<?= base_url('sales-orders/print/' . $so['id']) ?>" target="_blank" class="btn btn-light" title="Print SO"><i class="bi bi-printer"></i></a>
                                    <a href="<?= base_url('production/new?so_id=' . $so['id']) ?>" class="btn btn-light text-primary" title="Launch Fabrication Job"><i class="bi bi-gear"></i></a>
                                    <a href="<?= base_url('dispatches/new?so_id=' . $so['id']) ?>" class="btn btn-light text-success" title="Dispatch Challan"><i class="bi bi-truck"></i></a>
                                    <a href="<?= base_url('invoices/new?so_id=' . $so['id']) ?>" class="btn btn-light text-warning" title="Tax Invoice"><i class="bi bi-receipt"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
