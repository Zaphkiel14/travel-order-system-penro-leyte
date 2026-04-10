    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= session('role') === 'admin'
                    ? route_to('admin.dashboard')
                    : route_to('user.dashboard') ?>"
          class="nav-link">

          <span class="brand-text fw-light">DENR ICT INVENTORY</span>
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
              <a href="<?= route_to('dashboard') ?>"
                class="nav-link <?= ($page == 'Dashboard') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-house-door"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <!-- end::Dashboard -->
            <!-- begin:: Inventory -->
            <li class="nav-item <?= (($parentSidebar ?? '') === 'Inventory') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= (($parentSidebar ?? '') === 'Inventory') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-inboxes-fill"></i>
                <p>Inventory
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <!-- begin:: Inventory Submenu -->
              <ul class="nav nav-treeview">
                <!-- begin:: ICT Equipment -->
                <li class="nav-item <?= ($page == 'ICT Equipment') ? 'menu-open' : ''  ?>">
                  <a href="<?= route_to('view.ict-equipment') ?>" class="nav-link <?= ($page == 'ICT Equipment') ? 'active' : '' ?>">
                    <i class="nav-icon bi bi-pc-display"></i>
                    <p>ICT Equipment
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <!-- begin:: ICT Equipment Submenu -->
                  <ul class="nav nav-treeview">
                    <!-- begin:: ICT Maintenance -->
                    <li class="nav-item">
                      <a href="<?= route_to('view.maintenance') ?>" class="nav-link <?= ($page == 'Maintenance') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-tools"></i>
                        <p>Maintenance</p>
                      </a>
                    </li>
                    <!-- end:: ICT Maintenance -->
                  </ul>
                  <!-- end :: ICT Equipment Submenu -->
                </li>
                <!-- end:: ICT Equipment -->
                <!-- begin:: Technical & Scientific Equipment -->
                <li class="nav-item">
                  <a href="<?= route_to('view.technical-scientific-equipment') ?>" class="nav-link">
                    <i class="nav-icon bi bi-cpu"></i>
                    <p>Technical & Scientific<br>Equipment</p>
                  </a>
                </li>
                <!-- end:: Technical & Scientific Equipment -->
                <!-- begin:: Furniture, Fixtures & Books-->
                <li class="nav-item">
                  <a href="<?= route_to('view.furniture-fixture-books') ?>" class="nav-link">
                    <i class="nav-icon bi bi-bookshelf"></i>
                    <p>Furniture, Fixtures &<br>Books</p>
                  </a>
                </li>
                <!-- end:: Furniture, Fixtures & Books-->
                <!-- begin:: Communication Equipment -->
                <li class="nav-item">
                  <a href="<?= route_to('view.communication-equipment') ?>" class="nav-link">
                    <i class="nav-icon bi bi-broadcast"></i>
                    <p>Communication<br>Equipment</p>
                  </a>
                </li>
                <!-- end:: Communication Equipment -->
                <!-- begin:: Transportation Equipment -->
                <li class="nav-item">
                  <a href="<?= route_to('view.transportation-equipment') ?>" class="nav-link">
                    <i class="nav-icon bi bi-truck"></i>
                    <p>Transportation<br>Equipment</p>
                  </a>
                </li>
                <!-- end:: Transportation Equipment -->
                <!-- begin:: Watercraft -->
                <li class="nav-item">
                  <a href="<?= route_to('view.watercraft') ?>" class="nav-link">
                    <i class="nav-icon bi bi-water"></i>
                    <p>Watercraft</p>
                  </a>
                </li>
                <!-- end:: Watercraft -->
                <!-- begin::Building & Other Structure -->
                <li class="nav-item">
                  <a href="<?= route_to('view.building-other-structure') ?>" class="nav-link">
                    <i class="nav-icon bi bi-building-gear"></i>
                    <p>Building & Other<br>Structure</p>
                  </a>
                </li>
                <!-- end::Building & Other Structure -->
                <!-- begin:: Office Equipment & Other Machines -->
                <li class="nav-item">
                  <a href="<?= route_to('view.office-equipment-other-machines') ?>" class="nav-link">
                    <i class="nav-icon bi bi-printer"></i>
                    <p>Office Equipment &<br>Other Machines</p>
                  </a>
                </li>
                <!-- end:: Office Equipment & Other Machines -->
                <!-- begin::Water Supply Systems -->
                <li class="nav-item">
                  <a href="<?= route_to('view.water-supply-systems') ?>" class="nav-link">
                    <i class="nav-icon bi bi-droplet"></i>
                    <p>Water Supply Systems</p>
                  </a>
                </li>
                <!-- end::Water Supply Systems -->
                <!-- begin:: Flood Control Systems -->
                <li class="nav-item">
                  <a href="<?= route_to('view.flood-control-systems') ?>" class="nav-link">
                    <i class="nav-icon bi bi-bricks "></i>
                    <p>Flood Control Systems</p>
                  </a>
                </li>
                <!-- end:: Flood Control Systems -->
                <!-- begin:: Other Structures -->
                <li class="nav-item">
                  <a href="<?= route_to('view.other-structures') ?>" class="nav-link">
                    <i class="nav-icon bi bi-diagram-3"></i>
                    <p>Other Structures</p>
                  </a>
                </li>
                <!-- end:: Other Structures -->
                <!-- begin:: Equipment From Regional Office -->
                <li class="nav-item">
                  <a href="<?= route_to('view.equipment-from-regional-office') ?>" class="nav-link">
                    <i class="nav-icon bi bi-box-arrow-in-down"></i>
                    <p>Equipment From Regional Office</p>
                  </a>
                </li>
                <!-- end:: Equipment From Regional Office -->
              </ul>
              <!-- end:: Inventory Submenu -->
            </li>
            <!-- end:: Inventory -->
            <!-- begin:: PAR Management -->
            <li class="nav-item">
              <a href="<?= route_to('view.par') ?>" class="nav-link <?= ($page == 'PAR Management') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-file-earmark-medical-fill"></i>
                <p>PAR Management</p>
              </a>
            </li>
            <!-- end:: PAR Management -->
            <!-- begin:: ICS Management  -->
            <li class="nav-item">
              <a href="<?= route_to('view.ics') ?>" class="nav-link <?= ($page == 'ICS Management') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-file-earmark-medical-fill"></i>
                <p>ICS Management</p>
              </a>
            </li>
            <!-- end:: ICS Management  -->
          </ul>
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>