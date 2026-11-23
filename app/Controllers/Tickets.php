<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\EmployeeModel;
use App\Models\ProductionJobModel;
use App\Models\TicketCommentModel;
use App\Models\TicketModel;

class Tickets extends BaseController
{
    protected TicketModel $ticketModel;
    protected TicketCommentModel $commentModel;
    protected ProductionJobModel $jobModel;
    protected ClientModel $clientModel;
    protected EmployeeModel $employeeModel;

    public function __construct()
    {
        $this->ticketModel    = new TicketModel();
        $this->commentModel   = new TicketCommentModel();
        $this->jobModel       = new ProductionJobModel();
        $this->clientModel    = new ClientModel();
        $this->employeeModel  = new EmployeeModel();
    }

    /**
     * Display list of in-built tickets with filters and KPI stats
     */
    public function index()
    {
        $filters = [
            'status'     => $this->request->getGet('status') ?? 'all',
            'category'   => $this->request->getGet('category') ?? 'all',
            'priority'   => $this->request->getGet('priority') ?? 'all',
            'department' => $this->request->getGet('department') ?? 'all',
            'search'     => $this->request->getGet('search') ?? '',
            'job_id'     => $this->request->getGet('job_id') ?? '',
        ];

        $tickets = $this->ticketModel->getFiltered($filters);
        $stats   = $this->ticketModel->getStats();
        $jobs    = $this->jobModel->orderBy('id', 'DESC')->limit(20)->findAll();
        $clients = $this->clientModel->orderBy('company_name', 'ASC')->findAll();

        return $this->render('tickets/index', [
            'pageTitle'    => 'In-Built Industrial Ticketing & CAPA System',
            'tickets'      => $tickets,
            'stats'        => $stats,
            'filters'      => $filters,
            'jobs'         => $jobs,
            'clients'      => $clients,
            'nextTicketNo' => $this->ticketModel->generateTicketNo(),
        ]);
    }

    /**
     * Form to create a new ticket
     */
    public function new()
    {
        $jobs      = $this->jobModel->orderBy('id', 'DESC')->findAll();
        $clients   = $this->clientModel->orderBy('company_name', 'ASC')->findAll();
        $employees = $this->employeeModel->orderBy('first_name', 'ASC')->findAll();

        return $this->render('tickets/new', [
            'pageTitle'    => 'Raise New Industrial Ticket / NCR',
            'jobs'         => $jobs,
            'clients'      => $clients,
            'employees'    => $employees,
            'nextTicketNo' => $this->ticketModel->generateTicketNo(),
        ]);
    }

