<?= $this->extend('layouts/organization-base') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
        <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-bar-chart-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Total</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['total']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-hourglass-split"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Pending</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['pending']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Approved</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['approved']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-hand-thumbs-down-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Rejected</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['rejected']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title flex-grow-1">Incoming Travel Orders</h3>
                </div>
                <div class="card-body table-responsive">
                    <table id="incoming_travel_orders_table"
                        class="table table-bordered table-striped datatable-standard"
                        data-last-column-width="100"
                        data-page-length="10"
                        data-order='[[0,"desc"]]'
                        data-url="<?= base_url('incoming-travel-orders/data') ?>">
                        <thead>
                            <tr>
                                <th data-name="created_at">Date Submitted</th>
                                <th data-name="travel_order_number">Travel Order<br>Number</th>
                                <th data-name="destination">Destination</th>
                                <th data-name="travel_dates">Travel Dates</th>
                                <th data-name="status" data-orderable="false">Status</th>
                                <th data-name="actions" data-orderable="false" data-searchable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th data-name="created_at">Date Submitted</th>
                                <th data-name="travel_order_number">Travel Order<br>Number</th>
                                <th data-name="destination">Destination</th>
                                <th data-name="travel_dates">Travel Dates</th>
                                <th data-name="status" data-orderable="false">Status</th>
                                <th data-orderable="false" data-searchable="false">Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Review Travel Order Modal ──────────────────────────── -->
