
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= base_url('dashboard') ?>"
          class="nav-link">

          <span class="brand-text fw-light">TRAVEL ORDER SYSTEM</span>
        </a>
        <!--begin::Brand Image-->

        <!--end::Brand Image-->
        <!--begin::Brand Text-->
        <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
      </div>
      <!--end::Sidebar Brand-->
      <!--begin::Sidebar Wrapper-->
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <!--begin::Sidebar Menu-->
          <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="navigation"
            aria-label="Main navigation"
            data-accordion="false"
            id="navigation">

            <!-- begin::Dashboard -->
            <li class="nav-item">
              <a href="<?= base_url('dashboard') ?>" class="nav-link <?= ($page == 'Dashboard') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-house-door"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <!-- end::Dashboard -->


            <!-- begin:: PAR Management  -->
            <li class="nav-item">
              <a href="<?= base_url('incoming-travel-orders') ?>" class="nav-link <?= ($page == 'Incoming Travel Orders') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-envelope-arrow-down-fill"></i>
                <p>Incoming Travel<br>Orders</p>
                <?php $count = pending_count(); ?>

                <?php if ($count > 0): ?>
                  <span class="nav-badge badge text-bg-danger me-3">
                    <?= $count ?>
                  </span>
                <?php endif; ?>
              </a>
            </li>
            <!-- end:: PAR Management  -->
            <!-- begin:: ICS Management  -->
            <li class="nav-item">
              <a href="<?= base_url('processed-travel-orders') ?>" class="nav-link <?= ($page == 'Processed Travel Orders') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-envelope-arrow-up-fill"></i>
                <p>Processed Travel<br>Orders</p>
              </a>
            </li>
            <!-- end:: ICS Management  -->

          </ul>
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>