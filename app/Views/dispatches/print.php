<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?></title>
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #212529; background: #fff; }
        .print-container { max-width: 900px; margin: 20px auto; padding: 25px; border: 1px solid #dee2e6; }
        .doc-title { font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .table-bordered th, .table-bordered td { border-color: #333 !important; }
        .copy-pill { border: 1px solid #000; padding: 2px 8px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        @media print {
            body { margin: 0; padding: 0; }
            .print-container { border: none; margin: 0; padding: 10px; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center my-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm me-2">Print Delivery Challan</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">Close</button>
    </div>

    <div class="print-container">
        <!-- Header -->
        <div class="row align-items-center mb-3">
            <div class="col-8">
                <h3 class="fw-bold mb-0 text-dark"><?= esc(company_settings('company_name', 'BHARAT HEAVY STEEL FABRICATORS LTD.')) ?></h3>
                <div class="text-muted small">Specialists in Boiler Structures, ESP Casings, Kiln Shells & Heavy Steel Bridges</div>
                <div class="small">Works: <?= esc(company_settings('company_address', 'Plot No. 42-45, Heavy Industrial Area, MIDC, Butibori, Nagpur, Maharashtra - 441122')) ?></div>
                <div class="small">
                    <strong>GSTIN:</strong> <?= esc(company_settings('gstin', '27AABCB1234F1Z5')) ?> &bull; 
                    <strong>PAN:</strong> <?= esc(company_settings('pan_no', 'AABCB1234F')) ?> &bull; 
                    <strong>State Code:</strong> 27 (Maharashtra)
                </div>
            </div>
            <div class="col-4 text-end">
                <span class="copy-pill d-inline-block mb-2">Original for Consignee</span>
                <div class="doc-title text-primary">DELIVERY CHALLAN</div>
                <div class="small text-muted">[ Issued under Rule 55 of CGST Rules, 2017 ]</div>
                <div class="small fw-bold">GATE PASS CUM CHALLAN</div>
            </div>
        </div>

        <hr class="my-2 border-dark">

        <!-- Consignment Meta -->
        <table class="table table-bordered table-sm mb-3">
            <tr>
                <td style="width: 50%;">
                    <strong>Challan No:</strong> <span class="fs-6 fw-bold"><?= esc($dispatch['dc_no']) ?></span><br>
                    <strong>Challan Date:</strong> <?= date('d-M-Y', strtotime($dispatch['dc_date'])) ?><br>
                    <strong>Sales Order Ref:</strong> <?= esc($dispatch['so_no'] ?: 'Direct Dispatch') ?><br>
                    <strong>Client PO Ref:</strong> <?= esc($dispatch['client_po_no'] ?: 'As per Contract') ?>
                </td>
                <td style="width: 50%;">
                    <strong>Govt E-Way Bill No:</strong> <span class="fw-bold font-monospace"><?= esc($dispatch['eway_bill_no'] ?: 'IN-TRANSIT') ?></span><br>
                    <strong>Vehicle / Trailer No:</strong> <span class="badge bg-dark fs-6"><?= esc($dispatch['vehicle_no']) ?></span><br>
                    <strong>Transporter:</strong> <?= esc($dispatch['transporter'] ?: 'Self / Dedicated') ?><br>
                    <strong>LR No & Date:</strong> <?= esc($dispatch['lr_no'] ?: 'N/A') ?> (<?= $dispatch['lr_date'] ? date('d-M-Y', strtotime($dispatch['lr_date'])) : '' ?>)
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Consignee (Billed To):</strong><br>
                    <strong><?= esc($dispatch['client_name']) ?></strong><br>
                    GSTIN: <?= esc($dispatch['client_gstin'] ?: 'N/A') ?><br>
                    Contact: <?= esc($dispatch['contact_person'] ?: '') ?> (<?= esc($dispatch['client_phone'] ?: '') ?>)
                </td>
                <td>
                    <strong>Delivery Site / Shipped To:</strong><br>
                    <?= nl2br(esc($dispatch['delivery_address'])) ?>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="table table-bordered table-sm mb-3">
            <thead class="table-light">
                <tr class="text-center">
                    <th style="width: 5%;">Sr.</th>
                    <th style="width: 45%;">Description of Fabricated Steel Goods & Markings</th>
                    <th style="width: 12%;">HSN Code</th>
                    <th style="width: 12%;">Quantity</th>
                    <th style="width: 13%;">Net Wt (MT)</th>
                    <th style="width: 13%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totWt = 0;
                if (!empty($dispatch['items'])): foreach ($dispatch['items'] as $i => $item): 
                    $totWt += $item['weight'];
                ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td>
                        <strong><?= esc($item['description']) ?></strong>
                    </td>
                    <td class="text-center">73089090</td>
                    <td class="text-center"><?= number_format($item['quantity'], 2) ?> <?= esc($item['unit']) ?></td>
                    <td class="text-end fw-bold"><?= number_format($item['weight'], 3) ?></td>
                    <td class="small text-muted"><?= esc($item['remarks'] ?: '') ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center py-3">No line items recorded.</td></tr>
                <?php endif; ?>
                <tr class="fw-bold">
                    <td colspan="4" class="text-end">Total Consignment Net Weight:</td>
                    <td class="text-end text-primary"><?= number_format($totWt ?: $dispatch['total_weight'], 3) ?> MT</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Driver & Packages -->
        <table class="table table-bordered table-sm mb-3">
            <tr>
                <td style="width: 25%;"><strong>Driver Name:</strong> <?= esc($dispatch['driver_name'] ?: 'N/A') ?></td>
                <td style="width: 25%;"><strong>Driver Mobile:</strong> <?= esc($dispatch['driver_phone'] ?: 'N/A') ?></td>
                <td style="width: 25%;"><strong>Total Packages:</strong> <?= $dispatch['no_of_packages'] ?> Packages</td>
                <td style="width: 25%;"><strong>Dispatched By:</strong> Store & Dispatch Dept</td>
            </tr>
        </table>

        <!-- Terms / Notice -->
        <div class="border p-2 small mb-4 bg-light">
            <strong>Declaration & Terms of Movement:</strong>
            <ol class="mb-0 ps-3">
                <li>This delivery challan is issued under Rule 55 of the CGST Rules, 2017 for transportation of fabricated steel goods.</li>
                <li>The material received at destination must be verified against drawings and Mill Test Certificates before unloading.</li>
                <li>Any transit damage, short shipment, or discrepancy must be endorsed on this Challan and notified within 24 hours.</li>
            </ol>
        </div>

        <!-- Signatures -->
        <div class="row pt-4 text-center">
            <div class="col-3">
                <div style="height: 50px;"></div>
                <div class="border-top pt-1 small fw-bold">Prepared By<br>(Store Officer)</div>
            </div>
            <div class="col-3">
                <div style="height: 50px;"></div>
                <div class="border-top pt-1 small fw-bold">Security Main Gate<br>(Verified Outward)</div>
            </div>
            <div class="col-3">
                <div style="height: 50px;"></div>
                <div class="border-top pt-1 small fw-bold">Driver / Transporter<br>(Material Received)</div>
            </div>
            <div class="col-3">
                <div style="height: 50px;"></div>
                <div class="border-top pt-1 small fw-bold">Consignee Acknowledgment<br>(Site Incharge Seal & Sign)</div>
            </div>
        </div>
    </div>
</body>
</html>
