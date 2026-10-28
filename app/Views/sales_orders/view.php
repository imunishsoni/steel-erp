<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="mb-0 fw-bold"><?= esc($order['so_no']) ?></h3>
            <?= status_badge($order['status']) ?>
        </div>
        <p class="text-muted small mb-0"><?= esc($order['project_name']) ?> &bull; Client PO: <span class="font-mono text-dark fw-bold"><?= esc($order['client_po_no']) ?></span></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('production/new?so_id=' . $order['id']) ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-gear-fill me-1"></i> Issue Fabrication Work Order
        </a>
        <a href="<?= base_url('dispatches/new?so_id=' . $order['id']) ?>" class="btn btn-success btn-sm">
            <i class="bi bi-truck me-1"></i> Create Dispatch Challan
        </a>
        <a href="<?= base_url('invoices/new?so_id=' . $order['id']) ?>" class="btn btn-warning text-dark btn-sm">
            <i class="bi bi-receipt me-1"></i> Generate GST Tax Invoice
        </a>
        <a href="<?= base_url('sales-orders/print/' . $order['id']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Print Confirmation
        </a>
        <a href="<?= base_url('sales-orders') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-building me-2 text-primary"></i>Customer / Plant</h6>
            <div class="small">
                <div class="fw-bold text-dark fs-6 mb-1"><?= esc($order['client_name']) ?></div>
                <div class="text-muted mb-2"><?= esc($order['delivery_address'] ?: '-') ?></div>
                <div class="mb-1"><strong>GSTIN:</strong> <span class="font-mono"><?= esc($order['client_gstin'] ?: '-') ?></span></div>
                <div><strong>Contact:</strong> <?= esc($order['contact_person'] ?: '-') ?> (<?= esc($order['client_phone'] ?: '-') ?>)</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-file-earmark-check me-2 text-primary"></i>Order & PO Information</h6>
            <div class="small">
                <div class="mb-2">SO Date: <strong><?= format_date($order['so_date']) ?></strong></div>
                <div class="mb-2">Client PO No: <strong class="font-mono text-primary"><?= esc($order['client_po_no'] ?: '-') ?></strong></div>
                <div class="mb-2">Client PO Date: <strong><?= format_date($order['client_po_date']) ?></strong></div>
                <div class="mb-2">Target Delivery: <strong class="text-danger"><?= format_date($order['delivery_date']) ?></strong></div>
                <div>Payment Terms: <?= esc($order['payment_terms']) ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="steel-card p-3 h-100 bg-primary bg-opacity-10 border-primary border-opacity-25">
            <h6 class="fw-bold border-bottom border-primary border-opacity-25 pb-2 mb-3 text-primary"><i class="bi bi-currency-rupee me-2"></i>Contract Value</h6>
            <div class="fs-3 fw-bold text-primary font-mono mb-1"><?= format_inr($order['grand_total']) ?></div>
            <div class="small text-muted mb-2"><?= esc($order['amount_in_words']) ?></div>
            <div class="badge bg-primary">Taxable: <?= format_inr($order['taxable_amount']) ?> | Tax: <?= format_inr($order['total_tax']) ?></div>
        </div>
    </div>
</div>

<!-- Ordered Items & Fulfillment Progress -->
<div class="steel-card mb-4">
    <div class="steel-card-header">
        <span class="fw-bold"><i class="bi bi-list-check me-2"></i>Ordered Line Items & Dispatch Fulfillment</span>
    </div>
    <div class="table-responsive">
        <table class="table table-steel mb-0">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th>Fabrication Scope & Description</th>
                    <th>HSN</th>
                    <th class="text-end">Ordered Qty</th>
                    <th class="text-end">Dispatched Qty</th>
                    <th class="text-end">Balance Qty</th>
                    <th class="text-end">Rate (₹)</th>
                    <th class="text-end">Total Amount (₹)</th>
                    <th>Fulfillment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <?php 
                        $ord = (float)$item['quantity'];
                        $disp = (float)$item['dispatched_qty'];
                        $bal = max(0, $ord - $disp);
                        $pct = ($ord > 0) ? min(100, round(($disp / $ord) * 100)) : 0;
                    ?>
                    <tr>
                        <td class="text-center"><?= esc($item['sl_no']) ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= esc($item['description']) ?></div>
                            <small class="text-muted"><?= esc($item['specification']) ?></small>
                        </td>
                        <td class="font-mono small"><?= esc($item['hsn_code']) ?></td>
                        <td class="text-end font-mono fw-bold"><?= number_format($ord, 2) ?> <?= esc($item['unit']) ?></td>
                        <td class="text-end font-mono text-success"><?= number_format($disp, 2) ?> <?= esc($item['unit']) ?></td>
                        <td class="text-end font-mono <?= ($bal > 0) ? 'text-danger' : 'text-muted' ?>"><?= number_format($bal, 2) ?> <?= esc($item['unit']) ?></td>
                        <td class="text-end font-mono"><?= format_inr($item['rate'], false) ?></td>
                        <td class="text-end font-mono fw-bold"><?= format_inr($item['amount']) ?></td>
                        <td style="min-width: 110px;">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar <?= ($pct == 100) ? 'bg-success' : 'bg-primary' ?>" style="width: <?= $pct ?>%"></div>
                            </div>
                            <small class="text-muted"><?= $pct ?>% dispatched</small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Linked Execution Tabs: Jobs, Dispatches, Invoices -->
