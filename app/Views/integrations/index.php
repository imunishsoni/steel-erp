<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h4 class="mb-0"><i class="bi bi-diagram-3 me-2 text-primary"></i>Ticketing, Email &amp; Collaboration Integrations</h4>
        <small class="text-muted">Connect your Heavy Fabrication ERP with SMTP Email, Atlassian Jira, Slack channels, and automated webhook pipelines</small>
    </div>
    <div class="col-md-5 text-md-end mt-2 mt-md-0">
        <button class="btn btn-outline-warning text-dark btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalCreateClickupTask">
            <i class="bi bi-check2-square me-1"></i> New ClickUp Task
        </button>
        <button class="btn btn-outline-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalComposeEmail">
            <i class="bi bi-envelope-plus me-1"></i> Compose Email
        </button>
        <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalCreateJira">
            <i class="bi bi-plus-circle me-1"></i> New Jira Ticket
        </button>
        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalBroadcastSlack">
            <i class="bi bi-slack me-1"></i> Slack Broadcast
        </button>
    </div>
</div>

<!-- NAV TABS -->
<ul class="nav nav-pills mb-3 bg-white p-2 rounded shadow-sm border" id="integrationTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-jira-btn" data-bs-toggle="pill" data-bs-target="#tab-jira" type="button" role="tab">
            <i class="bi bi-kanban me-2"></i>Atlassian Jira
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-slack-btn" data-bs-toggle="pill" data-bs-target="#tab-slack" type="button" role="tab">
            <i class="bi bi-slack me-2"></i>Slack Channels
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-email-btn" data-bs-toggle="pill" data-bs-target="#tab-email" type="button" role="tab">
            <i class="bi bi-envelope-at me-2"></i>Email Notifications (SMTP)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-clickup-btn" data-bs-toggle="pill" data-bs-target="#tab-clickup" type="button" role="tab">
            <i class="bi bi-check2-square me-2"></i>ClickUp &amp; Open-Source PM
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-webhooks-btn" data-bs-toggle="pill" data-bs-target="#tab-webhooks" type="button" role="tab">
            <i class="bi bi-hdd-network me-2"></i>Webhooks &amp; API
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-logs-btn" data-bs-toggle="pill" data-bs-target="#tab-logs" type="button" role="tab">
            <i class="bi bi-clock-history me-2"></i>Delivery &amp; Event Logs
        </button>
    </li>
</ul>

