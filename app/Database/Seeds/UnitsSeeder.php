<?= $this->extend('layouts/admin-base') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
<div class="row">
<div class="col-12">

<div class="card">
<div class="card-header">
    <h3 class="card-title">Expandable Tree Table (Multi-Column)</h3>
</div>

<div class="card-body p-0">

<table class="table table-hover mb-0">

<!-- HEADER -->
<thead>
<tr>
    <th style="width: 180px;">Code</th>
    <th>Name</th>
    <th style="width: 160px;">Actions</th>
</tr>
</thead>

<tbody>

    <!-- ================= LEVEL 1 : ORGANIZATION ================= -->
    <tr class="expand-toggle" data-target="children-org-<?= $organization->organization_id ?>">
        <td>
            <i class="fas fa-caret-right me-2 toggle-icon"></i>
            <?= esc($organization->organization_code) ?>
        </td>
        <td><?= esc($organization->organization_name) ?></td>
        <td>
            <button class="btn btn-sm btn-primary">View</button>
            <button class="btn btn-sm btn-warning">Edit</button>
        </td>
    </tr>

    <?php foreach ($divisions as $division): ?>

        <!-- ================= LEVEL 2 : DIVISION ================= -->
        <tr class="tree-child children-org-<?= $organization->organization_id ?> expand-toggle d-none"
            data-target="children-div-<?= $division->division_id ?>">
            <td style="padding-left: 30px;">
                <i class="fas fa-caret-right me-2 toggle-icon"></i>
                <?= esc($division->division_code) ?>
            </td>
            <td><?= esc($division->division_name) ?></td>
            <td>
                <button class="btn btn-sm btn-primary">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
            </td>
        </tr>

        <?php foreach ($division->units as $unit): ?>

            <!-- ================= LEVEL 3 : UNIT ================= -->
            <tr class="tree-child children-div-<?= $division->division_id ?> d-none">
                <td style="padding-left: 60px;">
                    <?= esc($unit->unit_code) ?>
                </td>
                <td><?= esc($unit->unit_name) ?></td>
                <td>
                    <button class="btn btn-sm btn-primary">View</button>
                    <button class="btn btn-sm btn-warning">Edit</button>
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
<?= $this->extend('layouts/admin-base') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
<div class="row">
<div class="col-12">

<div class="card">
<div class="card-header">
    <h3 class="card-title">Expandable Tree Table (Multi-Column)</h3>
</div>

<div class="card-body p-0">

<table class="table table-hover mb-0">

<!-- HEADER -->
<thead>
<tr>
    <th style="width: 180px;">Code</th>
    <th>Name</th>
    <th style="width: 160px;">Actions</th>
</tr>
</thead>

<tbody>

    <!-- ================= LEVEL 1 : ORGANIZATION ================= -->
    <tr class="expand-toggle" data-target="children-org-<?= $organization->organization_id ?>">
        <td>
            <i class="fas fa-caret-right me-2 toggle-icon"></i>
            <?= esc($organization->organization_code) ?>
        </td>
        <td><?= esc($organization->organization_name) ?></td>
        <td>
            <button class="btn btn-sm btn-primary">View</button>
            <button class="btn btn-sm btn-warning">Edit</button>
        </td>
    </tr>

    <?php foreach ($divisions as $division): ?>

        <!-- ================= LEVEL 2 : DIVISION ================= -->
        <tr class="tree-child children-org-<?= $organization->organization_id ?> expand-toggle d-none"
            data-target="children-div-<?= $division->division_id ?>">
            <td style="padding-left: 30px;">
                <i class="fas fa-caret-right me-2 toggle-icon"></i>
                <?= esc($division->division_code) ?>
            </td>
            <td><?= esc($division->division_name) ?></td>
            <td>
                <button class="btn btn-sm btn-primary">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
            </td>
        </tr>

        <?php foreach ($division->units as $unit): ?>

            <!-- ================= LEVEL 3 : UNIT ================= -->
            <tr class="tree-child children-div-<?= $division->division_id ?> d-none">
                <td style="padding-left: 60px;">
                    <?= esc($unit->unit_code) ?>
                </td>
                <td><?= esc($unit->unit_name) ?></td>
                <td>
                    <button class="btn btn-sm btn-primary">View</button>
                    <button class="btn btn-sm btn-warning">Edit</button>
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

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

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