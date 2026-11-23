<?php

namespace App\Controllers;

use App\Libraries\JiraService;
use App\Libraries\SlackService;
use App\Libraries\EmailService;
use App\Libraries\ClickUpService;
use App\Models\IntegrationLogModel;
use App\Models\IntegrationModel;
use App\Models\ProductionJobModel;
use App\Models\DispatchModel;

class Integrations extends BaseController
{
    protected IntegrationModel $integrationModel;
    protected IntegrationLogModel $logModel;

    public function __construct()
    {
        $this->integrationModel = new IntegrationModel();
        $this->logModel = new IntegrationLogModel();
    }

    public function index()
    {
        $jira = $this->integrationModel->getServiceConfig('jira');
        $slack = $this->integrationModel->getServiceConfig('slack');
        $email = $this->integrationModel->getServiceConfig('email');
        $clickup = $this->integrationModel->getServiceConfig('clickup');
        $webhooks = $this->integrationModel->getServiceConfig('webhooks');
        $logs = $this->logModel->getRecent(20);

        // Pre-fetch sample Job Orders and Dispatches for test dropdowns
        $jobModel = new ProductionJobModel();
        $dispatchModel = new DispatchModel();

        $recentJobs = $jobModel->orderBy('id', 'DESC')->limit(10)->findAll();
        $recentDispatches = $dispatchModel->orderBy('id', 'DESC')->limit(10)->findAll();

        return $this->render('integrations/index', [
            'pageTitle'        => 'Ticketing, Email & Collaboration Integrations',
            'jira'             => $jira,
            'slack'            => $slack,
            'email'            => $email,
            'clickup'          => $clickup,
            'webhooks'         => $webhooks,
            'logs'             => $logs,
            'recentJobs'       => $recentJobs,
            'recentDispatches' => $recentDispatches,
        ]);
    }

    public function updateJira()
    {
        $isEnabled = (bool)$this->request->getPost('is_enabled');
        $config = [
            'host'        => rtrim(trim((string)$this->request->getPost('host')), '/'),
            'email'       => trim((string)$this->request->getPost('email')),
            'api_token'   => trim((string)$this->request->getPost('api_token')),
            'project_key' => strtoupper(trim((string)$this->request->getPost('project_key'))),
            'issue_type'  => trim((string)$this->request->getPost('issue_type')) ?: 'Task',
        ];
        $events = (array)$this->request->getPost('events');

        $this->integrationModel->saveServiceConfig('jira', $isEnabled, $config, $events);
        log_activity('Integrations', 'Updated Jira Ticketing integration settings');

        return redirect()->to('/integrations#tab-jira')->with('success', 'Jira configuration saved successfully.');
    }

    public function updateSlack()
    {
        $isEnabled = (bool)$this->request->getPost('is_enabled');
        $config = [
            'webhook_url'      => trim((string)$this->request->getPost('webhook_url')),
            'bot_token'        => trim((string)$this->request->getPost('bot_token')),
            'default_channel'  => trim((string)$this->request->getPost('default_channel')) ?: '#shopfloor-alerts',
            'dispatch_channel' => trim((string)$this->request->getPost('dispatch_channel')) ?: '#dispatch-alerts',
            'qc_channel'       => trim((string)$this->request->getPost('qc_channel')) ?: '#qc-ncr-alerts',
        ];
        $events = (array)$this->request->getPost('events');

        $this->integrationModel->saveServiceConfig('slack', $isEnabled, $config, $events);
        log_activity('Integrations', 'Updated Slack collaboration integration settings');

        return redirect()->to('/integrations#tab-slack')->with('success', 'Slack configuration saved successfully.');
    }

    public function updateWebhooks()
    {
        $isEnabled = (bool)$this->request->getPost('is_enabled');
        $config = [
            'outgoing_url' => trim((string)$this->request->getPost('outgoing_url')),
            'secret'       => trim((string)$this->request->getPost('secret')),
        ];
        $events = (array)$this->request->getPost('events');

        $this->integrationModel->saveServiceConfig('webhooks', $isEnabled, $config, $events);
        log_activity('Integrations', 'Updated Generic Webhooks configuration');

        return redirect()->to('/integrations#tab-webhooks')->with('success', 'Webhooks configuration saved successfully.');
    }

    public function updateEmail()
    {
        $isEnabled = (bool)$this->request->getPost('is_enabled');
        $config = [
            'smtp_host'               => trim((string)$this->request->getPost('smtp_host')),
            'smtp_port'               => (int)($this->request->getPost('smtp_port') ?: 587),
            'smtp_crypto'             => trim((string)$this->request->getPost('smtp_crypto')) ?: 'tls',
            'smtp_user'               => trim((string)$this->request->getPost('smtp_user')),
            'smtp_pass'               => trim((string)$this->request->getPost('smtp_pass')),
            'from_email'              => trim((string)$this->request->getPost('from_email')) ?: 'notifications@heavysteel.in',
            'from_name'               => trim((string)$this->request->getPost('from_name')) ?: 'Heavy Steel Fabricators ERP',
            'dispatch_notify_emails'  => trim((string)$this->request->getPost('dispatch_notify_emails')),
            'qc_notify_emails'        => trim((string)$this->request->getPost('qc_notify_emails')),
            'accounts_notify_emails'  => trim((string)$this->request->getPost('accounts_notify_emails')),
        ];
        $events = (array)$this->request->getPost('events');

        $this->integrationModel->saveServiceConfig('email', $isEnabled, $config, $events);
        log_activity('Integrations', 'Updated SMTP email notification settings');

        return redirect()->to('/integrations#tab-email')->with('success', 'Email notification settings saved successfully.');
    }