<div class="steel-card">
    <div class="steel-card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs border-0" id="soTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active px-4 py-3 fw-bold" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobsPane" type="button">
                    <i class="bi bi-gear-wide-connected me-1"></i> Shop Floor Jobs (<?= count($jobs) ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-4 py-3 fw-bold" id="disp-tab" data-bs-toggle="tab" data-bs-target="#dispPane" type="button">
                    <i class="bi bi-truck me-1"></i> Dispatches & Gate Passes (<?= count($dispatches) ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-4 py-3 fw-bold" id="inv-tab" data-bs-toggle="tab" data-bs-target="#invPane" type="button">
                    <i class="bi bi-receipt me-1"></i> Tax Invoices (<?= count($invoices) ?>)
                </button>
            </li>
        </ul>
    </div>
    <div class="p-3">
        <div class="tab-content" id="soTabsContent">
            <!-- Jobs -->
            <div class="tab-pane fade show active" id="jobsPane">
                <div class="table-responsive">
                    <table class="table table-steel">
                        <thead>
                            <tr>
                                <th>Job Order No</th>
                                <th>Start Date</th>
                                <th>Target Date</th>
                                <th>Total Weight</th>
                                <th>Completion %</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($jobs)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-3">No fabrication jobs launched for this order</td></tr>
                            <?php else: ?>
                                <?php foreach ($jobs as $j): ?>
                                    <tr>
                                        <td class="font-mono fw-bold text-primary"><?= esc($j['job_no']) ?></td>
                                        <td><?= format_date($j['start_date']) ?></td>
                                        <td><?= format_date($j['target_date']) ?></td>
                                        <td class="font-mono"><?= number_format($j['total_weight'] / 1000, 1) ?> MT</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: <?= (float)$j['completion_pct'] ?>%"></div>
                                            </div>
                                            <small><?= (int)$j['completion_pct'] ?>% complete</small>
                                        </td>
                                        <td><?= status_badge($j['status']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('production/view/' . $j['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Dispatches -->
            <div class="tab-pane fade" id="dispPane">
                <div class="table-responsive">
                    <table class="table table-steel">
                        <thead>
                            <tr>
                                <th>DC No</th>
                                <th>Date</th>
                                <th>Transporter</th>
                                <th>Trailer / Vehicle No</th>
                                <th>E-Way Bill</th>
                                <th>Weight</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dispatches)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-3">No consignments dispatched yet</td></tr>
                            <?php else: ?>
                                <?php foreach ($dispatches as $d): ?>
                                    <tr>
                                        <td class="font-mono fw-bold text-primary"><?= esc($d['dc_no']) ?></td>
                                        <td><?= format_date($d['dc_date']) ?></td>
                                        <td><?= esc($d['transporter']) ?></td>
                                        <td class="font-mono"><?= esc($d['vehicle_no']) ?></td>
                                        <td class="font-mono small"><?= esc($d['eway_bill_no']) ?></td>
                                        <td class="font-mono"><?= number_format($d['total_weight'] / 1000, 1) ?> MT</td>
                                        <td><?= status_badge($d['status']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('dispatches/view/' . $d['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoices -->
            <div class="tab-pane fade" id="invPane">
                <div class="table-responsive">
                    <table class="table table-steel">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Grand Total</th>
                                <th>Paid</th>
                                <th>Balance Due</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($invoices)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-3">No tax invoices generated yet</td></tr>
                            <?php else: ?>
                                <?php foreach ($invoices as $inv): ?>
                                    <tr>
                                        <td class="font-mono fw-bold text-primary"><?= esc($inv['invoice_no']) ?></td>
                                        <td><?= format_date($inv['invoice_date']) ?></td>
                                        <td class="font-mono fw-bold"><?= format_inr($inv['grand_total']) ?></td>
                                        <td class="font-mono text-success"><?= format_inr($inv['paid_amount']) ?></td>
                                        <td class="font-mono text-danger"><?= format_inr($inv['balance_amount']) ?></td>
                                        <td><?= status_badge($inv['status']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('invoices/view/' . $inv['id']) ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
