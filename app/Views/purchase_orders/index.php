<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold"><i class="bi bi-bag-plus text-primary me-2"></i>Procurement & Purchase Orders (PO)</h3>
        <p class="text-muted small mb-0">Raw steel procurement from primary mills (SAIL, JSPL, Tata) and welding consumables</p>
    </div>
    <a href="<?= base_url('purchase-orders/new') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Create Purchase Order
    </a>
</div>

<div class="steel-card">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-task me-2"></i>Purchase Orders Register</span>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-steel datatable">
                <thead>
                    <tr>
                        <th>PO No</th>
                        <th>Date</th>
                        <th>Steel Mill / Vendor</th>
                        <th>Category</th>
                        <th>Linked Sales Order</th>
                        <th>PO Value</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchaseOrders as $po): ?>
                        <tr>
                            <td class="font-mono fw-bold text-primary">
                                <a href="<?= base_url('purchase-orders/view/' . $po['id']) ?>" class="text-decoration-none">
                                    <?= esc($po['po_no']) ?>
                                </a>
                            </td>
                            <td><?= format_date($po['po_date']) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($po['vendor_company']) ?></div>
                            </td>
                            <td><span class="badge bg-secondary"><?= ucwords(str_replace('_', ' ', $po['vendor_type'])) ?></span></td>
                            <td class="font-mono"><?= esc($po['linked_so'] ?: 'Stock Procurement') ?></td>
                            <td class="font-mono fw-bold text-primary"><?= format_inr($po['grand_total']) ?></td>
                            <td><?= format_date($po['delivery_date']) ?></td>
                            <td><?= status_badge($po['status']) ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('purchase-orders/view/' . $po['id']) ?>" class="btn btn-light" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="<?= base_url('purchase-orders/print/' . $po['id']) ?>" target="_blank" class="btn btn-light" title="Print PO"><i class="bi bi-printer"></i></a>
                                    <a href="<?= base_url('grn/new?po_id=' . $po['id']) ?>" class="btn btn-light text-success" title="Inward Receipt (GRN)"><i class="bi bi-box-arrow-in-down"></i></a>
                                    <a href="<?= base_url('purchase-orders/delete/' . $po['id']) ?>" class="btn btn-light text-danger confirm-delete" title="Delete"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
