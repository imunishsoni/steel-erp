<?php

namespace App\Controllers;

use App\Models\QuotationModel;
use App\Models\QuotationItemModel;
use App\Models\ClientModel;
use App\Models\MaterialModel;
use App\Models\EnquiryModel;
use App\Models\SalesOrderModel;
use App\Models\SalesOrderItemModel;

class Quotations extends BaseController
{
    public function index()
    {
        $quotationModel = new QuotationModel();
        $quotations = $quotationModel->getWithRelations();

        return $this->render('quotations/index', [
            'pageTitle'  => 'Cost Estimations & Formal Quotations',
            'quotations' => $quotations,
        ]);
    }

    public function new()
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->getActiveClients();

        $matModel = new MaterialModel();
        $materials = $matModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        $enquiryId = $this->request->getGet('enquiry_id');
        $clientId  = $this->request->getGet('client_id');

        $prefill = [];
        if (!empty($enquiryId)) {
            $enqModel = new EnquiryModel();
            $enq = $enqModel->find($enquiryId);
            if ($enq) {
                $prefill['enquiry_id']   = $enq['id'];
                $prefill['client_id']    = $enq['client_id'];
                $prefill['project_name'] = $enq['project_name'];
                $prefill['subject']      = 'Quotation for ' . $enq['project_name'];
                $prefill['notes']        = $enq['description'];
            }
        } elseif (!empty($clientId)) {
            $prefill['client_id'] = $clientId;
        }

        $company = company_settings();

