<?php

namespace App\Controllers;

use App\Models\PaymentReceivedModel;
use App\Models\PaymentMadeModel;
use App\Models\InvoiceModel;
use App\Models\PurchaseOrderModel;
use App\Models\ClientModel;
use App\Models\VendorModel;

class Payments extends BaseController
{
    protected $receivedModel;
    protected $madeModel;

    public function __construct()
    {
        $this->receivedModel = new PaymentReceivedModel();
        $this->madeModel = new PaymentMadeModel();
    }

    public function received()
    {
        $receipts = $this->receivedModel->getWithRelations();

        $stats = [
            'total_received' => array_sum(array_column($receipts, 'amount')),
            'total_tds'      => array_sum(array_column($receipts, 'tds_amount')),
            'count'          => count($receipts),
        ];

        return $this->render('payments/received', [
            'pageTitle' => 'Collections & Client Payment Receipts',
            'receipts'  => $receipts,
            'stats'     => $stats,
        ]);
    }

    public function recordReceipt()
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->where('is_active', 1)->orderBy('company_name', 'ASC')->findAll();

        $invModel = new InvoiceModel();
        $invoices = $invModel->where('balance_amount >', 0)->orderBy('id', 'DESC')->findAll();

        $selectedInvId = $this->request->getGet('invoice_id');
        $selectedInv = null;
        if ($selectedInvId) {
            $selectedInv = $invModel->find($selectedInvId);
        }

        // Auto receipt number: RCT-YYMM-001
        $prefix = 'RCT-' . date('ym') . '-';
        $latest = $this->receivedModel->like('receipt_no', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $nextSeq = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['receipt_no'], $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        }
        $autoReceiptNo = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $this->render('payments/receipt_form', [
            'pageTitle'      => 'Record Client Payment Receipt',
            'clients'        => $clients,
            'invoices'       => $invoices,
            'selectedInv'    => $selectedInv,
            'autoReceiptNo'  => $autoReceiptNo,
        ]);
    }

    public function storeReceipt()
    {
        $rules = [
            'receipt_no'   => 'required|is_unique[payments_received.receipt_no]',
            'receipt_date' => 'required|valid_date',
            'client_id'    => 'required|is_not_unique[clients.id]',
            'amount'       => 'required|greater_than[0]',
            'payment_mode' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $amount = floatval($this->request->getPost('amount'));
        $tds = floatval($this->request->getPost('tds_amount') ?? 0);
        $totalCredit = $amount + $tds;
        $invoiceId = $this->request->getPost('invoice_id');

        $receiptData = [
            'receipt_no'   => $this->request->getPost('receipt_no'),
            'receipt_date' => $this->request->getPost('receipt_date'),
            'client_id'    => $this->request->getPost('client_id'),
            'invoice_id'   => $invoiceId ?: null,
            'amount'       => $amount,
            'payment_mode' => $this->request->getPost('payment_mode'),
            'reference_no' => $this->request->getPost('reference_no'),
            'bank_name'    => $this->request->getPost('bank_name'),
            'tds_amount'   => $tds,
            'remarks'      => $this->request->getPost('remarks'),
            'status'       => 'cleared',
            'created_by'   => session()->get('user_id'),
        ];

        $this->receivedModel->insert($receiptData);

        // If linked to invoice, update invoice paid amount and balance
        if ($invoiceId) {
            $invModel = new InvoiceModel();
            $inv = $invModel->find($invoiceId);
            if ($inv) {
                $newPaid = $inv['paid_amount'] + $totalCredit;
                $newBalance = max(0, $inv['grand_total'] - $newPaid);
                $newStatus = ($newBalance <= 0) ? 'paid' : 'partially_paid';

                $invModel->update($invoiceId, [
                    'paid_amount'    => $newPaid,
                    'balance_amount' => $newBalance,
                    'status'         => $newStatus,
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to record payment receipt.');
        }

        log_activity('Accounts', 'Recorded payment receipt ' . $receiptData['receipt_no'] . ' of ₹' . number_format($amount, 2));
        return redirect()->to('/payments/received')->with('success', 'Payment Receipt ' . $receiptData['receipt_no'] . ' recorded successfully.');
    }

    public function made()
    {
        $payments = $this->madeModel->getWithRelations();

        $stats = [
            'total_paid' => array_sum(array_column($payments, 'amount')),
            'total_tds'  => array_sum(array_column($payments, 'tds_amount')),
            'count'      => count($payments),
        ];

        return $this->render('payments/made', [
            'pageTitle' => 'Vendor Payments & Steel Mill Disbursals',
            'payments'  => $payments,
            'stats'     => $stats,
        ]);
    }

    public function recordPayment()
    {
        $vendorModel = new VendorModel();
        $vendors = $vendorModel->where('is_active', 1)->orderBy('company_name', 'ASC')->findAll();

        $poModel = new PurchaseOrderModel();
        $pos = $poModel->orderBy('id', 'DESC')->findAll();

        // Auto payment voucher: PV-YYMM-001
        $prefix = 'PV-' . date('ym') . '-';
        $latest = $this->madeModel->like('payment_no', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $nextSeq = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['payment_no'], $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        }
        $autoVoucherNo = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $this->render('payments/payment_form', [
            'pageTitle'     => 'Record Vendor Payment Voucher',
            'vendors'       => $vendors,
            'pos'           => $pos,
            'autoVoucherNo' => $autoVoucherNo,
        ]);
    }

    public function storePayment()
    {
        $rules = [
            'payment_no'   => 'required|is_unique[payments_made.payment_no]',
            'payment_date' => 'required|valid_date',
            'vendor_id'    => 'required|is_not_unique[vendors.id]',
            'amount'       => 'required|greater_than[0]',
            'payment_mode' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $paymentData = [
            'payment_no'        => $this->request->getPost('payment_no'),
            'payment_date'      => $this->request->getPost('payment_date'),
            'vendor_id'         => $this->request->getPost('vendor_id'),
            'purchase_order_id' => $this->request->getPost('purchase_order_id') ?: null,
            'amount'            => floatval($this->request->getPost('amount')),
            'payment_mode'      => $this->request->getPost('payment_mode'),
            'reference_no'      => $this->request->getPost('reference_no'),
            'bank_name'         => $this->request->getPost('bank_name'),
            'tds_amount'        => floatval($this->request->getPost('tds_amount') ?? 0),
            'remarks'           => $this->request->getPost('remarks'),
            'status'            => 'cleared',
            'created_by'        => session()->get('user_id'),
        ];

        $this->madeModel->insert($paymentData);

        log_activity('Accounts', 'Recorded vendor payment ' . $paymentData['payment_no'] . ' of ₹' . number_format($paymentData['amount'], 2));
        return redirect()->to('/payments/made')->with('success', 'Vendor Payment Voucher ' . $paymentData['payment_no'] . ' recorded successfully.');
    }
}
