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
                                    <!-- <button class="btn btn-success btn-edit"
                                        data-type="organization"
                                        data-id="<?= $orgstructure->organization_id ?>"><i class="bi bi-pencil-square"></i></button> -->
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
                                        <!-- <button class="btn btn-success btn-edit"
                                            data-type="division"
                                            data-id="<?= $division->division_id ?>"><i class="bi bi-pencil-square"></i></button> -->
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
                                            <!-- <button class="btn btn-success btn-edit"
                                                data-type="unit"
                                                data-id="<?= $unit->unit_id ?>"><i class="bi bi-pencil-square"></i></button> -->
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



<!-- BEGIN : Update Organization Modal -->
<div class="modal fade" id="modal-update-organization">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route_to('') ?>" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h4 class="modal-title">Add Organization</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="organization_name" class="form-label">Organization Name:</label>
                                <input type="text" class="form-control" name="organization_name" placeholder="Enter Organization Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="organization_head_position" class="form-label">Organization Head Position:</label>
                                <input type="text" class="form-control" name="organization_head_position" placeholder="Enter Organization Head Position" required>
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
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-floppy2"></i>Update Organization</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  -->


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
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-floppy2"></i>Update Organization</button>
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
                                <label for="unit_head_position " class="form-label">Unit Head Position:</label>
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
                    <button type="submit" class="btn btn-primary" name="submit"><i class="bi bi-floppy2"></i>Update Organization</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Tree toggle
        document.querySelectorAll('.expand-toggle').forEach(row => {
            row.addEventListener('click', function(e) {
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

        // View buttons
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                const id = this.dataset.id;

                const routes = {
                    organization: `/admin/organization/${id}`,
                    division: `/admin/division/${id}`,
                    unit: `/admin/unit/${id}`,
                };

                window.location.href = routes[type];
            });
        });

        // Edit buttons
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                const id = this.dataset.id;

                const routes = {
                    organization: `/admin/organization/${id}/edit`,
                    division: `/admin/division/${id}/edit`,
                    unit: `/admin/unit/${id}/edit`,
                };

                window.location.href = routes[type];
            });
        });

    });
</script>

<!-- STYLE -->
<style>
    .expand-toggle {
        cursor: pointer;
    }

    .expand-toggle:hover {
        background: #f8f9fa;
    }

    .toggle-icon {
        transition: transform 0.2s ease;
    }
</style>

<?= $this->endSection() ?>