    /**
     * Process creation of a new ticket
     */
    public function create()
    {
        $rules = [
            'title'       => 'required|min_length[5]|max_length[255]',
            'category'    => 'required|in_list[quality_ncr,drawing_rfi,machine_breakdown,production_delay,safety_hazard,client_punch,general]',
            'priority'    => 'required|in_list[low,medium,high,urgent,critical]',
            'department'  => 'required',
            'description' => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ticketNo = $this->request->getPost('ticket_no') ?: $this->ticketModel->generateTicketNo();
        $userId   = $this->currentUser['id'] ?? 1;
        $userName = $this->currentUser['full_name'] ?? 'System User';

        $jobId = $this->request->getPost('job_id') ? (int)$this->request->getPost('job_id') : null;
        $clientId = $this->request->getPost('client_id') ? (int)$this->request->getPost('client_id') : null;

        // Auto-detect client from job if not explicitly passed
        if ($jobId && !$clientId) {
            $job = $this->jobModel->getDetails($jobId);
            if ($job && !empty($job['client_id'])) {
                $clientId = (int)$job['client_id'];
            }
        }

        $data = [
            'ticket_no'        => $ticketNo,
            'title'            => trim((string)$this->request->getPost('title')),
            'category'         => $this->request->getPost('category'),
            'priority'         => $this->request->getPost('priority'),
            'status'           => 'open',
            'job_id'           => $jobId,
            'client_id'        => $clientId,
            'department'       => $this->request->getPost('department'),
            'assigned_to'      => $this->request->getPost('assigned_to') ? (int)$this->request->getPost('assigned_to') : null,
            'created_by'       => $userId,
            'description'      => trim((string)$this->request->getPost('description')),
            'root_cause'       => trim((string)$this->request->getPost('root_cause')),
            'resolution_notes' => trim((string)$this->request->getPost('resolution_notes')),
            'target_date'      => $this->request->getPost('target_date') ?: null,
        ];

        $ticketId = $this->ticketModel->insert($data);

        if (!$ticketId) {
            return redirect()->back()->withInput()->with('error', 'Failed to create ticket. Please check input values.');
        }

        // Add initial activity log in comment table
        $this->commentModel->insert([
            'ticket_id'   => $ticketId,
            'user_id'     => $userId,
            'user_name'   => $userName,
            'action_type' => 'comment',
            'comment'     => 'Ticket logged into system: ' . $data['title'],
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        log_activity('Ticketing', "Raised Ticket {$ticketNo}: {$data['title']}");

        // Automatically broadcast to integrations if configured
        if ($data['category'] === 'quality_ncr') {
            notify_integrations('qaqc_ncr_raised', [
                'report_no'   => $ticketNo,
                'job_no'      => $jobId ? "JOB-{$jobId}" : 'General',
                'summary'     => "[{$ticketNo}] {$data['title']}",
                'description' => $data['description'],
                'priority'    => ucfirst($data['priority']),
                'labels'      => ['internal-ticket', 'quality-ncr'],
            ]);
        }

        return redirect()->to('/tickets/view/' . $ticketId)->with('success', "Ticket {$ticketNo} generated successfully.");
    }

    /**
     * View detailed ticket traveler, timeline, and actions
     */
    public function view(int $id)
    {
        $ticket = $this->ticketModel->getDetails($id);
        if (!$ticket) {
            return redirect()->to('/tickets')->with('error', 'Requested Ticket was not found.');
        }

        $employees = $this->employeeModel->orderBy('first_name', 'ASC')->findAll();

        return $this->render('tickets/view', [
            'pageTitle' => "Ticket {$ticket['ticket_no']}: {$ticket['title']}",
            'ticket'    => $ticket,
            'employees' => $employees,
        ]);
    }

    /**
     * Update ticket lifecycle status & CAPA
     */
    public function updateStatus(int $id)
    {
        $ticket = $this->ticketModel->find($id);
        if (!$ticket) {
            return redirect()->to('/tickets')->with('error', 'Ticket not found.');
        }

        $newStatus        = $this->request->getPost('status');
        $resolutionNotes  = trim((string)$this->request->getPost('resolution_notes'));
        $rootCause        = trim((string)$this->request->getPost('root_cause'));
        $oldStatus        = $ticket['status'];

        $updateData = [
            'status' => $newStatus,
        ];

        if ($resolutionNotes) {
            $updateData['resolution_notes'] = $resolutionNotes;
        }
        if ($rootCause) {
            $updateData['root_cause'] = $rootCause;
        }
        if (in_array($newStatus, ['resolved', 'closed']) && empty($ticket['resolved_at'])) {
            $updateData['resolved_at'] = date('Y-m-d H:i:s');
        }

        $this->ticketModel->update($id, $updateData);

        // Record comment timeline
        $userName = $this->currentUser['full_name'] ?? 'System User';
        $userId   = $this->currentUser['id'] ?? 1;

        $commentText = "Status updated from " . strtoupper($oldStatus) . " to " . strtoupper($newStatus) . ".";
        if ($resolutionNotes) {
            $commentText .= "\n\nCAPA / Resolution Notes: " . $resolutionNotes;
        }

        $this->commentModel->insert([
            'ticket_id'   => $id,
            'user_id'     => $userId,
            'user_name'   => $userName,
            'action_type' => ($newStatus === 'resolved' || $newStatus === 'closed') ? 'capa_update' : 'status_change',
            'comment'     => $commentText,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        log_activity('Ticketing', "Updated Ticket {$ticket['ticket_no']} status to {$newStatus}");

        return redirect()->to('/tickets/view/' . $id)->with('success', "Ticket status updated to " . strtoupper($newStatus) . ".");
    }

    /**
     * Add engineer comment to ticket thread
     */
    public function addComment(int $id)
    {
        $ticket = $this->ticketModel->find($id);
        if (!$ticket) {
            return redirect()->to('/tickets')->with('error', 'Ticket not found.');
        }

        $comment = trim((string)$this->request->getPost('comment'));
        if (empty($comment)) {
            return redirect()->back()->with('error', 'Comment text cannot be empty.');
        }

        $userName = $this->currentUser['full_name'] ?? 'System User';
        $userId   = $this->currentUser['id'] ?? 1;

        $this->commentModel->insert([
            'ticket_id'   => $id,
            'user_id'     => $userId,
            'user_name'   => $userName,
            'action_type' => 'comment',
            'comment'     => $comment,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->ticketModel->update($id, ['updated_at' => date('Y-m-d H:i:s')]);
        log_activity('Ticketing', "Added comment to Ticket {$ticket['ticket_no']}");

        return redirect()->to('/tickets/view/' . $id)->with('success', 'Comment posted to ticket timeline.');
    }

    /**
     * Reassign ticket to another employee / engineer
     */
    public function reassign(int $id)
    {
        $ticket = $this->ticketModel->find($id);
        if (!$ticket) {
            return redirect()->to('/tickets')->with('error', 'Ticket not found.');
        }

        $assignedTo = (int)$this->request->getPost('assigned_to');
        $employee   = $this->employeeModel->find($assignedTo);
        $empName    = $employee ? ($employee['first_name'] . ' ' . $employee['last_name']) : "Employee #{$assignedTo}";

        $this->ticketModel->update($id, ['assigned_to' => $assignedTo]);

        $userName = $this->currentUser['full_name'] ?? 'System User';
        $userId   = $this->currentUser['id'] ?? 1;

        $this->commentModel->insert([
            'ticket_id'   => $id,
            'user_id'     => $userId,
            'user_name'   => $userName,
            'action_type' => 'assignment',
            'comment'     => "Ticket reassigned to {$empName}.",
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/tickets/view/' . $id)->with('success', "Ticket reassigned to {$empName}.");
    }
}
