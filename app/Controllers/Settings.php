<?php

namespace App\Controllers;

use App\Models\CompanySettingModel;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new CompanySettingModel();
    }

    public function index()
    {
        $settings = $this->settingModel->first();

        return $this->render('settings/index', [
            'pageTitle' => 'Company Profile & ERP Configuration',
            'settings'  => $settings,
        ]);
    }

    public function update()
    {
        $settings = $this->settingModel->first();
        $id = $settings ? $settings['id'] : 1;

        $rules = [
            'company_name'  => 'required|min_length[3]',
            'gstin'         => 'required|min_length[15]|max_length[15]',
            'pan'           => 'required|min_length[10]|max_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'company_name'     => $this->request->getPost('company_name'),
            'tagline'          => $this->request->getPost('tagline'),
            'address_line1'    => $this->request->getPost('address_line1'),
            'address_line2'    => $this->request->getPost('address_line2'),
            'city'             => $this->request->getPost('city'),
            'state'            => $this->request->getPost('state'),
            'pincode'          => $this->request->getPost('pincode'),
            'phone'            => $this->request->getPost('phone'),
            'email'            => $this->request->getPost('email'),
            'website'          => $this->request->getPost('website'),
            'gstin'            => strtoupper($this->request->getPost('gstin')),
            'pan'              => strtoupper($this->request->getPost('pan')),
            'cin'              => strtoupper($this->request->getPost('cin')),
            'bank_name'        => $this->request->getPost('bank_name'),
            'bank_account_no'  => $this->request->getPost('bank_account_no'),
            'bank_ifsc'        => strtoupper($this->request->getPost('bank_ifsc')),
            'bank_branch'      => $this->request->getPost('bank_branch'),
            'quotation_prefix' => $this->request->getPost('quotation_prefix') ?: 'QTN-',
            'so_prefix'        => $this->request->getPost('so_prefix') ?: 'SO-',
            'po_prefix'        => $this->request->getPost('po_prefix') ?: 'PO-',
            'dc_prefix'        => $this->request->getPost('dc_prefix') ?: 'DC-',
            'invoice_prefix'   => $this->request->getPost('invoice_prefix') ?: 'INV-',
        ];

        if ($settings) {
            $this->settingModel->update($id, $data);
        } else {
            $this->settingModel->insert($data);
        }

        log_activity('Settings', 'Updated company profile and GST configuration');
        return redirect()->to('/settings')->with('success', 'Company settings updated successfully.');
    }
}
