<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\IndianStateModel;

class Clients extends BaseController
{
    public function index()
    {
        $clientModel = new ClientModel();
        $industry = $this->request->getGet('industry');

        $query = $clientModel->orderBy('company_name', 'ASC');
        if (!empty($industry) && $industry !== 'all') {
            $query->where('industry_type', $industry);
        }

        $clients = $query->findAll();

        return $this->render('clients/index', [
            'pageTitle'        => 'Clients & Industrial Plants',
            'clients'          => $clients,
            'selectedIndustry' => $industry ?? 'all',
        ]);
    }

    public function new()
    {
        $stateModel = new IndianStateModel();
        $states = $stateModel->orderBy('state_name', 'ASC')->findAll();

        return $this->render('clients/form', [
            'pageTitle' => 'Add New Client / Plant',
            'client'    => [],
            'states'    => $states,
            'isEdit'    => false,
        ]);
    }

    public function create()
    {
        $clientModel = new ClientModel();

        $clientCode = trim((string)$this->request->getPost('client_code'));
        if (empty($clientCode)) {
            $count = $clientModel->countAllResults() + 1;
            $clientCode = 'CLI-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $data = [
            'client_code'    => $clientCode,
            'company_name'   => $this->request->getPost('company_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'email'          => $this->request->getPost('email'),
            'phone'          => $this->request->getPost('phone'),
            'mobile'         => $this->request->getPost('mobile'),
            'industry_type'  => $this->request->getPost('industry_type'),
            'address_line1'  => $this->request->getPost('address_line1'),
            'address_line2'  => $this->request->getPost('address_line2'),
            'city'           => $this->request->getPost('city'),
            'state'          => $this->request->getPost('state'),
            'pincode'        => $this->request->getPost('pincode'),
            'country'        => 'India',
            'gstin'          => strtoupper(trim((string)$this->request->getPost('gstin'))),
            'pan'            => strtoupper(trim((string)$this->request->getPost('pan'))),
            'credit_limit'   => (float)$this->request->getPost('credit_limit'),
            'credit_days'    => (int)$this->request->getPost('credit_days'),
            'notes'          => $this->request->getPost('notes'),
            'is_active'      => 1,
            'created_by'     => $this->currentUser['id'] ?? 1,
        ];

        $clientModel->insert($data);
        $insertId = $clientModel->getInsertID();

        log_activity('Clients', 'Create', $insertId, "Added new client {$data['company_name']} ({$data['industry_type']})");

        return redirect()->to('/clients')->with('success', "Client '{$data['company_name']}' created successfully.");
    }

    public function edit($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->find($id);

        if (!$client) {
            return redirect()->to('/clients')->with('error', 'Client not found.');
        }

        $stateModel = new IndianStateModel();
        $states = $stateModel->orderBy('state_name', 'ASC')->findAll();

        return $this->render('clients/form', [
            'pageTitle' => 'Edit Client - ' . $client['company_name'],
            'client'    => $client,
            'states'    => $states,
            'isEdit'    => true,
        ]);
    }

    public function update($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->find($id);

        if (!$client) {
            return redirect()->to('/clients')->with('error', 'Client not found.');
        }

        $data = [
            'company_name'   => $this->request->getPost('company_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'email'          => $this->request->getPost('email'),
            'phone'          => $this->request->getPost('phone'),
            'mobile'         => $this->request->getPost('mobile'),
            'industry_type'  => $this->request->getPost('industry_type'),
            'address_line1'  => $this->request->getPost('address_line1'),
            'address_line2'  => $this->request->getPost('address_line2'),
            'city'           => $this->request->getPost('city'),
            'state'          => $this->request->getPost('state'),
            'pincode'        => $this->request->getPost('pincode'),
            'gstin'          => strtoupper(trim((string)$this->request->getPost('gstin'))),
            'pan'            => strtoupper(trim((string)$this->request->getPost('pan'))),
            'credit_limit'   => (float)$this->request->getPost('credit_limit'),
            'credit_days'    => (int)$this->request->getPost('credit_days'),
            'notes'          => $this->request->getPost('notes'),
            'is_active'      => (int)$this->request->getPost('is_active'),
        ];

        $clientModel->update($id, $data);

        log_activity('Clients', 'Update', $id, "Updated client {$data['company_name']}");

        return redirect()->to('/clients')->with('success', "Client '{$data['company_name']}' updated successfully.");
    }

    public function view($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->find($id);

        if (!$client) {
            return redirect()->to('/clients')->with('error', 'Client not found.');
        }

        $db = \Config\Database::connect();
        $orders = $db->table('sales_orders')->where('client_id', $id)->orderBy('id', 'DESC')->get()->getResultArray();
        $quotations = $db->table('quotations')->where('client_id', $id)->orderBy('id', 'DESC')->get()->getResultArray();
        $invoices = $db->table('invoices')->where('client_id', $id)->orderBy('id', 'DESC')->get()->getResultArray();

        return $this->render('clients/view', [
            'pageTitle'  => $client['company_name'] . ' (Plant Details)',
            'client'     => $client,
            'orders'     => $orders,
            'quotations' => $quotations,
            'invoices'   => $invoices,
        ]);
    }

    public function delete($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->find($id);

        if ($client) {
            $clientModel->delete($id);
            log_activity('Clients', 'Delete', $id, "Deleted client {$client['company_name']}");
            return redirect()->to('/clients')->with('success', 'Client deleted successfully.');
        }

        return redirect()->to('/clients')->with('error', 'Client not found.');
    }
}
