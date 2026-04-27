<?php 
$role = session()->get('role');

// role-based layouts
$layouts = [
    'employee'       => 'layouts/user-base',
    'supervisor'     => 'layouts/user-base',
    'division_head'  => 'layouts/user-base',
    'penro'          => 'layouts/user-base',
    'records'        => 'layouts/user-base',
    'admin'          => 'layouts/admin-base',
];

$layout = $layouts[$role] ?? 'layouts/blank-base';

/* -------------------------
   ERROR DATA SAFETY
--------------------------*/
$code = $error_code ?? 500;
$type = $error_type ?? 'Error';
$message = $error_message ?? 'Something went wrong.';

/* -------------------------
   COLOR MAPPING (Bootstrap)
--------------------------*/
$colors = [
    400 => 'warning',
    401 => 'warning',
    403 => 'danger',
    404 => 'warning',
    422 => 'info',
    500 => 'danger',
];

$color = $colors[$code] ?? 'danger';
?>

<?= $this->extend($layout) ?>
<?= $this->section('content') ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 75vh;">

    <div class="text-center">

        <!-- ERROR CODE -->
        <h1 class="display-1 fw-bold text-<?= $color ?>">
            <?= esc($code) ?>
        </h1>

        <!-- ERROR TYPE -->
        <h3 class="mb-3">
            <?= esc($type) ?>
        </h3>

        <!-- ERROR MESSAGE -->
        <p class="text-muted mb-4">
            <?= $message ?>
        </p>

        <!-- ACTION BUTTONS -->
        <div class="d-flex justify-content-center gap-2">

            <a href="/" class="btn btn-<?= $color ?>">
                <i class="fas fa-home"></i> Go Home
            </a>

            <button onclick="history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Go Back
            </button>

        </div>

    </div>

</div>

<?= $this->endSection() ?>