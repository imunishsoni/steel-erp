<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <h4 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Raise Formal Industrial Ticket / NCR</h4>
        <small class="text-muted">Generate a formal quality non-conformance, engineering RFI, or breakdown ticket with CAPA root-cause tracking</small>
    </div>
    <div class="col-md-5 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('tickets') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Tickets List
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <span class="fw-bold"><i class="bi bi-card-checklist me-2 text-primary"></i>Ticket Information &amp; Technical Scope</span>
            </div>
            <form action="<?= base_url('tickets/create') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body p-4">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger py-2 mb-4">
                            <ul class="mb-0 small">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Ticket Number</label>
                            <input type="text" name="ticket_no" class="form-control font-monospace bg-light" value="<?= esc($nextTicketNo) ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold required">Category / Issue Classification</label>
                            <select name="category" class="form-select" required>
                                <option value="quality_ncr">Quality NCR (Weld / Material / Paint Defect)</option>
                                <option value="drawing_rfi">Drawing RFI / Engineering Change Order</option>
                                <option value="machine_breakdown">Plant Machinery Breakdown (Press, Crane, CNC)</option>
                                <option value="safety_hazard">Safety &amp; HSE Incident / Near-Miss</option>
                                <option value="client_punch">Client Pre-Shipment Punch Point</option>
                                <option value="production_delay">Production Bottleneck / Bay Delay</option>
                                <option value="general">General Administrative Ticket</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold required">Priority Level</label>
                            <select name="priority" class="form-select font-monospace" required>
                                <option value="critical">🔴 Critical (Immediate Works Stoppage)</option>
                                <option value="urgent">🟠 Urgent (TPI / NDT Failure)</option>
                                <option value="high" selected>🟡 High (Schedule Risk)</option>
                                <option value="medium">🔵 Medium (Normal Priority)</option>
                                <option value="low">⚪ Low (Minor Observation)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Linked Job Order (Optional)</label>
                            <select name="job_id" class="form-select" id="newTicketJobSelect">
                                <option value="">-- No Direct Job Linked --</option>
                                <?php foreach ($jobs as $j): ?>
                                    <option value="<?= $j['id'] ?>"><?= esc($j['job_no']) ?> - <?= esc($j['title']) ?> (<?= esc($j['drawing_no']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Plant / Client Reference (Optional)</label>
                            <select name="client_id" class="form-select">
                                <option value="">-- Select Plant Client --</option>
                                <?php foreach ($clients as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= esc($c['company_name']) ?> (<?= esc($c['industry_type']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold required">Target Department</label>
                            <select name="department" class="form-select" required>
                                <option value="qa_qc">QA / QC Quality Assurance</option>
                                <option value="production_shopfloor">Shopfloor Fabrication Bays</option>
                                <option value="design_engineering">Design &amp; Engineering Office</option>
                                <option value="maintenance">Plant Machinery Maintenance</option>
                                <option value="safety_hse">Safety &amp; HSE Department</option>
                                <option value="dispatch_logistics">Logistics &amp; Despatch</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Assign Engineer / Lead</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">-- Assign Later --</option>
                                <?php foreach ($employees as $emp): ?>
                                    <option value="<?= $emp['id'] ?>"><?= esc($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= esc($emp['designation']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Target Resolution Date</label>
                            <input type="date" name="target_date" class="form-control font-monospace" value="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold required">Ticket Title / Subject</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. UT 100% Weld Inspection: Lack of Fusion on Joint WJ-04 for NTPC Casing" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold required">Detailed Technical Description &amp; Observations</label>
                            <textarea name="description" class="form-control font-monospace" rows="5" placeholder="Specify:&#10;1. Exact location / Joint ID / Plate Heat Number&#10;2. Testing method (UT / RT / DPT / MPI / Visual / DFT gauge)&#10;3. Non-conformance defect measurements&#10;4. Acceptance standard (ASME Sec VIII / AWS D1.1 / IS 2062)&#10;5. Immediate quarantine / containment action taken on shop floor." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Preliminary Root Cause Analysis (Optional)</label>
                            <textarea name="root_cause" class="form-control font-monospace" rows="3" placeholder="e.g. Operator travel speed too high on submerged arc welding carriage, causing inadequate sidewall tie-in."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Corrective &amp; Preventive Action (CAPA) Plan</label>
                            <textarea name="resolution_notes" class="form-control font-monospace" rows="3" placeholder="e.g. Gouge out defect area to parent metal, verify with DP, preheat to 150°C and reweld with low-hydrogen electrodes under QC supervision."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light p-3 d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('tickets') ?>" class="btn btn-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Submit &amp; Generate Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
