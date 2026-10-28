<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1"><i class="bi bi-file-earmark-plus me-2 text-primary"></i>Create Fabrication Job Order</h4>
        <div class="text-muted small">Generate shop floor fabrication card linked to drawings and client sales order</div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= base_url('production') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Jobs</a>
    </div>
</div>

<form action="<?= base_url('production/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <span class="fw-bold"><i class="bi bi-info-circle me-2"></i>Job Information</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label required">Job Card No</label>
                    <input type="text" name="job_no" class="form-control font-monospace fw-bold" value="<?= old('job_no', $autoJobNo) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Job Order Date</label>
                    <input type="date" name="job_date" class="form-control" value="<?= old('job_date', date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Linked Sales Order</label>
                    <select name="sales_order_id" class="form-select select2">
                        <option value="">-- Direct Job / Internal Fabrication --</option>
                        <?php foreach ($salesOrders as $so): ?>
                            <option value="<?= $so['id'] ?>" <?= (old('sales_order_id', $selectedSoId ?? '') == $so['id']) ? 'selected' : '' ?>>
                                <?= esc($so['so_no']) ?> - <?= esc($so['client_name'] ?? 'Client') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fabrication Priority</label>
                    <select name="priority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent / Shutdown</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label required">Project Title / Equipment Name</label>
                    <input type="text" name="project_name" class="form-control" placeholder="e.g. ESP Casing & Flue Gas Ducts for Unit #4" value="<?= old('project_name', $selectedSO['project_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= old('start_date', date('Y-m-d')) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Target Delivery Date</label>
                    <input type="date" name="target_date" class="form-control" value="<?= old('target_date', date('Y-m-d', strtotime('+30 days'))) ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label required">Total Estimated Steel Tonnage (MT)</label>
                    <input type="number" step="0.001" name="total_weight" class="form-control fw-bold" placeholder="e.g. 85.500" value="<?= old('total_weight', '0.000') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assigned Production Incharge / Lead</label>
                    <select name="assigned_to" class="form-select select2">
                        <option value="">-- Select Engineer --</option>
                        <?php foreach ($engineers as $eng): ?>
                            <option value="<?= $eng['id'] ?>" <?= old('assigned_to') == $eng['id'] ? 'selected' : '' ?>>
                                <?= esc($eng['full_name']) ?> (<?= esc($eng['role']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Scope Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Heavy plate cutting, roll bending, submerged arc welding" value="<?= old('description') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Shop Floor Instructions & Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Strict compliance with WPS/PQR, Ultrasonic testing (UT) for 100% butt joints, SA 2.5 grit blasting before primer."><?= old('remarks') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-Assemblies / Processes Breakdown -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="bi bi-diagram-3 me-2"></i>Fabrication Assemblies & Drawing Breakdown</span>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-item">
                <i class="bi bi-plus-circle me-1"></i>Add Assembly / Drawing
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" id="items-table">
                <thead class="table-light small">
                    <tr>
                        <th style="width: 30%;">Sub-Assembly / Part Name</th>
                        <th style="width: 20%;">Approved Drawing No</th>
                        <th style="width: 12%;">Qty (PCS)</th>
                        <th style="width: 15%;">Unit Wt (MT)</th>
                        <th style="width: 18%;">Primary Process</th>
                        <th style="width: 5%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr>
                        <td>
                            <input type="text" name="item_description[]" class="form-control form-control-sm" placeholder="e.g. Main Girder Plate Web" required>
                        </td>
                        <td>
                            <input type="text" name="item_drawing_no[]" class="form-control form-control-sm" placeholder="DWG-ST-001 Rev B">
                        </td>
                        <td>
                            <input type="number" step="1" name="item_quantity[]" class="form-control form-control-sm" value="1" min="1">
                        </td>
                        <td>
                            <input type="number" step="0.001" name="item_weight_each[]" class="form-control form-control-sm" value="1.000">
                        </td>
                        <td>
                            <select name="item_process[]" class="form-select form-select-sm">
                                <option value="Cutting & Profile">Cutting & Profile</option>
                                <option value="Fit-up & Assembly">Fit-up & Assembly</option>
                                <option value="Submerged Arc Welding">Submerged Arc Welding</option>
                                <option value="NDT & Inspection">NDT & Inspection</option>
                                <option value="Blasting & Painting">Blasting & Painting</option>
                            </select>
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
        <a href="<?= base_url('production') ?>" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Job Order</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('items-body');
    const btnAdd = document.getElementById('btn-add-item');

    btnAdd.addEventListener('click', function() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="text" name="item_description[]" class="form-control form-control-sm" placeholder="e.g. Stiffener Brackets" required>
            </td>
            <td>
                <input type="text" name="item_drawing_no[]" class="form-control form-control-sm" placeholder="DWG-ST-002">
            </td>
            <td>
                <input type="number" step="1" name="item_quantity[]" class="form-control form-control-sm" value="1" min="1">
            </td>
            <td>
                <input type="number" step="0.001" name="item_weight_each[]" class="form-control form-control-sm" value="0.250">
            </td>
            <td>
                <select name="item_process[]" class="form-select form-select-sm">
                    <option value="Cutting & Profile">Cutting & Profile</option>
                    <option value="Fit-up & Assembly">Fit-up & Assembly</option>
                    <option value="Submerged Arc Welding">Submerged Arc Welding</option>
                    <option value="NDT & Inspection">NDT & Inspection</option>
                    <option value="Blasting & Painting">Blasting & Painting</option>
                </select>
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
            } else {
                alert('At least one assembly item is required.');
            }
        }
    });
});
</script>
