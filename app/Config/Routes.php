<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public Auth Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('logout', 'Auth::logout');

// Protected Routes (Require Authentication)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    // Profile
    $routes->get('profile', 'Auth::profile');
    $routes->post('profile', 'Auth::updateProfile');

    // Clients CRM
    $routes->group('clients', static function ($routes) {
        $routes->get('', 'Clients::index');
        $routes->get('create', 'Clients::new');
        $routes->get('new', 'Clients::new');
        $routes->post('store', 'Clients::create');
        $routes->post('create', 'Clients::create');
        $routes->get('view/(:num)', 'Clients::view/$1');
        $routes->get('edit/(:num)', 'Clients::edit/$1');
        $routes->post('update/(:num)', 'Clients::update/$1');
        $routes->get('delete/(:num)', 'Clients::delete/$1');
    });

    // Vendors
    $routes->group('vendors', static function ($routes) {
        $routes->get('', 'Vendors::index');
        $routes->get('create', 'Vendors::new');
        $routes->get('new', 'Vendors::new');
        $routes->post('store', 'Vendors::create');
        $routes->post('create', 'Vendors::create');
        $routes->get('view/(:num)', 'Vendors::view/$1');
        $routes->get('edit/(:num)', 'Vendors::edit/$1');
        $routes->post('update/(:num)', 'Vendors::update/$1');
        $routes->get('delete/(:num)', 'Vendors::delete/$1');
    });

    // Materials Master
    $routes->group('materials', static function ($routes) {
        $routes->get('', 'Materials::index');
        $routes->get('create', 'Materials::new');
        $routes->get('new', 'Materials::new');
        $routes->post('store', 'Materials::create');
        $routes->post('create', 'Materials::create');
        $routes->get('edit/(:num)', 'Materials::edit/$1');
        $routes->post('update/(:num)', 'Materials::update/$1');
    });

    // Yard Inventory
    $routes->group('inventory', static function ($routes) {
        $routes->get('', 'Inventory::index');
        $routes->get('adjust', 'Inventory::adjust');
        $routes->post('store-adjustment', 'Inventory::storeAdjustment');
        $routes->get('transactions', 'Inventory::transactions');
    });

    // Client Enquiries
    $routes->group('enquiries', static function ($routes) {
        $routes->get('', 'Enquiries::index');
        $routes->get('create', 'Enquiries::new');
        $routes->get('new', 'Enquiries::new');
        $routes->post('store', 'Enquiries::create');
        $routes->post('create', 'Enquiries::create');
        $routes->get('view/(:num)', 'Enquiries::view/$1');
        $routes->get('edit/(:num)', 'Enquiries::edit/$1');
        $routes->post('update/(:num)', 'Enquiries::update/$1');
    });

    // Quotations
    $routes->group('quotations', static function ($routes) {
        $routes->get('', 'Quotations::index');
        $routes->get('create', 'Quotations::new');
        $routes->get('new', 'Quotations::new');
        $routes->post('store', 'Quotations::create');
        $routes->post('create', 'Quotations::create');
        $routes->get('view/(:num)', 'Quotations::view/$1');
        $routes->get('edit/(:num)', 'Quotations::edit/$1');
        $routes->post('update/(:num)', 'Quotations::update/$1');
        $routes->get('print/(:num)', 'Quotations::printDoc/$1');
        $routes->get('convert/(:num)', 'Quotations::convertToSo/$1');
    });

    // Sales Orders
    $routes->group('sales-orders', static function ($routes) {
        $routes->get('', 'SalesOrders::index');
        $routes->get('create', 'SalesOrders::new');
        $routes->get('new', 'SalesOrders::new');
        $routes->post('store', 'SalesOrders::create');
        $routes->post('create', 'SalesOrders::create');
        $routes->get('view/(:num)', 'SalesOrders::view/$1');
        $routes->get('print/(:num)', 'SalesOrders::printDoc/$1');
    });

    // Purchase Orders
    $routes->group('purchase-orders', static function ($routes) {
        $routes->get('', 'PurchaseOrders::index');
        $routes->get('create', 'PurchaseOrders::new');
        $routes->get('new', 'PurchaseOrders::new');
        $routes->post('store', 'PurchaseOrders::create');
        $routes->post('create', 'PurchaseOrders::create');
        $routes->get('view/(:num)', 'PurchaseOrders::view/$1');
        $routes->get('print/(:num)', 'PurchaseOrders::printDoc/$1');
    });

    // Goods Receipt Notes (GRN)
    $routes->group('grn', static function ($routes) {
        $routes->get('', 'Grn::index');
        $routes->get('create', 'Grn::create');
        $routes->post('store', 'Grn::store');
        $routes->get('view/(:num)', 'Grn::view/$1');
    });

    // Production & Shop Floor
    $routes->group('production', static function ($routes) {
        $routes->get('', 'Production::index');
        $routes->get('create', 'Production::create');
        $routes->post('store', 'Production::store');
        $routes->get('view/(:num)', 'Production::view/$1');
        $routes->post('update-status/(:num)', 'Production::updateStatus/$1');
        $routes->post('update-item-progress/(:num)', 'Production::updateItemProgress/$1');
        $routes->get('issues', 'Production::issues');
        $routes->get('issue-material', 'Production::issueMaterial');
        $routes->get('issue-material/(:num)', 'Production::issueMaterial/$1');
        $routes->post('store-material-issue', 'Production::storeMaterialIssue');
    });

    // Dispatches & Logistics
    $routes->group('dispatches', static function ($routes) {
        $routes->get('', 'Dispatches::index');
        $routes->get('create', 'Dispatches::create');
        $routes->post('store', 'Dispatches::store');
        $routes->get('view/(:num)', 'Dispatches::view/$1');
        $routes->post('update-status/(:num)', 'Dispatches::updateStatus/$1');
        $routes->get('print/(:num)', 'Dispatches::print/$1');
    });

    // Invoices & Receivables
    $routes->group('invoices', static function ($routes) {
        $routes->get('', 'Invoices::index');
        $routes->get('create', 'Invoices::create');
        $routes->post('store', 'Invoices::store');
        $routes->get('view/(:num)', 'Invoices::view/$1');
        $routes->get('print/(:num)', 'Invoices::print/$1');
    });

    // Payments
    $routes->group('payments', static function ($routes) {
        $routes->get('received', 'Payments::received');
        $routes->get('record-receipt', 'Payments::recordReceipt');
        $routes->post('store-receipt', 'Payments::storeReceipt');
        $routes->get('made', 'Payments::made');
        $routes->get('record-payment', 'Payments::recordPayment');
        $routes->post('store-payment', 'Payments::storePayment');
    });

    // Plant Workforce & Employees
    $routes->group('employees', static function ($routes) {
        $routes->get('', 'Employees::index');
        $routes->get('create', 'Employees::create');
        $routes->post('store', 'Employees::store');
        $routes->get('edit/(:num)', 'Employees::edit/$1');
        $routes->post('update/(:num)', 'Employees::update/$1');
    });

    // Reports & Analytics
    $routes->get('reports', 'Reports::index');

    // System Settings
    $routes->group('settings', static function ($routes) {
        $routes->get('', 'Settings::index');
        $routes->post('update', 'Settings::update');
    });
});
