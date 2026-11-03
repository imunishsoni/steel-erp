<?php

namespace App\Models;

use CodeIgniter\Model;

class IntegrationModel extends Model
{
    protected $table            = 'integrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'service',
        'is_enabled',
        'config',
        'events',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get configuration for a specific service (jira, slack, teams, etc.)
     */
    public function getServiceConfig(string $service): array
    {
        $row = $this->where('service', $service)->first();
        if (!$row) {
            return [
                'is_enabled' => 0,
                'config'     => [],
                'events'     => [],
            ];
        }

        $config = is_string($row['config']) ? json_decode($row['config'], true) : ($row['config'] ?? []);
        $events = is_string($row['events']) ? json_decode($row['events'], true) : ($row['events'] ?? []);

        return [
            'id'         => $row['id'],
            'service'    => $row['service'],
            'is_enabled' => (int)$row['is_enabled'],
            'config'     => $config ?: [],
            'events'     => $events ?: [],
        ];
    }

    /**
     * Save configuration for a service
     */
    public function saveServiceConfig(string $service, bool $isEnabled, array $config, array $events = []): bool
    {
        $existing = $this->where('service', $service)->first();
        $data = [
            'service'    => $service,
            'is_enabled' => $isEnabled ? 1 : 0,
            'config'     => json_encode($config),
            'events'     => json_encode($events),
        ];

        if ($existing) {
            return (bool)$this->update($existing['id'], $data);
        }

        return (bool)$this->insert($data);
    }
}
