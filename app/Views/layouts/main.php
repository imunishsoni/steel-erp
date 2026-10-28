<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? esc($pageTitle) . ' | ' : '' ?>Heavy Steel ERP - Industrial Heavy Engineering</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/bootstrap.min.css') ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-icons/bootstrap-icons.min.css') ?>">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap5.min.css') ?>">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2-bootstrap-5-theme.min.css') ?>">
    <!-- Custom Steel ERP CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/steel-erp.css') ?>">
</head>
<body>

<div id="wrapper">
    <!-- SIDEBAR -->
    <nav id="sidebar">
        <a href="<?= base_url('dashboard') ?>" class="sidebar-brand">
            <i class="bi bi-hammer"></i>
            <div>
                <div>STEEL ERP</div>
                <div style="font-size: 0.65rem; font-weight: normal; color: #9ca3af; text-transform: uppercase;">Heavy Fabrication</div>
            </div>
        </a>

        <!-- User Info Strip -->
        <div class="px-3 py-2 border-bottom border-secondary border-opacity-25 bg-black bg-opacity-20 d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 font-weight-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                <?= strtoupper(substr($currentUser['full_name'] ?? 'Admin', 0, 1)) ?>
            </div>
            <div class="overflow-hidden">
                <div class="text-white text-truncate font-weight-semibold" style="font-size: 0.85rem;"><?= esc($currentUser['full_name'] ?? 'System Administrator') ?></div>
                <div class="badge bg-warning text-dark text-uppercase" style="font-size: 0.6rem;"><?= esc($currentUser['role'] ?? 'admin') ?></div>
            </div>
        </div>

        <div class="sidebar-menu">
            <!-- CORE -->
            <div class="sidebar-heading">Operations</div>
            <a href="<?= base_url('dashboard') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Executive Dashboard
            </a>

            <!-- CRM & SALES -->
            <div class="sidebar-heading">Sales & Orders</div>
            <a href="<?= base_url('clients') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'clients') ? 'active' : '' ?>">
                <i class="bi bi-building"></i> Clients & Plants
            </a>
            <a href="<?= base_url('enquiries') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'enquiries') ? 'active' : '' ?>">
                <i class="bi bi-chat-left-quote"></i> Enquiries
            </a>
            <a href="<?= base_url('quotations') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'quotations') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-spreadsheet"></i> Quotations
            </a>
            <a href="<?= base_url('sales-orders') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'sales-orders') ? 'active' : '' ?>">
                <i class="bi bi-cart-check"></i> Sales Orders (SO)
            </a>

            <!-- PROCUREMENT & STORE -->
            <div class="sidebar-heading">Procurement & Store</div>
            <a href="<?= base_url('vendors') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'vendors') ? 'active' : '' ?>">
                <i class="bi bi-truck"></i> Steel Mills & Vendors
            </a>
            <a href="<?= base_url('purchase-orders') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'purchase-orders') ? 'active' : '' ?>">
                <i class="bi bi-bag-plus"></i> Purchase Orders (PO)
            </a>
            <a href="<?= base_url('grn') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'grn') ? 'active' : '' ?>">
                <i class="bi bi-box-arrow-in-down"></i> Goods Receipt (GRN)
            </a>
            <a href="<?= base_url('inventory') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'inventory') ? 'active' : '' ?>">
                <i class="bi bi-boxes"></i> Warehouse Stock
            </a>

            <!-- SHOP FLOOR & PRODUCTION -->
            <div class="sidebar-heading">Manufacturing</div>
            <a href="<?= base_url('production') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'production') && !str_contains(uri_string(), 'issue') ? 'active' : '' ?>">
                <i class="bi bi-gear-wide-connected"></i> Fabrication Jobs
            </a>
            <a href="<?= base_url('production/issues') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'production/issues') ? 'active' : '' ?>">
                <i class="bi bi-arrow-right-square"></i> Material Issues
            </a>

            <!-- LOGISTICS & DISPATCH -->
            <div class="sidebar-heading">Logistics</div>
            <a href="<?= base_url('dispatches') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'dispatches') ? 'active' : '' ?>">
                <i class="bi bi-send-check"></i> Delivery Challans (DC)
            </a>

            <!-- BILLING & GST -->
            <div class="sidebar-heading">Finance & GST</div>
            <a href="<?= base_url('invoices') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'invoices') ? 'active' : '' ?>">
                <i class="bi bi-receipt"></i> GST Tax Invoices
            </a>
            <a href="<?= base_url('payments/received') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'payments/received') ? 'active' : '' ?>">
                <i class="bi bi-cash-stack"></i> Receipts (Clients)
            </a>
            <a href="<?= base_url('payments/made') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'payments/made') ? 'active' : '' ?>">
                <i class="bi bi-credit-card"></i> Payments (Vendors)
            </a>

            <!-- MANAGEMENT & MASTERS -->
            <div class="sidebar-heading">Organization</div>
            <a href="<?= base_url('materials') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'materials') ? 'active' : '' ?>">
                <i class="bi bi-stack"></i> Steel Catalog & Grades
            </a>
            <a href="<?= base_url('employees') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'employees') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Staff Directory
            </a>
            <a href="<?= base_url('reports') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'reports') ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-line"></i> Plant Reports & GST
            </a>
            <a href="<?= base_url('settings') ?>" class="sidebar-nav-link <?= str_contains(uri_string(), 'settings') ? 'active' : '' ?>">
                <i class="bi bi-sliders"></i> Company Settings
            </a>
        </div>

        <div class="p-3 border-top border-secondary border-opacity-25" style="font-size: 0.75rem; color: #6b7280;">
            <div>GSTIN: <span class="text-white"><?= esc($company['gstin'] ?? '08AABCU9603R1ZM') ?></span></div>
            <div>FY: <span class="text-white"><?= esc($company['financial_year'] ?? '2026-27') ?></span></div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper">
        <!-- TOP NAVBAR -->
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary me-3" id="sidebarToggle" type="button" title="Toggle Menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="d-none d-md-block">
                    <span class="badge bg-danger me-1"><i class="bi bi-lightning-charge me-1"></i>Power</span>
                    <span class="badge bg-success me-1"><i class="bi bi-cup-hot me-1"></i>Sugar</span>
                    <span class="badge bg-warning text-dark me-1"><i class="bi bi-buildings me-1"></i>Cement</span>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <!-- Notifications dropdown -->
                <div class="dropdown me-3">
                    <button class="btn btn-light position-relative btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-6"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 280px;">
                        <li><h6 class="dropdown-header">System Notifications</h6></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('enquiries') ?>"><small class="text-muted d-block">Urgent Enquiry</small>Adani Power - Turbine Crane Girders</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('inventory') ?>"><small class="text-muted d-block">Low Stock Alert</small>MIG Wire ER70S-6 below reorder level</a></li>
                        <li><a class="dropdown-item py-2" href="<?= base_url('invoices') ?>"><small class="text-muted d-block">Payment Credited</small>₹11,47,000 received from NTPC</a></li>
                    </ul>
                </div>

                <!-- User profile dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <span><?= esc($currentUser['full_name'] ?? 'Admin') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><span class="dropdown-item-text text-muted small">Logged as <strong><?= esc($currentUser['role'] ?? 'admin') ?></strong></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="bi bi-person me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('settings') ?>"><i class="bi bi-gear me-2"></i>Company Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- MAIN BODY -->
        <main class="main-content">
            <!-- FLASH MESSAGES -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= $mainContent ?>
        </main>

        <footer class="bg-white border-top py-2 px-4 text-muted small d-flex justify-content-between align-items-center">
            <div>&copy; <?= date('Y') ?> <strong><?= esc($company['company_name'] ?? 'Heavy Steel Fabricators Pvt. Ltd.') ?></strong> &bull; All Rights Reserved</div>
            <div>Engineering &bull; Procurement &bull; Fabrication &bull; Quality Testing</div>
        </footer>
    </div>
</div>

<!-- SCRIPTS -->
<script src="<?= base_url('assets/plugins/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/chartjs/chart.umd.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/steel-erp.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
