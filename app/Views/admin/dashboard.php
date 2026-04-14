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
                    <h3 class="card-title flex-grow-1">My Travel Orders</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-travel-order">
                            <i class="bi bi-plus-lg"></i> New Travel Order
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="inventory_table"
                        class="table table-bordered table-striped datatable-standard"
                        data-last-column-width="100"
                        data-url="<?= route_to('search.ict-equipment') ?>">
                        <thead>
                            <tr>
                                <th>Date Submitted</th>
                                <th>Destination</th>
                                <th>Travel Dates</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Date Submitted</th>
                                <th>Destination</th>
                                <th>Travel Dates</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal -->
<div class="modal fade" id="modal-add-travel-order">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= route_to('dashboard.createTravelOrder') ?>" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h4 class="modal-title">Travel Order Application Form</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="callout callout-info mb-3">
                                <p class="fs-6 mb-0">Property Number</p>
                                <p class="fs-3 mb-0"><b>Travel Order #2026-0001</b></p>
                                <small class="mb-0 text-muted">Auto-generated unique identifier</small>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!-- Begin :: Personal Information -->
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fs-4 mb-0">Personal Information</label>
                            <button type="button" class="btn btn-success btn-sm" id="add-person">
                                <i class="bi bi-plus"></i> Add Person
                            </button>
                        </div>
                    </div>

                    <div id="person-container">
                        <div class="person-group border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-muted small">Person #1</span>
                                <button type="button" class="btn btn-danger btn-sm delete-person" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="persons[0][name]" required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label">Salary Grade</label>
                                    <input type="text" class="form-control" name="persons[0][salary_grade]" required>
                                </div>
                                <div class="col-5">
                                    <label class="form-label">Position</label>
                                    <input type="text" class="form-control" name="persons[0][position]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="division_section_unit" class="form-label"></label>ROUTE TO: PENRO/Division/Section/Unit</label>
                                <input type="text" class="form-control" id="division_section_unit" name="division_section_unit" required>
                                <div class="invalid-feedback">
                                    Please Enter Division/Section/Unit.
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End :: Personal Information -->
                    <hr>
                    <!-- Begin :: Travel Details -->
                    <div class="row">
                        <label class="form-label fs-4">Travel Details</label>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="departure_date" class="form-label">Departure Date</label>
                                    <div class="input-group date-picker">
                                        <input type="text" class="form-control" name="departure_date" placeholder="YYYY-MM-DD" required>
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="arrival_date" class="form-label">Arrival Date</label>
                                <div class="input-group date-picker">
                                    <input type="text" class="form-control" name="arrival_date" placeholder="YYYY-MM-DD" required>
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="destination" class="form-label">Destination</label>
                                <input type="text" class="form-control" id="destination" name="destination" required>
                                <div class="invalid-feedback">
                                    Please Enter Travel Destination.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="travel_purpose" class="form-label">Travel Purpose</label>
                                <textarea class="form-control" id="travel_purpose" name="travel_purpose" rows="3" required></textarea>
                                <div class="invalid-feedback">
                                    Please Enter Travel Purpose.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End :: Travel Details -->
                    <hr>
                    <!-- Begin :: Supporting Documents -->
                    <div class="row">
                        <label class="form-label fs-4">Supporting Documents</label>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="request_memo" class="form-label">Request Memo</label>
                                <input type="file" class="form-control" id="request_memo" name="request_memo" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Request Memo.
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="special_order" class="form-label">Special Order</label>
                                <input type="file" class="form-control" id="special_order" name="special_order" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Special Order.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="request_letter" class="form-label">Request Letter</label>
                                <input type="file" class="form-control" id="request_letter" name="request_letter" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Request Letter.
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="invitation_letter" class="form-label">Invitation Letter</label>
                                <input type="file" class="form-control" id="invitation_letter" name="invitation_letter" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Invitation Letter.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="training_notification" class="form-label">Training Notification</label>
                                <input type="file" class="form-control" id="training_notification" name="training_notification" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Training Notification.
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="meeting_notice" class="form-label">Meeting Notice</label>
                                <input type="file" class="form-control" id="meeting_notice" name="meeting_notice" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Meeting Notice.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="conference_program" class="form-label">Conference Program</label>
                                <input type="file" class="form-control" id="conference_program" name="conference_program" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Conference Program.
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-3">
                                <label for="other_document" class="form-label">Other Document</label>
                                <input type="file" class="form-control" id="other_document" name="other_document" multiple>
                                <div class="invalid-feedback">
                                    Please Upload Other Document.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End :: Supporting Documents -->
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-floppy2"></i> Submit Travel Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

<?= $this->endSection() ?>