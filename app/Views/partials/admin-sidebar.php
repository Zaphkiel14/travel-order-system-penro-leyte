    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= session('role') === 'admin'
                    ? route_to('admin.dashboard')
                    : route_to('user.dashboard') ?>"
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
              <a href="<?= route_to('view.dashboard') ?>" class="nav-link <?= ($page == 'Dashboard') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-house-door"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <!-- end::Dashboard -->

            <!-- begin:: Travel Orders -->
            <li class="nav-item">
              <a href="<?= route_to('view.travel-orders') ?>" class="nav-link <?= ($page == 'Travel Orders') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-envelope-paper-fill"></i>
                <p>Travel Orders</p>
              </a>
            </li>
            <!-- end:: Travel Orders -->

            <!-- begin:: PAR Management  -->
            <li class="nav-item">
              <a href="<?= route_to('view.incoming-travel-orders') ?>" class="nav-link <?= ($page == 'Incoming Travel Orders') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-envelope-arrow-down-fill"></i>
                <p>Incoming Travel Orders</p>
              </a>
            </li>
            <!-- end:: PAR Management  -->
            <!-- begin:: ICS Management  -->
            <li class="nav-item">
              <a href="<?= route_to('view.processed-travel-orders') ?>" class="nav-link <?= ($page == 'Processed Travel Orders') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-envelope-arrow-up-fill"></i>
                <p>Processed Travel Orders</p>
              </a>
            </li>
            <!-- end:: ICS Management  -->
            <!-- begin:: User Management -->
            <li class="nav-item">
              <a href="<?= route_to('admin.user.management') ?>" class="nav-link <?= ($page == 'User Management') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-person-fill-gear"></i>
                <p>User Management</p>
              </a>
            </li>
            <!-- end:: User Management -->
            <!-- begin:: Configuration -->
            <li class="nav-item">
              <a href="<?= route_to('view.configuration') ?>" class="nav-link <?= ($page == 'Configuration') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-gear-fill"></i>
                <p>Configuration</p>
              </a>
            </li>
            <!-- end:: Configuration -->
          </ul>
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>