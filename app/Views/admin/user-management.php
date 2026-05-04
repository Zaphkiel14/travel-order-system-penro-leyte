<?= $this->extend('layouts/admin-base') ?>

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
                        10
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
                    <span class="info-box-number fs-4 fw-bold">41</span>
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
                    <span class="info-box-number fs-4 fw-bold">76</span>
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
                    <span class="info-box-number fs-4 fw-bold">20</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title flex-grow-1">User Accounts</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-travel-order">
                            <i class="bi bi-plus-lg"></i> Create New Account
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="user_management_table"
                        class="table table-bordered table-striped datatable-standard"
                        data-last-column-width="100"
                        data-page-length="10"
                        data-order='[[0,"desc"]]'
                        data-url="<?= route_to('data.userManagement') ?>">
                        <thead>
                            <tr>
                                <th data-name="first_name">First Name</th>
                                <th data-name="last_name">Last Name</th>
                                <th data-name="email">Email</th>
                                <th data-name="position">Position</th>
                                <th data-name="salary_grade">Salary Grade</th>
                                <th data-name="role">Role</th>
                                <th data-name="actions" data-orderable="false" data-searchable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th data-name="first_name">First Name</th>
                                <th data-name="last_name">Last Name</th>
                                <th data-name="email">Email</th>
                                <th data-name="position">Position</th>
                                <th data-name="salary_grade">Salary Grade</th>
                                <th data-name="role">Role</th>
                                <th data-name="actions" data-orderable="false" data-searchable="false">Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN :: View User Entry Modal -->
<div class="modal fade" id="modal-view-user-entry" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header py-2">
                <div>
                    <h5 class="modal-title mb-0">User Details</h5>
                    <small id="user-modal-fullname" class="text-muted fw-semibold"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Loading state -->
            <div id="user-state-loading" class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-muted mb-0">Loading details...</p>
            </div>

            <!-- Error state -->
            <div id="user-state-error" class="modal-body text-center py-5 d-none">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i>
                <p class="mt-3 text-danger mb-0" id="user-error-msg">Failed to load details.</p>
            </div>

            <!-- Content state -->
            <div id="user-state-content" class="d-none">
                <div class="modal-body">

                    <!-- Personal Information -->
                    <p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.05em">Personal Information</p>
                    <div class="row g-3 mb-4">

                        <!-- Left: Profile Picture -->
                        <div class="col-12 col-md-3 d-flex flex-column align-items-center">
                            <img id="user-profile-picture"
                                src=""
                                alt="Profile Picture"
                                class="rounded-circle shadow mb-2"
                                style="width: 110px; height: 110px; object-fit: cover; border: 3px solid #dee2e6;">
                        </div>

                        <!-- Right: Name + Email -->
                        <div class="col-12 col-md-9">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <p class="mb-0 small text-muted">First Name</p>
                                    <p class="mb-0 fw-semibold" id="user-first-name"></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0 small text-muted">Last Name</p>
                                    <p class="mb-0 fw-semibold" id="user-last-name"></p>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-12">
                                    <p class="mb-0 small text-muted">Email Address</p>
                                    <p class="mb-0 fw-semibold" id="user-email"></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr class="my-3">

                    <!-- Work Information -->
                    <p class="mb-2 small text-muted text-uppercase fw-semibold" style="letter-spacing:.05em">Work Information</p>
                    <div class="row g-2 mb-4">
                        <div class="col-8">
                            <p class="mb-0 small text-muted">Position</p>
                            <p class="mb-0 fw-semibold" id="user-position"></p>
                        </div>
                        <div class="col-4">
                            <p class="mb-0 small text-muted">Salary Grade</p>
                            <p class="mb-0 fw-semibold" id="user-salary-grade"></p>
                        </div>
                        <div class="col-12 mt-2">
                            <p class="mb-0 small text-muted">System Role</p>
                            <p class="mb-0 fw-semibold text-capitalize" id="user-role"></p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Member Since -->
                    <div>
                        <p class="mb-0 small text-muted">Account Created</p>
                        <p class="mb-0 small" id="user-created-at"></p>
                    </div>

                </div>

                <div class="modal-footer justify-content-between py-2">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" id="user-btn-edit">
                        <i class="bi bi-pencil-square me-1"></i> Edit User
                    </button>
                </div>
            </div><!-- /#user-state-content -->

        </div>
    </div>
</div>
<!-- END :: View User Entry Modal -->

