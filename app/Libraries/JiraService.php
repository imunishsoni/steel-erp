<?php

namespace App\Libraries;

use App\Models\IntegrationLogModel;
use App\Models\IntegrationModel;

class JiraService
{
    protected array $config = [];
    protected bool $isEnabled = false;
    protected IntegrationLogModel $logModel;

    public function __construct()
    {
        $integrationModel = new IntegrationModel();
        $serviceData = $integrationModel->getServiceConfig('jira');
        $this->isEnabled = (bool)($serviceData['is_enabled'] ?? false);
        $this->config = $serviceData['config'] ?? [];
        $this->logModel = new IntegrationLogModel();
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['host']) && !empty($this->config['email']) && !empty($this->config['api_token']);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled && $this->isConfigured();
    }

    /**
     * Test connection to Jira instance
     */
    public function testConnection(): array
    {
        $host = rtrim($this->config['host'] ?? '', '/');
        $email = $this->config['email'] ?? '';
        $apiToken = $this->config['api_token'] ?? '';
        $projectKey = strtoupper($this->config['project_key'] ?? 'STEEL');

        if (empty($host) || empty($email) || empty($apiToken)) {
            return [
                'success' => false,
                'message' => 'Jira host URL, email, and API token are required.',
            ];
        }

        $url = $host . '/rest/api/3/myself';
        $auth = base64_encode($email . ':' . $apiToken);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $auth,
            'Accept: application/json',
            'User-Agent: Steel-ERP/1.0',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $payload = ['endpoint' => $url, 'email' => $email, 'project_key' => $projectKey];

        if ($curlError) {
            $this->logModel->record('jira', 'outbound', 'test_connection', 'failed', $payload, 0, $curlError);
            return [
                'success' => false,
                'message' => 'cURL connection error: ' . $curlError,
                'code'    => 0,
            ];
        }

        $body = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            $displayName = $body['displayName'] ?? 'Jira User';
            $this->logModel->record('jira', 'outbound', 'test_connection', 'success', $payload, $httpCode, $response);
            return [
                'success' => true,
                'message' => "Successfully authenticated with Jira as {$displayName} ({$email}).",
                'user'    => $body,
                'code'    => $httpCode,
            ];
        }

        $errorMsg = $body['errorMessages'][0] ?? ($body['message'] ?? 'HTTP Code ' . $httpCode);
        $this->logModel->record('jira', 'outbound', 'test_connection', 'failed', $payload, $httpCode, $response);
        return [
            'success' => false,
            'message' => 'Jira authentication failed: ' . $errorMsg,
            'code'    => $httpCode,
        ];
    }

    /**
     * Create an Issue in Jira (e.g. Quality NCR, Breakdown, Task)
     */
    public function createIssue(array $data): array
    {
        $host = rtrim($this->config['host'] ?? '', '/');
        $email = $this->config['email'] ?? '';
        $apiToken = $this->config['api_token'] ?? '';
        $projectKey = strtoupper($data['project_key'] ?? ($this->config['project_key'] ?? 'STEEL'));
        $issueType = $data['issue_type'] ?? ($this->config['issue_type'] ?? 'Task');

        $summary = $data['summary'] ?? 'Heavy Steel Fabrication Issue';
        $description = $data['description'] ?? '';
        $priority = $data['priority'] ?? 'High';
        $labels = array_unique(array_merge(['steel-erp', 'fabrication'], $data['labels'] ?? []));

        // Format Atlassian Document Format (ADF) for Jira Cloud v3 or simple markdown
        $payload = [
            'fields' => [
                'project' => [
                    'key' => $projectKey,
                ],
                'summary' => $summary,
                'description' => [
                    'type' => 'doc',
                    'version' => 1,
                    'content' => [
                        [
                            'type' => 'paragraph',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $description,
                                ],
                            ],
                        ],
                    ],
                ],
                'issuetype' => [
                    'name' => $issueType,
                ],
                'labels' => $labels,
            ],
        ];

        // If mock or offline mode without live credentials
        if (empty($host) || empty($email) || empty($apiToken)) {
            $mockKey = $projectKey . '-' . rand(1001, 1999);
            $this->logModel->record('jira', 'outbound', 'create_issue', 'success', $payload, 201, json_encode([
                'id'  => (string)rand(10000, 99999),
                'key' => $mockKey,
                'self' => "https://mock-jira.atlassian.net/rest/api/3/issue/{$mockKey}",
                'mode' => 'simulated',
            ]));

            return [
                'success' => true,
                'issue_key' => $mockKey,
                'issue_url' => "https://your-company.atlassian.net/browse/{$mockKey}",
                'simulated' => true,
                'message' => "Simulated Jira Issue {$mockKey} created successfully.",
            ];
        }

        $url = $host . '/rest/api/3/issue';
        $auth = base64_encode($email . ':' . $apiToken);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $auth,
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: Steel-ERP/1.0',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            $this->logModel->record('jira', 'outbound', 'create_issue', 'failed', $payload, 0, $curlError);
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $curlError,
            ];
        }

        $body = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && !empty($body['key'])) {
            $issueKey = $body['key'];
            $issueUrl = $host . '/browse/' . $issueKey;
            $this->logModel->record('jira', 'outbound', 'create_issue', 'success', $payload, $httpCode, $response);
            return [
                'success'   => true,
                'issue_key' => $issueKey,
                'issue_url' => $issueUrl,
                'message'   => "Jira Ticket {$issueKey} created successfully.",
            ];
        }

        $errorMsg = json_encode($body['errors'] ?? ($body['errorMessages'] ?? 'Creation failed'));
        $this->logModel->record('jira', 'outbound', 'create_issue', 'failed', $payload, $httpCode, $response);
        return [
            'success' => false,
            'message' => 'Failed to create Jira issue: ' . $errorMsg,
            'code'    => $httpCode,
        ];
    }
}
