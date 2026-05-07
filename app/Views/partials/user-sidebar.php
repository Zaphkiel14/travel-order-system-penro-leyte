    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= route_to('view.dashboard') ?>"
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

            
          </ul>
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>