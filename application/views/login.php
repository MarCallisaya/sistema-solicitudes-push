<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>UE KASANI </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet">

</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h3 class="text-center mb-4">UNIDAD EDUCATIVA KASANI</h3>
                                    <h4 class="text-center mb-4">Iniciar Sesion</h4>
                                    <form action="<?= base_url('C_login/login') ?>" method="post">
                                        <?php if ($this->session->flashdata('error')): ?>
                                            <div class="alert alert-danger">
                                                <?= $this->session->flashdata('error') ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label><strong>Usuario</strong></label>
                                            <input name="username" type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Contraseña</strong></label>
                                            <input name="password" type="password" class="form-control" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="<?= base_url('assets/vendor/global/global.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/quixnav-init.js'); ?>"></script>
    <script src="<?= base_url('assets/js/custom.min.js'); ?>"></script>

</body>

</html>