<div class="modal fade" id="modal-review-travel-order" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header py-2">
                <div>
                    <h5 class="modal-title mb-0">Review Travel Order</h5>
                    <small id="rv-modal-number" class="text-muted fw-semibold"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Loading -->
            <div id="rv-state-loading" class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-muted mb-0">Loading details...</p>
            </div>

            <!-- Error -->
            <div id="rv-state-error" class="modal-body text-center py-5 d-none">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i>
                <p class="mt-3 text-danger mb-0" id="rv-error-msg">Failed to load details.</p>
            </div>

            <!-- Content -->
            <div id="rv-state-content" class="d-none">
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- ══ LEFT COL ══════════════════════════════════════ -->
                        <div class="col-12 col-lg-8 ">

                            <!-- Travel Order Details card -->
                            <div class="border rounded p-3 h-100 d-flex flex-column">

                                <!-- Personnel -->
                                <p class="mb-1 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Personnel</p>
                                <div class="row g-2 mb-3">
                                    <div class="col-5">
                                        <p class="mb-1 small text-muted">Name/s</p>
                                        <div id="rv-persons-names" class="d-flex flex-column gap-0"></div>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-1 small text-muted">Position/s</p>
                                        <div id="rv-persons-positions" class="d-flex flex-column gap-0"></div>
                                    </div>
                                    <div class="col-3">
                                        <p class="mb-1 small text-muted">Salary Grade/s</p>
                                        <div id="rv-persons-grades" class="d-flex flex-column gap-0"></div>
                                    </div>
                                </div>

                                <!-- Office Station -->
                                <div class="mb-3">
                                    <p class="mb-0 small text-muted">Office Station</p>
                                    <p class="mb-0 fw-semibold" id="rv-doc-office"></p>
                                </div>

                                <!-- Travel Details -->
                                <p class="mb-1 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Travel Details</p>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <p class="mb-0 small text-muted">Destination</p>
                                        <p class="mb-0 fw-semibold" id="rv-doc-destination"></p>
                                    </div>
                                    <div class="col-3">
                                        <p class="mb-0 small text-muted">Departure</p>
                                        <p class="mb-0 fw-semibold" id="rv-doc-departure"></p>
                                    </div>
                                    <div class="col-3">
                                        <p class="mb-0 small text-muted">Return</p>
                                        <p class="mb-0 fw-semibold" id="rv-doc-arrival"></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p class="mb-0 small text-muted">Purpose of Travel</p>
                                    <p class="mb-0" id="rv-doc-purpose"></p>
                                </div>

                                <!-- Signatories -->
                                <div class="row g-2 pt-3 border-top mt-auto">
                                    <div class="col-6 text-center">
                                        <p class="mb-0 small text-muted">Recommended by</p>
                                        <p class="mb-0 fw-semibold" id="rv-sig-division"></p>
                                        <p class="mb-0 small text-muted" id="rv-sig-division-position"></p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <p class="mb-0 small text-muted">Approved by</p>
                                        <p class="mb-0 fw-semibold" id="rv-sig-penro"></p>
                                        <p class="mb-0 small text-muted" id="rv-sig-penro-position"></p>
                                    </div>
                                </div>
                            </div><!-- /.border.rounded -->


                        </div>
                        <!-- END LEFT COL -->
                        <!-- ══ RIGHT COL ═════════════════════════════════════ -->
                        <div class="col-12 col-lg-4 d-flex flex-column gap-3">



                            <!-- Current Status -->
                            <div class="border rounded p-3">
                                <p class="mb-2 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Current Status</p>
                                <div class="d-flex align-items-center gap-2">
                                    <i id="rv-status-icon" class="bi fs-5"></i>
                                    <span id="rv-status-badge" class="badge fs-5 px-3 py-2"></span>
                                </div>
                            </div>



                            <!-- Approval Progress -->
                            <div class="border rounded p-3">
                                <p class="mb-3 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Approval Progress</p>
                                <div id="rv-approval-steps"></div>
                            </div>

                            <!-- ── Approval Action card ──────────────────────── -->
                            <div class="border rounded p-3">
                                <p class="mb-3 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Approval Action</p>

                                <div class="mb-3">
                                    <label for="rv-remarks" class="form-label">
                                        Remarks <span class="text-muted small">(optional)</span>
                                    </label>
                                    <textarea id="rv-remarks" class="form-control" rows="3"
                                        placeholder="Add a remark for the applicant…"></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success flex-fill" id="rv-btn-approve">
                                        <i class="bi bi-check-lg me-1"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger flex-fill" id="rv-btn-reject">
                                        <i class="bi bi-x-lg me-1"></i> Reject
                                    </button>
                                </div>

                                <div id="rv-decision-feedback" class="mt-3 d-none"></div>
                            </div>

                            <!-- Application Details / Timeline -->
                            <div class="border rounded p-3">
                                <p class="mb-3 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Application Details</p>
                                <div id="rv-timeline" class="position-relative ps-3"></div>
                            </div>

                            <!-- Supporting Documents -->
                            <div class="border rounded p-3">
                                <p class="mb-2 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Supporting Documents</p>
                                <p id="rv-no-attachments" class="text-muted fst-italic small d-none mb-0">
                                    No attachments uploaded.
                                </p>
                                <div id="rv-attachments-list" class="d-flex flex-column gap-2"></div>
                            </div>

                        </div>
                        <!-- END RIGHT COL -->

                    </div><!-- /.row -->
                </div><!-- /.modal-body -->

                <div class="modal-footer justify-content-between py-2">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>
                    <a href="javascript:void(0)" class="btn btn-secondary btn-sm" id="rv-btn-print">
                        <i class="bi bi-printer me-1"></i> Print
                    </a>
                    <iframe id="rv-print-frame" style="display:none;"></iframe>
                </div>
            </div><!-- /#rv-state-content -->

        </div>
    </div>
</div>
<!-- END :: Review Travel Order Modal -->