    public function testEmail()
    {
        $emailService = new EmailService();
        $recipient = trim((string)$this->request->getPost('recipient')) ?: 'admin@heavysteel.in';

        $result = $emailService->sendTestEmail($recipient);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-email')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-email')->with('error', $result['message']);
    }

    public function sendManualEmail()
    {
        $emailService = new EmailService();
        $recipient = trim((string)$this->request->getPost('recipient'));
        $subject = trim((string)$this->request->getPost('subject'));
        $message = trim((string)$this->request->getPost('message'));

        if (empty($recipient) || empty($subject) || empty($message)) {
            return redirect()->to('/integrations#tab-email')->with('error', 'Recipient, subject, and message are required.');
        }

        $html = "<p>" . nl2br(esc($message)) . "</p>";
        $result = $emailService->sendEmail($recipient, $subject, $html, [], 'manual_notice');

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-email')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-email')->with('error', $result['message']);
    }

    public function updateClickup()
    {
        $isEnabled = (bool)$this->request->getPost('is_enabled');
        $config = [
            'platform'         => trim((string)$this->request->getPost('platform')) ?: 'clickup',
            'api_url'          => trim((string)$this->request->getPost('api_url')) ?: 'https://api.clickup.com/api/v2',
            'api_token'        => trim((string)$this->request->getPost('api_token')),
            'team_id'          => trim((string)$this->request->getPost('team_id')),
            'list_id'          => trim((string)$this->request->getPost('list_id')),
            'default_priority' => (int)($this->request->getPost('default_priority') ?: 2),
        ];
        $events = (array)$this->request->getPost('events');

        $this->integrationModel->saveServiceConfig('clickup', $isEnabled, $config, $events);
        log_activity('Integrations', 'Updated ClickUp & Open-Source PM integration settings');

        return redirect()->to('/integrations#tab-clickup')->with('success', 'ClickUp & Open-Source PM settings saved successfully.');
    }

    public function testClickup()
    {
        $clickupService = new ClickUpService();
        $result = $clickupService->testConnection();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-clickup')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-clickup')->with('error', $result['message']);
    }

    public function createClickupTask()
    {
        $clickupService = new ClickUpService();
        $name = trim((string)$this->request->getPost('name'));
        $description = trim((string)$this->request->getPost('description'));
        $priority = (int)($this->request->getPost('priority') ?: 2);
        $tags = explode(',', (string)$this->request->getPost('tags'));
        $tags = array_filter(array_map('trim', $tags));

        if (empty($name)) {
            return redirect()->to('/integrations#tab-clickup')->with('error', 'Task name is required.');
        }

        $result = $clickupService->createTask([
            'name'        => $name,
            'description' => $description,
            'priority'    => $priority,
            'tags'        => $tags,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-clickup')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-clickup')->with('error', $result['message']);
    }

    public function testJira()
    {
        $jiraService = new JiraService();
        $result = $jiraService->testConnection();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-jira')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-jira')->with('error', $result['message']);
    }

    public function testSlack()
    {
        $slackService = new SlackService();
        $payload = [
            'text' => '🔔 *Steel ERP Alert Test:* Connection established successfully with Indian Heavy Fabrication Works!',
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => '⚙️ Bharat Heavy Steel ERP Connection Test',
                        'emoji' => true,
                    ],
                ],
                [
                    'type' => 'section',
                    'fields' => [
                        ['type' => 'mrkdwn', 'text' => "*Status:*\n🟢 Active & Connected"],
                        ['type' => 'mrkdwn', 'text' => "*Source:*\nJaipur Heavy Engineering Works"],
                        ['type' => 'mrkdwn', 'text' => "*Timestamp:*\n" . date('d M Y, H:i:s')],
                        ['type' => 'mrkdwn', 'text' => "*Channels Active:*\nDispatches, Quality NCR, Job Orders"],
                    ],
                ],
            ],
        ];

        $result = $slackService->sendMessage($payload);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-slack')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-slack')->with('error', $result['message']);
    }

    public function createJiraIssue()
    {
        $jiraService = new JiraService();
        $summary = $this->request->getPost('summary');
        $description = $this->request->getPost('description');
        $issueType = $this->request->getPost('issue_type') ?: 'Task';
        $priority = $this->request->getPost('priority') ?: 'High';
        $labels = explode(',', (string)$this->request->getPost('labels'));
        $labels = array_filter(array_map('trim', $labels));

        $result = $jiraService->createIssue([
            'summary'     => $summary,
            'description' => $description,
            'issue_type'  => $issueType,
            'priority'    => $priority,
            'labels'      => $labels,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-jira')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-jira')->with('error', $result['message']);
    }

    public function sendSlackMessage()
    {
        $slackService = new SlackService();
        $message = (string)$this->request->getPost('message');
        $channel = (string)$this->request->getPost('channel');

        $payload = [
            'text' => $message,
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "📢 *Broadcast from Steel ERP Management:*\n{$message}",
                    ],
                ],
                [
                    'type' => 'context',
                    'elements' => [
                        [
                            'type' => 'mrkdwn',
                            'text' => "Sent by Works GM / System Administrator | " . date('d M Y, H:i'),
                        ],
                    ],
                ],
            ],
        ];

        $result = $slackService->sendMessage($payload, $channel);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/integrations#tab-slack')->with('success', $result['message']);
        }
        return redirect()->to('/integrations#tab-slack')->with('error', $result['message']);
    }

    public function logs()
    {
        $service = $this->request->getGet('service');
        $logs = $this->logModel->getRecent(50, $service);

        return $this->response->setJSON(['status' => 'success', 'logs' => $logs]);
    }
}
