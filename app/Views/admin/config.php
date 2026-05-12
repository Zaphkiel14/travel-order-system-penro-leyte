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
                                <th style="width: 50%;">Unit</th>
                                <th style="width: 10%;">Actions</th>
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
                                <td>
                                    <button class="btn btn-primary btn-view"
                                        data-type="organization"
                                        data-id="<?= $orgstructure->organization_id ?>"><i class="bi bi-eye"></i></button>
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
                                    <td>
                                        <button class="btn btn-primary btn-view"
                                            data-type="division"
                                            data-id="<?= $division->division_id ?>"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>

                                <?php foreach ($division->units as $unit): ?>

                                    <!-- LEVEL 3 : UNIT -->
                                    <tr class="tree-child children-div-<?= $division->division_id ?> d-none">
                                        <td></td>
                                        <td></td>
                                        <td><?= esc($unit->unit_name) ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-view"
                                                data-type="unit"
                                                data-id="<?= $unit->unit_id ?>"><i class="bi bi-eye"></i></button>
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
    VIEW DETAIL MODAL  (shared for org / division / unit)
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

            <!-- Loading -->
            <div id="vd-state-loading" class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-muted mb-0">Loading details...</p>
            </div>

            <!-- Error -->
            <div id="vd-state-error" class="modal-body text-center py-5 d-none">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i>
                <p class="mt-3 text-danger mb-0" id="vd-error-msg">Failed to load details.</p>
            </div>

            <!-- Content -->
            <div id="vd-state-content" class="d-none">
                <div class="modal-body">
                    <div class="row g-3" id="vd-body">
                        <!-- Dynamically populated -->
                    </div>
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
<!-- END VIEW DETAIL MODAL -->

