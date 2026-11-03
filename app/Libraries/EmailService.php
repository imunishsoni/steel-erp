<?php

namespace App\Libraries;

use App\Models\IntegrationLogModel;
use App\Models\IntegrationModel;
use Config\Services;

class EmailService
{
    protected array $config = [];
    protected bool $isEnabled = false;
    protected IntegrationLogModel $logModel;

    public function __construct()
    {
        $integrationModel = new IntegrationModel();
        $serviceData = $integrationModel->getServiceConfig('email');
        $this->isEnabled = (bool)($serviceData['is_enabled'] ?? false);
        $this->config = $serviceData['config'] ?? [];
        $this->logModel = new IntegrationLogModel();
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['smtp_host']) && !empty($this->config['smtp_user']);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled && $this->isConfigured();
    }

    /**
     * Send email using CodeIgniter 4 Email Service or Simulated Output
     */
    public function sendEmail(string|array $to, string $subject, string $htmlBody, array $attachments = [], string $category = 'general'): array
    {
        $recipients = is_array($to) ? implode(', ', $to) : $to;
        $fromEmail = $this->config['from_email'] ?? 'notifications@heavysteel.in';
        $fromName = $this->config['from_name'] ?? 'Bharat Heavy Steel ERP';

        $payload = [
            'to'          => $recipients,
            'subject'     => $subject,
            'from_email'  => $fromEmail,
            'category'    => $category,
            'attachments' => array_keys($attachments),
        ];

        // Simulation / Local dev mode if SMTP credentials are blank
        if (!$this->isConfigured()) {
            $this->logModel->record(
                'email',
                'outbound',
                $category,
                'success',
                $payload,
                200,
                json_encode([
                    'status'    => 'simulated',
                    'message'   => "Simulated email sent to {$recipients}",
                    'subject'   => $subject,
                    'timestamp' => date('Y-m-d H:i:s'),
                ])
            );

            return [
                'success'   => true,
                'simulated' => true,
                'message'   => "Simulated email successfully queued for {$recipients}.",
            ];
        }

        // Live SMTP transmission via CodeIgniter 4 Email Service
        try {
            $email = Services::email();

            $emailConfig = [
                'protocol'       => 'smtp',
                'SMTPHost'       => $this->config['smtp_host'],
                'SMTPPort'       => (int)($this->config['smtp_port'] ?? 587),
                'SMTPUser'       => $this->config['smtp_user'],
                'SMTPPass'       => $this->config['smtp_pass'] ?? '',
                'SMTPCrypto'     => $this->config['smtp_crypto'] ?? 'tls',
                'mailType'       => 'html',
                'charset'        => 'UTF-8',
                'wordWrap'       => true,
                'validate'       => false,
                'priority'       => 2,
                'newline'        => "\r\n",
                'CRLF'           => "\r\n",
                'SMTPTimeout'    => 8,
            ];

            $email->initialize($emailConfig);
            $email->setFrom($fromEmail, $fromName);
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($htmlBody);

            foreach ($attachments as $fileName => $filePath) {
                if (file_exists($filePath)) {
                    $email->attach($filePath, 'attachment', is_string($fileName) ? $fileName : null);
                }
            }

            if ($email->send(false)) {
                $this->logModel->record('email', 'outbound', $category, 'success', $payload, 250, 'Message accepted for delivery');
                return [
                    'success' => true,
                    'message' => "Email delivered successfully to {$recipients}.",
                ];
            }

            $debugInfo = $email->printDebugger(['headers', 'subject']);
            $this->logModel->record('email', 'outbound', $category, 'failed', $payload, 500, substr($debugInfo, 0, 4000));

            return [
                'success' => false,
                'message' => 'Failed to send email. Check SMTP server logs.',
                'debug'   => $debugInfo,
            ];
        } catch (\Throwable $e) {
            $this->logModel->record('email', 'outbound', $category, 'failed', $payload, 0, $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception during email transmission: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send Consignment Dispatch Advice Notice
     */
    public function notifyDispatch(array $dc): array
    {
        $dcNo = $dc['challan_no'] ?? 'DC-NEW';
        $clientName = $dc['client_name'] ?? 'Consignee Plant';
        $clientEmail = $dc['client_email'] ?? ($this->config['dispatch_notify_emails'] ?? 'procurement@client.in');
        $netWeight = number_format((float)($dc['net_weight'] ?? 0), 2) . ' MT';
        $trailer = $dc['vehicle_no'] ?? 'RJ-14-Trailer';
        $driver = ($dc['driver_name'] ?? 'Driver') . ' (' . ($dc['driver_phone'] ?? 'N/A') . ')';
        $destination = $dc['destination'] ?? 'Plant Site';
        $ewayBill = $dc['eway_bill_no'] ?? 'EWAY-PENDING';
        $jobNo = $dc['job_no'] ?? 'JOB-2627';

        $subject = "🚚 Dispatch Advice: Consignment {$dcNo} ({$netWeight}) - Heavy Steel Fabricators";

        $bodyHtml = "
            <p>Dear Sir / Madam,</p>
            <p>We are pleased to inform you that your fabricated steel consignment under <strong>Job Order {$jobNo}</strong> has cleared final weighbridge inspection and has been dispatched on trailer vehicle.</p>
            
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0; font-family: sans-serif; font-size: 14px;'>
                <tr style='background-color: #f1f5f9;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold; width: 35%;'>Delivery Challan No</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-family: monospace; font-weight: bold; color: #1e3a5f;'>{$dcNo}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Consignee Plant</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$clientName}</td>
                </tr>
                <tr style='background-color: #f1f5f9;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Destination Site</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$destination}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Vehicle Trailer No</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>{$trailer}</td>
                </tr>
                <tr style='background-color: #f1f5f9;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Driver &amp; Mobile</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$driver}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Net Consignment Weight</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold; color: #16a34a;'>{$netWeight}</td>
                </tr>
                <tr style='background-color: #f1f5f9;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>GST E-Way Bill Number</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-family: monospace; font-weight: bold;'>{$ewayBill}</td>
                </tr>
            </table>

            <p style='margin-top: 15px;'>Please arrange unlashing and heavy crane offloading at your site store. Signed and stamped LR / challan copy should be endorsed upon arrival.</p>
        ";

        $fullHtml = $this->buildHtmlTemplate(
            'Heavy Consignment Dispatched',
            'Works Security Gate Pass & Weighbridge Advice',
            'DISPATCH ADVICE',
            $bodyHtml
        );

        return $this->sendEmail($clientEmail, $subject, $fullHtml, [], 'dispatch_advice');
    }

    /**
     * Send GST Tax Invoice Notification
     */
    public function notifyInvoice(array $inv): array
    {
        $invNo = $inv['inv_no'] ?? 'INV-NEW';
        $clientName = $inv['client_name'] ?? 'Buyer Plant';
        $clientEmail = $inv['client_email'] ?? ($this->config['accounts_notify_emails'] ?? 'accounts@client.in');
        $taxable = number_format((float)($inv['taxable_amount'] ?? 0), 2);
        $total = number_format((float)($inv['total_amount'] ?? 0), 2);
        $gstin = $inv['client_gstin'] ?? 'N/A';
        $dcRef = $inv['dc_ref'] ?? 'DC-N/A';
        $hsn = $inv['hsn'] ?? '730890';

        $subject = "🧾 GST Tax Invoice {$invNo} (₹{$total}) - Heavy Steel Fabricators Pvt. Ltd.";

        $bodyHtml = "
            <p>Dear Accounts Team,</p>
            <p>Please find details of the GST Tax Invoice generated for fabricated heavy steel structures supplied against <strong>Delivery Challan {$dcRef}</strong>.</p>
            
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0; font-family: sans-serif; font-size: 14px;'>
                <tr style='background-color: #f1f5f9;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold; width: 35%;'>Tax Invoice No</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-family: monospace; font-weight: bold; color: #1e3a5f;'>{$invNo}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Billed To</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$clientName} (GSTIN: {$gstin})</td>
                </tr>
                <tr style='background-color: #f1f5f9;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>HSN / SAC Code</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-family: monospace;'>{$hsn} (Fabricated Steel Structures)</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Taxable Amount</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>₹ {$taxable}</td>
                </tr>
                <tr style='background-color: #f8fafc;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Total Invoice Value (incl. GST)</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-size: 16px; font-weight: bold; color: #1e3a5f;'>₹ {$total}</td>
                </tr>
            </table>

            <div style='background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 12px; margin-top: 15px;'>
                <strong style='color: #1e3a5f;'>Bank Remittance Details:</strong><br>
                Bank: HDFC Bank Ltd, Industrial Estate Branch, Jaipur<br>
                A/C No: <code>50200088991122</code> | IFSC: <code>HDFC0000054</code>
            </div>
        ";

        $fullHtml = $this->buildHtmlTemplate(
            'Tax Invoice Notification',
            'Statutory Supply Under CGST Act 2017 (Rule 46)',
            'TAX INVOICE',
            $bodyHtml
        );

        return $this->sendEmail($clientEmail, $subject, $fullHtml, [], 'invoice_notice');
    }

    /**
     * Send Quality NCR Alert
     */
    public function notifyQuality(array $ncr): array
    {
        $reportNo = $ncr['report_no'] ?? 'NCR-NEW';
        $jobNo = $ncr['job_no'] ?? 'JOB-2627';
        $joint = $ncr['joint_id'] ?? 'Weld Joint';
        $method = $ncr['method'] ?? 'UT Testing';
        $defect = $ncr['defect_description'] ?? 'Defect observed in weld root';
        $inspector = $ncr['inspector'] ?? 'NDT Level II';
        $recipient = $this->config['qc_notify_emails'] ?? 'qc@heavysteel.in';

        $subject = "⚠️ Quality NCR Notice: {$reportNo} on Job {$jobNo} - Action Required";

        $bodyHtml = "
            <p><strong>URGENT: Quality Non-Conformance Notice</strong></p>
            <p>During Non-Destructive Testing (NDT) inspection for <strong>Job {$jobNo}</strong>, the following defect was identified and requires immediate corrective action:</p>

            <table style='width: 100%; border-collapse: collapse; margin: 20px 0; font-family: sans-serif; font-size: 14px;'>
                <tr style='background-color: #fef2f2;'>
                    <td style='padding: 10px; border: 1px solid #fecaca; font-weight: bold; width: 35%; color: #991b1b;'>NCR Report Number</td>
                    <td style='padding: 10px; border: 1px solid #fecaca; font-family: monospace; font-weight: bold; color: #b91c1c;'>{$reportNo}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Job Order #</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$jobNo}</td>
                </tr>
                <tr style='background-color: #f1f5f9;'>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Weld Joint / Identification</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$joint}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>NDT Method</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$method}</td>
                </tr>
                <tr style='background-color: #fef2f2;'>
                    <td style='padding: 10px; border: 1px solid #fecaca; font-weight: bold; color: #991b1b;'>Defect Observation</td>
                    <td style='padding: 10px; border: 1px solid #fecaca; color: #991b1b;'>{$defect}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #cbd5e1; font-weight: bold;'>Inspector</td>
                    <td style='padding: 10px; border: 1px solid #cbd5e1;'>{$inspector}</td>
                </tr>
            </table>

            <p style='color: #b91c1c; font-weight: bold;'>Required Action: Grind out defective weld pass, re-weld per qualified WPS, and perform 100% repeat NDT examination before releasing to blasting bay.</p>
        ";

        $fullHtml = $this->buildHtmlTemplate(
            'Quality Non-Conformance Alert',
            'Welding & NDT Inspection Punch Point',
            'QUALITY NCR',
            $bodyHtml
        );

        return $this->sendEmail($recipient, $subject, $fullHtml, [], 'quality_ncr');
    }

    /**
     * Send Verification Test Email
     */
    public function sendTestEmail(string $recipient): array
    {
        $subject = "✅ Steel ERP Email Gateway Test - " . date('d M Y, H:i');

        $bodyHtml = "
            <p>Hello,</p>
            <p>This is a verification test message from the <strong>Bharat Heavy Steel ERP</strong> automated notification system.</p>
            <div style='background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 15px; margin: 15px 0;'>
                <strong style='color: #166534;'>Connection Status: 🟢 ACTIVE &amp; OPERATIONAL</strong><br>
                <div style='font-size: 13px; color: #374151; margin-top: 5px;'>
                    Facility: Jaipur Heavy Engineering Works<br>
                    Server Timestamp: " . date('Y-m-d H:i:s') . "<br>
                    Notification Channels: Dispatches, Quality NCRs, Invoices, Job Route Cards
                </div>
            </div>
            <p style='font-size: 13px; color: #6b7280;'>If you received this message, your SMTP credentials and transport parameters are configured properly.</p>
        ";

        $fullHtml = $this->buildHtmlTemplate(
            'System Notification Test',
            'SMTP Gateway Diagnostic Verification',
            'CONNECTION TEST',
            $bodyHtml
        );

        return $this->sendEmail($recipient, $subject, $fullHtml, [], 'test_email');
    }

    /**
     * Base HTML Email Layout Builder
     */
    protected function buildHtmlTemplate(string $title, string $subtitle, string $badgeText, string $bodyHtml): string
    {
        $year = date('Y');

        return "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$title}</title>
</head>
<body style='margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; color: #1f2937;'>
    <div style='max-width: 640px; margin: 25px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;'>
        <!-- HEADER -->
        <div style='background-color: #1e3a5f; padding: 25px; border-bottom: 3px solid #f97316; color: #ffffff;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td style='vertical-align: middle;'>
                        <div style='font-size: 20px; font-weight: 800; letter-spacing: 0.5px;'>HEAVY STEEL FABRICATORS PVT. LTD.</div>
                        <div style='font-size: 12px; color: #cbd5e1; margin-top: 2px;'>Industrial Heavy Steel Fabrication &bull; Jaipur Works</div>
                    </td>
                    <td style='text-align: right; vertical-align: middle;'>
                        <span style='background-color: #f97316; color: #ffffff; font-size: 11px; font-weight: bold; padding: 5px 10px; border-radius: 4px; text-transform: uppercase;'>{$badgeText}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TITLE BAR -->
        <div style='background-color: #f8fafc; padding: 15px 25px; border-bottom: 1px solid #e2e8f0;'>
            <h2 style='margin: 0; font-size: 18px; color: #0f172a;'>{$title}</h2>
            <div style='font-size: 12px; color: #64748b; margin-top: 2px;'>{$subtitle}</div>
        </div>

        <!-- CONTENT -->
        <div style='padding: 25px; line-height: 1.6; font-size: 14px;'>
            {$bodyHtml}
        </div>

        <!-- FOOTER -->
        <div style='background-color: #f1f5f9; padding: 20px 25px; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; line-height: 1.5;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td>
                        <strong>Heavy Steel Fabricators Pvt. Ltd.</strong><br>
                        Plot No. 45, Industrial Area Phase-II, Jaipur, Rajasthan - 302022<br>
                        GSTIN: <code>08AABCU9603R1ZM</code> | Phone: +91 141 2345678
                    </td>
                    <td style='text-align: right; vertical-align: top;'>
                        &copy; {$year} Heavy Steel ERP<br>
                        <span style='color: #94a3b8;'>Automated System Notice</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
";
    }
}
