<?= $this->extend('layouts/supervisor-base') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title flex-grow-1">Processed Travel Orders</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="my_travel_orders_table"
                        class="table table-bordered table-striped datatable-standard"
                        data-last-column-width="100"
                        data-page-length="10"
                        data-order='[[0,"desc"]]'
                        data-url="<?= base_url('processed-travel-orders/data') ?>">
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
                        <tbody>
                        </tbody>
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

<!-- BEGIN :: modal view travel order -->

<!-- ── Travel Order Detail Modal ──────────────────────────────── -->
<div class="modal fade" id="modal-view-travel-order" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-height: 90vh;">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header py-2">
                <div>
                    <h5 class="modal-title mb-0">Travel Order Details</h5>
                    <small id="to-modal-number" class="text-muted fw-semibold"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Loading state -->
            <div id="to-state-loading" class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-muted mb-0">Loading details...</p>
            </div>

            <!-- Error state -->
            <div id="to-state-error" class="modal-body text-center py-5 d-none">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i>
                <p class="mt-3 text-danger mb-0" id="to-error-msg">Failed to load details.</p>
            </div>

            <!-- Content state -->
            <div id="to-state-content" class="d-none">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-8">
                            <div class="border rounded p-3 h-100 d-flex flex-column">

                                <!-- Persons -->
                                <p class="mb-1 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Personnel</p>
                                <div class="row g-2 mb-3">
                                    <div class="col-5">
                                        <p class="mb-1 small text-muted">Name/s</p>
                                        <div id="to-persons-names" class="d-flex flex-column gap-0"></div>
                                    </div>
                                    <div class="col-4">
                                        <p class="mb-1 small text-muted">Position/s</p>
                                        <div id="to-persons-positions" class="d-flex flex-column gap-0"></div>
                                    </div>
                                    <div class="col-3">
                                        <p class="mb-1 small text-muted">Salary Grade/s</p>
                                        <div id="to-persons-grades" class="d-flex flex-column gap-0"></div>
                                    </div>
                                </div>

                                <!-- Office Station -->
                                <div class="mb-3">
                                    <p class="mb-0 small text-muted">Office Station</p>
                                    <p class="mb-0 fw-semibold" id="to-doc-office"></p>
                                </div>

                                <!-- Travel Details -->
                                <p class="mb-1 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Travel Details</p>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <p class="mb-0 small text-muted">Destination</p>
                                        <p class="mb-0 fw-semibold" id="to-doc-destination"></p>
                                    </div>
                                    <div class="col-3">
                                        <p class="mb-0 small text-muted">Departure</p>
                                        <p class="mb-0 fw-semibold" id="to-doc-departure"></p>
                                    </div>
                                    <div class="col-3">
                                        <p class="mb-0 small text-muted">Return</p>
                                        <p class="mb-0 fw-semibold" id="to-doc-arrival"></p>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <p class="mb-0 small text-muted">Purpose of Travel</p>
                                    <p class="mb-0" id="to-doc-purpose"></p>
                                </div>

                                <!-- Approval Signatories -->
                                <div class="row g-2 pt-3 border-top mt-auto">
                                    <div class="col-6 text-center">
                                        <p class="mb-0 small text-muted">Recommended by</p>
                                        <p class="mb-0 fw-semibold" id="to-sig-division"></p>
                                        <p class="mb-0 small text-muted" id="to-sig-division-position"></p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <p class="mb-0 small text-muted">Approved by</p>
                                        <p class="mb-0 fw-semibold" id="to-sig-penro"></p>
                                        <p class="mb-0 small text-muted" id="to-sig-penro-position"></p>
                                    </div>
                                </div>

                            </div><!-- /.border.rounded -->
                        </div>
                        <!-- END LEFT COL -->

                        <!-- ═══════════════════════════════════════ -->
                        <!-- RIGHT COL — Tracking sidebar           -->
                        <!-- ═══════════════════════════════════════ -->
                        <div class="col-12 col-lg-4 d-flex flex-column gap-3">

                            <!-- Current Status -->
                            <div class="border rounded p-3">
                                <p class="mb-2 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Current Status</p>
                                <div id="to-status-banner" class="d-flex align-items-center gap-2">
                                    <i id="to-status-icon" class="bi fs-5"></i>
                                    <span id="to-status-badge" class="badge fs-5 px-3 py-2"></span>
                                </div>
                            </div>

                            <!-- Approval Steps -->
                            <div class="border rounded p-3">
                                <p class="mb-3 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Approval Progress</p>
                                <div id="to-approval-steps">
                                    <!-- JS-rendered approval steps -->
                                </div>
                            </div>
                            <!-- Tracking History / Timeline -->
                            <div class="border rounded p-3">
                                <p class="mb-3 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Application Details</p>
                                <div id="to-timeline" class="position-relative ps-3">
                                    <!-- JS-rendered timeline items -->
                                </div>
                            </div>

                            <!-- Supporting Documents -->
                            <div class="border rounded p-3">
                                <p class="mb-2 small text-muted text-uppercase fw-semibold"
                                    style="letter-spacing:.05em">Supporting Documents</p>
                                <p id="to-no-attachments" class="text-muted fst-italic small d-none mb-0">
                                    No attachments uploaded.
                                </p>
                                <div id="to-attachments-list" class="d-flex flex-column gap-2"></div>
                            </div>

                        </div>
                        <!-- END RIGHT COL -->

                    </div><!-- /.row -->
                </div><!-- /.modal-body -->

                <div class="modal-footer justify-content-between py-2">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>

                    <a href="javascript:void(0)" class="btn btn-secondary btn-sm" id="to-btn-print">
                        <i class="bi bi-printer me-1"></i> Print
                    </a>

                    <iframe id="print-frame" style="display:none;"></iframe>
                </div>
            </div><!-- /#to-state-content -->

        </div>
    </div>
