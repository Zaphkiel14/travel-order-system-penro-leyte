<!doctype html>
<html lang="en">

<head>
    <title>DENR ICT INVENTORY | Login</title>
    <?= $this->include('links/adminltelinks') ?>
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">

                <h1 class="mb-0 text-center"><b>PENRO</b></h1>
                <h2 class="mb-0 text-center">TRAVEL ORDER SYSTEM</h2>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <?php
                $alerts = session()->getFlashdata('alert');
                if (!empty($alerts)) :
                    // If it's a single alert (not an array of alerts)
                    if (isset($alerts['type'])) :
                ?>
                        <div class="alert alert-<?= esc($alerts['type']) ?> alert-dismissible fade show">
                            <strong><?= esc($alerts['title']) ?></strong>
                            <?= esc($alerts['message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php
                    else :
                        // If multiple alerts
                        foreach ($alerts as $alert) :
                        ?>
                            <div class="alert alert-<?= esc($alert['type']) ?> alert-dismissible fade show">
                                <strong><?= esc($alert['title']) ?></strong>
                                <?= esc($alert['message']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                <?php
                        endforeach;
                    endif;
                endif;
                ?>
                <form action="<?= route_to('auth.submit') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="loginEmail" type="email" name="email" class="form-control" value="" placeholder="" />
                            <label for="loginEmail">Email</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="loginPassword" type="password" name="password" class="form-control" placeholder="" />
                            <label for="loginPassword">Password</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <button class="btn btn-primary" type="submit">
                    Sign In
            </button>
                </div>
                </form>
                <script>
                    // Example starter JavaScript for disabling form submissions if there are invalid fields
                    (() => {
                        'use strict';

                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        const forms = document.querySelectorAll('.needs-validation');

                        // Loop over them and prevent submission
                        Array.from(forms).forEach((form) => {
                            form.addEventListener(
                                'submit',
                                (event) => {
                                    if (!form.checkValidity()) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }

                                    form.classList.add('was-validated');
                                },
                                false,
                            );
                        });
                    })();
                </script>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p>- OR -</p>
                    <a class="btn btn-danger" href="<?= route_to('google.login') ?>">
                        <i class="bi bi-google me-2"></i> Sign in using Google
                    </a>
                </div>
                <!-- /.social-auth-links -->
                <small class="text-muted"><i>First login: use Google Sign-In to connect your Drive.</i></small>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <?= $this->include('scripts/adminltescripts') ?>
</body>

</html>