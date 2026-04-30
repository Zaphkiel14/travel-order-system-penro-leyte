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
                    <table id="my_travel_orders_table"
                        class="table table-bordered table-striped datatable-standard"
                        data-last-column-width="100"
                        data-page-length="10"
                        data-order='[[0,"desc"]]'
                        data-url="<?= route_to('data.travelOrders') ?>">
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

<?= $this->endSection() ?>