</div>
<!-- END :: modal view travel order -->

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
        color: inherit;
    }

    .to-attachment-row:hover .to-attachment-download-icon {
        color: var(--bs-primary) !important;
    }
</style>

<!-- BEGIN :: Add Person Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('person-container');
        const addBtn = document.getElementById('add-person');

        function createPersonGroup(index) {
            const i = index - 1;
            const div = document.createElement('div');
            div.className = 'person-group border rounded p-3 mb-3';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-muted small">Person #${index}</span>
                    <button type="button" class="btn btn-danger btn-sm delete-person">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-5">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="persons[${i}][name]" required>
                    </div>
                    <div class="col-2">
                        <label class="form-label">Salary Grade</label>
                        <input type="text" class="form-control" name="persons[${i}][salary_grade]" required>
                    </div>
                    <div class="col-5">
                        <label class="form-label">Position</label>
                        <input type="text" class="form-control" name="persons[${i}][position]" required>
                    </div>
                </div>
            `;
            return div;
        }

        function reindexGroups() {
            const groups = container.querySelectorAll('.person-group');
            groups.forEach(function(group, i) {
                const label = group.querySelector('.fw-semibold');
                if (label) label.textContent = `Person #${i + 1}`;

                group.querySelector('[name*="[name]"]').name = `persons[${i}][name]`;
                group.querySelector('[name*="[salary_grade]"]').name = `persons[${i}][salary_grade]`;
                group.querySelector('[name*="[division_section_unit]"]').name = `persons[${i}][division_section_unit]`;
                group.querySelector('[name*="[position]"]').name = `persons[${i}][position]`;
            });

            const deleteButtons = container.querySelectorAll('.delete-person');
            deleteButtons.forEach(function(btn) {
                btn.disabled = groups.length === 1;
            });
        }

        addBtn.addEventListener('click', function() {
            const count = container.querySelectorAll('.person-group').length + 1;
            const newGroup = createPersonGroup(count);
            container.appendChild(newGroup);
            reindexGroups();
        });

        container.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-person');
            if (!deleteBtn) return;

            const groups = container.querySelectorAll('.person-group');
            if (groups.length <= 1) return;

            deleteBtn.closest('.person-group').remove();
            reindexGroups();
        });
    });
</script>
<!-- END :: Add Person Script -->

