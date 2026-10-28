<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\SalesOrderModel;
use App\Models\DispatchModel;
use App\Models\ClientModel;
use App\Models\PaymentReceivedModel;

class Invoices extends BaseController
{
    protected $invoiceModel;
    protected $itemModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->itemModel = new InvoiceItemModel();
    }

    public function index()
    {
        $invoices = $this->invoiceModel->getWithRelations();

        $stats = [
            'total_invoices' => count($invoices),
            'total_billed'   => array_sum(array_column($invoices, 'grand_total')),
            'total_paid'     => array_sum(array_column($invoices, 'paid_amount')),
            'total_due'      => array_sum(array_column($invoices, 'balance_amount')),
        ];

        return $this->render('invoices/index', [
            'pageTitle' => 'GST Tax Invoices & Accounts Receivable',
            'invoices'  => $invoices,
            'stats'     => $stats,
        ]);
    }

    public function create()
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->where('is_active', 1)->orderBy('company_name', 'ASC')->findAll();

        $soModel = new SalesOrderModel();
        $salesOrders = $soModel->whereIn('status', ['confirmed', 'in_production', 'ready_for_dispatch', 'completed'])->findAll();

        $dcModel = new DispatchModel();
        $dispatches = $dcModel->findAll();

        // Check if preselected via query params
        $clientId = $this->request->getGet('client_id');
        $soId = $this->request->getGet('so_id');

        // Auto Invoice number: INV-2627-001
        $prefix = 'INV-' . date('y') . (date('y') + 1) . '-';
        $latest = $this->invoiceModel->like('invoice_no', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $nextSeq = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['invoice_no'], $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        }
        $autoInvNo = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $this->render('invoices/form', [
            'pageTitle'   => 'Create GST Tax Invoice',
            'clients'     => $clients,
            'salesOrders' => $salesOrders,
            'dispatches'  => $dispatches,
            'selectedClient' => $clientId,
            'selectedSO'     => $soId,
            'autoInvNo'   => $autoInvNo,
        ]);
    }

    public function new()
    {
        return $this->create();
    }

    public function store()
    {
        $rules = [
            'invoice_no'   => 'required|is_unique[invoices.invoice_no]',
            'invoice_date' => 'required|valid_date',
            'client_id'    => 'required|is_not_unique[clients.id]',
            'grand_total'  => 'required|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $isIgst = intval($this->request->getPost('is_igst') ?? 0);
        $subtotal = floatval($this->request->getPost('subtotal') ?? 0);
        $discount = floatval($this->request->getPost('discount_amt') ?? 0);
        $taxable = floatval($this->request->getPost('taxable_amount') ?? ($subtotal - $discount));
        $cgst = floatval($this->request->getPost('cgst_amount') ?? 0);
        $sgst = floatval($this->request->getPost('sgst_amount') ?? 0);
        $igst = floatval($this->request->getPost('igst_amount') ?? 0);
        $totalTax = floatval($this->request->getPost('total_tax') ?? ($cgst + $sgst + $igst));
        $roundOff = floatval($this->request->getPost('round_off') ?? 0);
        $grandTotal = floatval($this->request->getPost('grand_total') ?? ($taxable + $totalTax + $roundOff));

        $invoiceData = [
            'invoice_no'       => $this->request->getPost('invoice_no'),
            'invoice_date'     => $this->request->getPost('invoice_date'),
            'invoice_type'     => $this->request->getPost('invoice_type') ?: 'tax_invoice',
            'sales_order_id'   => $this->request->getPost('sales_order_id') ?: null,
            'dispatch_id'      => $this->request->getPost('dispatch_id') ?: null,
            'client_id'        => $this->request->getPost('client_id'),
            'billing_address'  => $this->request->getPost('billing_address'),
            'shipping_address' => $this->request->getPost('shipping_address'),
            'place_of_supply'  => $this->request->getPost('place_of_supply'),
            'is_igst'          => $isIgst,
            'subtotal'         => $subtotal,
            'discount_amt'     => $discount,
            'taxable_amount'   => $taxable,
            'cgst_amount'      => $cgst,
            'sgst_amount'      => $sgst,
            'igst_amount'      => $igst,
            'total_tax'        => $totalTax,
            'round_off'        => $roundOff,
            'grand_total'      => $grandTotal,
            'amount_in_words'  => number_to_words_indian($grandTotal),
            'payment_terms'    => $this->request->getPost('payment_terms') ?: '30 days from invoice date',
            'due_date'         => $this->request->getPost('due_date') ?: date('Y-m-d', strtotime('+30 days')),
            'bank_details'     => company_settings('bank_details', "Bank: State Bank of India\nA/C No: 39485720194\nIFSC: SBIN0001234\nBranch: Industrial Estate, Nagpur"),
            'terms_conditions' => $this->request->getPost('terms_conditions'),
            'notes'            => $this->request->getPost('notes'),
            'status'           => 'unpaid',
            'paid_amount'      => 0,
            'balance_amount'   => $grandTotal,
            'created_by'       => session()->get('user_id'),
        ];

        $this->invoiceModel->insert($invoiceData);
        $invoiceId = $this->invoiceModel->getInsertID();

        // Insert items
        $descriptions = $this->request->getPost('item_description') ?? [];
        $hsnCodes = $this->request->getPost('item_hsn') ?? [];
        $quantities = $this->request->getPost('item_quantity') ?? [];
        $units = $this->request->getPost('item_unit') ?? [];
        $rates = $this->request->getPost('item_rate') ?? [];
        $amounts = $this->request->getPost('item_amount') ?? [];
        $gstRates = $this->request->getPost('item_gst_rate') ?? [];

        foreach ($descriptions as $idx => $desc) {
            if (!empty(trim($desc))) {
                $qty = floatval($quantities[$idx] ?? 1);
                $rate = floatval($rates[$idx] ?? 0);
                $amt = floatval($amounts[$idx] ?? ($qty * $rate));
                $gRate = floatval($gstRates[$idx] ?? 18);

                $itemCgst = 0;
                $itemSgst = 0;
                $itemIgst = 0;

                if ($isIgst) {
                    $itemIgst = round(($amt * $gRate) / 100, 2);
                } else {
                    $itemCgst = round(($amt * ($gRate / 2)) / 100, 2);
                    $itemSgst = round(($amt * ($gRate / 2)) / 100, 2);
                }

                $this->itemModel->insert([
                    'invoice_id'   => $invoiceId,
                    'sl_no'        => $idx + 1,
                    'description'  => trim($desc),
                    'hsn_code'     => $hsnCodes[$idx] ?? '73089090',
                    'quantity'     => $qty,
                    'unit'         => $units[$idx] ?? 'MT',
                    'rate'         => $rate,
                    'amount'       => $amt,
                    'gst_rate'     => $gRate,
                    'cgst_amt'     => $itemCgst,
                    'sgst_amt'     => $itemSgst,
                    'igst_amt'     => $itemIgst,
                    'total_amount' => $amt + $itemCgst + $itemSgst + $itemIgst,
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to generate Tax Invoice.');
        }

        log_activity('Billing', 'Generated Tax Invoice ' . $invoiceData['invoice_no'] . ' for ₹' . number_format($grandTotal, 2));
        return redirect()->to('/invoices/view/' . $invoiceId)->with('success', 'Tax Invoice ' . $invoiceData['invoice_no'] . ' created successfully.');
    }

    public function view($id)
    {
        $invoice = $this->invoiceModel->getDetails($id);
        if (!$invoice) {
            return redirect()->to('/invoices')->with('error', 'Tax Invoice not found.');
        }

        return $this->render('invoices/view', [
            'pageTitle' => $invoice['invoice_no'] . ' - Tax Invoice',
            'invoice'   => $invoice,
        ]);
    }

    public function print($id)
    {
        $invoice = $this->invoiceModel->getDetails($id);
        if (!$invoice) {
            return redirect()->to('/invoices')->with('error', 'Tax Invoice not found.');
        }

        return view('invoices/print', [
            'pageTitle' => 'Print Tax Invoice - ' . $invoice['invoice_no'],
            'invoice'   => $invoice,
        ]);
    }
}
