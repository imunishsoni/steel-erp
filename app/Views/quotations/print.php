<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - <?= esc($quotation['quotation_no']) ?></title>
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
    <button class="btn btn-primary btn-sm me-2" onclick="window.print()"><i class="bi bi-printer"></i> Print Quotation</button>
    <button class="btn btn-secondary btn-sm" onclick="window.close()">Close</button>
</div>

<div class="print-box">
    <!-- Header -->
    <div class="company-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0 text-primary"><?= esc($company['company_name']) ?></h4>
            <div class="small text-muted"><?= esc($company['tagline']) ?></div>
            <div class="small"><?= esc($company['address_line1']) ?>, <?= esc($company['city']) ?>, <?= esc($company['state']) ?> - <?= esc($company['pincode']) ?></div>
            <div class="small"><strong>GSTIN:</strong> <?= esc($company['gstin']) ?> | <strong>PAN:</strong> <?= esc($company['pan']) ?> | <strong>Phone:</strong> <?= esc($company['phone']) ?></div>
        </div>
        <div class="text-end">
            <h5 class="fw-bold text-uppercase border p-2 bg-light mb-1">PRICE QUOTATION</h5>
            <div class="font-mono fw-bold"><?= esc($quotation['quotation_no']) ?></div>
            <div class="small">Date: <?= format_date($quotation['quotation_date']) ?></div>
            <div class="small">Valid Until: <?= format_date($quotation['valid_until']) ?></div>
        </div>
    </div>

    <!-- Client & Project Info -->
    <div class="row g-2 mb-3">
        <div class="col-7">
            <div class="border p-2 rounded h-100">
                <div class="text-muted small text-uppercase fw-bold">Customer Details:</div>
                <div class="fw-bold fs-6"><?= esc($quotation['client_name']) ?></div>
                <div class="small"><?= esc($quotation['address_line1']) ?>, <?= esc($quotation['city']) ?>, <?= esc($quotation['client_state']) ?></div>
                <div class="small"><strong>GSTIN:</strong> <?= esc($quotation['client_gstin'] ?: 'Unregistered') ?> | <strong>Attn:</strong> <?= esc($quotation['contact_person'] ?: '-') ?></div>
            </div>
        </div>
        <div class="col-5">
            <div class="border p-2 rounded h-100">
                <div class="text-muted small text-uppercase fw-bold">Project Details:</div>
                <div class="fw-bold"><?= esc($quotation['project_name']) ?></div>
                <div class="small mt-1"><strong>Subject:</strong> <?= esc($quotation['subject']) ?></div>
                <div class="small mt-1"><strong>Delivery:</strong> <?= esc($quotation['delivery_period']) ?></div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <table class="table-quote">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Description & Technical Specification</th>
                <th style="width: 12%;">HSN/SAC</th>
                <th style="width: 12%;" class="text-end">Quantity</th>
                <th style="width: 12%;" class="text-end">Rate (₹)</th>
                <th style="width: 14%;" class="text-end">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quotation['items'] as $it): ?>
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
                <td colspan="5" class="text-end fw-bold">Subtotal:</td>
                <td class="text-end font-mono fw-bold"><?= format_inr($quotation['subtotal'], false) ?></td>
            </tr>
            <?php if ((float)$quotation['discount_amt'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">Discount (<?= (float)$quotation['discount_pct'] ?>%):</td>
                    <td class="text-end font-mono">- <?= format_inr($quotation['discount_amt'], false) ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="5" class="text-end fw-bold">Taxable Amount:</td>
                <td class="text-end font-mono fw-bold"><?= format_inr($quotation['taxable_amount'], false) ?></td>
            </tr>
            <?php if ((float)$quotation['cgst_amount'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">CGST (9%):</td>
                    <td class="text-end font-mono"><?= format_inr($quotation['cgst_amount'], false) ?></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">SGST (9%):</td>
                    <td class="text-end font-mono"><?= format_inr($quotation['sgst_amount'], false) ?></td>
                </tr>
            <?php endif; ?>
            <?php if ((float)$quotation['igst_amount'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">IGST (18% Inter-State):</td>
                    <td class="text-end font-mono"><?= format_inr($quotation['igst_amount'], false) ?></td>
                </tr>
            <?php endif; ?>
            <?php if ((float)$quotation['freight_charges'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">Freight & Handling:</td>
                    <td class="text-end font-mono"><?= format_inr($quotation['freight_charges'], false) ?></td>
                </tr>
            <?php endif; ?>
            <tr style="background: #e2e8f0; font-size: 10pt;">
                <td colspan="5" class="text-end fw-bold">Grand Total (INR):</td>
                <td class="text-end font-mono fw-bold"><?= format_inr($quotation['grand_total']) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="border p-2 bg-light mb-3 small">
        <strong>Amount in Words:</strong> <?= esc($quotation['amount_in_words']) ?>
    </div>

    <!-- Commercial Terms -->
    <div class="row g-2 mb-4 small">
        <div class="col-12 border p-2 rounded">
            <div class="fw-bold text-uppercase border-bottom pb-1 mb-1">Commercial Terms & Conditions:</div>
            <div><strong>Payment Terms:</strong> <?= esc($quotation['payment_terms']) ?></div>
            <div><strong>Delivery Terms:</strong> <?= esc($quotation['delivery_terms']) ?></div>
            <div><strong>Delivery Period:</strong> <?= esc($quotation['delivery_period']) ?></div>
            <div class="mt-2 text-muted" style="white-space: pre-wrap;"><?= esc($quotation['terms_conditions']) ?></div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="row mt-4 pt-3 text-center small">
        <div class="col-6 text-start">
            <div>Customer Acceptance Signature & Stamp:</div>
            <div style="height: 50px;"></div>
            <div>_____________________________</div>
            <div class="text-muted">Authorized Signatory</div>
        </div>
        <div class="col-6 text-end">
            <div>For <strong><?= esc($company['company_name']) ?></strong></div>
            <div style="height: 50px;"></div>
            <div>_____________________________</div>
            <div class="text-muted">Authorized Estimation Signatory</div>
        </div>
    </div>
</div>

</body>
</html>