<!-- BEGIN :: View Travel Order Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Current Status banner ──────────────────────────────────────────
        function getStatusConfig(status) {
            if (!status) return {
                badge: 'bg-secondary text-white',
                icon: 'bi-question-circle text-muted'
            };

            var s = status.toLowerCase();

            if (s.startsWith('forwarded to')) {
                return {
                    badge: 'bg-info-subtle text-info-emphasis',
                    icon: 'bi-arrow-right-circle-fill text-info'
                };
            }
            if (s.startsWith('rejected by')) {
                return {
                    badge: 'bg-danger-subtle text-danger-emphasis',
                    icon: 'bi-x-circle-fill text-danger'
                };
            }
            if (s.startsWith('approved by')) {
                return {
                    badge: 'bg-success-subtle text-success-emphasis',
                    icon: 'bi-check-circle-fill text-success'
                };
            }

            // fallback for plain values
            if (s === 'pending') {
                return {
                    badge: 'bg-warning-subtle text-warning-emphasis',
                    icon: 'bi-clock-history text-warning'
                };
            }
            if (s === 'approved') {
                return {
                    badge: 'bg-success-subtle text-success-emphasis',
                    icon: 'bi-check-circle-fill text-success'
                };
            }
            if (s === 'rejected') {
                return {
                    badge: 'bg-danger-subtle text-danger-emphasis',
                    icon: 'bi-x-circle-fill text-danger'
                };
            }

            return {
                badge: 'bg-secondary-subtle text-white-emphasis',
                icon: 'bi-question-circle text-muted'
            };
        }

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

        // ── Utilities ──────────────────────────────────────────────────────
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
            // MySQL datetime comes as "2026-04-07 10:30:00" — replace space with T
            // so all browsers parse it correctly as a local time
            var normalized = str.replace(' ', 'T');
            var d = new Date(normalized);
            if (isNaN(d.getTime())) return '—';
            return d.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            }) + ', ' + d.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true,
            });
        }

        function setText(id, val) {
            var el = document.getElementById(id);
            if (el) el.textContent = val || '—';
        }

        // ── State machine ──────────────────────────────────────────────────
        var bsModal = new bootstrap.Modal(document.getElementById('modal-view-travel-order'));

        function showState(state) {
            ['loading', 'error', 'content'].forEach(function(s) {
                document.getElementById('to-state-' + s)
                    .classList.toggle('d-none', s !== state);
            });
        }

        function buildApprovalSteps(d) {
            var container = document.getElementById('to-approval-steps');
            container.innerHTML = '';

            var steps = [];

            if (d.unit_id) {
                steps.push({
                    position: d.unit_head_position || 'Unit Supervisor',
                    personName: d.unit_head_name || null,
                    approvedBy: d.assigned_to_unit_head || null,
                    remarks: d.supervisor_remarks || null,
                    status: d.unit_status || 'pending',
                });
            }

            if (d.division_id) {
                steps.push({
                    position: d.division_head_position || 'Division Head',
                    personName: d.division_head_name || null,
                    approvedBy: d.assigned_to_division_head || null,
                    remarks: d.division_head_remarks || null,
                    status: d.division_status || 'pending',
                });
            }

            if (d.organization_id) {
                steps.push({
                    position: d.organization_head_position || 'PENRO Officer',
                    personName: d.organization_head_name || null,
                    approvedBy: d.assigned_to_organization_head || null,
                    remarks: d.organization_head_remarks || null,
                    status: d.organization_status || 'pending',
                });
            }

            if (steps.length === 0) {
                container.innerHTML = '<p class="text-muted fst-italic small mb-0">No approval levels assigned.</p>';
                return;
            }

            steps.forEach(function(step, idx) {
                var iconClass, badgeClass, badgeText;

                if (step.status === 'approved') {
                    iconClass = 'bi-check2-all text-success';
                    badgeClass = 'bg-success-subtle text-success-emphasis';
                    badgeText = 'Approved';
                } else if (step.status === 'rejected') {
                    iconClass = 'bi-x-circle-fill text-danger';
                    badgeClass = 'bg-danger-subtle text-danger-emphasis';
                    badgeText = 'Rejected';
                } else {
                    iconClass = 'bi-clock text-muted';
                    badgeClass = 'bg-secondary-subtle text-secondary-emphasis';
                    badgeText = 'Pending';
                }

                var isLast = (idx === steps.length - 1);
                var marginClass = isLast ? '' : 'mb-3';

                var remarksHtml = step.remarks ?
                    '<p class="mb-0 small fst-italic text-muted">"' + step.remarks + '"</p>' :
                    '';

                var nameHtml = step.approvedBy ?
                    '<p class="mb-0 small text-muted">' + step.approvedBy + '</p>' :
                    (step.personName ?
                        '<p class="mb-0 small text-muted">' + step.personName + '</p>' :
                        '<p class="mb-0 small text-muted fst-italic">Unassigned</p>');

                var html = '<div class="d-flex align-items-start gap-2 ' + marginClass + '">' +
                    '<div class="flex-shrink-0 mt-1"><i class="bi ' + iconClass + ' fs-5"></i></div>' +
                    '<div class="flex-grow-1">' +
                    '<p class="mb-0 fw-semibold small">' + step.position + '</p>' +
                    nameHtml +
                    remarksHtml +
                    '</div>' +
                    '<div class="flex-shrink-0">' +
                    '<span class="badge ' + badgeClass + ' small">' + badgeText + '</span>' +
                    '</div>' +
                    '</div>';

                container.insertAdjacentHTML('beforeend', html);
            });
        }
        // ── Build timeline ─────────────────────────────────────────────────
        function buildTimeline(d) {
            var container = document.getElementById('to-timeline');
            container.innerHTML = '';

            function addItem(iconClass, colorClass, title, subtitle) {
                var item = document.createElement('div');
                item.className = 'mb-3 position-relative';
                item.style.cssText = 'padding-left: 1.8rem;';
                var icon = document.createElement('i');
                icon.className = 'bi ' + iconClass + ' ' + colorClass;
                icon.style.cssText = 'position:absolute; left:0; top:4px; font-size:16px;';

                var content = document.createElement('div');
                content.innerHTML =
                    '<p class="mb-0 small fw-semibold" style="line-height:1.3">' + title + '</p>' +
                    '<p class="mb-0 small text-muted" style="font-size:11px">' + subtitle + '</p>';

                item.appendChild(icon);
                item.appendChild(content);
                container.appendChild(item);
            }
            addItem('bi-file-earmark-text-fill', 'text-primary',
                'Travel Order Submitted',
                (d.applicant_name || 'Applicant') + ' &mdash; ' + (d.applicant_position || '') + '<br>' + fmtDateTime(d.created_at));

            if (d.assigned_to_unit_head) {
                var supRejected = d.status === 'Rejected by Supervisor';
                addItem(
                    supRejected ? 'bi-x-fill' : 'bi-check-lg',
                    supRejected ? 'text-danger' : 'text-success',
                    (supRejected ? 'Rejected' : 'Approved') + ' by Supervisor',
                    d.assigned_to_unit_head + (d.supervisor_remarks ? ' &bull; "' + d.supervisor_remarks + '"' : '')
                );
            }

            if (d.assigned_to_division_head) {
                var divRejected = d.status === 'Rejected by Division Head';
                addItem(
                    divRejected ? 'bi-x-fill' : 'bi-check-lg',
                    divRejected ? 'text-danger' : 'text-success',
                    (divRejected ? 'Rejected' : 'Approved') + ' by Division Head',
                    d.assigned_to_division_head + (d.division_head_remarks ? ' &bull; "' + d.division_head_remarks + '"' : '')
                );
            }

            if (d.assigned_to_organization_head) {
                var orgRejected = d.status === 'Rejected by PENRO';
                addItem(
                    orgRejected ? 'bi-x-fill' : 'bi-check-lg',
                    orgRejected ? 'text-danger' : 'text-success',
                    (orgRejected ? 'Rejected' : 'Approved') + ' by PENRO',
                    d.assigned_to_organization_head + (d.organization_head_remarks ? ' &bull; "' + d.organization_head_remarks + '"' : '')
                );
            }

            var items = container.querySelectorAll('.mb-3');
            if (items.length > 0) {
                var lastLine = items[items.length - 1].querySelector('div[style*="position:absolute; left:7px"]');
                if (lastLine) lastLine.style.display = 'none';
            }
        }

        // ── Populate entire modal ──────────────────────────────────────────
        function populateModal(d) {

            setText('to-modal-number', d.travel_order_number);
            setText('to-doc-number', d.travel_order_number);
            setText('to-doc-date', fmtDate(d.created_at));
            setText('to-doc-destination', d.destination);
            setText('to-doc-departure', fmtDate(d.departure_date));
            setText('to-doc-arrival', fmtDate(d.arrival_date));
            setText('to-doc-purpose', d.purpose_of_travel);
            // Signatories — use whoever is the division-level and org-level approver
            setText('to-sig-division',
                d.assigned_to_division_head || d.division_head_name || '___________________');
            setText('to-sig-division-position',
                d.division_head_position || 'Division Chief');
            setText('to-sig-penro',
                d.assigned_to_organization_head || d.organization_head_name || '___________________');
            setText('to-sig-penro-position',
                d.organization_head_position || 'PENRO Officer');

            var namesEl = document.getElementById('to-persons-names');
            var positionsEl = document.getElementById('to-persons-positions');
            var gradesEl = document.getElementById('to-persons-grades');

            namesEl.innerHTML = '';
            positionsEl.innerHTML = '';
            gradesEl.innerHTML = '';

            if (d.persons && d.persons.length > 0) {
                d.persons.forEach(function(p) {
                    var mkLine = function(val) {
                        var el = document.createElement('p');
                        el.className = 'mb-0 fw-semibold small';
                        el.textContent = val || '—';
                        return el;
                    };
                    namesEl.appendChild(mkLine(p.name));
                    positionsEl.appendChild(mkLine(p.position));
                    gradesEl.appendChild(mkLine(p.salary_grade));
                });
            } else {
                namesEl.innerHTML = '<p class="mb-0 small text-muted fst-italic">None listed</p>';
            }

            setText('to-doc-office', d.organization_name || 'PENRO Leyte');

            var cfg = getStatusConfig(d.current_status);
            var iconEl = document.getElementById('to-status-icon');
            var badgeEl = document.getElementById('to-status-badge');
            iconEl.className = 'bi ' + cfg.icon + ' fs-3';
            badgeEl.className = 'badge px-3 py-2 fs-5 ' + cfg.badge;
            badgeEl.textContent = d.current_status || '—';


            buildApprovalSteps(d);

            buildTimeline(d);

            // ── Attachments ───────────────────────────────────────────────
            var list = document.getElementById('to-attachments-list');
            var noAtch = document.getElementById('to-no-attachments');
            list.innerHTML = '';

            if (d.attachments && d.attachments.length > 0) {
                noAtch.classList.add('d-none');

                d.attachments.forEach(function(a) {
                    var label = ATTACHMENT_LABELS[a.attachment_type] || a.attachment_type || 'Document';
                    var iconCls = ATTACHMENT_ICONS[a.attachment_type] || 'bi-file-earmark';
                    var dispName = a.attachment_name || label;
                    var href = '<?= site_url('dashboard/travel-orders/attachment/download') ?>/' + a.file_id;

                    // The entire row is the <a> — clicking anywhere triggers the download
                    var row = document.createElement('a');
                    row.href = href;
                    row.download = dispName; // suggested filename for the browser
                    row.className = 'to-attachment-row d-flex align-items-center gap-2 p-2 rounded border';

                    row.innerHTML =
                        '<i class="bi ' + iconCls + ' text-primary flex-shrink-0 fs-5"></i>' +
                        '<div class="flex-grow-1 overflow-hidden">' +
                        '<p class="mb-0 small fw-semibold text-truncate">' + dispName + '</p>' +
                        '<p class="mb-0 text-muted text-truncate" style="font-size:11px">' + label + '</p>' +
                        '</div>' +
                        '<i class="bi bi-download to-attachment-download-icon text-muted flex-shrink-0"></i>';

                    list.appendChild(row);
                });
            } else {
                noAtch.classList.remove('d-none');
            }
        }

        // ── Delegated click handler ────────────────────────────────────────
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-view-travel-order');
            if (!btn) return;

            var id = btn.getAttribute('data-id');
            if (!id) return;

            document.getElementById('to-btn-print').setAttribute('data-id', id);
            showState('loading');
            bsModal.show();

            fetch('<?= site_url('dashboard/travel-orders/details') ?>/' + id, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(function(res) {
                    if (!res.ok) throw new Error('Server returned ' + res.status);
                    return res.json();
                })
                .then(function(json) {
                    if (!json.success) throw new Error(json.message || 'Unknown error.');
                    populateModal(json.data);
                    showState('content');
                    // Preload print frame as soon as modal content is ready
                    loadPrintFrame(id);
                })
                .catch(function(err) {
                    document.getElementById('to-error-msg').textContent =
                        err.message || 'Failed to load travel order details.';
                    showState('error');
                });
        });
        // ── Print ──────────────────────────────────────────────────────────
        var printReady = false;
        var printIframe = document.getElementById('print-frame');

        function loadPrintFrame(travel_order_id) {
            printReady = false;
            printIframe.onload = function() {
                printReady = true;
            };
            printIframe.src = '<?= base_url('dashboard/travel-orders/print/') ?>'+ travel_order_id;
        }

        function printTO() {
            if (!printReady) {
                var btn = document.getElementById('to-btn-print');
                var original = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
                btn.disabled = true;

                var check = setInterval(function() {
                    if (printReady) {
                        clearInterval(check);
                        btn.innerHTML = original;
                        btn.disabled = false;
                        printIframe.contentWindow.focus();
                        printIframe.contentWindow.print();
                    }
                }, 100);
            } else {
                printIframe.contentWindow.focus();
                printIframe.contentWindow.print();
            }
        }

        document.getElementById('to-btn-print').addEventListener('click', function() {
            printTO();
        });
    });
</script>
<!-- END :: View Travel Order Script -->

<?= $this->endSection() ?>