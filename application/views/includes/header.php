<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Unidad Educativa Kasani </title>
    <!-- Favicon icon -->

    <link rel="stylesheet" href="<?= base_url('assets/vendor/owl-carousel/css/owl.carousel.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/owl-carousel/css/owl.theme.default.min.css'); ?>">
    <link href="<?= base_url('assets/vendor/jqvmap/css/jqvmap.min.css'); ?>" rel="stylesheet">

    <!-- Datatable -->
    <link href="<?= base_url('assets/vendor/datatables/css/jquery.dataTables.min.css'); ?>" rel="stylesheet">
    <!-- Sweetalert -->
    <link href="<?= base_url('assets/vendor/sweetalert2/dist/sweetalert2.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <!-- Para las letras de l contenido -->
    <!--  <link href="<?= base_url('assets/css/override.css'); ?>" rel="stylesheet"> -->


</head>

<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">

        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <span> UE. KASANI </span>


            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="search_bar dropdown">

                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                <p> Bienvenido: <?= $this->session->userdata('nombre'); ?></p>
                            </li>

                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="<?= site_url('C_login/logout') ?>" class="dropdown-item">
                                        <i class="icon-key"></i>
                                        <span class="ml-2">Cerrar sesion </span>
                                    </a>

                                    <!-- 13 1 26-->
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="activarNotificaciones()">
                                        <i class="icon-bell"></i>
                                        <span class="ml-2">Activar notificaciones</span>
                                    </a>

                                    <!-- 13 1 26-->

                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->