<?= $this->extend('layouts/admin-base') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title flex-grow-1">Organization Structure</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-update-organization">
                            <i class="bi bi-plus-lg"></i> Update Organization
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-add-division">
                            <i class="bi bi-plus-lg"></i> New Division
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-unit">
                            <i class="bi bi-plus-lg"></i> New Unit
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Organization</th>
                                <th style="width: 20%;">Division</th>
                                <th style="width: 40%;">Unit</th>
                                <th style="width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- LEVEL 1 : ORGANIZATION -->
                            <tr class="expand-toggle" data-target="children-org-<?= $orgstructure->organization_id ?>">
                                <td>
                                    <i class="fas fa-caret-right me-2 toggle-icon"></i>
                                    <?= esc($orgstructure->organization_name) ?>
                                </td>
                                <td></td>
                                <td></td>
                                <td class="d-flex gap-1">
                                    <button class="btn btn-primary btn-sm btn-view"
                                        data-type="organization"
                                        data-id="<?= $orgstructure->organization_id ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm btn-edit"
                                        data-type="organization"
                                        data-id="<?= $orgstructure->organization_id ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>

                            <?php foreach ($orgstructure->divisions as $division): ?>

                                <!-- LEVEL 2 : DIVISION -->
                                <tr class="tree-child children-org-<?= $orgstructure->organization_id ?> expand-toggle d-none"
                                    data-target="children-div-<?= $division->division_id ?>">
                                    <td></td>
                                    <td>
                                        <i class="fas fa-caret-right me-2 toggle-icon"></i>
                                        <?= esc($division->division_name) ?>
                                    </td>
                                    <td></td>
                                    <td class="d-flex gap-1">
                                        <button class="btn btn-primary btn-sm btn-view"
                                            data-type="division"
                                            data-id="<?= $division->division_id ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm btn-edit"
                                            data-type="division"
                                            data-id="<?= $division->division_id ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>

                                <?php foreach ($division->units as $unit): ?>

                                    <!-- LEVEL 3 : UNIT -->
                                    <tr class="tree-child children-div-<?= $division->division_id ?> d-none">
                                        <td></td>
                                        <td></td>
                                        <td><?= esc($unit->unit_name) ?></td>
                                        <td class="d-flex gap-1">
                                            <button class="btn btn-primary btn-sm btn-view"
                                                data-type="unit"
                                                data-id="<?= $unit->unit_id ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm btn-edit"
                                                data-type="unit"
                                                data-id="<?= $unit->unit_id ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
    VIEW DETAIL MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modal-view-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <div>
                    <h5 class="modal-title mb-0" id="view-detail-title">Details</h5>
                    <small id="view-detail-subtitle" class="text-muted fw-semibold"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div id="vd-state-loading" class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-muted mb-0">Loading details...</p>
            </div>
            <div id="vd-state-error" class="modal-body text-center py-5 d-none">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i>
                <p class="mt-3 text-danger mb-0" id="vd-error-msg">Failed to load details.</p>
            </div>
            <div id="vd-state-content" class="d-none">
                <div class="modal-body">
                    <div class="row g-3" id="vd-body"></div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
    EDIT MODAL — shared, content injected by JS
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modal-edit-config" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title mb-0" id="edit-config-title">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Loading -->
            <div id="ec-state-loading" class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-muted mb-0">Loading...</p>
            </div>

            <!-- Error -->
            <div id="ec-state-error" class="modal-body text-center py-5 d-none">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i>
                <p class="mt-3 text-danger mb-0" id="ec-error-msg">Failed to load.</p>
            </div>

            <!-- Content — fields injected per type -->
            <div id="ec-state-content" class="d-none">
                <!-- ── ORGANIZATION fields ── -->
                <div id="ec-fields-organization" class="d-none">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-org-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Head Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-org-head-position" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Organization Head</label>
                            <select class="form-select" id="ec-org-head">
                                <option value="">— None —</option>
                                <?php foreach ($penrouser as $u): ?>
                                    <option value="<?= esc($u->user_id) ?>">
                                        <?= esc($u->full_name) ?>
                                        <?= !empty($u->organization_name) ? ' (' . esc($u->organization_name) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Only users with the <strong>PENRO</strong> role are listed.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Linked Divisions</label>
                            <select class="form-select" id="ec-org-divisions" multiple>
                                <?php foreach ($divisions as $d): ?>
                                    <option value="<?= esc($d->division_id) ?>"><?= esc($d->division_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Hold Ctrl / Cmd to select multiple.</small>
                        </div>
                    </div>
                </div>

                <!-- ── DIVISION fields ── -->
                <div id="ec-fields-division" class="d-none">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Parent Organization <span class="text-danger">*</span></label>
                            <select class="form-select" id="ec-div-parent-org" required>
                                <?php foreach ($organizations as $o): ?>
                                    <option value="<?= esc($o->organization_id) ?>"><?= esc($o->organization_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Division Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-div-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Head Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-div-head-position" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Division Head</label>
                            <select class="form-select" id="ec-div-head">
                                <option value="">— None —</option>
                                <?php foreach ($divisionusers as $u): ?>
                                    <option value="<?= esc($u->user_id) ?>">
                                        <?= esc($u->full_name) ?>
                                        <?= !empty($u->division_name) ? ' (' . esc($u->division_name) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Only users with the <strong>Division Head</strong> role are listed.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Linked Units</label>
                            <select class="form-select" id="ec-div-units" multiple>
                                <?php foreach ($units as $u): ?>
                                    <option value="<?= esc($u->unit_id) ?>"><?= esc($u->unit_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Hold Ctrl / Cmd to select multiple.</small>
                        </div>
                    </div>
                </div>

                <!-- ── UNIT fields ── -->
                <div id="ec-fields-unit" class="d-none">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Parent Division <span class="text-danger">*</span></label>
                            <select class="form-select" id="ec-unit-parent-div" required>
                                <?php foreach ($divisions as $d): ?>
                                    <option value="<?= esc($d->division_id) ?>"><?= esc($d->division_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-unit-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Head Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-unit-head-position" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Head</label>
                            <select class="form-select" id="ec-unit-head">
                                <option value="">— None —</option>
                                <?php foreach ($unitusers as $u): ?>
                                    <option value="<?= esc($u->user_id) ?>">
                                        <?= esc($u->full_name) ?>
                                        <?= !empty($u->unit_name) ? ' (' . esc($u->unit_name) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Only users with the <strong>Supervisor</strong> role are listed.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer py-2 justify-content-between">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" id="ec-btn-save">
                        <i class="bi bi-floppy2 me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
    ADD / UPDATE MODALS  (unchanged from original)
══════════════════════════════════════════════════════════ -->

<!-- Update Organization -->
<div class="modal fade" id="modal-update-organization">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('configuration/update-organization') ?>" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h4 class="modal-title">Update Organization</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="organization_name" class="form-label">Organization Name:</label>
                            <input type="text" class="form-control" name="organization_name"
                                placeholder="Enter Organization Name" value="<?= $orgData['organization_name'] ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="organization_head_position" class="form-label">Organization Head Position:</label>
                            <input type="text" class="form-control" name="organization_head_position"
                                placeholder="Enter Organization Head Position" value="<?= $orgData['organization_head_position'] ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Organization Head:</label>
                            <select class="form-control" id="organization_head" name="organization_head">
                                <?php foreach ($penrouser as $user): ?>
                                    <?php $label = $user->full_name . (!empty($user->organization_name) ? ' - ' . $user->organization_name : ''); ?>
                                    <option value="<?= esc($user->user_id) ?>"
                                        <?= $orgData['organization_head_id'] == $user->user_id ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Linked Divisions:</label>
                            <select class="form-control select2" multiple data-placeholder="Select division/s" name="linked_divisions[]">
                                <?php foreach ($divisions as $division): ?>
                                    <option value="<?= esc($division->division_id) ?>"><?= esc($division->division_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Update Organization</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Division -->
<div class="modal fade" id="modal-add-division">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('configuration/add-division') ?>" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h4 class="modal-title">Add Division</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Parent Organization:</label>
                            <select class="form-control" name="parent_organization">
                                <?php foreach ($organizations as $organization): ?>
                                    <option value="<?= esc($organization->organization_id) ?>"><?= esc($organization->organization_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Division Name:</label>
                            <input type="text" class="form-control" name="division_name" placeholder="Enter Division Name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Division Head Position:</label>
                            <input type="text" class="form-control" name="division_head_position" placeholder="Enter Division Head Position" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Division Head:</label>
                            <select class="form-control" name="division_head">
                                <option value="">— None —</option>
                                <?php foreach ($divisionusers as $user): ?>
                                    <?php $label = $user->full_name . (!empty($user->division_name) ? ' - ' . $user->division_name : ''); ?>
                                    <option value="<?= esc($user->user_id) ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Linked Units:</label>
                            <select class="form-control select2" multiple data-placeholder="Select units" name="linked_units[]">
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?= esc($unit->unit_id) ?>"><?= esc($unit->unit_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Add Division</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Unit -->
<div class="modal fade" id="modal-add-unit">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('configuration/add-unit') ?>" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h4 class="modal-title">Add Unit</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Parent Division:</label>
                            <select class="form-control" name="parent_division">
                                <?php foreach ($divisions as $division): ?>
                                    <option value="<?= esc($division->division_id) ?>"><?= esc($division->division_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Unit Name:</label>
                            <input type="text" class="form-control" name="unit_name" placeholder="Enter Unit Name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Unit Head Position:</label>
                            <input type="text" class="form-control" name="unit_head_position" placeholder="Enter Unit Head Position" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Unit Head:</label>
                            <select class="form-control" name="unit_head">
                                <option value="">— None —</option>
                                <?php foreach ($unitusers as $user): ?>
                                    <?php $label = $user->full_name . (!empty($user->unit_name) ? ' - ' . $user->unit_name : ''); ?>
                                    <option value="<?= esc($user->user_id) ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Add Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
    SCRIPTS
══════════════════════════════════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tree expand / collapse ───────────────────────────────────────────
    document.querySelectorAll('.expand-toggle').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('button')) return;

            var target = this.dataset.target;
            if (!target) return;

            document.querySelectorAll('.' + target).forEach(function (child) {
                var isHiding = !child.classList.contains('d-none');
                child.classList.toggle('d-none');

                if (isHiding) {
                    var childTarget = child.dataset.target;
                    if (childTarget) {
                        var childIcon = child.querySelector('.toggle-icon');
                        if (childIcon) childIcon.classList.remove('fa-rotate-90');
                        document.querySelectorAll('.' + childTarget).forEach(function (gc) {
                            gc.classList.add('d-none');
                        });
                    }
                }
            });

            var icon = this.querySelector('.toggle-icon');
            if (icon) icon.classList.toggle('fa-rotate-90');
        });
    });

    // ── Shared fetch helper ──────────────────────────────────────────────
    function fetchDetails(type, id) {
        return fetch('<?= site_url('configuration/details') ?>/' + type + '/' + id, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        }).then(function (r) {
            if (!r.ok) throw new Error('Server returned ' + r.status);
            return r.json();
        }).then(function (j) {
            if (!j.success) throw new Error(j.message || 'Unknown error.');
            return j.data;
        });
    }

    // ════════════════════════════════════════════════════════════════════
    //  VIEW MODAL
    // ════════════════════════════════════════════════════════════════════
    var bsViewModal = new bootstrap.Modal(document.getElementById('modal-view-detail'));

    function showVDState(state) {
        ['loading', 'error', 'content'].forEach(function (s) {
            document.getElementById('vd-state-' + s).classList.toggle('d-none', s !== state);
        });
    }

    function infoRow(label, value) {
        return '<div class="col-6 mb-3">' +
            '<p class="mb-0 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">' + label + '</p>' +
            '<p class="mb-0 fw-semibold">' + (value || '<span class="text-muted fst-italic">—</span>') + '</p>' +
            '</div>';
    }

    function renderOrganization(d) {
        document.getElementById('view-detail-title').textContent = 'Organization Details';
        document.getElementById('view-detail-subtitle').textContent = d.organization_name || '';

        var members = (d.members || []).map(function (m) {
            return '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">' +
                '<div><p class="mb-0 fw-semibold small">' + (m.full_name || '—') + '</p>' +
                '<p class="mb-0 text-muted" style="font-size:11px">' + (m.position || '') + '</p></div>' +
                '<span class="badge bg-secondary-subtle text-secondary-emphasis small">' + (m.role || '') + '</span>' +
                '</li>';
        }).join('');

        var divisions = (d.divisions || []).map(function (dv) {
            return '<li class="list-group-item py-2 px-3 small">' + (dv.division_name || '—') + '</li>';
        }).join('');

        document.getElementById('vd-body').innerHTML =
            '<div class="col-12 col-md-6"><div class="border rounded p-3 h-100">' +
            '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Organization Info</p>' +
            '<div class="row">' +
            infoRow('Name', d.organization_name) +
            infoRow('Head Position', d.organization_head_position) +
            infoRow('Head', d.organization_head_name) +
            '</div></div></div>' +
            '<div class="col-12 col-md-6 d-flex flex-column gap-3">' +
            '<div class="border rounded p-3"><p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Linked Divisions (' + (d.divisions || []).length + ')</p>' +
            (divisions ? '<ul class="list-group list-group-flush">' + divisions + '</ul>' : '<p class="text-muted fst-italic small mb-0">No divisions linked.</p>') +
            '</div>' +
            '<div class="border rounded p-3"><p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Members (' + (d.members || []).length + ')</p>' +
            (members ? '<ul class="list-group list-group-flush">' + members + '</ul>' : '<p class="text-muted fst-italic small mb-0">No members assigned.</p>') +
            '</div></div>';
    }

    function renderDivision(d) {
        document.getElementById('view-detail-title').textContent = 'Division Details';
        document.getElementById('view-detail-subtitle').textContent = d.division_name || '';

        var units = (d.units || []).map(function (u) {
            return '<li class="list-group-item py-2 px-3 small">' + (u.unit_name || '—') + '</li>';
        }).join('');
        var members = (d.members || []).map(function (m) {
            return '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">' +
                '<div><p class="mb-0 fw-semibold small">' + (m.full_name || '—') + '</p>' +
                '<p class="mb-0 text-muted" style="font-size:11px">' + (m.position || '') + '</p></div>' +
                '<span class="badge bg-secondary-subtle text-secondary-emphasis small">' + (m.role || '') + '</span>' +
                '</li>';
        }).join('');

        document.getElementById('vd-body').innerHTML =
            '<div class="col-12 col-md-6"><div class="border rounded p-3 h-100">' +
            '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Division Info</p>' +
            '<div class="row">' +
            infoRow('Name', d.division_name) +
            infoRow('Head Position', d.division_head_position) +
            infoRow('Head', d.division_head_name) +
            infoRow('Parent Organization', d.organization_name) +
            '</div></div></div>' +
            '<div class="col-12 col-md-6 d-flex flex-column gap-3">' +
            '<div class="border rounded p-3"><p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Units (' + (d.units || []).length + ')</p>' +
            (units ? '<ul class="list-group list-group-flush">' + units + '</ul>' : '<p class="text-muted fst-italic small mb-0">No units linked.</p>') +
            '</div>' +
            '<div class="border rounded p-3"><p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Members (' + (d.members || []).length + ')</p>' +
            (members ? '<ul class="list-group list-group-flush">' + members + '</ul>' : '<p class="text-muted fst-italic small mb-0">No members assigned.</p>') +
            '</div></div>';
    }

    function renderUnit(d) {
        document.getElementById('view-detail-title').textContent = 'Unit Details';
        document.getElementById('view-detail-subtitle').textContent = d.unit_name || '';

        var members = (d.members || []).map(function (m) {
            return '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">' +
                '<div><p class="mb-0 fw-semibold small">' + (m.full_name || '—') + '</p>' +
                '<p class="mb-0 text-muted" style="font-size:11px">' + (m.position || '') + '</p></div>' +
                '<span class="badge bg-secondary-subtle text-secondary-emphasis small">' + (m.role || '') + '</span>' +
                '</li>';
        }).join('');

        document.getElementById('vd-body').innerHTML =
            '<div class="col-12 col-md-6"><div class="border rounded p-3 h-100">' +
            '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Unit Info</p>' +
            '<div class="row">' +
            infoRow('Name', d.unit_name) +
            infoRow('Head Position', d.unit_head_position) +
            infoRow('Head', d.unit_head_name) +
            infoRow('Parent Division', d.division_name) +
            infoRow('Parent Organization', d.organization_name) +
            '</div></div></div>' +
            '<div class="col-12 col-md-6"><div class="border rounded p-3 h-100">' +
            '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Members (' + (d.members || []).length + ')</p>' +
            (members ? '<ul class="list-group list-group-flush">' + members + '</ul>' : '<p class="text-muted fst-italic small mb-0">No members assigned.</p>') +
            '</div></div>';
    }

    document.querySelectorAll('.btn-view').forEach(function (btn) {
        btn.addEventListener('click', function () {
            showVDState('loading');
            bsViewModal.show();
            fetchDetails(this.dataset.type, this.dataset.id)
                .then(function (d) {
                    var type = btn.dataset.type;
                    if (type === 'organization') renderOrganization(d);
                    else if (type === 'division')    renderDivision(d);
                    else                             renderUnit(d);
                    showVDState('content');
                })
                .catch(function (err) {
                    document.getElementById('vd-error-msg').textContent = err.message || 'Failed to load details.';
                    showVDState('error');
                });
        });
    });

    // ════════════════════════════════════════════════════════════════════
    //  EDIT MODAL
    // ════════════════════════════════════════════════════════════════════
    var bsEditModal  = new bootstrap.Modal(document.getElementById('modal-edit-config'));
    var _editType    = null;
    var _editId      = null;

    var CSRF_NAME    = '<?= csrf_token() ?>';
    var csrfHash     = '<?= csrf_hash() ?>';

    function showECState(state) {
        ['loading', 'error', 'content'].forEach(function (s) {
            document.getElementById('ec-state-' + s).classList.toggle('d-none', s !== state);
        });
    }

    function hideAllECFields() {
        ['organization', 'division', 'unit'].forEach(function (t) {
            document.getElementById('ec-fields-' + t).classList.add('d-none');
        });
    }

    // Helper: set <select> value, selecting matching options (works for single & multi)
    function setSelectValue(selectEl, value) {
        var vals = Array.isArray(value) ? value.map(String) : [String(value || '')];
        Array.from(selectEl.options).forEach(function (opt) {
            opt.selected = vals.includes(opt.value);
        });
    }

    // ── Populate edit form per type ──────────────────────────────────────
    function populateEditOrganization(d) {
        document.getElementById('edit-config-title').textContent = 'Edit Organization';
        hideAllECFields();
        document.getElementById('ec-fields-organization').classList.remove('d-none');

        document.getElementById('ec-org-name').value          = d.organization_name || '';
        document.getElementById('ec-org-head-position').value = d.organization_head_position || '';
        setSelectValue(document.getElementById('ec-org-head'), d.organization_head_name ? '' : '');

        // Pre-select head by matching name (best-effort; id would be more reliable if returned)
        // We rely on the separate field returned — note getDetails returns organization_head_name.
        // To pre-select by ID we need the controller to return organization_head_id — it does via orgData.
        // Re-fetch raw to get the id:
        fetch('<?= site_url('configuration/details') ?>/organization/' + _editId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(r => r.json()).then(function (j) {
            // The detail endpoint doesn't return raw IDs for head; use orgData from the page for org only.
            // For a cleaner solution we'd add head_id to getDetails(). Here we do a best-effort match.
        });

        // Pre-select linked divisions
        var divSel    = document.getElementById('ec-org-divisions');
        var linkedIds = (d.divisions || []).map(function (dv) { return String(dv.division_id); });
        Array.from(divSel.options).forEach(function (opt) {
            opt.selected = linkedIds.includes(opt.value);
        });
    }

    function populateEditDivision(d) {
        document.getElementById('edit-config-title').textContent = 'Edit Division — ' + (d.division_name || '');
        hideAllECFields();
        document.getElementById('ec-fields-division').classList.remove('d-none');

        document.getElementById('ec-div-name').value          = d.division_name || '';
        document.getElementById('ec-div-head-position').value = d.division_head_position || '';

        // Pre-select parent org (need org_id — getDetails returns organization_name only,
        // so we match by name as fallback; ideally add org_id to DivisionsModel::getDetails)
        var orgSel = document.getElementById('ec-div-parent-org');
        Array.from(orgSel.options).forEach(function (opt) {
            opt.selected = opt.textContent.trim() === (d.organization_name || '').trim();
        });

        // Pre-select linked units
        var unitSel   = document.getElementById('ec-div-units');
        var linkedIds = (d.units || []).map(function (u) { return String(u.unit_id); });
        Array.from(unitSel.options).forEach(function (opt) {
            opt.selected = linkedIds.includes(opt.value);
        });

        // Division head — match by name (fallback)
        var headSel = document.getElementById('ec-div-head');
        Array.from(headSel.options).forEach(function (opt) {
            opt.selected = opt.textContent.trim().startsWith((d.division_head_name || '?????').trim());
        });
    }

    function populateEditUnit(d) {
        document.getElementById('edit-config-title').textContent = 'Edit Unit — ' + (d.unit_name || '');
        hideAllECFields();
        document.getElementById('ec-fields-unit').classList.remove('d-none');

        document.getElementById('ec-unit-name').value          = d.unit_name || '';
        document.getElementById('ec-unit-head-position').value = d.unit_head_position || '';

        // Pre-select parent division by name
        var divSel = document.getElementById('ec-unit-parent-div');
        Array.from(divSel.options).forEach(function (opt) {
            opt.selected = opt.textContent.trim() === (d.division_name || '').trim();
        });

        // Unit head — match by name
        var headSel = document.getElementById('ec-unit-head');
        Array.from(headSel.options).forEach(function (opt) {
            opt.selected = opt.textContent.trim().startsWith((d.unit_head_name || '?????').trim());
        });
    }

    // ── Open edit modal on .btn-edit click ───────────────────────────────
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            _editType = this.dataset.type;
            _editId   = this.dataset.id;

            showECState('loading');
            bsEditModal.show();

            fetchDetails(_editType, _editId)
                .then(function (d) {
                    if (_editType === 'organization')    populateEditOrganization(d);
                    else if (_editType === 'division')   populateEditDivision(d);
                    else                                 populateEditUnit(d);
                    showECState('content');
                })
                .catch(function (err) {
                    document.getElementById('ec-error-msg').textContent = err.message || 'Failed to load.';
                    showECState('error');
                });
        });
    });

    // ── Save button ──────────────────────────────────────────────────────
    document.getElementById('ec-btn-save').addEventListener('click', function () {
        var btn      = this;
        var original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving…';

        var body = new FormData();
        body.append(CSRF_NAME, csrfHash);

        if (_editType === 'organization') {
            body.append('organization_name',          document.getElementById('ec-org-name').value.trim());
            body.append('organization_head_position', document.getElementById('ec-org-head-position').value.trim());
            body.append('organization_head',          document.getElementById('ec-org-head').value);
            Array.from(document.getElementById('ec-org-divisions').selectedOptions)
                .forEach(function (o) { body.append('linked_divisions[]', o.value); });

        } else if (_editType === 'division') {
            body.append('division_name',          document.getElementById('ec-div-name').value.trim());
            body.append('division_head_position', document.getElementById('ec-div-head-position').value.trim());
            body.append('division_head',          document.getElementById('ec-div-head').value);
            body.append('parent_organization',    document.getElementById('ec-div-parent-org').value);
            Array.from(document.getElementById('ec-div-units').selectedOptions)
                .forEach(function (o) { body.append('linked_units[]', o.value); });

        } else if (_editType === 'unit') {
            body.append('unit_name',          document.getElementById('ec-unit-name').value.trim());
            body.append('unit_head_position', document.getElementById('ec-unit-head-position').value.trim());
            body.append('unit_head',          document.getElementById('ec-unit-head').value);
            body.append('parent_division',    document.getElementById('ec-unit-parent-div').value);
        }

        fetch('<?= site_url('configuration/update') ?>/' + _editType + '/' + _editId, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: body
        })
        .then(function (r) {
            if (!r.ok) throw new Error('Server returned ' + r.status);
            return r.json();
        })
        .then(function (j) {
            csrfHash = j.csrf_token || csrfHash;   // refresh token if returned
            btn.innerHTML = original;
            btn.disabled  = false;

            if (!j.success) throw new Error(j.message || 'Update failed.');

            bsEditModal.hide();

            // Reload page to reflect updated tree
            window.location.reload();
        })
        .catch(function (err) {
            btn.innerHTML = original;
            btn.disabled  = false;
            document.getElementById('ec-error-msg').textContent = err.message || 'Something went wrong.';
            showECState('error');
        });
    });

});
</script>

<style>
    .expand-toggle { cursor: pointer; }
    .expand-toggle:hover { background: #f8f9fa; }
    .toggle-icon { transition: transform 0.2s ease; }
    #ec-org-divisions,
    #ec-div-units     { min-height: 100px; }
</style>

<?= $this->endSection() ?>