<div class="tab-content" id="integrationTabsContent">

    <!-- ==================== TAB 1: JIRA CONFIGURATION ==================== -->
    <div class="tab-pane fade show active" id="tab-jira" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="bi bi-kanban me-2 text-primary"></i>Jira Cloud / Server Ticketing Configuration</span>
                        <span class="badge <?= !empty($jira['is_enabled']) ? 'bg-success' : 'bg-secondary' ?>">
                            <?= !empty($jira['is_enabled']) ? 'Active' : 'Disabled' ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('integrations/update-jira') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="jira_enabled" name="is_enabled" value="1" <?= !empty($jira['is_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="jira_enabled">Enable Jira Issue Tracking Integration</label>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label required">Jira Host Domain URL</label>
                                    <input type="url" name="host" class="form-control font-monospace" placeholder="https://your-company.atlassian.net" value="<?= esc($jira['config']['host'] ?? '') ?>" required>
                                    <div class="form-text">Base URL of your Jira Cloud or Server instance (e.g. <code>https://heavysteel.atlassian.net</code>).</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Atlassian Account Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="admin@heavysteel.in" value="<?= esc($jira['config']['email'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">API Token</label>
                                    <input type="password" name="api_token" class="form-control font-monospace" placeholder="Atlassian API token" value="<?= esc($jira['config']['api_token'] ?? '') ?>" required>
                                    <div class="form-text">Generate from <strong>Atlassian Account &rarr; Security &rarr; API Tokens</strong>.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Default Project Key</label>
                                    <input type="text" name="project_key" class="form-control font-monospace text-uppercase" placeholder="STEEL" value="<?= esc($jira['config']['project_key'] ?? 'STEEL') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Default Issue Type</label>
                                    <select name="issue_type" class="form-select">
                                        <option value="Task" <?= ($jira['config']['issue_type'] ?? '') === 'Task' ? 'selected' : '' ?>>Task</option>
                                        <option value="Bug" <?= ($jira['config']['issue_type'] ?? '') === 'Bug' ? 'selected' : '' ?>>Bug</option>
                                        <option value="Quality NCR" <?= ($jira['config']['issue_type'] ?? '') === 'Quality NCR' ? 'selected' : '' ?>>Quality NCR (Non-Conformance)</option>
                                        <option value="Breakdown" <?= ($jira['config']['issue_type'] ?? '') === 'Breakdown' ? 'selected' : '' ?>>Machine Breakdown</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i>Automated Jira Ticket Triggers</h6>
                            <div class="row g-2">
                                <?php $jiraEvents = $jira['events'] ?? []; ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="qaqc_ncr_failed" id="ev_jira_ncr" <?= in_array('qaqc_ncr_failed', $jiraEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_jira_ncr">
                                            <strong>NDT / QA Defect:</strong> Auto-create Jira NCR ticket when weld fails Ultrasonic/Radiography test
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="job_created" id="ev_jira_job" <?= in_array('job_created', $jiraEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_jira_job">
                                            <strong>New Job Order:</strong> Create Engineering Drawing Review ticket for shop floor
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="machine_breakdown" id="ev_jira_breakdown" <?= in_array('machine_breakdown', $jiraEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_jira_breakdown">
                                            <strong>Bay Equipment Breakdown:</strong> Alert maintenance team for CNC / SAW repair
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="testJiraConnection()">
                                    <i class="bi bi-arrow-repeat me-1"></i> Test Connection
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Save Jira Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Jira Quick Info -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-info-circle me-2 text-info"></i>Industrial Use Cases for Jira</span>
                    </div>
                    <div class="card-body small">
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">1. Quality Non-Conformance (NCR)</h6>
                            <p class="text-muted mb-0">When ASME or AWS D1.1 weld tests detect porosity or slag inclusions, an NCR ticket is automatically logged in Jira with Heat No, Joint ID, and NDT film link.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">2. Drawing &amp; Engineering Punch Points</h6>
                            <p class="text-muted mb-0">Track BHEL, NTPC, or UltraTech client drawing revision approvals and TPI inspection clearance remarks.</p>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">3. Heavy Bay Machine Maintenance</h6>
                            <p class="text-muted mb-0">Oxy-fuel CNC torch failure or Submerged Arc Welding (SAW) boom breakdown triggers priority ticketing.</p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-ticket-perforated fs-1 text-primary mb-2"></i>
                        <h6 class="fw-bold">Need a Manual Jira Ticket?</h6>
                        <p class="text-muted small">Quickly create a task linked to an existing Fabrication Job Order or Client project.</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreateJira">
                            <i class="bi bi-plus-circle me-1"></i> Create Jira Ticket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 2: SLACK CONFIGURATION ==================== -->
    <div class="tab-pane fade" id="tab-slack" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="bi bi-slack me-2 text-success"></i>Slack Webhooks &amp; Bot Channel Configuration</span>
                        <span class="badge <?= !empty($slack['is_enabled']) ? 'bg-success' : 'bg-secondary' ?>">
                            <?= !empty($slack['is_enabled']) ? 'Active' : 'Disabled' ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('integrations/update-slack') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="slack_enabled" name="is_enabled" value="1" <?= !empty($slack['is_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="slack_enabled">Enable Slack Notifications Integration</label>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label required">Slack Incoming Webhook URL</label>
                                    <input type="url" name="webhook_url" class="form-control font-monospace" placeholder="https://hooks.slack.com/services/WORKSPACE_ID/BOT_ID/SECRET_TOKEN" value="<?= esc($slack['config']['webhook_url'] ?? '') ?>">
                                    <div class="form-text">Incoming Webhook created via <strong>Slack App &rarr; Incoming Webhooks</strong>.</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bot User OAuth Token (Optional)</label>
                                    <input type="password" name="bot_token" class="form-control font-monospace" placeholder="xoxb-your-slack-bot-token" value="<?= esc($slack['config']['bot_token'] ?? '') ?>">
                                    <div class="form-text">Used if you want the ERP to dynamically switch target channels using <code>chat.postMessage</code>.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">General Channel</label>
                                    <input type="text" name="default_channel" class="form-control font-monospace" placeholder="#shopfloor-alerts" value="<?= esc($slack['config']['default_channel'] ?? '#shopfloor-alerts') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Dispatches Channel</label>
                                    <input type="text" name="dispatch_channel" class="form-control font-monospace" placeholder="#dispatch-alerts" value="<?= esc($slack['config']['dispatch_channel'] ?? '#dispatch-alerts') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quality NCR Channel</label>
                                    <input type="text" name="qc_channel" class="form-control font-monospace" placeholder="#qc-ncr-alerts" value="<?= esc($slack['config']['qc_channel'] ?? '#qc-ncr-alerts') ?>">
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-broadcast text-success me-2"></i>Automated Slack Notification Channels</h6>
                            <div class="row g-2">
                                <?php $slackEvents = $slack['events'] ?? []; ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="dispatch_created" id="ev_slack_dispatch" <?= in_array('dispatch_created', $slackEvents) || empty($slackEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_slack_dispatch">
                                            <strong>Consignment Dispatched:</strong> Trailer gate pass, driver phone, net weight &amp; E-Way bill
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="job_stage_advanced" id="ev_slack_stage" <?= in_array('job_stage_advanced', $slackEvents) || empty($slackEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_slack_stage">
                                            <strong>Fabrication Milestone:</strong> Progress from Fit-Up &rarr; SAW &rarr; SA 2.5 Blasting &rarr; PU Paint
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="qaqc_ncr_raised" id="ev_slack_ncr" <?= in_array('qaqc_ncr_raised', $slackEvents) || empty($slackEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_slack_ncr">
                                            <strong>Quality Failure / Punch Point:</strong> Urgent alert to QC managers with joint details
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="invoice_created" id="ev_slack_invoice" <?= in_array('invoice_created', $slackEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_slack_invoice">
                                            <strong>Tax Invoice Issued:</strong> Post invoice value (₹) and client GSTIN to accounts channel
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" class="btn btn-outline-success" onclick="testSlackConnection()">
                                    <i class="bi bi-bell me-1"></i> Send Test Alert
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save me-1"></i> Save Slack Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Slack Message Preview Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-eye me-2 text-primary"></i>Live Slack Message Format Preview</span>
                    </div>
                    <div class="card-body p-3 bg-light">
                        <div class="p-3 bg-white border rounded shadow-sm">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary p-2 me-2"><i class="bi bi-hammer text-white"></i></span>
                                <div>
                                    <strong class="text-dark">Steel ERP Bot</strong> <span class="badge bg-secondary" style="font-size: 0.65rem;">APP</span>
                                    <div class="text-muted" style="font-size: 0.7rem;">Today at <?= date('h:i A') ?></div>
                                </div>
                            </div>
                            <div class="fw-bold text-dark small mb-2">🚚 Heavy Steel Consignment Dispatched: DC-2627-001</div>
                            <div class="bg-light p-2 rounded small mb-2 border-start border-4 border-warning">
                                <div><strong>Client:</strong> NTPC Limited (Ramagundam STPP)</div>
                                <div><strong>Net Weight:</strong> 20.00 MT (ESP Casings)</div>
                                <div><strong>Vehicle:</strong> RJ-14-GC-8842 (Trailer)</div>
                                <div><strong>Driver:</strong> Balram Gurjar (9829988112)</div>
                                <div><strong>E-Way Bill:</strong> 241890334812</div>
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                🏭 Works Weighbridge | Cleared under Rule 55 CGST Rules
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-megaphone fs-1 text-success mb-2"></i>
                        <h6 class="fw-bold">Broadcast to Plant Floor</h6>
                        <p class="text-muted small">Broadcast emergency alerts or production announcements to all shop floor Slack channels.</p>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalBroadcastSlack">
                            <i class="bi bi-send me-1"></i> Broadcast Announcement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 3: EMAIL NOTIFICATIONS (SMTP) ==================== -->
    <div class="tab-pane fade" id="tab-email" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="bi bi-envelope-at me-2 text-info"></i>SMTP Server &amp; Gateway Configuration</span>
                        <span class="badge <?= !empty($email['is_enabled']) ? 'bg-success' : 'bg-secondary' ?>">
                            <?= !empty($email['is_enabled']) ? 'Active' : 'Disabled' ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('integrations/update-email') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="email_enabled" name="is_enabled" value="1" <?= !empty($email['is_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="email_enabled">Enable Automated Email Notifications</label>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">SMTP Server Host</label>
                                    <input type="text" name="smtp_host" class="form-control font-monospace" placeholder="smtp.gmail.com or mail.heavysteel.in" value="<?= esc($email['config']['smtp_host'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">SMTP Port</label>
                                    <input type="number" name="smtp_port" class="form-control font-monospace" placeholder="587" value="<?= esc($email['config']['smtp_port'] ?? '587') ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Encryption</label>
                                    <select name="smtp_crypto" class="form-select font-monospace">
                                        <option value="tls" <?= ($email['config']['smtp_crypto'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS (STARTTLS)</option>
                                        <option value="ssl" <?= ($email['config']['smtp_crypto'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                        <option value="" <?= empty($email['config']['smtp_crypto']) ? 'selected' : '' ?>>None (Port 25)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">SMTP Username / Email</label>
                                    <input type="text" name="smtp_user" class="form-control font-monospace" placeholder="notifications@heavysteel.in" value="<?= esc($email['config']['smtp_user'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">SMTP Password</label>
                                    <input type="password" name="smtp_pass" class="form-control font-monospace" placeholder="SMTP Password or App Key" value="<?= esc($email['config']['smtp_pass'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">From Email Address</label>
                                    <input type="email" name="from_email" class="form-control" placeholder="notifications@heavysteel.in" value="<?= esc($email['config']['from_email'] ?? 'notifications@heavysteel.in') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">From Sender Display Name</label>
                                    <input type="text" name="from_name" class="form-control" placeholder="Heavy Steel Fabricators ERP" value="<?= esc($email['config']['from_name'] ?? 'Heavy Steel Fabricators ERP') ?>" required>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-people me-2 text-primary"></i>Default Department Recipients</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Dispatches Notification Recipient(s)</label>
                                    <input type="text" name="dispatch_notify_emails" class="form-control font-monospace small" placeholder="logistics@heavysteel.in" value="<?= esc($email['config']['dispatch_notify_emails'] ?? 'logistics@heavysteel.in') ?>">
                                    <div class="form-text">Receives gate pass &amp; weighbridge copy.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Quality NCR Recipient(s)</label>
                                    <input type="text" name="qc_notify_emails" class="form-control font-monospace small" placeholder="qc@heavysteel.in" value="<?= esc($email['config']['qc_notify_emails'] ?? 'qc@heavysteel.in') ?>">
                                    <div class="form-text">Receives defect observations &amp; NDT reports.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Billing / Accounts Recipient(s)</label>
                                    <input type="text" name="accounts_notify_emails" class="form-control font-monospace small" placeholder="billing@heavysteel.in" value="<?= esc($email['config']['accounts_notify_emails'] ?? 'billing@heavysteel.in') ?>">
                                    <div class="form-text">Receives copy of released tax invoices.</div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i>Automated Email Triggers</h6>
                            <div class="row g-2">
                                <?php $emailEvents = $email['events'] ?? []; ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="dispatch_created" id="ev_email_dispatch" <?= in_array('dispatch_created', $emailEvents) || empty($emailEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_email_dispatch">
                                            <strong>Consignment Dispatched:</strong> Send formal Dispatch Advice with trailer details &amp; E-Way bill to client procurement officer
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="invoice_created" id="ev_email_invoice" <?= in_array('invoice_created', $emailEvents) || empty($emailEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_email_invoice">
                                            <strong>GST Tax Invoice Issued:</strong> Email invoice copy with bank remittance IFSC &amp; 30-day payment term
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="qaqc_ncr_raised" id="ev_email_ncr" <?= in_array('qaqc_ncr_raised', $emailEvents) || empty($emailEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_email_ncr">
                                            <strong>Quality NCR Notice:</strong> Urgent weld defect alert to QA/QC manager &amp; TPI inspection agency
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="quotation_sent" id="ev_email_qtn" <?= in_array('quotation_sent', $emailEvents) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_email_qtn">
                                            <strong>Quotation Released:</strong> Send technical estimation &amp; BOQ price schedule to client tender committee
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="openTestEmailModal()">
                                    <i class="bi bi-send-check me-1"></i> Send Test Email
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Save Email Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Email Info Column -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-eye me-2 text-primary"></i>Branded HTML Email Template Preview</span>
                    </div>
                    <div class="card-body p-3 bg-light">
                        <div class="border rounded shadow-sm bg-white overflow-hidden">
                            <div style="background-color: #1e3a5f; color: #fff; padding: 12px 16px; border-bottom: 2px solid #f97316;">
                                <div class="fw-bold small">HEAVY STEEL FABRICATORS PVT. LTD.</div>
                                <div style="font-size: 0.65rem; color: #cbd5e1;">Jaipur Heavy Engineering Works</div>
                            </div>
                            <div class="p-3 small">
                                <div class="badge bg-primary text-uppercase mb-2">DISPATCH ADVICE</div>
                                <div class="fw-bold mb-1">Consignment Dispatched: DC-2627-001</div>
                                <p class="text-muted" style="font-size: 0.75rem;">Fabricated ESP Casing Panels (20.00 MT) safely loaded on 40ft trailer RJ-14-GC-8842 with GST E-Way Bill 241890334812.</p>
                                <div class="p-2 bg-light rounded" style="font-size: 0.7rem;">
                                    <strong>Client:</strong> NTPC Limited (Ramagundam STPP)<br>
                                    <strong>Net Weight:</strong> 20.00 MT
                                </div>
                            </div>
                            <div class="bg-light p-2 border-top text-muted" style="font-size: 0.65rem;">
                                GSTIN: 08AABCU9603R1ZM &bull; Automated System Notice
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-envelope-paper fs-1 text-info mb-2"></i>
                        <h6 class="fw-bold">Direct Client Notification</h6>
                        <p class="text-muted small">Compose and dispatch a formal email notice to NTPC, Adani, or UltraTech engineers.</p>
                        <button class="btn btn-info text-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalComposeEmail">
                            <i class="bi bi-envelope-plus me-1"></i> Compose Email Notice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 4: CLICKUP & OPEN-SOURCE PM ==================== -->
    <div class="tab-pane fade" id="tab-clickup" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="bi bi-check2-square me-2 text-warning"></i>ClickUp &amp; Open-Source Project Management Gateway</span>
                        <span class="badge <?= !empty($clickup['is_enabled']) ? 'bg-success' : 'bg-secondary' ?>">
                            <?= !empty($clickup['is_enabled']) ? 'Active' : 'Disabled' ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('/integrations/update-clickup') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_enabled" id="clickup_enabled" value="1" <?= !empty($clickup['is_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="clickup_enabled">Enable ClickUp / Open-Source PM Synchronization</label>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Platform / Engine</label>
                                    <select name="platform" class="form-select" id="clickup_platform">
                                        <option value="clickup" <?= ($clickup['config']['platform'] ?? 'clickup') === 'clickup' ? 'selected' : '' ?>>ClickUp Cloud (REST API v2)</option>
                                        <option value="plane" <?= ($clickup['config']['platform'] ?? '') === 'plane' ? 'selected' : '' ?>>Plane (Open-Source ClickUp/Jira Alternative)</option>
                                        <option value="openproject" <?= ($clickup['config']['platform'] ?? '') === 'openproject' ? 'selected' : '' ?>>OpenProject (Self-Hosted)</option>
                                        <option value="custom" <?= ($clickup['config']['platform'] ?? '') === 'custom' ? 'selected' : '' ?>>Custom REST Task Gateway</option>
                                    </select>
                                    <div class="form-text">Choose your preferred task tracking stack.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">API Base Endpoint URL</label>
                                    <input type="text" name="api_url" class="form-control font-monospace" placeholder="https://api.clickup.com/api/v2" value="<?= esc($clickup['config']['api_url'] ?? 'https://api.clickup.com/api/v2') ?>">
                                    <div class="form-text">ClickUp Cloud default: <code>https://api.clickup.com/api/v2</code></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">API Access Token / Personal Key</label>
                                    <input type="password" name="api_token" class="form-control font-monospace" placeholder="pk_12345678_ABCDEF..." value="<?= esc($clickup['config']['api_token'] ?? '') ?>">
                                    <div class="form-text">Generated under <strong>ClickUp Settings &rarr; Apps</strong>.</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Team / Workspace ID</label>
                                    <input type="text" name="team_id" class="form-control font-monospace" placeholder="9012345" value="<?= esc($clickup['config']['team_id'] ?? '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">List / Project ID</label>
                                    <input type="text" name="list_id" class="form-control font-monospace" placeholder="901800123456" value="<?= esc($clickup['config']['list_id'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Default Task Priority</label>
                                    <select name="default_priority" class="form-select font-monospace">
                                        <option value="1" <?= ($clickup['config']['default_priority'] ?? 2) == 1 ? 'selected' : '' ?>>1 - Urgent (🔴 Red)</option>
                                        <option value="2" <?= ($clickup['config']['default_priority'] ?? 2) == 2 ? 'selected' : '' ?>>2 - High (🟡 Yellow)</option>
                                        <option value="3" <?= ($clickup['config']['default_priority'] ?? 2) == 3 ? 'selected' : '' ?>>3 - Normal (🔵 Blue)</option>
                                        <option value="4" <?= ($clickup['config']['default_priority'] ?? 2) == 4 ? 'selected' : '' ?>>4 - Low (⚪ Grey)</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-diagram-2 text-warning me-2"></i>Automated Shop Floor Event Triggers</h6>
                            <div class="row g-2">
                                <?php $ev = $clickup['events'] ?? []; ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="job_created" id="ev_clk_job" <?= in_array('job_created', $ev) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_clk_job">
                                            <strong>New Job Order Released:</strong> Automatically create task with structural drawing, tonnage &amp; bay milestone checklist
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="qaqc_ncr_raised" id="ev_clk_ncr" <?= in_array('qaqc_ncr_raised', $ev) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_clk_ncr">
                                            <strong>Quality NCR / Defect Raised:</strong> Urgent task for welding foreman &amp; TPI agency with joint defect specifics
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="machine_breakdown" id="ev_clk_maint" <?= in_array('machine_breakdown', $ev) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_clk_maint">
                                            <strong>Machine Breakdown Logged:</strong> Dispatch maintenance ticket for plate rollers or SAW gantries
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="dispatch_created" id="ev_clk_dispatch" <?= in_array('dispatch_created', $ev) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="ev_clk_dispatch">
                                            <strong>Consignment Dispatched:</strong> Create gate clearance task with E-Way bill and trailer number
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="testClickupConnection()">
                                    <i class="bi bi-arrow-repeat me-1"></i> Test Connection
                                </button>
                                <button type="submit" class="btn btn-warning text-dark fw-bold">
                                    <i class="bi bi-save me-1"></i> Save ClickUp Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-info-circle me-2 text-info"></i>Why Fabricators Use ClickUp / Plane</span>
                    </div>
                    <div class="card-body small">
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">1. Shop Floor Bay Kanban</h6>
                            <p class="text-muted mb-0">Track plate cutting, fit-up, SAW welding, stress relieving, and painting progress in visual board swimlanes.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">2. Plant Client Project Deliverables</h6>
                            <p class="text-muted mb-0">Manage NTPC, Adani, and UltraTech milestone packages with target dispatch dates and checklists.</p>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">3. Open-Source Data Sovereignty</h6>
                            <p class="text-muted mb-0">Self-host <strong>Plane</strong> or <strong>OpenProject</strong> on-premise at the factory without relying on third-party public cloud providers.</p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-card-checklist me-2 text-warning"></i>Live Task Format Preview</span>
                    </div>
                    <div class="card-body p-3 bg-light">
                        <div class="p-3 bg-white border rounded shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-warning text-dark"><i class="bi bi-flag-fill me-1"></i>High Priority</span>
                                <span class="badge bg-light text-dark border">TO DO</span>
                            </div>
                            <div class="fw-bold text-dark small mb-1">[JOB-2627-001] Shop Floor Traveler: ESP Casing Panels</div>
                            <p class="text-muted" style="font-size: 0.75rem;">Fabrication of 20.00 MT casing assemblies for NTPC Ramagundam STPP Stage-IV.</p>
                            <div class="d-flex gap-1 mb-2">
                                <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">#job-order</span>
                                <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">#shopfloor</span>
                                <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">#bay-1</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                📅 Target: 45 Days &bull; 🏗️ Drawing: DWG-NTPC-ESP-01
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 bg-warning bg-opacity-10">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-check2-square fs-1 text-warning mb-2"></i>
                        <h6 class="fw-bold">Create ClickUp / Plane Task</h6>
                        <p class="text-muted small">Push a work package, drawing review, or NCR ticket directly into your workspace list.</p>
                        <button class="btn btn-warning text-dark btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalCreateClickupTask">
                            <i class="bi bi-plus-circle me-1"></i> New Task Card
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 5: WEBHOOKS & REST API ==================== -->
    <div class="tab-pane fade" id="tab-webhooks" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Inbound Webhook Listeners</span>
                    </div>
                    <div class="card-body small">
                        <p class="text-muted">Configure external systems (Jira Automation, Slack Event Subscriptions, Microsoft Teams) to notify this ERP:</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jira Webhook Endpoint:</label>
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" value="<?= base_url('api/webhooks/jira') ?>" readonly id="jiraWebhookUrl">
                                <button class="btn btn-outline-secondary" onclick="copyToClipboard('jiraWebhookUrl')"><i class="bi bi-clipboard"></i></button>
                            </div>
                            <div class="form-text">In Jira Settings &rarr; System &rarr; Webhooks, point to this URL for events: <code>jira:issue_created</code>, <code>jira:issue_updated</code>.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slack Events Endpoint:</label>
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" value="<?= base_url('api/webhooks/slack') ?>" readonly id="slackWebhookUrl">
                                <button class="btn btn-outline-secondary" onclick="copyToClipboard('slackWebhookUrl')"><i class="bi bi-clipboard"></i></button>
                            </div>
                            <div class="form-text">Responds to Slack URL Verification challenges and captures interactivity events.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <span class="fw-bold"><i class="bi bi-box-arrow-up-right me-2 text-info"></i>Outgoing Generic Webhook Pipeline</span>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('integrations/update-webhooks') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="webhooks_enabled" name="is_enabled" value="1" <?= !empty($webhooks['is_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="webhooks_enabled">Enable Outbound Webhook Streaming</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Destination Webhook URL</label>
                                <input type="url" name="outgoing_url" class="form-control font-monospace" placeholder="https://api.yourcompany.com/erp-events" value="<?= esc($webhooks['config']['outgoing_url'] ?? '') ?>">
                                <div class="form-text">Receives real-time JSON payloads for all ERP operations.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Signing Secret Key</label>
                                <input type="text" name="secret" class="form-control font-monospace" placeholder="secret_key_for_hmac_sha256" value="<?= esc($webhooks['config']['secret'] ?? '') ?>">
                                <div class="form-text">Used to verify payload integrity via <code>X-Steel-Signature</code> header.</div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Save Webhook Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 4: DELIVERY & EVENT LOGS ==================== -->
    <div class="tab-pane fade" id="tab-logs" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Integration Transmission &amp; Audit Logs</span>
                <div>
                    <span class="badge bg-light text-dark border">Recent 20 Records</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 datatable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Timestamp</th>
                            <th>Service</th>
                            <th>Direction</th>
                            <th>Event Type</th>
                            <th>HTTP Code</th>
                            <th>Status</th>
                            <th>Payload &amp; Response</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No integration events recorded yet. Try testing a connection or creating a ticket.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><code class="text-muted">#<?= $log['id'] ?></code></td>
                                    <td><small><?= date('d M Y, H:i:s', strtotime($log['created_at'])) ?></small></td>
                                    <td>
                                        <?php if ($log['service'] === 'jira'): ?>
                                            <span class="badge bg-primary"><i class="bi bi-kanban me-1"></i>Jira</span>
                                        <?php elseif ($log['service'] === 'slack'): ?>
                                            <span class="badge bg-success"><i class="bi bi-slack me-1"></i>Slack</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="bi bi-hdd-network me-1"></i>Webhook</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $log['direction'] === 'outbound' ? 'bg-light text-dark border' : 'bg-info text-dark' ?>">
                                            <?= strtoupper($log['direction']) ?>
                                        </span>
                                    </td>
                                    <td><strong><?= esc($log['event_type']) ?></strong></td>
                                    <td><span class="badge <?= ($log['response_code'] >= 200 && $log['response_code'] < 300) ? 'bg-success' : 'bg-danger' ?>"><?= $log['response_code'] ?: '0' ?></span></td>
                                    <td>
                                        <span class="badge <?= $log['status'] === 'success' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= ucfirst($log['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;" onclick='viewLogPayload(<?= json_encode($log) ?>)'>
                                            <i class="bi bi-eye"></i> View JSON
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL: CREATE JIRA ISSUE ==================== -->
<div class="modal fade" id="modalCreateJira" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-kanban me-2"></i>Create Atlassian Jira Issue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('integrations/create-jira-issue') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Issue Type</label>
                            <select name="issue_type" class="form-select">
                                <option value="Task">Task</option>
                                <option value="Bug">Bug</option>
                                <option value="Quality NCR">Quality NCR (Non-Conformance)</option>
                                <option value="Breakdown">Shop Floor Breakdown</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="High">High</option>
                                <option value="Highest">Highest / Critical</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Related Job Order (Optional)</label>
                            <select class="form-select" onchange="autofillJobToJira(this)">
                                <option value="">-- Select Job Order --</option>
                                <?php foreach ($recentJobs as $job): ?>
                                    <option value="<?= esc($job['job_no']) ?>" data-title="<?= esc($job['title']) ?>" data-client="<?= esc($job['client_name'] ?? '') ?>" data-tonnage="<?= esc($job['total_tonnage']) ?>">
                                        <?= esc($job['job_no']) ?> (<?= esc($job['client_name'] ?? '') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold required">Issue Summary / Title</label>
                            <input type="text" name="summary" id="jiraIssueSummary" class="form-control" placeholder="e.g. SA 516 Gr 70 Weld Joint Root Crack on ESP Duct Panel" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold required">Detailed Technical Description</label>
                            <textarea name="description" id="jiraIssueDescription" class="form-control font-monospace" rows="4" placeholder="Specify Joint ID, Plate Heat No, NDT findings (UT/RT/DPT), Root cause, and required corrective action." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Labels (Comma Separated)</label>
                            <input type="text" name="labels" class="form-control" placeholder="heavy-steel, welding, boiler-quality, tpi-punchpoint" value="heavy-steel, fabrication, qc-ncr">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle me-1"></i> Submit to Jira</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL: BROADCAST SLACK MESSAGE ==================== -->
<div class="modal fade" id="modalBroadcastSlack" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-slack me-2"></i>Broadcast Slack Announcement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('integrations/send-slack-message') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Target Channel</label>
                        <select name="channel" class="form-select font-monospace">
                            <option value="<?= esc($slack['config']['default_channel'] ?? '#shopfloor-alerts') ?>"><?= esc($slack['config']['default_channel'] ?? '#shopfloor-alerts') ?> (General)</option>
                            <option value="<?= esc($slack['config']['dispatch_channel'] ?? '#dispatch-alerts') ?>"><?= esc($slack['config']['dispatch_channel'] ?? '#dispatch-alerts') ?> (Dispatches)</option>
                            <option value="<?= esc($slack['config']['qc_channel'] ?? '#qc-ncr-alerts') ?>"><?= esc($slack['config']['qc_channel'] ?? '#qc-ncr-alerts') ?> (Quality NCR)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold required">Message Text</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="e.g. All Bay 1 and Bay 2 welders: Client TPI inspection by SGS scheduled tomorrow 10:00 AM for NTPC ESP Casings. Ensure DP test certificates are ready." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-send me-1"></i> Send to Slack</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL: TEST EMAIL ==================== -->
<div class="modal fade" id="modalTestEmail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-send-check me-2"></i>Send Test Verification Email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3">Send a formatted test email to verify your SMTP server connection, STARTTLS encryption, and authentication credentials.</p>
                <div class="mb-3">
                    <label class="form-label small fw-bold required">Recipient Email Address</label>
                    <input type="email" class="form-control" id="testEmailRecipient" placeholder="e.g. yourname@heavysteel.in" value="<?= esc($email['config']['from_email'] ?? 'admin@heavysteel.in') ?>" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white btn-sm" id="btnSubmitTestEmail" onclick="sendTestEmailAction()">
                    <i class="bi bi-send-check me-1"></i> Send Test
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAL: COMPOSE MANUAL EMAIL ==================== -->
<div class="modal fade" id="modalComposeEmail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-envelope-plus me-2"></i>Compose Email Notification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('integrations/send-email') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold required">Recipient Email</label>
                            <input type="email" name="recipient" class="form-control" placeholder="client.procurement@ntpc.co.in" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold required">Email Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Fabrication Progress Update - Stage IV ESP Casings" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold required">Message Text</label>
                            <textarea name="message" class="form-control font-monospace" rows="5" placeholder="Dear Sir/Madam,&#10;&#10;Please be informed that Phase 2 welding DP testing has been cleared by TPI. Material is being transferred to surface preparation bay for grit blasting to SA 2.5." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white btn-sm"><i class="bi bi-send me-1"></i> Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL: CREATE CLICKUP / PLANE TASK ==================== -->
<div class="modal fade" id="modalCreateClickupTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="bi bi-check2-square me-2"></i>Create Task in ClickUp / Plane</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('integrations/create-clickup-task') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Task Priority</label>
                            <select name="priority" class="form-select font-monospace">
                                <option value="1">1 - Urgent (Defect NCR / Breakdown)</option>
                                <option value="2" selected>2 - High (Drawing &amp; Cutting Plan)</option>
                                <option value="3">3 - Normal (Fit-Up &amp; SAW Welding)</option>
                                <option value="4">4 - Low (Surface Preparation)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Link to Fabrication Job Order</label>
                            <select class="form-select" onchange="autofillJobToClickup(this)">
                                <option value="">-- Select Active Job Order --</option>
                                <?php foreach ($recentJobs as $job): ?>
                                    <option value="<?= esc($job['job_no']) ?>" data-title="<?= esc($job['title']) ?>" data-client="<?= esc($job['client_name'] ?? '') ?>" data-tonnage="<?= esc($job['total_tonnage']) ?>" data-dwg="<?= esc($job['drawing_no']) ?>">
                                        <?= esc($job['job_no']) ?> (<?= esc($job['client_name'] ?? '') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold required">Task Title / Name</label>
                            <input type="text" name="name" id="clickupTaskName" class="form-control" placeholder="e.g. [JOB-2627-001] Ultrasonic Testing &amp; Radiography Review for ESP Casings" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold required">Task Markdown Description</label>
                            <textarea name="description" id="clickupTaskDesc" class="form-control font-monospace" rows="4" placeholder="Milestone checklist, plate specifications (IS 2062 / SA 516 Gr 70), Bay assignment, or NDT defect observations." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Tags (Comma Separated)</label>
                            <input type="text" name="tags" id="clickupTaskTags" class="form-control font-monospace" placeholder="shopfloor, job-order, ntpc, bay-1, saw-welding" value="shopfloor, fabrication">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark btn-sm fw-bold"><i class="bi bi-plus-circle me-1"></i> Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL: VIEW JSON LOG PAYLOAD ==================== -->
<div class="modal fade" id="modalViewLogPayload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-code-square me-2"></i>Event Payload &amp; Response</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold">Request Payload:</h6>
                <pre id="logPayloadDisplay" class="bg-light p-3 rounded font-monospace small" style="max-height: 250px; overflow-y: auto;"></pre>
                
                <h6 class="fw-bold mt-3">Response Body:</h6>
                <pre id="logResponseDisplay" class="bg-light p-3 rounded font-monospace small" style="max-height: 200px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function testJiraConnection() {
    const btn = event.target;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Testing...';
    btn.disabled = true;

    fetch('<?= base_url('integrations/test-jira') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Test Connection';
        btn.disabled = false;
        if (data.success) {
            alert('✅ Jira Success: ' + data.message);
        } else {
            alert('❌ Jira Error: ' + data.message);
        }
    })
    .catch(err => {
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Test Connection';
        btn.disabled = false;
        alert('Server request failed: ' + err);
    });
}

function testSlackConnection() {
    const btn = event.target;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';
    btn.disabled = true;

    fetch('<?= base_url('integrations/test-slack') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="bi bi-bell me-1"></i> Send Test Alert';
        btn.disabled = false;
        if (data.success) {
            alert('✅ Slack Success: ' + data.message);
        } else {
            alert('❌ Slack Error: ' + data.message);
        }
    })
    .catch(err => {
        btn.innerHTML = '<i class="bi bi-bell me-1"></i> Send Test Alert';
        btn.disabled = false;
        alert('Server request failed: ' + err);
    });
}

function openTestEmailModal() {
    new bootstrap.Modal(document.getElementById('modalTestEmail')).show();
}

function sendTestEmailAction() {
    const recipient = document.getElementById('testEmailRecipient').value.trim();
    if (!recipient) {
        alert('Please enter a recipient email address.');
        return;
    }
    const btn = document.getElementById('btnSubmitTestEmail');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';
    btn.disabled = true;

    const fd = new FormData();
    fd.append('recipient', recipient);

    fetch('<?= base_url('integrations/test-email') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Send Test';
        btn.disabled = false;
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalTestEmail'));
        if (modal) modal.hide();
        if (data.success) {
            alert('✅ Email Success: ' + data.message);
        } else {
            alert('❌ Email Failed: ' + data.message);
        }
    })
    .catch(err => {
        btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Send Test';
        btn.disabled = false;
        alert('Server request failed: ' + err);
    });
}

function autofillJobToJira(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt.value) return;
    const jobNo = opt.value;
    const title = opt.getAttribute('data-title');
    const client = opt.getAttribute('data-client');
    const tonnage = opt.getAttribute('data-tonnage');

    document.getElementById('jiraIssueSummary').value = `[${jobNo}] Quality Inspection / Action Required: ${title}`;
    document.getElementById('jiraIssueDescription').value = `Job Order Reference: ${jobNo}\nClient / Plant: ${client}\nTonnage: ${tonnage} MT\nAssembly: ${title}\n\nTechnical Notes / Action Item:\n`;
}

function testClickupConnection() {
    const btn = event.target;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Testing...';
    btn.disabled = true;

    fetch('<?= base_url('integrations/test-clickup') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Test Connection';
        btn.disabled = false;
        if (data.success) {
            alert('✅ ClickUp/Plane Success: ' + data.message);
        } else {
            alert('❌ ClickUp/Plane Failed: ' + data.message);
        }
    })
    .catch(err => {
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Test Connection';
        btn.disabled = false;
        alert('Server request failed: ' + err);
    });
}

function autofillJobToClickup(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt.value) return;
    const jobNo = opt.value;
    const title = opt.getAttribute('data-title');
    const client = opt.getAttribute('data-client');
    const tonnage = opt.getAttribute('data-tonnage');
    const dwg = opt.getAttribute('data-dwg') || 'DWG-STD';

    document.getElementById('clickupTaskName').value = `[${jobNo}] Fabrication Traveler: ${title}`;
    document.getElementById('clickupTaskDesc').value = `### Job Order: ${jobNo}\n- **Client / Plant:** ${client}\n- **Assembly:** ${title}\n- **Allocated Steel:** ${tonnage} MT\n- **Drawing No:** ${dwg}\n\n### Checklist:\n- [ ] Material requisition from Plate Yard\n- [ ] CNC Oxy-Fuel profile cutting\n- [ ] Sub-assembly fit-up & root run\n- [ ] SAW full-penetration welding\n- [ ] 100% NDT inspection\n- [ ] Blasting & painting`;
    document.getElementById('clickupTaskTags').value = `shopfloor, job-order, fabrication, ${jobNo.toLowerCase()}`;
}

function copyToClipboard(id) {
    const copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert('Copied URL to clipboard: ' + copyText.value);
}

function viewLogPayload(log) {
    let payloadStr = '';
    try {
        const p = typeof log.payload === 'string' ? JSON.parse(log.payload) : log.payload;
        payloadStr = JSON.stringify(p, null, 2);
    } catch(e) {
        payloadStr = log.payload;
    }

    document.getElementById('logPayloadDisplay').textContent = payloadStr || '(Empty payload)';
    document.getElementById('logResponseDisplay').textContent = log.response_body || '(No response body)';
    new bootstrap.Modal(document.getElementById('modalViewLogPayload')).show();
}

// Preserve active tab on page refresh
document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash;
    if (hash) {
        const triggerEl = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (triggerEl) {
            new bootstrap.Tab(triggerEl).show();
        }
    }
});
</script>