<!-- BEGIN : Update Organization Modal -->
<div class="modal fade" id="modal-update-organization">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route_to('update.organization') ?>" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h4 class="modal-title">Update Organization</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="organization_name" class="form-label">Organization Name:</label>
                                <input type="text" class="form-control" name="organization_name" placeholder="Enter Organization Name" value="<?= $orgData['organization_name'] ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="organization_head_position" class="form-label">Organization Head Position:</label>
                                <input type="text" class="form-control" name="organization_head_position" placeholder="Enter Organization Head Position" value="<?= $orgData['organization_head_position'] ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="organization_head" class="form-label">Organization Head:</label>
                                <select class="form-control" style="width: 100%;" id="organization_head" data-placeholder="Select organization head" name="organization_head">
                                    <?php if (!empty($penrouser)) : ?>
                                        <?php foreach ($penrouser as $user) : ?>
                                            <?php
                                            $label = $user->full_name;
                                            if (!empty($user->organization_name)) {
                                                $label .= " - " . $user->organization_name;
                                            }
                                            ?>
                                            <option value="<?= esc($user->user_id) ?>">
                                                <?= esc($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="update-linked-divisions" class="form-label">Linked Divisions:</label>
                                <select class="form-control select2" style="width: 100%;" id="update-linked-divisions" multiple="multiple" data-placeholder="Select division/s" name="linked_divions[]">
                                    <?php if (!empty($divisions)) : ?>
                                        <?php foreach ($divisions as $division) : ?>
                                            <option value="<?= esc($division->division_id) ?>"><?= esc($division->division_name) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-floppy2"></i> Update Organization</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-division">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route_to('add.division') ?>" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h4 class="modal-title">Add Division</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="parent_organization" class="form-label">Parent Organization:</label>
                                <select class="form-control" style="width:100%;" name="parent_organization" id="parent_organization">
                                    <?php if (!empty($organizations)) : ?>
                                        <?php foreach ($organizations as $organization) : ?>
                                            <option value="<?= esc($organization->organization_id) ?>"><?= esc($organization->organization_name) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="division_name" class="form-label">Division Name:</label>
                                <input type="text" class="form-control" name="division_name" placeholder="Enter Division Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="division_head_position" class="form-label">Division Head Position:</label>
                                <input type="text" class="form-control" name="division_head_position" placeholder="Enter Division Head Position" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="division_head" class="form-label">Division Head:</label>
                                <select class="form-control" style="width: 100%;" id="division_head" data-placeholder="Select division head" name="division_head">
                                    <?php if (!empty($divisionusers)) : ?>
                                        <?php foreach ($divisionusers as $user) : ?>
                                            <?php
                                            $label = $user->full_name;
                                            if (!empty($user->division_name)) {
                                                $label .= " - " . $user->division_name;
                                            }
                                            ?>
                                            <option value="<?= esc($user->user_id) ?>">
                                                <?= esc($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="update-linked-units" class="form-label">Linked Units:</label>
                                <select class="form-control select2" style="width: 100%;" id="update-linked-units" multiple="multiple" data-placeholder="Select units/s" name="linked_units[]">
                                    <?php if (!empty($units)) : ?>
                                        <?php foreach ($units as $unit) : ?>
                                            <option value="<?= esc($unit->unit_id) ?>"><?= esc($unit->unit_name) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-floppy2"></i> Add Division</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-unit">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route_to('add.unit') ?>" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h4 class="modal-title">Add Unit</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="parent_division" class="form-label">Parent Division:</label>
                                <select class="form-control" style="width: 100%;" id="parent_division" data-placeholder="Select division" name="parent_division">
                                    <?php if (!empty($divisions)) : ?>
                                        <?php foreach ($divisions as $division) : ?>
                                            <option value="<?= esc($division->division_id) ?>"><?= esc($division->division_name) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="unit_name" class="form-label">Unit Name:</label>
                                <input type="text" class="form-control" name="unit_name" placeholder="Enter Unit Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="unit_head_position" class="form-label">Unit Head Position:</label>
                                <input type="text" class="form-control" name="unit_head_position" placeholder="Enter Unit Head Position" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="unit_head" class="form-label">Unit Head:</label>
                                <select class="form-control" style="width: 100%;" id="unit_head" data-placeholder="Select unit head" name="unit_head">
                                    <?php if (!empty($unitusers)) : ?>
                                        <?php foreach ($unitusers as $user) : ?>
                                            <?php
                                            $label = $user->full_name;
                                            if (!empty($user->unit_name)) {
                                                $label .= " - " . $user->unit_name;
                                            }
                                            ?>
                                            <option value="<?= esc($user->user_id) ?>">
                                                <?= esc($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-floppy2"></i> Add Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Tree toggle ──────────────────────────────────────────────────────
        document.querySelectorAll('.expand-toggle').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('button')) return;

                const target = this.dataset.target;
                if (!target) return;

                document.querySelectorAll('.' + target).forEach(child => {
                    const isHiding = !child.classList.contains('d-none');
                    child.classList.toggle('d-none');

                    if (isHiding) {
                        const childTarget = child.dataset.target;
                        if (childTarget) {
                            const childIcon = child.querySelector('.toggle-icon');
                            if (childIcon) childIcon.classList.remove('fa-rotate-90');
                            document.querySelectorAll('.' + childTarget).forEach(grandchild => {
                                grandchild.classList.add('d-none');
                            });
                        }
                    }
                });

                const icon = this.querySelector('.toggle-icon');
                if (icon) icon.classList.toggle('fa-rotate-90');
            });
        });

        // ── View modal ───────────────────────────────────────────────────────
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

        function badge(text, color) {
            color = color || 'secondary';
            return '<span class="badge bg-' + color + '-subtle text-' + color + '-emphasis px-2 py-1">' + text + '</span>';
        }

        function renderOrganization(d) {
            document.getElementById('view-detail-title').textContent = 'Organization Details';
            document.getElementById('view-detail-subtitle').textContent = d.organization_name || '';

            var members = (d.members || []).map(function (m) {
                return '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">' +
                    '<div>' +
                    '<p class="mb-0 fw-semibold small">' + (m.full_name || '—') + '</p>' +
                    '<p class="mb-0 text-muted" style="font-size:11px">' + (m.position || '') + '</p>' +
                    '</div>' +
                    '<span class="badge bg-secondary-subtle text-secondary-emphasis small">' + (m.role || '') + '</span>' +
                    '</li>';
            }).join('');

            var divisions = (d.divisions || []).map(function (dv) {
                return '<li class="list-group-item py-2 px-3 small">' + (dv.division_name || '—') + '</li>';
            }).join('');

            document.getElementById('vd-body').innerHTML =
                '<div class="col-12 col-md-6">' +
                    '<div class="border rounded p-3 h-100">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Organization Info</p>' +
                        '<div class="row">' +
                            infoRow('Name', d.organization_name) +
                            infoRow('Head Position', d.organization_head_position) +
                            infoRow('Head', d.organization_head_name) +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="col-12 col-md-6 d-flex flex-column gap-3">' +
                    '<div class="border rounded p-3">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Linked Divisions (' + (d.divisions || []).length + ')</p>' +
                        (divisions
                            ? '<ul class="list-group list-group-flush">' + divisions + '</ul>'
                            : '<p class="text-muted fst-italic small mb-0">No divisions linked.</p>') +
                    '</div>' +
                    '<div class="border rounded p-3">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Members (' + (d.members || []).length + ')</p>' +
                        (members
                            ? '<ul class="list-group list-group-flush">' + members + '</ul>'
                            : '<p class="text-muted fst-italic small mb-0">No members assigned.</p>') +
                    '</div>' +
                '</div>';
        }

        function renderDivision(d) {
            document.getElementById('view-detail-title').textContent = 'Division Details';
            document.getElementById('view-detail-subtitle').textContent = d.division_name || '';

            var units = (d.units || []).map(function (u) {
                return '<li class="list-group-item py-2 px-3 small">' + (u.unit_name || '—') + '</li>';
            }).join('');

            var members = (d.members || []).map(function (m) {
                return '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">' +
                    '<div>' +
                    '<p class="mb-0 fw-semibold small">' + (m.full_name || '—') + '</p>' +
                    '<p class="mb-0 text-muted" style="font-size:11px">' + (m.position || '') + '</p>' +
                    '</div>' +
                    '<span class="badge bg-secondary-subtle text-secondary-emphasis small">' + (m.role || '') + '</span>' +
                    '</li>';
            }).join('');

            document.getElementById('vd-body').innerHTML =
                '<div class="col-12 col-md-6">' +
                    '<div class="border rounded p-3 h-100">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Division Info</p>' +
                        '<div class="row">' +
                            infoRow('Name', d.division_name) +
                            infoRow('Head Position', d.division_head_position) +
                            infoRow('Head', d.division_head_name) +
                            infoRow('Parent Organization', d.organization_name) +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="col-12 col-md-6 d-flex flex-column gap-3">' +
                    '<div class="border rounded p-3">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Units (' + (d.units || []).length + ')</p>' +
                        (units
                            ? '<ul class="list-group list-group-flush">' + units + '</ul>'
                            : '<p class="text-muted fst-italic small mb-0">No units linked.</p>') +
                    '</div>' +
                    '<div class="border rounded p-3">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Members (' + (d.members || []).length + ')</p>' +
                        (members
                            ? '<ul class="list-group list-group-flush">' + members + '</ul>'
                            : '<p class="text-muted fst-italic small mb-0">No members assigned.</p>') +
                    '</div>' +
                '</div>';
        }

        function renderUnit(d) {
            document.getElementById('view-detail-title').textContent = 'Unit Details';
            document.getElementById('view-detail-subtitle').textContent = d.unit_name || '';

            var members = (d.members || []).map(function (m) {
                return '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">' +
                    '<div>' +
                    '<p class="mb-0 fw-semibold small">' + (m.full_name || '—') + '</p>' +
                    '<p class="mb-0 text-muted" style="font-size:11px">' + (m.position || '') + '</p>' +
                    '</div>' +
                    '<span class="badge bg-secondary-subtle text-secondary-emphasis small">' + (m.role || '') + '</span>' +
                    '</li>';
            }).join('');

            document.getElementById('vd-body').innerHTML =
                '<div class="col-12 col-md-6">' +
                    '<div class="border rounded p-3 h-100">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Unit Info</p>' +
                        '<div class="row">' +
                            infoRow('Name', d.unit_name) +
                            infoRow('Head Position', d.unit_head_position) +
                            infoRow('Head', d.unit_head_name) +
                            infoRow('Parent Division', d.division_name) +
                            infoRow('Parent Organization', d.organization_name) +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="col-12 col-md-6">' +
                    '<div class="border rounded p-3 h-100">' +
                        '<p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.04em">Members (' + (d.members || []).length + ')</p>' +
                        (members
                            ? '<ul class="list-group list-group-flush">' + members + '</ul>'
                            : '<p class="text-muted fst-italic small mb-0">No members assigned.</p>') +
                    '</div>' +
                '</div>';
        }

        function openViewModal(type, id) {
            showVDState('loading');
            bsViewModal.show();

            fetch('<?= site_url('configuration/details') ?>/' + type + '/' + id, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function (r) {
                if (!r.ok) throw new Error('Server returned ' + r.status);
                return r.json();
            })
            .then(function (j) {
                if (!j.success) throw new Error(j.message || 'Unknown error.');
                var d = j.data;
                if (type === 'organization') renderOrganization(d);
                else if (type === 'division')    renderDivision(d);
                else                             renderUnit(d);
                showVDState('content');
            })
            .catch(function (err) {
                document.getElementById('vd-error-msg').textContent = err.message || 'Failed to load details.';
                showVDState('error');
            });
        }

        // Attach to all view buttons
        document.querySelectorAll('.btn-view').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openViewModal(this.dataset.type, this.dataset.id);
            });
        });

    });
</script>

<style>
    .expand-toggle { cursor: pointer; }
    .expand-toggle:hover { background: #f8f9fa; }
    .toggle-icon { transition: transform 0.2s ease; }
</style>

<?= $this->endSection() ?>