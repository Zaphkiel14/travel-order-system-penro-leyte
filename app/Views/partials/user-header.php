    <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>

                <li class="nav-item d-none d-md-block">
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                        <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                        <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                    </a>
                </li>
                <?php
                $session = session();
                $profilePicture = $session->get('profile_picture');
                $fullName       = $session->get('full_name') ?? 'User';
                $userRole       = $session->get('user_role') ?? '';
                $defaultAvatar = base_url('defaultProfile.jpg');
                if (!empty($profilePicture) && file_exists(FCPATH . $profilePicture)) {
                    $avatarSrc = base_url($profilePicture);
                } else {
                    $avatarSrc = $defaultAvatar;
                }
                ?>
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img
                            src="<?= esc($avatarSrc) ?>"
                            class="user-image rounded-circle shadow"
                            alt="User Image" />
                        <span class="d-none d-md-inline"><?= esc($fullName) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                            <img
                                src="<?= esc($avatarSrc) ?>"
                                class="rounded-circle shadow"
                                alt="User Image" />
                            <p>
                                <?= esc($fullName) ?>
                                <?= $userRole ? ' - ' . esc(ucfirst($userRole)) : '' ?>
                            </p>
                            <small>
                                Member since:
                                <?php
                                $createdAt = $session->get('created_at');
                                echo $createdAt ? date('F j, Y', strtotime($createdAt)) : 'Unknown';
                                ?>
                            </small>
                        </li>
                        <li class="user-footer">
                            <a href="<?= site_url(route_to('account-settings')) ?>" class="btn btn-default btn-flat">Profile</a>
                            <a href="<?= site_url(route_to('logout')) ?>" class="btn btn-default btn-flat float-end">Sign out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
    </nav>