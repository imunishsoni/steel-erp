<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-1">
            <i class="bi bi-person-plus me-2 text-primary"></i><?= $employee ? 'Edit Employee: ' . esc($employee['full_name']) : 'Add Plant Employee' ?>
        </h4>
        <div class="text-muted small">Workforce details, statutory Aadhaar/PAN, and salary structures</div>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="<?= base_url('employees') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Employees</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-person-vcard me-2"></i>Employee Profile</span>
            </div>
            <div class="card-body">
                <form action="<?= $employee ? base_url('employees/update/' . $employee['id']) : base_url('employees/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label required">Employee Code</label>
                            <input type="text" name="emp_code" class="form-control font-monospace fw-bold" value="<?= old('emp_code', $employee['emp_code'] ?? $autoCode) ?>" <?= $employee ? 'readonly' : 'required' ?>>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label required">Full Name</label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. Ramesh Kumar Sharma" value="<?= old('full_name', $employee['full_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Department</label>
                            <select name="department_id" class="form-select select2" required>
                                <option value="">-- Select Department --</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= (old('department_id', $employee['department_id'] ?? '') == $dept['id']) ? 'selected' : '' ?>>
                                        <?= esc($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Designation / Role</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Welding Inspector (ASME/CSWIP)" value="<?= old('designation', $employee['designation'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="emp@bharatsteel.com" value="<?= old('email', $employee['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="10-digit mobile" value="<?= old('phone', $employee['phone'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Date of Joining</label>
                            <input type="date" name="date_of_joining" class="form-control" value="<?= old('date_of_joining', $employee['date_of_joining'] ?? date('Y-m-d')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Aadhaar Card No</label>
                            <input type="text" name="aadhar_no" class="form-control font-monospace" placeholder="12-digit Aadhaar" maxlength="14" value="<?= old('aadhar_no', $employee['aadhar_no'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Permanent Account Number (PAN)</label>
                            <input type="text" name="pan" class="form-control font-monospace text-uppercase" placeholder="10-digit PAN" maxlength="10" value="<?= old('pan', $employee['pan'] ?? '') ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Salary (₹ / Month)</label>
                            <input type="number" step="0.01" name="basic_salary" class="form-control" placeholder="0.00" value="<?= old('basic_salary', $employee['basic_salary'] ?? '0.00') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bank Account No</label>
                            <input type="text" name="bank_account_no" class="form-control font-monospace" value="<?= old('bank_account_no', $employee['bank_account_no'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bank IFSC Code</label>
                            <input type="text" name="bank_ifsc" class="form-control font-monospace text-uppercase" value="<?= old('bank_ifsc', $employee['bank_ifsc'] ?? '') ?>">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Residential Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Residential street, city, pin code" value="<?= old('address', $employee['address'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Emergency Contact Phone</label>
                            <input type="text" name="emergency_contact" class="form-control" placeholder="10-digit mobile" maxlength="15" value="<?= old('emergency_contact', $employee['emergency_contact'] ?? '') ?>">
                        </div>

                        <?php if ($employee): ?>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?= $employee['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="is_active">Employee Currently Active</label>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-end mt-4">
                        <a href="<?= base_url('employees') ?>" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= $employee ? 'Update Employee' : 'Save Employee' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