        return $this->render('quotations/form', [
            'pageTitle' => 'Create Formal Quotation / Price Proposal',
            'quotation' => $prefill,
            'items'     => [],
            'clients'   => $clients,
            'materials' => $materials,
            'company'   => $company,
            'isEdit'    => false,
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $quotationModel = new QuotationModel();
        $itemModel = new QuotationItemModel();

        $quotationNo = trim((string)$this->request->getPost('quotation_no'));
        if (empty($quotationNo)) {
            $count = $quotationModel->countAllResults() + 1;
            $quotationNo = 'QTN-2627-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $grandTotal = (float)$this->request->getPost('grand_total');

        $data = [
            'quotation_no'     => $quotationNo,
            'quotation_date'   => $this->request->getPost('quotation_date') ?: date('Y-m-d'),
            'valid_until'      => $this->request->getPost('valid_until') ?: date('Y-m-d', strtotime('+30 days')),
            'revision'         => 0,
            'enquiry_id'       => !empty($this->request->getPost('enquiry_id')) ? (int)$this->request->getPost('enquiry_id') : null,
            'client_id'        => (int)$this->request->getPost('client_id'),
            'project_name'     => $this->request->getPost('project_name'),
            'subject'          => $this->request->getPost('subject'),
            'payment_terms'    => $this->request->getPost('payment_terms'),
            'delivery_terms'   => $this->request->getPost('delivery_terms'),
            'delivery_period'  => $this->request->getPost('delivery_period'),
            'freight_charges'  => (float)$this->request->getPost('freight_charges'),
            'taxes_note'       => $this->request->getPost('taxes_note'),
            'subtotal'         => (float)$this->request->getPost('subtotal'),
            'discount_pct'     => (float)$this->request->getPost('discount_pct'),
            'discount_amt'     => (float)$this->request->getPost('discount_amt'),
            'taxable_amount'   => (float)$this->request->getPost('taxable_amount'),
            'cgst_amount'      => (float)$this->request->getPost('cgst_amount'),
            'sgst_amount'      => (float)$this->request->getPost('sgst_amount'),
            'igst_amount'      => (float)$this->request->getPost('igst_amount'),
            'total_tax'        => (float)$this->request->getPost('total_tax'),
            'grand_total'      => $grandTotal,
            'amount_in_words'  => $this->request->getPost('amount_in_words') ?: number_to_words_indian($grandTotal),
            'terms_conditions' => $this->request->getPost('terms_conditions'),
            'notes'            => $this->request->getPost('notes'),
            'status'           => $this->request->getPost('status') ?: 'sent',
            'created_by'       => $this->currentUser['id'] ?? 1,
        ];

        $db->transStart();

        $quotationModel->insert($data);
        $quotationId = $quotationModel->getInsertID();

        // Items
        $items = $this->request->getPost('items') ?? [];
        $sl = 1;
        foreach ($items as $item) {
            if (empty($item['description']) && empty($item['material_id'])) {
                continue;
            }
            $itemModel->insert([
                'quotation_id'  => $quotationId,
                'sl_no'         => $sl++,
                'material_id'   => !empty($item['material_id']) ? (int)$item['material_id'] : null,
                'description'   => $item['description'],
                'specification' => $item['specification'] ?? null,
                'hsn_code'      => $item['hsn_code'] ?? '73089090',
                'quantity'      => (float)($item['quantity'] ?? 0),
                'unit'          => $item['unit'] ?? 'kg',
                'rate'          => (float)($item['rate'] ?? 0),
                'amount'        => (float)($item['amount'] ?? 0),
                'gst_rate'      => (float)($item['gst_rate'] ?? 18),
                'remarks'       => $item['remarks'] ?? null,
            ]);
        }

        // Update enquiry status if linked
        if (!empty($data['enquiry_id'])) {
            $db->table('enquiries')->where('id', $data['enquiry_id'])->update(['status' => 'quoted']);
        }

        $db->transComplete();

        log_activity('Quotations', 'Create', $quotationId, "Generated quotation {$quotationNo} for {$data['project_name']}");

        return redirect()->to('/quotations/view/' . $quotationId)->with('success', "Quotation '{$quotationNo}' generated successfully.");
    }

    public function view($id)
    {
        $quotationModel = new QuotationModel();
        $quotation = $quotationModel->getDetails($id);

        if (!$quotation) {
            return redirect()->to('/quotations')->with('error', 'Quotation not found.');
        }

        return $this->render('quotations/view', [
            'pageTitle' => $quotation['quotation_no'] . ' - Quotation',
            'quotation' => $quotation,
        ]);
    }

    public function printDoc($id)
    {
        $quotationModel = new QuotationModel();
        $quotation = $quotationModel->getDetails($id);

        if (!$quotation) {
            return redirect()->to('/quotations')->with('error', 'Quotation not found.');
        }

        $company = company_settings();

        return view('quotations/print', [
            'docTitle'  => 'Quotation - ' . $quotation['quotation_no'],
            'quotation' => $quotation,
            'company'   => $company,
        ]);
    }

    public function convertToSo($id)
    {
        $quotationModel = new QuotationModel();
        $quotation = $quotationModel->getDetails($id);

        if (!$quotation) {
            return redirect()->to('/quotations')->with('error', 'Quotation not found.');
        }

        $db = \Config\Database::connect();
        $soModel = new SalesOrderModel();
        $soItemModel = new SalesOrderItemModel();

        $soCount = $soModel->countAllResults() + 1;
        $soNo = 'SO-2627-' . str_pad($soCount, 3, '0', STR_PAD_LEFT);

        $db->transStart();

        $soModel->insert([
            'so_no'            => $soNo,
            'so_date'          => date('Y-m-d'),
            'quotation_id'     => $quotation['id'],
            'client_id'        => $quotation['client_id'],
            'client_po_no'     => 'Awaiting PO',
            'client_po_date'   => date('Y-m-d'),
            'project_name'     => $quotation['project_name'],
            'delivery_address' => ($quotation['address_line1'] ?? '') . ', ' . ($quotation['city'] ?? '') . ', ' . ($quotation['client_state'] ?? ''),
            'payment_terms'    => $quotation['payment_terms'],
            'delivery_date'    => date('Y-m-d', strtotime('+45 days')),
            'subtotal'         => $quotation['subtotal'],
            'discount_amt'     => $quotation['discount_amt'],
            'taxable_amount'   => $quotation['taxable_amount'],
            'cgst_amount'      => $quotation['cgst_amount'],
            'sgst_amount'      => $quotation['sgst_amount'],
            'igst_amount'      => $quotation['igst_amount'],
            'total_tax'        => $quotation['total_tax'],
            'grand_total'      => $quotation['grand_total'],
            'amount_in_words'  => $quotation['amount_in_words'],
            'status'           => 'confirmed',
            'remarks'          => 'Created from quotation ' . $quotation['quotation_no'],
            'created_by'       => $this->currentUser['id'] ?? 1,
        ]);
        $soId = $soModel->getInsertID();

        // Copy items
        if (!empty($quotation['items'])) {
            foreach ($quotation['items'] as $item) {
                $soItemModel->insert([
                    'sales_order_id' => $soId,
                    'sl_no'          => $item['sl_no'],
                    'material_id'    => $item['material_id'],
                    'description'    => $item['description'],
                    'specification'  => $item['specification'],
                    'hsn_code'       => $item['hsn_code'],
                    'quantity'       => $item['quantity'],
                    'dispatched_qty' => 0,
                    'unit'           => $item['unit'],
                    'rate'           => $item['rate'],
                    'amount'         => $item['amount'],
                    'gst_rate'       => $item['gst_rate'],
                    'status'         => 'pending',
                    'remarks'        => $item['remarks'],
                ]);
            }
        }

        // Update quotation status
        $quotationModel->update($id, ['status' => 'converted']);

        $db->transComplete();

        log_activity('Sales Orders', 'Create', $soId, "Converted quotation {$quotation['quotation_no']} into Sales Order {$soNo}");

        return redirect()->to('/sales-orders/view/' . $soId)->with('success', "Quotation successfully converted to Sales Order '{$soNo}'.");
    }

    public function delete($id)
    {
        $quotationModel = new QuotationModel();
        $quotation = $quotationModel->find($id);

        if ($quotation) {
            $quotationModel->delete($id);
            log_activity('Quotations', 'Delete', $id, "Deleted quotation {$quotation['quotation_no']}");
            return redirect()->to('/quotations')->with('success', 'Quotation deleted.');
        }

        return redirect()->to('/quotations')->with('error', 'Quotation not found.');
    }
}
