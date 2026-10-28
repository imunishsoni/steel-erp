<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?></title>
    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #111; background: #fff; }
        .print-container { max-width: 920px; margin: 15px auto; padding: 20px; border: 1px solid #dee2e6; }
        .doc-title { font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .table-bordered th, .table-bordered td { border-color: #333 !important; }
        .copy-pill { border: 1px solid #000; padding: 2px 8px; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        @media print {
            body { margin: 0; padding: 0; }
            .print-container { border: none; margin: 0; padding: 5px; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center my-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm me-2">Print Tax Invoice</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">Close</button>
    </div>

    <div class="print-container">
        <!-- Top Banner -->
        <div class="row align-items-center mb-2">
            <div class="col-8">
                <h3 class="fw-bold mb-0"><?= esc(company_settings('company_name', 'BHARAT HEAVY STEEL FABRICATORS LTD.')) ?></h3>
                <div class="text-muted small">Specialists in Boiler Structures, ESP Casings, Kiln Shells & Heavy Steel Bridges</div>
                <div class="small">Works: <?= esc(company_settings('company_address', 'Plot No. 42-45, Heavy Industrial Area, MIDC, Butibori, Nagpur, Maharashtra - 441122')) ?></div>
                <div class="small">
                    <strong>GSTIN:</strong> <?= esc(company_settings('gstin', '27AABCB1234F1Z5')) ?> &bull; 
                    <strong>PAN:</strong> <?= esc(company_settings('pan_no', 'AABCB1234F')) ?> &bull; 
                    <strong>State:</strong> Maharashtra (27)
                </div>
            </div>
            <div class="col-4 text-end">
                <span class="copy-pill d-inline-block mb-1">Original for Recipient</span>
                <div class="doc-title text-primary">TAX INVOICE</div>
                <div class="small text-muted">[ Under Rule 46 of CGST Rules, 2017 ]</div>
                <div class="small"><strong>GST Category:</strong> B2B Supply</div>
            </div>
        </div>

        <hr class="my-2 border-dark">

        <!-- Invoice Meta Details -->
        <table class="table table-bordered table-sm mb-2">
            <tr>
                <td style="width: 50%;">
                    <strong>Invoice No:</strong> <span class="fs-6 fw-bold"><?= esc($invoice['invoice_no']) ?></span><br>
                    <strong>Invoice Date:</strong> <?= date('d-M-Y', strtotime($invoice['invoice_date'])) ?><br>
                    <strong>Due Date:</strong> <?= date('d-M-Y', strtotime($invoice['due_date'])) ?><br>
                    <strong>Payment Terms:</strong> <?= esc($invoice['payment_terms']) ?><br>
                    <strong>Place of Supply:</strong> <?= esc($invoice['place_of_supply']) ?> (<?= $invoice['is_igst'] ? 'Inter-State' : 'Intra-State' ?>)
                </td>
                <td style="width: 50%;">
                    <strong>Delivery Challan No:</strong> <?= esc($invoice['dc_no'] ?: 'Direct Handover') ?><br>
                    <strong>Client PO No & Date:</strong> <?= esc($invoice['client_po_no'] ?: 'As per LOI') ?> (<?= $invoice['client_po_date'] ? date('d-M-Y', strtotime($invoice['client_po_date'])) : '' ?>)<br>
                    <strong>Vehicle / Trailer No:</strong> <span class="font-monospace fw-bold"><?= esc($invoice['vehicle_no'] ?: 'N/A') ?></span><br>
                    <strong>Govt E-Way Bill No:</strong> <span class="font-monospace fw-bold"><?= esc($invoice['eway_bill_no'] ?: 'N/A') ?></span><br>
                    <strong>Reverse Charge Applicable:</strong> NO
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Details of Receiver (Billed To):</strong><br>
                    <strong><?= esc($invoice['client_name']) ?></strong><br>
                    <?= nl2br(esc($invoice['billing_address'])) ?><br>
                    <strong>GSTIN / UIN:</strong> <?= esc($invoice['client_gstin'] ?: 'Unregistered') ?> &bull; <strong>State:</strong> <?= esc($invoice['client_state'] ?: $invoice['place_of_supply']) ?>
                </td>
                <td>
                    <strong>Details of Consignee (Shipped To):</strong><br>
                    <?= nl2br(esc($invoice['shipping_address'])) ?>
                </td>
            </tr>
        </table>

        <!-- Line Items Table -->
        <table class="table table-bordered table-sm mb-2">
            <thead class="table-light">
                <tr class="text-center align-middle">
                    <th rowspan="2" style="width: 4%;">#</th>
                    <th rowspan="2" style="width: 36%;">Description of Goods / Fabricated Steel Components</th>
                    <th rowspan="2" style="width: 10%;">HSN Code</th>
                    <th rowspan="2" style="width: 8%;">Qty</th>
                    <th rowspan="2" style="width: 6%;">Unit</th>
                    <th rowspan="2" style="width: 12%;">Rate (₹)</th>
                    <th rowspan="2" style="width: 12%;">Taxable Value (₹)</th>
                    <?php if (!$invoice['is_igst']): ?>
                    <th colspan="2" style="width: 12%;">Central Tax (CGST)</th>
                    <th colspan="2" style="width: 12%;">State Tax (SGST)</th>
                    <?php else: ?>
                    <th colspan="2" style="width: 12%;">Integrated Tax (IGST)</th>
                    <?php endif; ?>
                    <th rowspan="2" style="width: 12%;">Total (₹)</th>
                </tr>
                <tr class="text-center">
                    <?php if (!$invoice['is_igst']): ?>
                    <th>Rate</th><th>Amt</th><th>Rate</th><th>Amt</th>
                    <?php else: ?>
                    <th>Rate</th><th>Amt</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoice['items'])): foreach ($invoice['items'] as $item): ?>
                <tr>
                    <td class="text-center"><?= $item['sl_no'] ?></td>
                    <td>
                        <strong><?= esc($item['description']) ?></strong>
                    </td>
                    <td class="text-center"><?= esc($item['hsn_code']) ?></td>
                    <td class="text-end"><?= number_format($item['quantity'], 3) ?></td>
                    <td class="text-center"><?= esc($item['unit']) ?></td>
                    <td class="text-end"><?= number_format($item['rate'], 2) ?></td>
                    <td class="text-end fw-semibold"><?= number_format($item['amount'], 2) ?></td>
                    <?php if (!$invoice['is_igst']): ?>
                    <td class="text-center small">9%</td>
                    <td class="text-end small"><?= number_format($item['cgst_amt'], 2) ?></td>
                    <td class="text-center small">9%</td>
                    <td class="text-end small"><?= number_format($item['sgst_amt'], 2) ?></td>
                    <?php else: ?>
                    <td class="text-center small">18%</td>
                    <td class="text-end small"><?= number_format($item['igst_amt'], 2) ?></td>
                    <?php endif; ?>
                    <td class="text-end fw-bold"><?= number_format($item['total_amount'], 2) ?></td>
                </tr>
                <?php endforeach; endif; ?>
                
                <!-- Totals Row -->
                <tr class="fw-bold bg-light">
                    <td colspan="6" class="text-end">Total Taxable Value & Tax:</td>
                    <td class="text-end"><?= format_inr($invoice['taxable_amount']) ?></td>
                    <?php if (!$invoice['is_igst']): ?>
                    <td colspan="2" class="text-end"><?= format_inr($invoice['cgst_amount']) ?></td>
                    <td colspan="2" class="text-end"><?= format_inr($invoice['sgst_amount']) ?></td>
                    <?php else: ?>
                    <td colspan="2" class="text-end"><?= format_inr($invoice['igst_amount']) ?></td>
                    <?php endif; ?>
                    <td class="text-end"><?= format_inr($invoice['taxable_amount'] + $invoice['total_tax']) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Amount In Words & Grand Total Box -->
        <table class="table table-bordered table-sm mb-2">
            <tr>
                <td style="width: 65%;">
                    <div class="small text-muted">Total Invoice Value in Words:</div>
                    <div class="fw-bold text-dark fs-6"><?= esc($invoice['amount_in_words']) ?></div>
                    <hr class="my-1">
                    <div class="small">
                        <strong>Bank Remittance Details:</strong><br>
                        Bank Name: State Bank of India &bull; Branch: Industrial Area, Nagpur<br>
                        Account Name: Bharat Heavy Steel Fabricators Ltd &bull; Account No: 39485720194<br>
                        IFSC Code: SBIN0001234
                    </div>
                </td>
                <td style="width: 35%;">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td>Taxable Value:</td>
                            <td class="text-end"><?= format_inr($invoice['taxable_amount']) ?></td>
                        </tr>
                        <?php if (!$invoice['is_igst']): ?>
                        <tr>
                            <td>Central Tax (CGST 9%):</td>
                            <td class="text-end"><?= format_inr($invoice['cgst_amount']) ?></td>
                        </tr>
                        <tr>
                            <td>State Tax (SGST 9%):</td>
                            <td class="text-end"><?= format_inr($invoice['sgst_amount']) ?></td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <td>Integrated Tax (IGST 18%):</td>
                            <td class="text-end"><?= format_inr($invoice['igst_amount']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Round Off:</td>
                            <td class="text-end"><?= format_inr($invoice['round_off']) ?></td>
                        </tr>
                        <tr class="fw-bold border-top">
                            <td class="fs-6">Grand Total:</td>
                            <td class="text-end fs-6 text-primary"><?= format_inr($invoice['grand_total']) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Terms & Signatures -->
        <div class="row align-items-end pt-2">
            <div class="col-7">
                <div class="border p-2 small bg-light">
                    <strong>Terms & Conditions of Sale:</strong>
                    <ol class="mb-0 ps-3">
                        <li>Interest @ 18% p.a. will be levied on bills unpaid beyond 30 days.</li>
                        <li>Certified Mill Test Certificates (MTC) and Inspection Release Notes (IRN) attached.</li>
                        <li>All disputes subject to Nagpur, Maharashtra jurisdiction only.</li>
                    </ol>
                </div>
            </div>
            <div class="col-5 text-center">
                <div class="small fw-semibold">For BHARAT HEAVY STEEL FABRICATORS LTD.</div>
                <div style="height: 55px;"></div>
                <div class="border-top pt-1 small fw-bold">Authorized Signatory / Accounts Manager</div>
            </div>
        </div>
    </div>
</body>
</html>
