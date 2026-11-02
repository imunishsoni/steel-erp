<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\IntegrationLogModel;
use App\Models\IntegrationModel;

class Webhooks extends BaseController
{
    protected IntegrationLogModel $logModel;
    protected IntegrationModel $integrationModel;

    public function __construct()
    {
        $this->logModel = new IntegrationLogModel();
        $this->integrationModel = new IntegrationModel();
    }

    /**
     * Inbound Jira Webhook Receiver
     * e.g. jira:issue_updated, jira:issue_created
     */
    public function jira()
    {
        $raw = (string)$this->request->getBody();
        $payload = json_decode($raw, true) ?: [];
        $headers = $this->request->headers();

        $event = $payload['webhookEvent'] ?? 'jira:unknown_event';
        $issueKey = $payload['issue']['key'] ?? 'UNKNOWN';

        $this->logModel->record('jira', 'inbound', $event, 'success', $payload, 200, "Received webhook for issue {$issueKey}");

        return $this->response->setStatusCode(200)->setJSON([
            'status'    => 'received',
            'event'     => $event,
            'issue_key' => $issueKey,
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Inbound Slack Events / Interactive Component Receiver
     */
    public function slack()
    {
        $raw = (string)$this->request->getBody();
        $payload = json_decode($raw, true) ?: [];

        // Handle URL verification challenge during Slack App setup
        if (isset($payload['challenge'])) {
            return $this->response->setStatusCode(200)->setBody($payload['challenge']);
        }

        $eventType = $payload['event']['type'] ?? ($payload['type'] ?? 'slack:unknown_event');

        $this->logModel->record('slack', 'inbound', $eventType, 'success', $payload, 200, 'Received Slack event');

        return $this->response->setStatusCode(200)->setJSON([
            'status'    => 'ok',
            'event'     => $eventType,
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
}