<!-- BEGIN :: modal edit user entry -->
<div class="modal fade" id="modal-edit-user-entry">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-edit-user-entry" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h4 class="modal-title">Edit User Entry</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- Loading state -->
                    <div id="edit-user-state-loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3 text-muted mb-0">Loading user data...</p>
                    </div>

                    <!-- Error state -->
                    <div id="edit-user-state-error" class="text-center py-5 d-none">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem"></i>
                        <p class="mt-3 text-danger mb-0" id="edit-user-error-msg">Failed to load user data.</p>
                    </div>

                    <!-- Content state -->
                    <div id="edit-user-state-content" class="d-none">

                        <input type="hidden" id="edit-user-id" name="user_id">

                        <!-- Begin :: Personal Information -->
                        <label class="form-label fs-4 mb-3">Personal Information</label>

                        <div class="row g-3 mb-3">

                            <!-- Profile Picture -->
                            <div class="col-12 col-md-3 d-flex flex-column align-items-center">
                                <img id="edit-user-profile-picture"
                                    src=""
                                    alt="Profile Picture"
                                    class="rounded-circle shadow mb-2"
                                    style="width: 110px; height: 110px; object-fit: cover; border: 3px solid #dee2e6;">
                                <label for="edit-profile-picture-input" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="bi bi-pencil"></i> Change
                                </label>
                                <input type="file" id="edit-profile-picture-input" name="profile_picture" accept="image/*" class="d-none">
                            </div>

                            <!-- First Name, Last Name, Email -->
                            <div class="col-12 col-md-9">
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label for="edit-first-name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="edit-first-name" name="first_name" required>
                                        <div class="invalid-feedback">Please enter a first name.</div>
                                    </div>
                                    <div class="col-6">
                                        <label for="edit-last-name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="edit-last-name" name="last_name" required>
                                        <div class="invalid-feedback">Please enter a last name.</div>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label for="edit-email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="edit-email" name="email" required>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- End :: Personal Information -->

                        <hr>
                        <!-- Begin :: Work Information -->
                        <label class="form-label fs-4 mb-3">Work Information</label>

                        <div class="row g-2 mb-3">
                            <div class="col-8">
                                <label for="edit-position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="edit-position" name="position" required>
                                <div class="invalid-feedback">Please enter a position.</div>
                            </div>
                            <div class="col-4">
                                <label for="edit-salary-grade" class="form-label">Salary Grade</label>
                                <input type="text" class="form-control" id="edit-salary-grade" name="salary_grade">
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label for="edit-role" class="form-label">System Role</label>
                                <select class="form-select" id="edit-role" name="role" required>
                                    <option value="employee">Employee</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="division_head">Division Head</option>
                                    <option value="penro">PENRO</option>
                                    <option value="records">Records</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div class="invalid-feedback">Please select a role.</div>
                            </div>
                            <div class="col-6">
                                <label for="edit-division_unit" class="form-label">Division Unit</label>
                                <select class="form-select" id="edit-division_unit" name="division_unit" required>
                                    <?php foreach ($divunits as $divunit): ?>
                                        <option value="<?= $divunit['type'] . '-id-' . $divunit['id'] ?>">
                                            <?= ucfirst($divunit['type']) . ': ' . $divunit['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select a role.</div>
                            </div>
                        </div>
                        <!-- End :: Work Information -->

                    </div><!-- /#edit-user-state-content -->

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="edit-user-submit-btn" disabled>
                        <i class="bi bi-floppy2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END :: modal edit user entry -->


<!-- BEGIN :: Edit User Entry Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var defaultAvatar = '<?= base_url('defaultProfile.jpg') ?>';
        var bsEditModal = new bootstrap.Modal(document.getElementById('modal-edit-user-entry'));

        // ── State machine ──────────────────────────────────────────────────
        function showEditState(state) {
            ['loading', 'error', 'content'].forEach(function(s) {
                document.getElementById('edit-user-state-' + s)
                    .classList.toggle('d-none', s !== state);
            });

            // Disable submit while loading or errored
            document.getElementById('edit-user-submit-btn').disabled = (state !== 'content');
        }

        // ── Populate form fields ───────────────────────────────────────────
        function populateEditForm(d) {
            document.getElementById('edit-user-id').value = d.user_id || '';
            document.getElementById('edit-first-name').value = d.first_name || '';
            document.getElementById('edit-last-name').value = d.last_name || '';
            document.getElementById('edit-email').value = d.email || '';
            document.getElementById('edit-position').value = d.position || '';
            document.getElementById('edit-salary-grade').value = d.salary_grade || '';
            document.getElementById('edit-role').value = d.role || 'employee';

            var img = document.getElementById('edit-user-profile-picture');
            img.src = d.profile_picture_url || defaultAvatar;
            img.onerror = function() {
                this.src = defaultAvatar;
            };
        }

        // ── Profile picture preview on file select ─────────────────────────
        document.getElementById('edit-profile-picture-input').addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('edit-user-profile-picture').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // ── Delegated click — open modal and fetch user data ───────────────
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-edit-user-entry');
            if (!btn) return;

            var id = btn.getAttribute('data-id');
            if (!id) return;

            // Reset form and show loading
            document.getElementById('form-edit-user-entry').classList.remove('was-validated');
            document.getElementById('edit-profile-picture-input').value = '';
            showEditState('loading');
            bsEditModal.show();

            fetch('<?= site_url('user-management/details') ?>/' + id, {
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
                    populateEditForm(json.data);
                    showEditState('content');
                })
                .catch(function(err) {
                    document.getElementById('edit-user-error-msg').textContent =
                        err.message || 'Failed to load user data.';
                    showEditState('error');
                });
        });

        // ── Form submit ────────────────────────────────────────────────────
        document.getElementById('form-edit-user-entry').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            var userId = document.getElementById('edit-user-id').value;
            var formData = new FormData(this);

            fetch('<?= site_url('user-management/update') ?>/' + userId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                })
                .then(function(res) {
                    if (!res.ok) throw new Error('Server returned ' + res.status);
                    return res.json();
                })
                .then(function(json) {
                    if (!json.success) throw new Error(json.message || 'Update failed.');

                    bsEditModal.hide();

                    // Reload the DataTable to reflect changes
                    var table = $('#user_management_table').DataTable();
                    if (table) table.ajax.reload(null, false);

                    // Flash a toast if your toast system is available
                    session_toast = {
                        type: 'success',
                        message: json.message || 'User updated successfully.'
                    };
                })
                .catch(function(err) {
                    document.getElementById('edit-user-error-msg').textContent =
                        err.message || 'Failed to update user.';
                    showEditState('error');
                });
        });

    });
