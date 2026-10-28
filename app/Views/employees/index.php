<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="bi bi-people me-2 text-primary"></i>Plant Workforce & Engineering Staff</h4>
        <small class="text-muted">Certified welding engineers, NDT inspectors, fitters & plant management</small>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('employees/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Add Employee
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-bold"><i class="bi bi-person-badge me-2"></i>Employee Master Register</span>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle datatable w-100">
            <thead class="table-light small">
                <tr>
                    <th>Emp Code</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Contact Phone</th>
                    <th>Date Joined</th>
                    <th>Salary (₹/mo)</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($employees)): foreach ($employees as $emp): ?>
                <tr>
                    <td><code><?= esc($emp['emp_code']) ?></code></td>
                    <td>
                        <div class="fw-bold"><?= esc($emp['full_name']) ?></div>
                        <small class="text-muted"><?= esc($emp['email'] ?: 'No email') ?></small>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?= esc($emp['department_name'] ?: 'General') ?></span></td>
                    <td><?= esc($emp['designation']) ?></td>
                    <td><?= esc($emp['phone']) ?></td>
                    <td><?= date('d M Y', strtotime($emp['date_of_joining'])) ?></td>
                    <td><?= format_inr($emp['basic_salary']) ?></td>
                    <td>
                        <span class="badge bg-<?= $emp['is_active'] ? 'success' : 'secondary' ?>">
                            <?= $emp['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?= base_url('employees/edit/' . $emp['id']) ?>" class="btn btn-outline-secondary btn-sm" title="Edit Employee">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
