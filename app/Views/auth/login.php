<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Steel ERP - Heavy Steel Fabrication System</title>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-icons/bootstrap-icons.min.css') ?>">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f1f33 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
            padding: 1.5rem;
        }
        .login-card {
            max-width: 440px;
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(180deg, #1e3a5f 0%, #0f1f33 100%);
            color: #ffffff;
            padding: 2rem 2rem 1.75rem 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .badge-sector {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.35rem 0.6rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle mb-3" style="width: 54px; height: 54px;">
            <i class="bi bi-hammer fs-2"></i>
        </div>
        <h4 class="fw-bold mb-1 letter-spacing-1">STEEL ERP</h4>
        <p class="text-white-50 small mb-2">Heavy Steel Fabrication Management System</p>
        <div class="d-flex justify-content-center gap-1">
            <span class="badge bg-danger badge-sector">Power Plants</span>
            <span class="badge bg-success badge-sector">Sugar Mills</span>
            <span class="badge bg-warning text-dark badge-sector">Cement Plants</span>
        </div>
    </div>

    <div class="login-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show small" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="login" class="form-label small fw-semibold text-secondary">Username or Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" name="login" id="login" class="form-control" value="<?= old('login', 'admin') ?>" required autofocus placeholder="e.g. admin or sales_head">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" value="Admin@123" required placeholder="Enter password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Secure Login
            </button>
        </form>

        <!-- Quick Demo Switcher -->
        <div class="mt-4 pt-3 border-top">
            <div class="text-muted small fw-semibold mb-2 text-center text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                One-Click Quick Role Switch (Password: Admin@123)
            </div>
            <div class="d-flex flex-wrap gap-1 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-dark demo-fill" data-user="admin" style="font-size: 0.72rem;">Admin</button>
                <button type="button" class="btn btn-sm btn-outline-danger demo-fill" data-user="sales_head" style="font-size: 0.72rem;">Sales</button>
                <button type="button" class="btn btn-sm btn-outline-info demo-fill" data-user="purchase_mgr" style="font-size: 0.72rem;">Purchase</button>
                <button type="button" class="btn btn-sm btn-outline-secondary demo-fill" data-user="store_keeper" style="font-size: 0.72rem;">Store</button>
                <button type="button" class="btn btn-sm btn-outline-warning demo-fill text-dark" data-user="prod_head" style="font-size: 0.72rem;">Production</button>
                <button type="button" class="btn btn-sm btn-outline-success demo-fill" data-user="accounts_mgr" style="font-size: 0.72rem;">Accounts</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script>
    $('.demo-fill').on('click', function() {
        $('#login').val($(this).data('user'));
        $('#password').val('Admin@123');
    });
</script>
</body>
</html>
