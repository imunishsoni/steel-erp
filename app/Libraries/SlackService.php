<?php

namespace App\Libraries;

use App\Models\IntegrationLogModel;
use App\Models\IntegrationModel;

class SlackService
{
    protected array $config = [];
    protected bool $isEnabled = false;
    protected IntegrationLogModel $logModel;

    public function __construct()
    {
        $integrationModel = new IntegrationModel();
        $serviceData = $integrationModel->getServiceConfig('slack');
        $this->isEnabled = (bool)($serviceData['is_enabled'] ?? false);
        $this->config = $serviceData['config'] ?? [];
        $this->logModel = new IntegrationLogModel();
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['webhook_url']) || !empty($this->config['bot_token']);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled && $this->isConfigured();
    }

    /**
     * Send a raw Slack message or Block Kit payload
     */
    public function sendMessage(array $payload, ?string $channel = null): array
    {
        $webhookUrl = $this->config['webhook_url'] ?? '';
        $botToken = $this->config['bot_token'] ?? '';
        $targetChannel = $channel ?: ($this->config['default_channel'] ?? '#general');

        // Allow overriding channel if using bot token or webhook support
        if (!empty($targetChannel) && !isset($payload['channel'])) {
            $payload['channel'] = $targetChannel;
        }

        // Mock / Offline simulation mode if no live credentials
        if (empty($webhookUrl) && empty($botToken)) {
            $this->logModel->record('slack', 'outbound', 'send_message', 'success', $payload, 200, json_encode([
                'ok' => true,
                'channel' => $targetChannel,
                'mode' => 'simulated',
                'ts' => (string)time() . '.000100',
            ]));

            return [
                'success' => true,
                'simulated' => true,
                'channel' => $targetChannel,
                'message' => "Slack alert simulated successfully to {$targetChannel}.",
            ];
        }

        $headers = ['Content-Type: application/json; charset=utf-8'];
        $url = $webhookUrl;

        // If using Bot Token instead of Webhook URL
        if (!empty($botToken) && empty($webhookUrl)) {
            $url = 'https://slack.com/api/chat.postMessage';
            $headers[] = 'Authorization: Bearer ' . $botToken;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            $this->logModel->record('slack', 'outbound', 'send_message', 'failed', $payload, 0, $curlError);
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $curlError,
            ];
        }

        $body = json_decode($response, true);
        $isOk = ($httpCode >= 200 && $httpCode < 300) && ($response === 'ok' || !empty($body['ok']));

        if ($isOk) {
            $this->logModel->record('slack', 'outbound', 'send_message', 'success', $payload, $httpCode, $response);
            return [
                'success' => true,
                'channel' => $targetChannel,
                'message' => "Message posted to Slack channel {$targetChannel}.",
            ];
        }

        $errorMsg = is_array($body) ? ($body['error'] ?? 'Slack API error') : $response;
        $this->logModel->record('slack', 'outbound', 'send_message', 'failed', $payload, $httpCode, $response);
        return [
            'success' => false,
            'message' => 'Failed to send Slack message: ' . $errorMsg,
            'code'    => $httpCode,
        ];
    }

    /**
     * Send structured Dispatch Notification to Slack
     */
    public function notifyDispatch(array $dc): array
    {
        $channel = $this->config['dispatch_channel'] ?? ($this->config['default_channel'] ?? '#dispatch-alerts');
        $dcNo = $dc['challan_no'] ?? 'DC-NEW';
        $client = $dc['client_name'] ?? 'Client Plant';
        $vehicle = $dc['vehicle_no'] ?? 'RJ-14-Trailer';
        $netWt = number_format((float)($dc['net_weight'] ?? 0), 2) . ' MT';
        $destination = $dc['destination'] ?? 'Project Site';
        $driver = ($dc['driver_name'] ?? 'Driver') . ' (' . ($dc['driver_phone'] ?? 'N/A') . ')';
        $eway = $dc['eway_bill_no'] ?? 'EWAY-PENDING';

        $payload = [
            'text' => "🚚 *Consignment Dispatched:* {$dcNo} ({$netWt}) for {$client}",
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => "🚚 Heavy Steel Consignment Dispatched: {$dcNo}",
                        'emoji' => true,
                    ],
                ],
                [
                    'type' => 'section',
                    'fields' => [
                        ['type' => 'mrkdwn', 'text' => "*Client / Plant:*\n{$client}"],
                        ['type' => 'mrkdwn', 'text' => "*Net Dispatched:*\n*{$netWt}*"],
                        ['type' => 'mrkdwn', 'text' => "*Vehicle Trailer:*\n`{$vehicle}`"],
                        ['type' => 'mrkdwn', 'text' => "*Driver & Phone:*\n{$driver}"],
                        ['type' => 'mrkdwn', 'text' => "*Destination Site:*\n{$destination}"],
                        ['type' => 'mrkdwn', 'text' => "*GST E-Way Bill:*\n`{$eway}`"],
                    ],
                ],
                [
                    'type' => 'context',
                    'elements' => [
                        [
                            'type' => 'mrkdwn',
                            'text' => "🏭 *Works Weighbridge* | Security Gate Pass cleared under CGST Rule 55 | Time: " . date('d M Y, H:i'),
                        ],
                    ],
                ],
            ],
        ];

        return $this->sendMessage($payload, $channel);
    }

    /**
     * Send Quality / NDT Alert to Slack
     */
    public function notifyQuality(array $ncr): array
    {
        $channel = $this->config['qc_channel'] ?? ($this->config['default_channel'] ?? '#qc-ncr-alerts');
        $reportNo = $ncr['report_no'] ?? 'NDT-NCR';
        $jobNo = $ncr['job_no'] ?? 'JOB-2627';
        $joint = $ncr['joint_id'] ?? 'Weld Joint';
        $method = $ncr['method'] ?? 'UT / RT Testing';
        $defect = $ncr['defect_description'] ?? 'Porosity / Lack of Fusion detected';
        $inspector = $ncr['inspector'] ?? 'NDT Level II';

        $payload = [
            'text' => "⚠️ *Quality Defect / NCR Raised:* Report {$reportNo} on Job {$jobNo}",
            'blocks' => [
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => "⚠️ Quality Non-Conformance Notice: {$reportNo}",
                        'emoji' => true,
                    ],
                ],
                [
                    'type' => 'section',
                    'fields' => [
                        ['type' => 'mrkdwn', 'text' => "*Job Order:*\n`{$jobNo}`"],
                        ['type' => 'mrkdwn', 'text' => "*Joint ID:*\n{$joint}"],
                        ['type' => 'mrkdwn', 'text' => "*NDT Method:*\n{$method}"],
                        ['type' => 'mrkdwn', 'text' => "*Inspector:*\n{$inspector}"],
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "*Defect / Punch Point Observation:*\n>{$defect}\n_Action Required: Immediate gouging, re-weld and 100% NDT re-examination._",
                    ],
                ],
            ],
        ];

        return $this->sendMessage($payload, $channel);
    }
}
