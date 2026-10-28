<?php

namespace App\Controllers;

use App\Models\EnquiryModel;
use App\Models\ClientModel;
use App\Models\UserModel;

class Enquiries extends BaseController
{
    public function index()
    {
        $enquiryModel = new EnquiryModel();
        $enquiries = $enquiryModel->getWithRelations();

        return $this->render('enquiries/index', [
            'pageTitle' => 'Project Enquiries & Tender Estimations',
            'enquiries' => $enquiries,
        ]);
    }

    public function new()
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->getActiveClients();

        $userModel = new UserModel();
        $users = $userModel->where('is_active', 1)->findAll();

        return $this->render('enquiries/form', [
            'pageTitle' => 'New Project Enquiry / Tender Lead',
            'enquiry'   => [],
            'clients'   => $clients,
            'users'     => $users,
            'isEdit'    => false,
        ]);
    }

    public function create()
    {
        $enquiryModel = new EnquiryModel();

        $enquiryNo = trim((string)$this->request->getPost('enquiry_no'));
        if (empty($enquiryNo)) {
            $count = $enquiryModel->countAllResults() + 1;
            $enquiryNo = 'ENQ-2627-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $clientId = $this->request->getPost('client_id');
        $clientName = $this->request->getPost('client_name');

        if (!empty($clientId)) {
            $clientModel = new ClientModel();
            $client = $clientModel->find($clientId);
            if ($client) {
                $clientName = $client['company_name'];
            }
        }

        $data = [
            'enquiry_no'      => $enquiryNo,
            'enquiry_date'    => $this->request->getPost('enquiry_date') ?: date('Y-m-d'),
            'client_id'       => !empty($clientId) ? (int)$clientId : null,
            'client_name'     => $clientName,
            'contact_person'  => $this->request->getPost('contact_person'),
            'contact_phone'   => $this->request->getPost('contact_phone'),
            'contact_email'   => $this->request->getPost('contact_email'),
            'source'          => $this->request->getPost('source') ?: 'direct',
            'project_name'    => $this->request->getPost('project_name'),
            'plant_type'      => $this->request->getPost('plant_type') ?: 'power',
            'description'     => $this->request->getPost('description'),
            'estimated_value' => (float)$this->request->getPost('estimated_value'),
            'expected_date'   => $this->request->getPost('expected_date') ?: null,
            'priority'        => $this->request->getPost('priority') ?: 'medium',
            'status'          => $this->request->getPost('status') ?: 'new',
            'assigned_to'     => !empty($this->request->getPost('assigned_to')) ? (int)$this->request->getPost('assigned_to') : null,
            'remarks'         => $this->request->getPost('remarks'),
            'created_by'      => $this->currentUser['id'] ?? 1,
        ];

        $enquiryModel->insert($data);
        $insertId = $enquiryModel->getInsertID();

        log_activity('Enquiries', 'Create', $insertId, "Created enquiry {$enquiryNo} for {$data['project_name']}");

        return redirect()->to('/enquiries')->with('success', "Enquiry '{$enquiryNo}' registered successfully.");
    }

    public function edit($id)
    {
        $enquiryModel = new EnquiryModel();
        $enquiry = $enquiryModel->find($id);

        if (!$enquiry) {
            return redirect()->to('/enquiries')->with('error', 'Enquiry not found.');
        }

        $clientModel = new ClientModel();
        $clients = $clientModel->getActiveClients();

        $userModel = new UserModel();
        $users = $userModel->where('is_active', 1)->findAll();

        return $this->render('enquiries/form', [
            'pageTitle' => 'Edit Enquiry - ' . $enquiry['enquiry_no'],
            'enquiry'   => $enquiry,
            'clients'   => $clients,
            'users'     => $users,
            'isEdit'    => true,
        ]);
    }

    public function update($id)
    {
        $enquiryModel = new EnquiryModel();
        $enquiry = $enquiryModel->find($id);

        if (!$enquiry) {
            return redirect()->to('/enquiries')->with('error', 'Enquiry not found.');
        }

        $clientId = $this->request->getPost('client_id');
        $clientName = $this->request->getPost('client_name');
        if (!empty($clientId)) {
            $clientModel = new ClientModel();
            $client = $clientModel->find($clientId);
            if ($client) {
                $clientName = $client['company_name'];
            }
        }

        $data = [
            'enquiry_date'    => $this->request->getPost('enquiry_date'),
            'client_id'       => !empty($clientId) ? (int)$clientId : null,
            'client_name'     => $clientName,
            'contact_person'  => $this->request->getPost('contact_person'),
            'contact_phone'   => $this->request->getPost('contact_phone'),
            'contact_email'   => $this->request->getPost('contact_email'),
            'source'          => $this->request->getPost('source'),
            'project_name'    => $this->request->getPost('project_name'),
            'plant_type'      => $this->request->getPost('plant_type'),
            'description'     => $this->request->getPost('description'),
            'estimated_value' => (float)$this->request->getPost('estimated_value'),
            'expected_date'   => $this->request->getPost('expected_date') ?: null,
            'priority'        => $this->request->getPost('priority'),
            'status'          => $this->request->getPost('status'),
            'assigned_to'     => !empty($this->request->getPost('assigned_to')) ? (int)$this->request->getPost('assigned_to') : null,
            'remarks'         => $this->request->getPost('remarks'),
        ];

        $enquiryModel->update($id, $data);

        log_activity('Enquiries', 'Update', $id, "Updated enquiry {$enquiry['enquiry_no']}");

        return redirect()->to('/enquiries')->with('success', "Enquiry updated successfully.");
    }

    public function view($id)
    {
        $enquiryModel = new EnquiryModel();
        $enquiry = $enquiryModel->select('enquiries.*, clients.company_name as client_company, clients.city as client_city, clients.state as client_state, users.full_name as assigned_name')
                                ->join('clients', 'clients.id = enquiries.client_id', 'left')
                                ->join('users', 'users.id = enquiries.assigned_to', 'left')
                                ->where('enquiries.id', $id)
                                ->first();

        if (!$enquiry) {
            return redirect()->to('/enquiries')->with('error', 'Enquiry not found.');
        }

        return $this->render('enquiries/view', [
            'pageTitle' => $enquiry['enquiry_no'] . ' - ' . $enquiry['project_name'],
            'enquiry'   => $enquiry,
        ]);
    }

    public function delete($id)
    {
        $enquiryModel = new EnquiryModel();
        $enquiry = $enquiryModel->find($id);

        if ($enquiry) {
            $enquiryModel->delete($id);
            log_activity('Enquiries', 'Delete', $id, "Deleted enquiry {$enquiry['enquiry_no']}");
            return redirect()->to('/enquiries')->with('success', 'Enquiry deleted.');
        }

        return redirect()->to('/enquiries')->with('error', 'Enquiry not found.');
    }
}
