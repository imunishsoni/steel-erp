<?php

namespace App\Models;

use CodeIgniter\Model;

class IntegrationLogModel extends Model
{
    protected $table            = 'integration_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'service',
        'direction',
        'event_type',
        'status',
        'payload',
        'response_code',
        'response_body',
    ];

    protected $useTimestamps = false;

    /**
     * Log an integration transaction (outbound request or inbound webhook)
     */
    public function record(string $service, string $direction, string $eventType, string $status, array $payload, ?int $responseCode = null, ?string $responseBody = null): int
    {
        return (int)$this->insert([
            'service'       => $service,
            'direction'     => $direction,
            'event_type'    => $eventType,
            'status'        => $status,
            'payload'       => json_encode($payload),
            'response_code' => $responseCode,
            'response_body' => $responseBody ? substr($responseBody, 0, 5000) : null,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get recent logs
     */
    public function getRecent(int $limit = 25, ?string $service = null): array
    {
        $builder = $this->orderBy('id', 'DESC')->limit($limit);
        if ($service) {
            $builder->where('service', $service);
        }
        return $builder->get()->getResultArray();
    }
}
