<?php

namespace App\Controllers;

use App\Models\VendorModel;
use App\Models\IndianStateModel;

class Vendors extends BaseController
{
    public function index()
    {
        $vendorModel = new VendorModel();
        $type = $this->request->getGet('type');

        $query = $vendorModel->orderBy('company_name', 'ASC');
        if (!empty($type) && $type !== 'all') {
            $query->where('vendor_type', $type);
        }

        $vendors = $query->findAll();

        return $this->render('vendors/index', [
            'pageTitle'    => 'Approved Vendors & Steel Mills',
            'vendors'      => $vendors,
            'selectedType' => $type ?? 'all',
        ]);
    }

    public function new()
    {
        $stateModel = new IndianStateModel();
        $states = $stateModel->orderBy('state_name', 'ASC')->findAll();

        return $this->render('vendors/form', [
            'pageTitle' => 'Add Approved Vendor',
            'vendor'    => [],
            'states'    => $states,
            'isEdit'    => false,
        ]);
    }

    public function create()
    {
        $vendorModel = new VendorModel();

        $vendorCode = trim((string)$this->request->getPost('vendor_code'));
        if (empty($vendorCode)) {
            $count = $vendorModel->countAllResults() + 1;
            $vendorCode = 'VEN-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $data = [
            'vendor_code'     => $vendorCode,
            'company_name'    => $this->request->getPost('company_name'),
            'contact_person'  => $this->request->getPost('contact_person'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'mobile'          => $this->request->getPost('mobile'),
            'vendor_type'     => $this->request->getPost('vendor_type'),
            'address_line1'   => $this->request->getPost('address_line1'),
            'address_line2'   => $this->request->getPost('address_line2'),
            'city'            => $this->request->getPost('city'),
            'state'           => $this->request->getPost('state'),
            'pincode'         => $this->request->getPost('pincode'),
            'country'         => 'India',
            'gstin'           => strtoupper(trim((string)$this->request->getPost('gstin'))),
            'pan'             => strtoupper(trim((string)$this->request->getPost('pan'))),
            'payment_terms'   => $this->request->getPost('payment_terms'),
            'bank_name'       => $this->request->getPost('bank_name'),
            'bank_account_no' => $this->request->getPost('bank_account_no'),
            'bank_ifsc'       => strtoupper(trim((string)$this->request->getPost('bank_ifsc'))),
            'notes'           => $this->request->getPost('notes'),
            'rating'          => (int)$this->request->getPost('rating'),
            'is_active'       => 1,
            'created_by'      => $this->currentUser['id'] ?? 1,
        ];

        $vendorModel->insert($data);
        $insertId = $vendorModel->getInsertID();

        log_activity('Vendors', 'Create', $insertId, "Registered vendor {$data['company_name']}");

        return redirect()->to('/vendors')->with('success', "Vendor '{$data['company_name']}' created successfully.");
    }

    public function edit($id)
    {
        $vendorModel = new VendorModel();
        $vendor = $vendorModel->find($id);

        if (!$vendor) {
            return redirect()->to('/vendors')->with('error', 'Vendor not found.');
        }

        $stateModel = new IndianStateModel();
        $states = $stateModel->orderBy('state_name', 'ASC')->findAll();

        return $this->render('vendors/form', [
            'pageTitle' => 'Edit Vendor - ' . $vendor['company_name'],
            'vendor'    => $vendor,
            'states'    => $states,
            'isEdit'    => true,
        ]);
    }

    public function update($id)
    {
        $vendorModel = new VendorModel();
        $vendor = $vendorModel->find($id);

        if (!$vendor) {
            return redirect()->to('/vendors')->with('error', 'Vendor not found.');
        }

        $data = [
            'company_name'    => $this->request->getPost('company_name'),
            'contact_person'  => $this->request->getPost('contact_person'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'mobile'          => $this->request->getPost('mobile'),
            'vendor_type'     => $this->request->getPost('vendor_type'),
            'address_line1'   => $this->request->getPost('address_line1'),
            'address_line2'   => $this->request->getPost('address_line2'),
            'city'            => $this->request->getPost('city'),
            'state'           => $this->request->getPost('state'),
            'pincode'         => $this->request->getPost('pincode'),
            'gstin'           => strtoupper(trim((string)$this->request->getPost('gstin'))),
            'pan'             => strtoupper(trim((string)$this->request->getPost('pan'))),
            'payment_terms'   => $this->request->getPost('payment_terms'),
            'bank_name'       => $this->request->getPost('bank_name'),
            'bank_account_no' => $this->request->getPost('bank_account_no'),
            'bank_ifsc'       => strtoupper(trim((string)$this->request->getPost('bank_ifsc'))),
            'notes'           => $this->request->getPost('notes'),
            'rating'          => (int)$this->request->getPost('rating'),
            'is_active'       => (int)$this->request->getPost('is_active'),
        ];

        $vendorModel->update($id, $data);

        log_activity('Vendors', 'Update', $id, "Updated vendor {$data['company_name']}");

        return redirect()->to('/vendors')->with('success', "Vendor '{$data['company_name']}' updated successfully.");
    }

    public function view($id)
    {
        $vendorModel = new VendorModel();
        $vendor = $vendorModel->find($id);

        if (!$vendor) {
            return redirect()->to('/vendors')->with('error', 'Vendor not found.');
        }

        $db = \Config\Database::connect();
        $orders = $db->table('purchase_orders')->where('vendor_id', $id)->orderBy('id', 'DESC')->get()->getResultArray();
        $grns   = $db->table('grn')->where('vendor_id', $id)->orderBy('id', 'DESC')->get()->getResultArray();
        $payments = $db->table('payments_made')->where('vendor_id', $id)->orderBy('id', 'DESC')->get()->getResultArray();

        return $this->render('vendors/view', [
            'pageTitle' => $vendor['company_name'] . ' (Vendor Profile)',
            'vendor'    => $vendor,
            'orders'    => $orders,
            'grns'      => $grns,
            'payments'  => $payments,
        ]);
    }

    public function delete($id)
    {
        $vendorModel = new VendorModel();
        $vendor = $vendorModel->find($id);

        if ($vendor) {
            $vendorModel->delete($id);
            log_activity('Vendors', 'Delete', $id, "Deleted vendor {$vendor['company_name']}");
            return redirect()->to('/vendors')->with('success', 'Vendor deleted successfully.');
        }

        return redirect()->to('/vendors')->with('error', 'Vendor not found.');
    }
}
