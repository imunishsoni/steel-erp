<?php

namespace App\Libraries;

use App\Models\IntegrationLogModel;
use App\Models\IntegrationModel;

/**
 * ClickUp & Open-Source Project Management Service
 *
 * Compatible with ClickUp Cloud REST API v2 as well as self-hosted
 * open-source project management platforms like Plane, OpenProject, and Taiga.
 */
class ClickUpService
{
    protected array $config = [];
    protected bool $isEnabled = false;
    protected IntegrationLogModel $logModel;

    public function __construct()
    {
        $integrationModel = new IntegrationModel();
        $serviceData = $integrationModel->getServiceConfig('clickup');
        $this->isEnabled = (bool)($serviceData['is_enabled'] ?? false);
        $this->config = $serviceData['config'] ?? [];
        $this->logModel = new IntegrationLogModel();
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['api_token']) && !empty($this->config['list_id']);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled && $this->isConfigured();
    }

    public function getPlatform(): string
    {
        return $this->config['platform'] ?? 'clickup';
    }

    public function getApiBaseUrl(): string
    {
        $url = trim($this->config['api_url'] ?? '');
        if (empty($url)) {
            return 'https://api.clickup.com/api/v2';
        }
        return rtrim($url, '/');
    }

    /**
     * Test connection to ClickUp or Open-Source PM instance
     */
    public function testConnection(): array
    {
        $platform = $this->getPlatform();
        $baseUrl = $this->getApiBaseUrl();
        $apiToken = trim($this->config['api_token'] ?? '');
        $listId = trim($this->config['list_id'] ?? '');

        if (empty($apiToken)) {
            // Simulated connection test when in offline / demo mode
            $mockUser = [
                'id'       => 98412,
                'username' => 'Jaipur Works PM Lead',
                'email'    => 'pm.works@heavysteel.in',
                'platform' => $platform,
                'mode'     => 'simulated'
            ];

            $this->logModel->record('clickup', 'outbound', 'test_connection', 'success', [
                'platform' => $platform,
                'endpoint' => "{$baseUrl}/user",
                'list_id'  => $listId ?: 'demo_list_101',
            ], 200, json_encode($mockUser));

            return [
                'success' => true,
                'message' => "Simulated connection verified with {$platform} gateway for pm.works@heavysteel.in.",
                'user'    => $mockUser,
                'code'    => 200,
            ];
        }

        // Real HTTP test
        $endpoint = ($platform === 'clickup') ? "{$baseUrl}/user" : "{$baseUrl}/me";

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $apiToken,
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: Steel-ERP-Fabrication/1.0',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $payload = ['platform' => $platform, 'endpoint' => $endpoint, 'list_id' => $listId];

        if ($curlError) {
            $this->logModel->record('clickup', 'outbound', 'test_connection', 'failed', $payload, 0, $curlError);
            return [
                'success' => false,
                'message' => "cURL connection error to {$platform}: {$curlError}",
                'code'    => 0,
            ];
        }

        $body = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            $userName = $body['user']['username'] ?? ($body['username'] ?? 'PM User');
            $this->logModel->record('clickup', 'outbound', 'test_connection', 'success', $payload, $httpCode, $response);
            return [
                'success' => true,
                'message' => "Successfully authenticated with {$platform} as {$userName}.",
                'user'    => $body,
                'code'    => $httpCode,
            ];
        }

        $errorMsg = $body['err'] ?? ($body['message'] ?? "HTTP Code {$httpCode}");
        $this->logModel->record('clickup', 'outbound', 'test_connection', 'failed', $payload, $httpCode, $response);
        return [
            'success' => false,
            'message' => "{$platform} authentication failed: {$errorMsg}",
            'code'    => $httpCode,
        ];
    }

    /**
     * Create a Task in ClickUp or Open-Source PM tool (Plane / OpenProject)
     *
     * @param array $data Task parameters: name, description, priority (1=Urgent..4=Low), tags, due_date
     */
    public function createTask(array $data): array
    {
        $platform = $this->getPlatform();
        $baseUrl = $this->getApiBaseUrl();
        $apiToken = trim($this->config['api_token'] ?? '');
        $listId = trim($data['list_id'] ?? ($this->config['list_id'] ?? ''));

        $name = $data['name'] ?? 'Heavy Steel Fabrication Task';
        $description = $data['description'] ?? '';
        $priority = (int)($data['priority'] ?? ($this->config['default_priority'] ?? 2)); // 1=Urgent, 2=High, 3=Normal, 4=Low
        if ($priority < 1 || $priority > 4) {
            $priority = 2;
        }

        $tags = array_unique(array_merge(['steel-erp', 'fabrication'], $data['tags'] ?? []));
        $dueDate = !empty($data['due_date']) ? (int)$data['due_date'] : null;

        // Build ClickUp v2 payload
        $payload = [
            'name'                 => $name,
            'description'          => $description,
            'markdown_description' => $description,
            'priority'             => $priority,
            'tags'                 => array_values($tags),
            'status'               => $data['status'] ?? 'to do',
        ];

        if ($dueDate) {
            $payload['due_date'] = $dueDate;
        }

        // Offline / Simulation fallback mode
        if (empty($apiToken) || empty($listId)) {
            $taskId = 'clk_' . strtolower(substr(md5(uniqid((string)rand(), true)), 0, 9));
            $simulatedUrl = ($platform === 'clickup')
                ? "https://app.clickup.com/t/{$taskId}"
                : "https://plane.internal/issues/{$taskId}";

            $mockResponse = [
                'id'       => $taskId,
                'name'     => $name,
                'status'   => ['status' => 'to do', 'color' => '#d3d3d3'],
                'priority' => ['priority' => $priority === 1 ? 'urgent' : ($priority === 2 ? 'high' : ($priority === 3 ? 'normal' : 'low'))],
                'url'      => $simulatedUrl,
                'mode'     => 'simulated'
            ];

            $this->logModel->record('clickup', 'outbound', 'create_task', 'success', $payload, 201, json_encode($mockResponse));

            return [
                'success'   => true,
                'task_id'   => $taskId,
                'task_url'  => $simulatedUrl,
                'simulated' => true,
                'message'   => "Task \"{$name}\" created in {$platform} (Simulated).",
            ];
        }

        // ClickUp Cloud Task creation endpoint
        $endpoint = "{$baseUrl}/list/{$listId}/task";

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $apiToken,
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: Steel-ERP-Fabrication/1.0',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            $this->logModel->record('clickup', 'outbound', 'create_task', 'failed', $payload, 0, $curlError);
            return [
                'success' => false,
                'message' => "cURL Error: {$curlError}",
            ];
        }

        $body = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && !empty($body['id'])) {
            $taskId = $body['id'];
            $taskUrl = $body['url'] ?? ($body['task_url'] ?? "https://app.clickup.com/t/{$taskId}");
            $this->logModel->record('clickup', 'outbound', 'create_task', 'success', $payload, $httpCode, $response);
            return [
                'success'  => true,
                'task_id'  => $taskId,
                'task_url' => $taskUrl,
                'message'  => "Task {$taskId} created successfully in {$platform}.",
            ];
        }

        $errorMsg = $body['err'] ?? ($body['message'] ?? 'Task creation failed');
        $this->logModel->record('clickup', 'outbound', 'create_task', 'failed', $payload, $httpCode, $response);
        return [
            'success' => false,
            'message' => "Failed to create task in {$platform}: {$errorMsg}",
            'code'    => $httpCode,
        ];
    }

    /**
     * Automated Trigger: Job Order Released -> Create Task in ClickUp / Plane
     */
    public function notifyJobCreated(array $data): array
    {
        $jobNo = $data['job_no'] ?? 'JOB-REF';
        $title = $data['title'] ?? 'Fabrication Order';
        $tonnage = $data['tonnage'] ?? 0;
        $drawingNo = $data['drawing_no'] ?? 'DWG-STD';
        $targetDate = $data['target_date'] ?? $data['target_completion_date'] ?? date('Y-m-d', strtotime('+30 days'));

        $markdown = "## 🏗️ Heavy Steel Fabrication Job Order Released\n\n"
                  . "**Job Reference:** `{$jobNo}`\n"
                  . "**Assembly Title:** {$title}\n"
                  . "**Allocated Steel Tonnage:** {$tonnage} MT\n"
                  . "**Approved Structural Drawing:** `{$drawingNo}`\n"
                  . "**Target Works Completion:** {$targetDate}\n\n"
                  . "### Required Shop Floor Milestones:\n"
                  . "1. [ ] Raw plate issue from yard & Heat No verification\n"
                  . "2. [ ] CNC Oxy-Fuel / Plasma profile cutting\n"
                  . "3. [ ] Fit-up & root pass welding\n"
                  . "4. [ ] Submerged Arc Welding (SAW) full penetration\n"
                  . "5. [ ] Non-Destructive Testing (UT / RT / MPI)\n"
                  . "6. [ ] Grit blasting to SA 2.5 & epoxy painting\n\n"
                  . "*Generated automatically by Bharat Heavy Steel ERP*";

        return $this->createTask([
            'name'        => "[{$jobNo}] Shop Floor Traveler: {$title}",
            'description' => $markdown,
            'priority'    => 2, // High
            'tags'        => ['job-order', 'shopfloor', 'production'],
        ]);
    }

    /**
     * Automated Trigger: QA/QC Non-Conformance (NCR) Raised -> Create Urgent Task
     */
    public function notifyQualityNCR(array $data): array
    {
        $reportNo = $data['report_no'] ?? 'NCR-' . rand(100, 999);
        $jobNo = $data['job_no'] ?? 'JOB-REF';
        $jointId = $data['joint_id'] ?? 'WJ-01';
        $defect = $data['defect'] ?? $data['observation'] ?? 'Weld defect requiring gouging';
        $method = $data['method'] ?? 'Ultrasonic Testing (UT)';

        $markdown = "## 🚨 Quality Non-Conformance Notice (NCR)\n\n"
                  . "**NCR Report:** `{$reportNo}`\n"
                  . "**Job Reference:** `{$jobNo}`\n"
                  . "**Weld Joint ID:** `{$jointId}`\n"
                  . "**NDT Inspection Method:** {$method}\n"
                  . "**Defect Observation:** {$defect}\n\n"
                  . "### Rectification Protocol:\n"
                  . "- [ ] Carbon arc gouge defect cavity to sound parent metal\n"
                  . "- [ ] DP check to verify crack/inclusion removal\n"
                  . "- [ ] Preheat joint to minimum 150°C\n"
                  . "- [ ] Reweld using approved low-hydrogen E7018 / SAW wire\n"
                  . "- [ ] Re-inspect by TPI Agency (BVIS/SGS/TUV)\n\n"
                  . "*Urgent attention required by Welding Quality Supervisor*";

        return $this->createTask([
            'name'        => "🚨 [NCR {$reportNo}] Defect on {$jointId} ({$jobNo})",
            'description' => $markdown,
            'priority'    => 1, // Urgent
            'tags'        => ['quality-ncr', 'ndt-failure', 'urgent'],
        ]);
    }
}
