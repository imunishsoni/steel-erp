<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_no',
        'title',
        'category',
        'priority',
        'status',
        'job_id',
        'client_id',
        'department',
        'assigned_to',
        'created_by',
        'description',
        'root_cause',
        'resolution_notes',
        'target_date',
        'resolved_at',
    ];

    protected $useTimestamps = true;

    /**
     * Get tickets with joined relation data and optional filters
     */
    public function getFiltered(array $filters = []): array
    {
        $builder = $this->select('tickets.*, 
                                  production_jobs.job_no, 
                                  production_jobs.title as job_title, 
                                  production_jobs.drawing_no,
                                  clients.company_name as client_name, 
                                  clients.industry_type,
                                  assigned_user.full_name as assigned_name,
                                  creator_user.full_name as creator_name')
                        ->join('production_jobs', 'production_jobs.id = tickets.job_id', 'left')
                        ->join('clients', 'clients.id = tickets.client_id', 'left')
                        ->join('users as assigned_user', 'assigned_user.id = tickets.assigned_to', 'left')
                        ->join('users as creator_user', 'creator_user.id = tickets.created_by', 'left');

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder->where('tickets.status', $filters['status']);
        }

        if (!empty($filters['category']) && $filters['category'] !== 'all') {
            $builder->where('tickets.category', $filters['category']);
        }

        if (!empty($filters['priority']) && $filters['priority'] !== 'all') {
            $builder->where('tickets.priority', $filters['priority']);
        }

        if (!empty($filters['department']) && $filters['department'] !== 'all') {
            $builder->where('tickets.department', $filters['department']);
        }

        if (!empty($filters['job_id'])) {
            $builder->where('tickets.job_id', (int)$filters['job_id']);
        }

        if (!empty($filters['search'])) {
            $s = trim($filters['search']);
            $builder->groupStart()
                    ->like('tickets.ticket_no', $s)
                    ->orLike('tickets.title', $s)
                    ->orLike('tickets.description', $s)
                    ->orLike('production_jobs.job_no', $s)
                    ->orLike('clients.company_name', $s)
                    ->groupEnd();
        }

        return $builder->orderBy('tickets.id', 'DESC')->findAll();
    }

    /**
     * Get full details for a single ticket including comments
     */
    public function getDetails(int $id): ?array
    {
        $ticket = $this->select('tickets.*, 
                                 production_jobs.job_no, 
                                 production_jobs.title as job_title, 
                                 production_jobs.drawing_no,
                                 production_jobs.total_tonnage,
                                 clients.company_name as client_name, 
                                 clients.industry_type,
                                 clients.gstin as client_gstin,
                                 assigned_user.full_name as assigned_name,
                                 creator_user.full_name as creator_name')
                       ->join('production_jobs', 'production_jobs.id = tickets.job_id', 'left')
                       ->join('clients', 'clients.id = tickets.client_id', 'left')
                       ->join('users as assigned_user', 'assigned_user.id = tickets.assigned_to', 'left')
                       ->join('users as creator_user', 'creator_user.id = tickets.created_by', 'left')
                       ->where('tickets.id', $id)
                       ->first();

        if ($ticket) {
            $commentModel = new TicketCommentModel();
            $ticket['comments'] = $commentModel->where('ticket_id', $id)
                                               ->orderBy('created_at', 'ASC')
                                               ->findAll();
        }

        return $ticket;
    }

    /**
     * Generate next sequential ticket number e.g. TCK-2627-006
     */
    public function generateTicketNo(): string
    {
        $companyModel = new CompanySettingModel();
        $setting = $companyModel->first();
        $prefix = $setting['ticket_prefix'] ?? 'TCK';
        $fy = '2627';

        $last = $this->orderBy('id', 'DESC')->first();
        $nextSeq = $last ? ($last['id'] + 1) : 1;

        return sprintf('%s-%s-%03d', $prefix, $fy, $nextSeq);
    }

    /**
     * Get summary KPI statistics
     */
    public function getStats(): array
    {
        $all = $this->findAll();
        $total = count($all);
        $open = 0;
        $urgentCritical = 0;
        $resolvedClosed = 0;
        $ncrCount = 0;

        foreach ($all as $t) {
            if (in_array($t['status'], ['open', 'in_progress', 'under_review'])) {
                $open++;
            }
            if (in_array($t['priority'], ['urgent', 'critical'])) {
                $urgentCritical++;
            }
            if (in_array($t['status'], ['resolved', 'closed'])) {
                $resolvedClosed++;
            }
            if ($t['category'] === 'quality_ncr') {
                $ncrCount++;
            }
        }

        $resolutionRate = $total > 0 ? round(($resolvedClosed / $total) * 100) : 0;

        return [
            'total'           => $total,
            'open'            => $open,
            'urgent_critical' => $urgentCritical,
            'resolved_closed' => $resolvedClosed,
            'ncr_count'       => $ncrCount,
            'resolution_rate' => $resolutionRate,
        ];
    }
}
