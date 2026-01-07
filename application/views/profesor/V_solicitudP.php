<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">SOLICITUDES</h4>

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalSolicitud">
                                + Formulario
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_prof" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Tipo de solicitud</th>
                                        <th>Archivo</th>
                                        <th>Destinatario</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VER SOLICITUD (CARTA) -->
<div class="modal fade" id="modalVerSolicitud" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Ver Solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="cartaSolicitud" style="
       font-family: Arial, sans-serif;
       font-size: 16px;
       line-height: 1.6;
     ">
                    <div style="text-align:right;">
                        <span id="cartaFecha"></span>
                    </div>

                    <br>

                    <div>
                        <strong>Lic. <span id="cartaDirector"></span></strong><br>
                        <strong>DIRECTOR DE LA UNIDAD EDUCATIVA KASANI</strong>
                    </div>

                    <br>

                    <div style="text-align:right;">
                        <strong>Ref.:</strong> <span id="cartaReferencia"></span>
                    </div>

                    <br>

                    <div style="text-align:justify; white-space:pre-wrap;" id="cartaDescripcion"></div>

                    <br><br>

                    <div>
                        Atentamente.<br>
                        Prof: <span id="cartaProfesor"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>



<script>
    const BASE_URL = <?= json_encode(base_url()); ?>;
    const NOMBRE_PROFESOR = <?= json_encode(trim(
                                $this->session->userdata('nombre') . ' ' .
                                    $this->session->userdata('apellido_paterno') . ' ' .
                                    $this->session->userdata('apellido_materno')
                            )); ?>;
</script>


<script>
    (function initSolicitudesDT() {

        // ✅ esperar a jQuery + DataTable
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
            return setTimeout(initSolicitudesDT, 50);
        }

        // ✅ aquí recién podemos usar $
        var $ = window.jQuery;

        // evitar doble inicialización
        if ($.fn.dataTable.isDataTable('#datatable_prof')) {
            return;
        }

        console.log('DataTable solicitudes inicializando...');

        var tabla = $('#datatable_prof').DataTable({
            ajax: {
                url: BASE_URL + "profesor/C_solicitudP/ajax_listar",
                type: "GET",
                dataSrc: "data"
            },
            order: [
                [5, 'desc']
            ],
            responsive: true
        });

        // ✅ eventos dentro del mismo IIFE
        $(document).on('click', '.btnVer', function() {
            var id = $(this).data('id');
            console.log('VER id:', id);

            $.ajax({
                url: BASE_URL + "profesor/C_solicitudP/ajax_ver_carta/" + id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        // si SweetAlert no está aún, fallback a alert
                        if (window.Swal) Swal.fire('Error', res.message || 'No se pudo cargar', 'error');
                        else alert(res.message || 'No se pudo cargar');
                        return;
                    }

                    var d = res.data;

                    $('#cartaFecha').text(d.fecha_formato || '');
                    $('#cartaDirector').text(d.director_nombre || '');
                    $('#cartaReferencia').text(d.referencia || '—');
                    $('#cartaDescripcion').text(d.descripcion || '');
                    $('#cartaProfesor').text(NOMBRE_PROFESOR || '');

                    $('#modalVerSolicitud').modal('show');
                },
                error: function() {
                    if (window.Swal) Swal.fire('Error', 'Error del servidor', 'error');
                    else alert('Error del servidor');
                }
            });
        });

    })();
</script>

<script>
    function whenJqueryReady(cb) {
        if (window.jQuery) return cb();
        setTimeout(function() {
            whenJqueryReady(cb);
        }, 50);
    }

    whenJqueryReady(function() {

        // Evitar reinit si vuelves a entrar
        if ($.fn.DataTable.isDataTable('#datatable_prof')) {
            $('#datatable_prof').DataTable().destroy();
        }

        const tabla = $('#datatable_prof').DataTable({
            ajax: {
                url: "<?= site_url('profesor/C_solicitudP/ajax_listar'); ?>",
                type: "GET",
                dataSrc: "data"
            },
            order: [
                [5, 'desc']
            ], // Fecha (col 5) asc (orden de llegada)
            responsive: true
        });

        // (por ahora) eventos de botones
        $(document).on('click', '.btnVer', function() {
            const id = $(this).data('id');
            console.log('VER id:', id);
            // en el PASO 2 haremos el modal carta
        });

        $(document).on('click', '.btnEditar', function() {
            const id = $(this).data('id');
            console.log('EDITAR id:', id);
            // en el PASO 4 haremos el modal editar
        });

    });
</script>
