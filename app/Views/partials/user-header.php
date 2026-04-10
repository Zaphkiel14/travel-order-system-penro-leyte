    <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Start Navbar Links-->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>

                <li class="nav-item d-none d-md-block">
                    <span class="nav-link">

                        Field Office: <b><?= session('field_office_name') ?? ''  ?></b>
                    </span>
                </li>
            </ul>
            <!--end::Start Navbar Links-->
            <!--begin::End Navbar Links-->
            <ul class="navbar-nav ms-auto">
                <!--begin::Navbar Search-->
                <!-- <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
            </a>
            </li> -->
                <!--end::Navbar Search-->
                <!--begin::Messages Dropdown Menu-->


                <!--end::Messages Dropdown Menu-->
                <!--begin::Notifications Dropdown Menu-->

                <!--end::Notifications Dropdown Menu-->

                <!--begin::Fullscreen Toggle-->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                        <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                        <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                    </a>
                </li>
                <!--end::Fullscreen Toggle-->
                <!-- begin:: QR Quick Scan -->
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="modal" data-bs-target="#qrScannerModal">
                        <i class="bi bi-qr-code-scan"></i>
                    </button>
                </li>
                <!--end::QR Quick Scan-->
                <!--begin::User Menu Dropdown-->



                <li class="nav-item dropdown user-menu">

                    <?php
                    $session = session();
                    $fullName = $session->get('full_name') ?? 'User';
                    $position = $session->get('position') ?? '';

                    ?>

                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <span class="d-none d-md-inline"><?= $fullName ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <!--begin::User Image-->
                        <li class="user-header text-bg-primary">

                            <p>
                                <?= $fullName ?> - <?= $position ?>
                                <small>
                                    Account Created:
                                    <?php
                                    $createdAt = $session->get('created_at');
                                    if ($createdAt) {
                                        echo date('F j, Y', strtotime($createdAt));
                                    } else {
                                        echo 'Unknown';
                                    }
                                    ?>
                                </small>
                            </p>
                        </li>
                        <!--end::User Image-->
                        <!--begin::Menu Body-->
                        <li class="user-body">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-6 text-center"><?= $fullName ?></div>

                                <div class="col-2 text-center">-</div>
                                <div class="col-4 text-center"><?= $position ?></div>
                            </div>
                            <!--end::Row-->
                        </li>
                        <!--end::Menu Body-->
                        <!--begin::Menu Footer-->
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                            <a href="<?= site_url(route_to('logout')) ?>" class="btn btn-default btn-flat float-end">Sign out</a>

                        </li>
                        <!--end::Menu Footer-->
                    </ul>
                </li>
                <!--end::User Menu Dropdown-->
            </ul>
            <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
    </nav>


    <div class="modal fade" id="qrScannerModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Scan Inventory QR Code</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <div id="qr-reader" style="width:100%"></div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://unpkg.com/html5-qrcode"></script>
<script>
function startScanner() {

    const scanner = new Html5Qrcode("qr-reader");

    scanner.start(
        { facingMode: "environment" }, // use back camera
        { fps: 24, qrbox: 500, disableFlip: true },

        (decodedText) => {

            console.log("QR Code scanned:", decodedText);

            // stop scanner immediately
            scanner.stop().then(() => {

                console.log("Scanner stopped");

                // remove possible prefix like ICT:
                let code = decodedText.split(":").pop().trim();

                // redirect to universal scan route
                window.location.href = "/inventory/scan/" + encodeURIComponent(code);

            }).catch((err) => {
                console.error("Error stopping scanner:", err);
            });

        },

        (errorMessage) => {
            // optional error logging
            // console.warn(errorMessage);
        }
    );
}
// Trigger scanner when modal is shown
document.getElementById("qrScannerModal").addEventListener("shown.bs.modal", startScanner);
</script>
    <!-- <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function startScanner() {

            const scanner = new Html5Qrcode("qr-reader");

            scanner.start({
                    facingMode: "environment"
                }, // use back camera
                {
                    fps: 24,
                    qrbox: 500,
                    disableFlip: true
                },
                (decodedText) => {

                    console.log("QR Code:", decodedText);

                    // redirect to inventory item
                    window.location.href = "/inventory/item/" + decodedText;

                },
                (errorMessage) => {
                    // ignore scanning errors
                }
            );
        }

        document.getElementById("qrScannerModal").addEventListener("shown.bs.modal", startScanner);
    </script> -->