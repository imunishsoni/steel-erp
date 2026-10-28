<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - <?= esc($order['po_no']) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/bootstrap.min.css') ?>">
    <style>
        body { font-family: "Helvetica Neue", Arial, sans-serif; font-size: 9.5pt; color: #111; }
        .print-box { max-width: 850px; margin: 0 auto; padding: 20px; }
        .company-header { border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 15px; }
        .table-quote { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-quote th, .table-quote td { border: 1px solid #94a3b8; padding: 6px 8px; font-size: 8.5pt; }
        .table-quote th { background: #f1f5f9; text-transform: uppercase; }
        .font-mono { font-family: "Courier New", monospace; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>

<div class="no-print bg-light border-bottom p-2 text-center mb-3">
    <button class="btn btn-primary btn-sm me-2" onclick="window.print()"><i class="bi bi-printer"></i> Print Purchase Order</button>
    <button class="btn btn-secondary btn-sm" onclick="window.close()">Close</button>
</div>

<div class="print-box">
    <div class="company-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0 text-primary"><?= esc($company['company_name']) ?></h4>
            <div class="small text-muted"><?= esc($company['tagline']) ?></div>
            <div class="small"><?= esc($company['address_line1']) ?>, <?= esc($company['city']) ?>, <?= esc($company['state']) ?></div>
            <div class="small"><strong>GSTIN:</strong> <?= esc($company['gstin']) ?> | <strong>PAN:</strong> <?= esc($company['pan']) ?></div>
        </div>
        <div class="text-end">
            <h5 class="fw-bold text-uppercase border p-2 bg-light mb-1">PURCHASE ORDER</h5>
            <div class="font-mono fw-bold fs-6 text-primary"><?= esc($order['po_no']) ?></div>
            <div class="small">Date: <?= format_date($order['po_date']) ?></div>
            <div class="small">Expected Delivery: <?= format_date($order['delivery_date']) ?></div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6">
            <div class="border p-2 rounded h-100">
                <div class="text-muted small text-uppercase fw-bold">Supplier / Mill:</div>
                <div class="fw-bold fs-6"><?= esc($order['vendor_name']) ?></div>
                <div class="small"><?= esc($order['address_line1']) ?>, <?= esc($order['city']) ?>, <?= esc($order['vendor_state']) ?></div>
                <div class="small"><strong>GSTIN:</strong> <?= esc($order['vendor_gstin'] ?: '-') ?></div>
            </div>
        </div>
        <div class="col-6">
            <div class="border p-2 rounded h-100">
                <div class="text-muted small text-uppercase fw-bold">Delivery Location & Terms:</div>
                <div class="small"><strong>Destination:</strong> <?= esc($order['delivery_address']) ?></div>
                <div class="small mt-1"><strong>Payment:</strong> <?= esc($order['payment_terms']) ?></div>
                <div class="small mt-1"><strong>Bank RTGS:</strong> <?= esc($order['bank_name']) ?> | <?= esc($order['bank_ifsc']) ?></div>
            </div>
        </div>
    </div>

    <table class="table-quote">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 48%;">Material Specification & Standard</th>
                <th style="width: 12%;">HSN</th>
                <th style="width: 15%;" class="text-end">Quantity</th>
                <th style="width: 15%;" class="text-end">Rate (₹)</th>
                <th style="width: 18%;" class="text-end">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order['items'] as $it): ?>
                <tr>
                    <td class="text-center"><?= esc($it['sl_no']) ?></td>
                    <td>
                        <strong><?= esc($it['description']) ?></strong>
                        <?php if (!empty($it['specification'])): ?>
                            <div class="small text-muted"><?= esc($it['specification']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="font-mono text-center"><?= esc($it['hsn_code']) ?></td>
                    <td class="text-end font-mono"><?= number_format($it['quantity'], 2) ?> <?= esc($it['unit']) ?></td>
                    <td class="text-end font-mono"><?= format_inr($it['rate'], false) ?></td>
                    <td class="text-end font-mono fw-bold"><?= format_inr($it['amount'], false) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5" class="text-end fw-bold">Taxable Amount:</td>
                <td class="text-end font-mono fw-bold"><?= format_inr($order['taxable_amount'], false) ?></td>
            </tr>
            <?php if ((float)$order['cgst_amount'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">CGST (9%) + SGST (9%):</td>
                    <td class="text-end font-mono"><?= format_inr($order['cgst_amount'] + $order['sgst_amount'], false) ?></td>
                </tr>
            <?php endif; ?>
            <?php if ((float)$order['igst_amount'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">IGST (18% Inter-State):</td>
                    <td class="text-end font-mono"><?= format_inr($order['igst_amount'], false) ?></td>
                </tr>
            <?php endif; ?>
            <?php if ((float)$order['freight'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">Freight Charges:</td>
                    <td class="text-end font-mono"><?= format_inr($order['freight'], false) ?></td>
                </tr>
            <?php endif; ?>
            <tr style="background: #e2e8f0; font-size: 10pt;">
                <td colspan="5" class="text-end fw-bold">Grand Total (INR):</td>
                <td class="text-end font-mono fw-bold"><?= format_inr($order['grand_total']) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="border p-2 bg-light mb-3 small">
        <strong>Amount in Words:</strong> <?= esc($order['amount_in_words']) ?>
    </div>

    <div class="border p-2 rounded small mb-3">
        <div class="fw-bold border-bottom pb-1 mb-1">Terms & Conditions of Purchase:</div>
        <div style="white-space: pre-wrap;"><?= esc($order['terms_conditions']) ?></div>
    </div>

    <div class="row mt-4 pt-3 text-center small">
        <div class="col-6 text-start">
            <div>Prepared By: <strong><?= esc($currentUser['full_name'] ?? 'Procurement Officer') ?></strong></div>
        </div>
        <div class="col-6 text-end">
            <div>For <strong><?= esc($company['company_name']) ?></strong></div>
            <div style="height: 40px;"></div>
            <div>Authorized Purchase Authority</div>
        </div>
    </div>
</div>

</body>
</html>
