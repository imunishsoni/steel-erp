<div class="row mb-3">
    <div class="col-md-7">
        <h4 class="mb-1"><i class="bi bi-receipt me-2 text-primary"></i>Tax Invoice #<?= esc($invoice['invoice_no']) ?></h4>
        <div class="text-muted small">
            Date: <strong><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></strong> &bull; 
            Client: <strong><?= esc($invoice['client_name']) ?></strong> &bull; 
            GSTIN: <strong><?= esc($invoice['client_gstin'] ?: 'Unregistered') ?></strong>
        </div>
    </div>
    <div class="col-md-5 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('invoices') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <?php if ($invoice['balance_amount'] > 0): ?>
        <a href="<?= base_url('payments/record-receipt?invoice_id=' . $invoice['id']) ?>" class="btn btn-success btn-sm">
            <i class="bi bi-cash-stack me-1"></i>Record Payment
        </a>
        <?php endif; ?>
        <a href="<?= base_url('invoices/print/' . $invoice['id']) ?>" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i>Print GST Invoice
        </a>
    </div>
</div>

<div class="row g-3">
    <!-- Invoice Overview -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Invoice Summary</span>
                <div>
                    <?= status_badge($invoice['status']) ?>
                    <span class="badge bg-light text-dark border ms-1"><?= $invoice['is_igst'] ? 'Inter-State (IGST)' : 'Intra-State (CGST + SGST)' ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Billed To (Buyer)</small>
                        <h6 class="fw-bold mb-1"><?= esc($invoice['client_name']) ?></h6>
                        <div class="small text-muted"><?= nl2br(esc($invoice['billing_address'])) ?></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Shipped To (Plant Site)</small>
                        <div class="small fw-semibold"><?= nl2br(esc($invoice['shipping_address'])) ?></div>
                        <div class="small text-muted mt-1">Place of Supply: <strong><?= esc($invoice['place_of_supply']) ?></strong></div>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">Client PO Ref</small>
                        <strong><?= esc($invoice['client_po_no'] ?: 'Direct') ?></strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Internal SO Ref</small>
                        <?php if ($invoice['sales_order_id']): ?>
                            <a href="<?= base_url('sales-orders/view/' . $invoice['sales_order_id']) ?>" class="fw-bold text-decoration-none"><?= esc($invoice['so_no']) ?></a>
                        <?php else: ?>
                            <span class="text-muted">&mdash;</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Outward DC No</small>
                        <?php if ($invoice['dispatch_id']): ?>
                            <a href="<?= base_url('dispatches/view/' . $invoice['dispatch_id']) ?>" class="fw-bold text-decoration-none"><?= esc($invoice['dc_no']) ?></a>
                        <?php else: ?>
                            <span class="text-muted">&mdash;</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Vehicle / Trailer</small>
                        <span class="badge bg-dark font-monospace"><?= esc($invoice['vehicle_no'] ?: 'Ex-Works') ?></span>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">E-Way Bill No</small>
                        <code><?= esc($invoice['eway_bill_no'] ?: 'N/A') ?></code>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Payment Terms</small>
                        <span><?= esc($invoice['payment_terms']) ?></span>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Payment Due Date</small>
                        <span class="text-danger fw-semibold"><?= date('d M Y', strtotime($invoice['due_date'])) ?></span>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Reverse Charge</small>
                        <span class="badge bg-secondary">NO</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-stack me-2"></i>Invoice Line Items</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>HSN</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Rate (₹)</th>
                            <th class="text-end">Taxable Value (₹)</th>
                            <th class="text-end">GST</th>
                            <th class="text-end">Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($invoice['items'])): foreach ($invoice['items'] as $i => $item): ?>
                        <tr>
                            <td><?= $item['sl_no'] ?></td>
                            <td><strong><?= esc($item['description']) ?></strong></td>
                            <td><code><?= esc($item['hsn_code']) ?></code></td>
                            <td class="text-end"><?= number_format($item['quantity'], 3) ?> <?= esc($item['unit']) ?></td>
                            <td class="text-end"><?= number_format($item['rate'], 2) ?></td>
                            <td class="text-end"><?= number_format($item['amount'], 2) ?></td>
                            <td class="text-end">
                                <small class="text-muted"><?= number_format($item['gst_rate'], 0) ?>%</small><br>
                                <?= number_format($invoice['is_igst'] ? $item['igst_amt'] : ($item['cgst_amt'] + $item['sgst_amt']), 2) ?>
                            </td>
                            <td class="text-end fw-bold"><?= number_format($item['total_amount'], 2) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="8" class="text-center py-3 text-muted">No items in this invoice.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments Received Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-cash-stack me-2"></i>Collections / Payments Received Against This Invoice</span>
                <?php if ($invoice['balance_amount'] > 0): ?>
                <a href="<?= base_url('payments/record-receipt?invoice_id=' . $invoice['id']) ?>" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus me-1"></i>Record Receipt
                </a>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Receipt No</th>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>UTR / Cheque No</th>
                            <th class="text-end">TDS Deducted</th>
                            <th class="text-end">Amount Cleared (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($invoice['payments'])): foreach ($invoice['payments'] as $pay): ?>
                        <tr>
                            <td><code><?= esc($pay['receipt_no'] ?? $pay['payment_no'] ?? 'RCT') ?></code></td>
                            <td><?= date('d-m-Y', strtotime($pay['receipt_date'] ?? $pay['payment_date'] ?? date('Y-m-d'))) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= strtoupper($pay['payment_mode']) ?></span></td>
                            <td><?= esc($pay['reference_no'] ?: 'Direct Transfer') ?></td>
                            <td class="text-end text-muted"><?= format_inr($pay['tds_amount'] ?? 0) ?></td>
                            <td class="text-end fw-bold text-success"><?= format_inr($pay['amount']) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-3 text-muted">No payments received yet for this invoice.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Side Totals Card -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-calculator me-2"></i>Tax & Payment Breakdown</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal (Taxable Value):</span>
                    <strong><?= format_inr($invoice['taxable_amount']) ?></strong>
                </div>

                <?php if (!$invoice['is_igst']): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Central GST (CGST 9%):</span>
                    <span><?= format_inr($invoice['cgst_amount']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">State GST (SGST 9%):</span>
                    <span><?= format_inr($invoice['sgst_amount']) ?></span>
                </div>
                <?php else: ?>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Integrated GST (IGST 18%):</span>
                    <span><?= format_inr($invoice['igst_amount']) ?></span>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Tax:</span>
                    <strong class="text-danger"><?= format_inr($invoice['total_tax']) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Round Off:</span>
                    <span><?= format_inr($invoice['round_off']) ?></span>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <span class="fs-5 fw-bold">Grand Total:</span>
                    <span class="fs-4 fw-bold text-primary"><?= format_inr($invoice['grand_total']) ?></span>
                </div>

                <div class="p-2 border rounded bg-light mb-3">
                    <div class="small text-muted">Amount in Words:</div>
                    <div class="small fw-semibold text-dark"><?= esc($invoice['amount_in_words']) ?></div>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Paid to Date:</span>
                        <strong class="text-success"><?= format_inr($invoice['paid_amount']) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fs-6 fw-bold">Balance Receivable:</span>
                        <span class="fs-5 fw-bold text-danger"><?= format_inr($invoice['balance_amount']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-bold"><i class="bi bi-bank me-2"></i>Bank Remittance Details</span>
            </div>
            <div class="card-body small">
                <pre class="mb-0 bg-light p-2 border rounded font-monospace" style="white-space: pre-wrap; font-size: 12px;"><?= esc($invoice['bank_details'] ?: company_settings('bank_details', "Bank: SBI\nA/c: 39485720194\nIFSC: SBIN0001234")) ?></pre>
            </div>
        </div>
    </div>
</div>