<style>
    .to-attachment-row {
        cursor: pointer;
        transition: background-color .15s ease, border-color .15s ease;
        text-decoration: none;
        color: inherit;
    }

    .to-attachment-row:hover {
        background-color: var(--bs-primary-bg-subtle, #cfe2ff) !important;
        border-color: var(--bs-primary-border-subtle, #9ec5fe) !important;
    }

    .to-attachment-row:hover .rv-dl-icon {
        color: var(--bs-primary) !important;
    }
</style>

<!-- BEGIN :: Review Travel Order Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var ATTACHMENT_ICONS = {
            'request_memo': 'bi-file-text',
            'special_order': 'bi-file-ruled',
            'request_letter': 'bi-envelope',
            'invitation_letter': 'bi-envelope-open',
            'training_notification': 'bi-mortarboard',
            'meeting_notice': 'bi-calendar-event',
            'conference_program': 'bi-journal-text',
            'other_document': 'bi-paperclip',
        };
        var ATTACHMENT_LABELS = {
            'request_memo': 'Request Memo',
            'special_order': 'Special Order',
            'request_letter': 'Request Letter',
            'invitation_letter': 'Invitation Letter',
            'training_notification': 'Training Notification',
            'meeting_notice': 'Meeting Notice',
            'conference_program': 'Conference Program',
            'other_document': 'Other Document',
        };

        // ── helpers ─────────────────────────────────────────────────────────────
        function fmtDate(str) {
            if (!str || str === '0000-00-00') return '—';
            return new Date(str + 'T00:00:00')
                .toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
        }

        function fmtDateTime(str) {
            if (!str) return '—';
            var d = new Date(str.replace(' ', 'T'));
            if (isNaN(d)) return '—';
            return d.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                }) +
                ', ' + d.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
        }

        function setText(id, val) {
            var el = document.getElementById(id);
            if (el) el.textContent = val || '—';
        }

        function getStatusConfig(status) {
            if (!status) return {
                badge: 'bg-secondary text-white',
                icon: 'bi-question-circle text-muted'
            };
            var s = status.toLowerCase();
            if (s.startsWith('forwarded to'))
                return {
                    badge: 'bg-info-subtle text-info-emphasis',
                    icon: 'bi-arrow-right-circle-fill text-info'
                };
            if (s.startsWith('rejected by'))
                return {
                    badge: 'bg-danger-subtle text-danger-emphasis',
                    icon: 'bi-x-circle-fill text-danger'
                };
            if (s.startsWith('approved by'))
                return {
                    badge: 'bg-success-subtle text-success-emphasis',
                    icon: 'bi-check-circle-fill text-success'
                };
            if (s === 'pending')
                return {
                    badge: 'bg-warning-subtle text-warning-emphasis',
                    icon: 'bi-clock-history text-warning'
                };
            return {
                badge: 'bg-secondary-subtle text-white-emphasis',
                icon: 'bi-question-circle text-muted'
            };
        }

        // ── state machine ────────────────────────────────────────────────────────
        var bsModal = new bootstrap.Modal(document.getElementById('modal-review-travel-order'));
        var _currentTOId = null;

        function showState(state) {
            ['loading', 'error', 'content'].forEach(function(s) {
                document.getElementById('rv-state-' + s).classList.toggle('d-none', s !== state);
            });
        }

        // ── approval steps ───────────────────────────────────────────────────────
        function buildApprovalSteps(d) {
            var el = document.getElementById('rv-approval-steps');
            el.innerHTML = '';
            var steps = [];
            if (d.unit_id) steps.push({
                position: d.unit_head_position || 'Unit Supervisor',
                approvedBy: d.unit_head_name,
                remarks: d.supervisor_remarks,
                status: d.unit_status || 'pending'
            });
            if (d.division_id) steps.push({
                position: d.division_head_position || 'Division Head',
                approvedBy: d.division_head_name,
                remarks: d.division_head_remarks,
                status: d.division_status || 'pending'
            });
            if (d.organization_id) steps.push({
                position: d.organization_head_position || 'PENRO Officer',
                approvedBy: d.organization_head_name,
                remarks: d.organization_head_remarks,
                status: d.organization_status || 'pending'
            });

            if (!steps.length) {
                el.innerHTML = '<p class="text-muted fst-italic small mb-0">No approval levels assigned.</p>';
                return;
            }

            steps.forEach(function(step, idx) {
                var iconCls, badgeCls, badgeTxt;
                if (step.status === 'approved') {
                    iconCls = 'bi-check2-all text-success';
                    badgeCls = 'bg-success-subtle text-success-emphasis';
                    badgeTxt = 'Approved';
                } else if (step.status === 'rejected') {
                    iconCls = 'bi-x-circle-fill text-danger';
                    badgeCls = 'bg-danger-subtle text-danger-emphasis';
                    badgeTxt = 'Rejected';
                } else {
                    iconCls = 'bi-clock text-muted';
                    badgeCls = 'bg-secondary-subtle text-secondary-emphasis';
                    badgeTxt = 'Pending';
                }
                var mb = (idx < steps.length - 1) ? 'mb-3' : '';
                el.insertAdjacentHTML('beforeend',
                    '<div class="d-flex align-items-start gap-2 ' + mb + '">' +
                    '<div class="flex-shrink-0 mt-1"><i class="bi ' + iconCls + ' fs-5"></i></div>' +
                    '<div class="flex-grow-1">' +
                    '<p class="mb-0 fw-semibold small">' + step.position + '</p>' +
                    (step.approvedBy ? '<p class="mb-0 small text-muted">' + step.approvedBy + '</p>' : '<p class="mb-0 small text-muted fst-italic">Unassigned</p>') +
                    (step.remarks ? '<p class="mb-0 small fst-italic text-muted">"' + step.remarks + '"</p>' : '') +
                    '</div>' +
                    '<span class="badge ' + badgeCls + ' small">' + badgeTxt + '</span>' +
                    '</div>');
            });
        }

        // ── timeline ─────────────────────────────────────────────────────────────
        function buildTimeline(d) {
            var el = document.getElementById('rv-timeline');
            el.innerHTML = '';

            function addItem(iconCls, colorCls, title, sub) {
                var div = document.createElement('div');
                div.className = 'mb-3 position-relative';
                div.style.cssText = 'padding-left:1.8rem';
                div.innerHTML =
                    '<i class="bi ' + iconCls + ' ' + colorCls + '" style="position:absolute;left:0;top:4px;font-size:16px"></i>' +
                    '<p class="mb-0 small fw-semibold" style="line-height:1.3">' + title + '</p>' +
                    '<p class="mb-0 small text-muted" style="font-size:11px">' + sub + '</p>';
                el.appendChild(div);
            }
            addItem('bi-file-earmark-text-fill', 'text-primary', 'Travel Order Submitted',
                (d.applicant_name || 'Applicant') + ' — ' + (d.applicant_position || '') + '<br>' + fmtDateTime(d.created_at));
            if (d.unit_head_name)
                addItem(d.unit_status === 'rejected' ? 'bi-x-fill' : 'bi-check-lg',
                    d.unit_status === 'rejected' ? 'text-danger' : 'text-success',
                    (d.unit_status === 'rejected' ? 'Rejected' : 'Reviewed') + ' by Supervisor',
                    d.unit_head_name + (d.supervisor_remarks ? ' • "' + d.supervisor_remarks + '"' : ''));
            if (d.division_head_name)
                addItem(d.division_status === 'rejected' ? 'bi-x-fill' : 'bi-check-lg',
                    d.division_status === 'rejected' ? 'text-danger' : 'text-success',
                    (d.division_status === 'rejected' ? 'Rejected' : 'Reviewed') + ' by Division Head',
                    d.division_head_name + (d.division_head_remarks ? ' • "' + d.division_head_remarks + '"' : ''));
            if (d.organization_head_name)
                addItem(d.organization_status === 'rejected' ? 'bi-x-fill' : 'bi-check-lg',
                    d.organization_status === 'rejected' ? 'text-danger' : 'text-success',
                    (d.organization_status === 'rejected' ? 'Rejected' : 'Approved') + ' by PENRO',
                    d.organization_head_name + (d.organization_head_remarks ? ' • "' + d.organization_head_remarks + '"' : ''));
        }

        // ── populate ─────────────────────────────────────────────────────────────
        function populate(d) {
            _currentTOId = d.travel_order_id;

            setText('rv-modal-number', d.travel_order_number);
            setText('rv-doc-destination', d.destination);
            setText('rv-doc-departure', fmtDate(d.departure_date));
            setText('rv-doc-arrival', fmtDate(d.arrival_date));
            setText('rv-doc-purpose', d.purpose_of_travel);
            setText('rv-doc-office', d.organization_name || 'PENRO Leyte');
            setText('rv-sig-division', d.division_head_name || d.division_head_name || '___________________');
            setText('rv-sig-division-position', d.division_head_position || 'Division Chief');
            setText('rv-sig-penro', d.organization_head_name || d.organization_head_name || '___________________');
            setText('rv-sig-penro-position', d.organization_head_position || 'PENRO Officer');

            // Persons
            var names = document.getElementById('rv-persons-names');
            var positions = document.getElementById('rv-persons-positions');
            var grades = document.getElementById('rv-persons-grades');
            names.innerHTML = positions.innerHTML = grades.innerHTML = '';
            (d.persons || []).forEach(function(p) {
                function mkLine(v) {
                    var el = document.createElement('p');
                    el.className = 'mb-0 fw-semibold small';
                    el.textContent = v || '—';
                    return el;
                }
                names.appendChild(mkLine(p.name));
                positions.appendChild(mkLine(p.position));
                grades.appendChild(mkLine(p.salary_grade));
            });

            // Status badge
            var cfg = getStatusConfig(d.current_status);
            document.getElementById('rv-status-icon').className = 'bi ' + cfg.icon + ' fs-3';
            document.getElementById('rv-status-badge').className = 'badge px-3 py-2 fs-5 ' + cfg.badge;
            document.getElementById('rv-status-badge').textContent = d.current_status || '—';

            buildApprovalSteps(d);
            buildTimeline(d);

            // Attachments
            var list = document.getElementById('rv-attachments-list');
            var noAtch = document.getElementById('rv-no-attachments');
            list.innerHTML = '';
            if (d.attachments && d.attachments.length) {
                noAtch.classList.add('d-none');
                d.attachments.forEach(function(a) {
                    var lbl = ATTACHMENT_LABELS[a.attachment_type] || a.attachment_type || 'Document';
                    var icon = ATTACHMENT_ICONS[a.attachment_type] || 'bi-file-earmark';
                    var name = a.attachment_name || lbl;
                    var href = '<?= site_url('dashboard/travel-orders/attachment/download') ?>/' + a.file_id;
                    var row = document.createElement('a');
                    row.href = href;
                    row.download = name;
                    row.className = 'to-attachment-row d-flex align-items-center gap-2 p-2 rounded border';
                    row.innerHTML =
                        '<i class="bi ' + icon + ' text-primary flex-shrink-0 fs-5"></i>' +
                        '<div class="flex-grow-1 overflow-hidden">' +
                        '<p class="mb-0 small fw-semibold text-truncate">' + name + '</p>' +
                        '<p class="mb-0 text-muted text-truncate" style="font-size:11px">' + lbl + '</p>' +
                        '</div>' +
                        '<i class="bi bi-download rv-dl-icon text-muted flex-shrink-0"></i>';
                    list.appendChild(row);
                });
            } else {
                noAtch.classList.remove('d-none');
            }

            // Reset decision area
            document.getElementById('rv-remarks').value = '';
            var fb = document.getElementById('rv-decision-feedback');
            fb.className = 'mt-3 d-none';
            fb.innerHTML = '';
            document.getElementById('rv-btn-approve').disabled = false;
            document.getElementById('rv-btn-reject').disabled = false;

            // Print frame
            var iframe = document.getElementById('rv-print-frame');
            iframe.src = '<?= base_url('dashboard/travel-orders/print/') ?>'+ travel_order_id;
        }

        // ── open modal ───────────────────────────────────────────────────────────
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-view-travel-order');
            if (!btn) return;
            var id = btn.getAttribute('data-id');
            if (!id) return;
            showState('loading');
            bsModal.show();
            fetch('<?= site_url('dashboard/travel-orders/details') ?>/' + id, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(r) {
                    if (!r.ok) throw new Error('Server ' + r.status);
                    return r.json();
                })
                .then(function(j) {
                    if (!j.success) throw new Error(j.message || 'Error');
                    populate(j.data);
                    showState('content');
                })
                .catch(function(err) {
                    document.getElementById('rv-error-msg').textContent = err.message || 'Failed to load.';
                    showState('error');
                });
        });

        // ── decision helper ──────────────────────────────────────────────────────
        function submitDecision(action) {
            if (!_currentTOId) return;
            var remarks = document.getElementById('rv-remarks').value.trim();
            var approveBtn = document.getElementById('rv-btn-approve');
            var rejectBtn = document.getElementById('rv-btn-reject');
            var fb = document.getElementById('rv-decision-feedback');

            approveBtn.disabled = rejectBtn.disabled = true;
            approveBtn.innerHTML = action === 'approve' ?
                '<span class="spinner-border spinner-border-sm me-1"></span> Approving…' :
                '<i class="bi bi-check-lg me-1"></i> Approve';
            rejectBtn.innerHTML = action === 'reject' ?
                '<span class="spinner-border spinner-border-sm me-1"></span> Rejecting…' :
                '<i class="bi bi-x-lg me-1"></i> Reject';

            fetch('<?= site_url('incoming-travel-orders/review') ?>/' + _currentTOId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                            '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({
                        action: action,
                        remarks: remarks
                    })
                })
                .then(function(r) {
                    if (!r.ok) throw new Error('Server ' + r.status);
                    return r.json();
                })
                .then(function(j) {
                    approveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Approve';
                    rejectBtn.innerHTML = '<i class="bi bi-x-lg me-1"></i> Reject';

                    if (!j.success) throw new Error(j.message || 'Action failed.');

                    fb.className = 'mt-3 alert alert-' + (action === 'approve' ? 'success' : 'danger') + ' mb-0';
                    fb.innerHTML = '<i class="bi bi-' + (action === 'approve' ? 'check-circle' : 'x-circle') + ' me-1"></i>' + j.message;
                    fb.classList.remove('d-none');

                    // Keep buttons disabled — action is done
                    approveBtn.disabled = rejectBtn.disabled = true;

                    // Reload the datatable
                    var dt = $('#incoming_travel_orders_table').DataTable();
                    if (dt) dt.ajax.reload(null, false);
                })
                .catch(function(err) {
                    approveBtn.disabled = rejectBtn.disabled = false;
                    approveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Approve';
                    rejectBtn.innerHTML = '<i class="bi bi-x-lg me-1"></i> Reject';
                    fb.className = 'mt-3 alert alert-danger mb-0';
                    fb.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>' + (err.message || 'Something went wrong.');
                    fb.classList.remove('d-none');
                });
        }

        document.getElementById('rv-btn-approve').addEventListener('click', function() {
            submitDecision('approve');
        });
        document.getElementById('rv-btn-reject').addEventListener('click', function() {
            submitDecision('reject');
        });

        // ── print ────────────────────────────────────────────────────────────────
        var rvPrintReady = false;
        document.getElementById('rv-print-frame').addEventListener('load', function() {
            rvPrintReady = true;
        });
        document.getElementById('rv-btn-print').addEventListener('click', function() {
            var iframe = document.getElementById('rv-print-frame');
            if (!rvPrintReady) {
                var btn = this;
                var orig = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';
                var check = setInterval(function() {
                    if (rvPrintReady) {
                        clearInterval(check);
                        btn.innerHTML = orig;
                        btn.disabled = false;
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    }
                }, 100);
            } else {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        });

    });
</script>
<!-- END :: Review Travel Order Script -->

<?= $this->endSection() ?>