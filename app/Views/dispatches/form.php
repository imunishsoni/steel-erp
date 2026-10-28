<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-truck me-2 text-primary"></i>Generate Delivery Challan (DC)</h4>
        <div class="text-muted small">Outward material movement, security gate pass & E-Way Bill documentation</div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= base_url('dispatches') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Dispatches</a>
    </div>
</div>

<form action="<?= base_url('dispatches/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <span class="fw-bold"><i class="bi bi-info-circle me-2"></i>Consignment & Transport Details</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label required">Delivery Challan No</label>
                    <input type="text" name="dc_no" class="form-control font-monospace fw-bold" value="<?= old('dc_no', $autoDcNo) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Challan Date</label>
                    <input type="date" name="dc_date" class="form-control" value="<?= old('dc_date', date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Linked Sales Order</label>
                    <select name="sales_order_id" class="form-select select2" id="so-select">
                        <option value="">-- Direct Dispatch --</option>
                        <?php foreach ($salesOrders as $so): ?>
                            <option value="<?= $so['id'] ?>" 
                                    data-client-id="<?= $so['client_id'] ?>"
                                    data-delivery-address="<?= esc($so['delivery_address'] ?? '') ?>"
                                    <?= old('sales_order_id', $selectedSO['id'] ?? '') == $so['id'] ? 'selected' : '' ?>>
                                <?= esc($so['so_no']) ?> &bull; <?= esc($so['client_company'] ?? $so['client_name'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Client / Consignee</label>
                    <select name="client_id" class="form-select select2" id="client-select" required>
                        <option value="">-- Select Client --</option>
                        <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id'] ?>" 
                                    data-address="<?= esc(trim(($c['address_line1'] ?? '') . ', ' . ($c['address_line2'] ?? '') . ', ' . ($c['city'] ?? '') . ', ' . ($c['state'] ?? '') . ' - ' . ($c['pincode'] ?? ''), ', - ')) ?>"
                                    <?= old('client_id', $selectedSO['client_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                <?= esc($c['company_name']) ?> (<?= esc($c['city']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label required">Site Delivery Address / Consignee Location</label>
                    <textarea name="delivery_address" id="delivery-address" class="form-control" rows="2" placeholder="Plant site erection location, Gate No..." required><?= old('delivery_address', $selectedSO['delivery_address'] ?? '') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dispatch Remarks / Instructions</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Trailer with side supports required. Material with identification tagging."><?= old('remarks') ?></textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label required">Vehicle / Trailer No</label>
                    <input type="text" name="vehicle_no" class="form-control font-monospace text-uppercase fw-bold" placeholder="e.g. MH 12 AB 1234" value="<?= old('vehicle_no') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transporter Name</label>
                    <input type="text" name="transporter" class="form-control" placeholder="e.g. Jaipur Golden / VRL Logistics" value="<?= old('transporter') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Lorry Receipt (LR) No</label>
                    <input type="text" name="lr_no" class="form-control" placeholder="e.g. LR-987654" value="<?= old('lr_no') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">LR Date</label>
                    <input type="date" name="lr_date" class="form-control" value="<?= old('lr_date', date('Y-m-d')) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Driver Name</label>
                    <input type="text" name="driver_name" class="form-control" placeholder="Driver full name" value="<?= old('driver_name') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Driver Mobile No</label>
                    <input type="text" name="driver_phone" class="form-control" placeholder="10-digit mobile" value="<?= old('driver_phone') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Government E-Way Bill No</label>
                    <input type="text" name="eway_bill_no" class="form-control font-monospace" placeholder="12-digit E-Way Bill" maxlength="16" value="<?= old('eway_bill_no') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">No of Packages / Bundles</label>
                    <input type="number" name="no_of_packages" class="form-control" value="<?= old('no_of_packages', 1) ?>" min="1">
                </div>

                <div class="col-md-3">
                    <label class="form-label required">Total Net Weight (MT)</label>
                    <input type="number" step="0.001" name="total_weight" id="total_weight" class="form-control fw-bold" placeholder="0.000" value="<?= old('total_weight', '0.000') ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-stack me-2"></i>Consignment Item Particulars</span>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-item">
                <i class="bi bi-plus-circle me-1"></i>Add Item
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" id="items-table">
                <thead class="table-light small">
                    <tr>
                        <th style="width: 40%;">Description & Drawing Particulars</th>
                        <th style="width: 15%;">Quantity</th>
                        <th style="width: 10%;">Unit</th>
                        <th style="width: 15%;">Weight (MT)</th>
                        <th style="width: 15%;">Remarks / Mark No</th>
                        <th style="width: 5%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr>
                        <td>
                            <input type="text" name="item_description[]" class="form-control form-control-sm" placeholder="e.g. Fabricated Heavy Box Column Mark C-101" required>
                        </td>
                        <td>
                            <input type="number" step="1" name="item_quantity[]" class="form-control form-control-sm" value="1" min="1">
                        </td>
                        <td>
                            <select name="item_unit[]" class="form-select form-select-sm">
                                <option value="PCS">PCS</option>
                                <option value="MT" selected>MT</option>
                                <option value="SETS">SETS</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.001" name="item_weight[]" class="form-control form-control-sm item-wt" value="0.000">
                        </td>
                        <td>
                            <input type="text" name="item_remarks[]" class="form-control form-control-sm" placeholder="e.g. Heat #9842">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-end mb-4">
        <a href="<?= base_url('dispatches') ?>" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save & Generate Challan</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('items-body');
    const btnAdd = document.getElementById('btn-add-item');
    const soSelect = document.getElementById('so-select');
    const clientSelect = document.getElementById('client-select');
    const deliveryAddress = document.getElementById('delivery-address');

    if (soSelect) {
        soSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                const clientId = opt.getAttribute('data-client-id');
                const addr = opt.getAttribute('data-delivery-address');
                if (clientId && clientSelect) {
                    clientSelect.value = clientId;
                    $(clientSelect).trigger('change');
                }
                if (addr) {
                    deliveryAddress.value = addr;
                }
            }
        });
    }

    if (clientSelect) {
        clientSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt.value && !deliveryAddress.value) {
                deliveryAddress.value = opt.getAttribute('data-address') || '';
            }
        });
    }

    btnAdd.addEventListener('click', function() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="text" name="item_description[]" class="form-control form-control-sm" placeholder="Item description" required>
            </td>
            <td>
                <input type="number" step="1" name="item_quantity[]" class="form-control form-control-sm" value="1" min="1">
            </td>
            <td>
                <select name="item_unit[]" class="form-select form-select-sm">
                    <option value="PCS">PCS</option>
                    <option value="MT" selected>MT</option>
                    <option value="SETS">SETS</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.001" name="item_weight[]" class="form-control form-control-sm item-wt" value="0.000">
            </td>
            <td>
                <input type="text" name="item_remarks[]" class="form-control form-control-sm">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });

    tbody.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-item')) {
            if (tbody.querySelectorAll('tr').length > 1) {
                e.target.closest('tr').remove();
            }
        }
    });
});
</script>
