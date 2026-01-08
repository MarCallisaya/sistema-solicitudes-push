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



            <li>
                <a  href="<?= site_url('administrador/C_solicitudA'); ?>" aria-expanded="false">
                    <i class="icon icon-form"></i>
                    <span class="nav-text">Solicitudes</span>
                </a>
            </li>
            
            <li><a href="widget-basic.html" aria-expanded="false"><i class="icon icon-plug"></i><span
                         class="nav-text">Notificaciones</span></a></li>
            <li>
                <a href="<?= site_url('administrador/C_documento'); ?>" aria-expanded="false">
                    <i class="icon icon-single-copy-06"></i>
                    <span class="nav-text">Documentos</span>
                </a>
            </li>

            <li>
                <a href="<?= site_url('usuarios/C_usuarios'); ?>" aria-expanded="false">
                    <i class="icon icon-single-04"></i>
                    <span class="nav-text">Usuarios</span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('unidad/C_unidad'); ?>" aria-expanded="false">
                    <i class="icon icon-app-store"></i>
                    <span class="nav-text">Unidades Educativas</span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('metricas/C_metricas'); ?>" aria-expanded="false">
                    <i class="icon icon-chart-bar-33"></i>
                    <span class="nav-text">Metricas</span>
                </a>
            </li>
         </ul>
     </div>
 </div>
 <!--********************************** Sidebar end ***********************************-->