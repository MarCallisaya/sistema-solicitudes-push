 <!--********************************** Sidebar start***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">

            <li class="nav-label first">
                <span>
                    <?php
                        $rol = $this->session->userdata('rol_id');

                        if ($rol == 1) {
                            echo 'Profesor';
                        } elseif ($rol == 2) {
                            echo 'Director';
                        } elseif ($rol == 3) {
                            echo 'Administrador';
                        }
                    ?>
                </span>
            </li>

            <li class="nav-label first">
                <span >
                    <?=
                        $this->session->userdata('nombre') . ' ' .
                        $this->session->userdata('apellido_paterno') . ' ' .
                        $this->session->userdata('apellido_materno');
                    ?>
                </span>              
            </li>

           

            <li><a href="<?= site_url('director/C_solicitudD'); ?>" aria-expanded="false">
                    <i class="icon icon-form"></i>
                    <span class="nav-text">Solicitudes</span>
                </a>
            </li>

            <li><a href="widget-basic.html" aria-expanded="false"><i class="icon icon-single-04"></i><span
                         class="nav-text">Usuarios</span></a></li>
            <li><a href="widget-basic.html" aria-expanded="false"><i class="icon icon-app-store"></i><span
                         class="nav-text">Unidades Educativas</span></a></li>

        </ul>
    </div>
</div>
 <!--********************************** Sidebar end ***********************************-->