</script>
<!-- END :: Edit User Entry Script -->

<!-- BEGIN :: View User Entry Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {


        var defaultAvatar = '<?= base_url('defaultProfile.jpg') ?>';

        function setText(id, val) {
            var el = document.getElementById(id);
            if (el) el.textContent = val || '—';
        }

        function fmtDate(str) {
            if (!str) return '—';
            var d = new Date(str.replace(' ', 'T'));
            if (isNaN(d.getTime())) return '—';
            return d.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        var bsModal = new bootstrap.Modal(document.getElementById('modal-view-user-entry'));

        function showState(state) {
            ['loading', 'error', 'content'].forEach(function(s) {
                document.getElementById('user-state-' + s)
                    .classList.toggle('d-none', s !== state);
            });
        }

        function populateModal(d) {
            var fullName = ((d.first_name || '') + ' ' + (d.last_name || '')).trim();
            setText('user-modal-fullname', fullName);
            setText('user-first-name', d.first_name);
            setText('user-last-name', d.last_name);
            setText('user-email', d.email);
            setText('user-position', d.position);
            setText('user-salary-grade', d.salary_grade || 'N/A');
            setText('user-role', d.role ? d.role.replace('_', ' ') : '—');
            setText('user-created-at', fmtDate(d.created_at));

            var img = document.getElementById('user-profile-picture');
            img.src = d.profile_picture_url || defaultAvatar;
            img.onerror = function() {
                this.src = defaultAvatar;
            };


            document.getElementById('user-btn-edit').setAttribute('data-id', d.user_id);
        }

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-view-user-entry');
            if (!btn) return;

            var id = btn.getAttribute('data-id');
            if (!id) return;
            showState('loading');
            bsModal.show();
            fetch('<?= site_url('user-management/details') ?>/' + id, {
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
                })
                .catch(function(err) {
                    document.getElementById('user-error-msg').textContent =
                        err.message || 'Failed to load user details.';
                    showState('error');
                });
        });
        document.getElementById('user-btn-edit').addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            if (id) {
                window.location.href = '<?= site_url('user-management/edit') ?>/' + id;
            }
        });
    });
</script>
<!-- END :: View User Entry Script -->

<?= $this->endSection() ?>