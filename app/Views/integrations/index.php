<div class="row mb-3 align-items-center">
    <div class="col-md-8">
        <h4 class="mb-0"><i class="bi bi-diagram-3 me-2 text-primary"></i>Ticketing & Collaboration Integrations</h4>
        <small class="text-muted">Connect your Heavy Fabrication ERP with Atlassian Jira, Slack channels, and automated webhook pipelines</small>
    </div>
    <div class="col-md-4 text-md-end mt-2 mt-md-0">
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
        <button class="nav-link" id="tab-webhooks-btn" data-bs-toggle="pill" data-bs-target="#tab-webhooks" type="button" role="tab">
            <i class="bi bi-hdd-network me-2"></i>Webhooks & API
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-logs-btn" data-bs-toggle="pill" data-bs-target="#tab-logs" type="button" role="tab">
            <i class="bi bi-clock-history me-2"></i>Delivery & Event Logs
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

    <!-- ==================== TAB 3: WEBHOOKS & REST API ==================== -->
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
