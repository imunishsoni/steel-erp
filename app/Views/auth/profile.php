<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 fw-bold">My Profile</h3>
        <p class="text-muted small mb-0">Manage your credentials and personal account details</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="steel-card p-4 text-center">
            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3 fw-bold" style="width: 80px; height: 80px; font-size: 2rem;">
                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
            </div>
            <h5 class="fw-bold mb-1"><?= esc($user['full_name']) ?></h5>
            <div class="badge bg-warning text-dark text-uppercase mb-3"><?= esc($user['role']) ?></div>
            <div class="text-muted small text-start border-top pt-3">
                <div class="mb-2"><i class="bi bi-person me-2"></i><strong>Username:</strong> <?= esc($user['username']) ?></div>
                <div class="mb-2"><i class="bi bi-envelope me-2"></i><strong>Email:</strong> <?= esc($user['email']) ?></div>
                <div class="mb-2"><i class="bi bi-telephone me-2"></i><strong>Phone:</strong> <?= esc($user['phone'] ?? '-') ?></div>
                <div><i class="bi bi-clock-history me-2"></i><strong>Last Login:</strong> <?= format_date($user['last_login'], 'd M Y, h:i A') ?></div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="steel-card">
            <div class="steel-card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Profile Information</h6>
            </div>
            <div class="p-4">
                <form action="<?= base_url('profile') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?= esc($user['full_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= esc($user['username']) ?>" disabled readonly>
                            <small class="text-muted">Username cannot be altered</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?= esc($user['phone'] ?? '') ?>">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i>Change Password</h6>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Leave empty to keep current password">
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
