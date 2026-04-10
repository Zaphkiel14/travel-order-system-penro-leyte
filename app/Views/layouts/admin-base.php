<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <title><?= $title ?></title>
  <?= $this->include('links/adminltelinks') ?>
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <?= $this->include('partials/user-header') ?>
    <!--end::Header-->
    <!--begin::Sidebar-->
    <?= $this->include('partials/admin-sidebar') ?>

    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0"><?= $page ?></h3>
              <p class="mb-0"><?= $description ?? '' ?></p>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $page ?></li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>

      <!--end::App Content Header-->
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
<?php
$alerts = session()->getFlashdata('alerts');
if (! empty($alerts)): ?>
    <?php foreach ($alerts as $alert): ?>
    <div class="alert alert-<?= esc($alert['type']) ?> alert-dismissible fade show">
        <strong><?= esc($alert['title']) ?></strong>
        <?= $alert['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach;
    // keep them if you expect another redirect
    session()->keepFlashdata('alerts');
endif;
?>
        <?= $this->renderSection('content') ?>
        <!--end::Container-->
      </div>
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <?= $this->include('partials/user-footer') ?>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <?= $this->include('scripts/adminltescripts') ?>
</body>
<!--end::Body-->

</html>