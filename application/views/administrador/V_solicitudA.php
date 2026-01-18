<style>
    .badge-estado {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 120px;
        height: 28px;

        padding: 0 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 999px;

        color: #fff;
        letter-spacing: .4px;
        text-transform: uppercase;
    }


    .bg-pendiente {
        background: #f0ad4e;
    }

    .bg-revision {
        background: #5bc0de;
    }

    .bg-aceptado {
        background: #5cb85c;
    }

    .bg-rechazado {
        background: #d9534f;
    }

    .bg-otro {
        background: #6c757d;
    }


    /* Para el total de las solicitudes y los estados */
    .sol-resumen {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 10px 12px;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 10px;
        background: #fff;
    }

    .sol-resumen-left {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
        min-width: 150px;
    }

    .sol-titulo {
        font-weight: 700;
        font-size: 14px;
    }

    .sol-total {
        font-size: 12px;
        color: #6c757d;
    }

    .sol-resumen-right {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .sol-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        white-space: nowrap;
    }

    .sol-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .sol-label {
        color: #495057;
        font-weight: 600;
    }

    .sol-num {
        font-weight: 800;
        margin-left: 2px;
    }
</style>


<div class="content-body">
    <div class="container-fluid">
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">SOLICITUDES</h4>
                    </div>
                    <div class="card-body">
                        <!-- Total de solicitudes -->
                        <div class="sol-resumen mb-2">
                            <div class="sol-resumen-left">
                                <div class="sol-total">Total: <span id="r_total">0</span></div>
                            </div>

                            <div class="sol-resumen-right">
                                <div class="sol-item">
                                    <span class="sol-dot bg-revision"></span>
                                    <span class="sol-label">En Revisión</span>
                                    <span class="sol-num" id="r_revision">0</span>
                                </div>

                                <div class="sol-item">
                                    <span class="sol-dot bg-aceptado"></span>
                                    <span class="sol-label">Aprobadas</span>
                                    <span class="sol-num" id="r_aceptado">0</span>
                                </div>

                                <div class="sol-item">
                                    <span class="sol-dot bg-rechazado"></span>
                                    <span class="sol-label">Rechazadas</span>
                                    <span class="sol-num" id="r_rechazado">0</span>
                                </div>

                                <div class="sol-item">
                                    <span class="sol-dot bg-pendiente"></span>
                                    <span class="sol-label">Pendientes</span>
                                    <span class="sol-num" id="r_pendiente">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
<!-- <table id="datatable_admin" class="display w-100">-->
                            <table id="datatable_admin" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Ver</th>
                                        <th>Tipo de solicitud</th>
                                        <th>Archivo</th>
                                        <th>Remitente</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver la carta -->
<div class="modal fade" id="modalVerSolicitud" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ver Solicitud</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6;">
                    <div style="text-align:right;"><span id="cartaFecha"></span></div>
                    <br>
                    <div>
                        <strong>Lic. <span id="cartaDirector"></span></strong><br>
                        <strong>DIRECTOR DE LA UNIDAD EDUCATIVA KASANI</strong>

                    </div>
                    <br>
                    <div style="text-align:right;"><strong>Ref.:</strong> <span id="cartaReferencia"></span></div>
                    <br>
                    <div id="cartaDescripcion" style="text-align:justify; white-space:pre-wrap;"></div>
                    <br><br>
                    <div>Atentamente.<br>Prof: <span id="cartaProfesor"></span></div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar los estados -->
<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="formEstado">
                <input type="hidden" id="estado_id_solicitud" name="id_solicitud">

                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estado</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <label>Estado</label>
                    <select class="form-control" name="estado" id="estado_select" required>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="EN_REVISION">EN_REVISION</option>
                        <option value="ACEPTADO">ACEPTADO</option>
                        <option value="RECHAZADO">RECHAZADO</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cerrar</button>
                    <button class="btn btn-primary" type="submit" id="btnGuardarEstado">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para añadir las observaciones-->
<div class="modal fade" id="modalObs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="formObs">
                <input type="hidden" id="obs_id_solicitud" name="id_solicitud">

                <div class="modal-header">
                    <h5 class="modal-title">Observación</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <label>Detalle</label>
                    <textarea class="form-control" name="observaciones" id="obs_text" rows="4" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\/\-,\.]/g, '')"> </textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cerrar</button>
                    <button class="btn btn-primary" type="submit" id="btnGuardarObs">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    const BASE_URL = <?= json_encode(base_url()); ?>;
</script>

<script>
    (function initSolicitudesAdmin() {

        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
            return setTimeout(initSolicitudesAdmin, 50);
        }

        var $ = window.jQuery;

        // funcion Para mostrar el total de las colicitudes y por estados 
        function cargarResumenAdmin() {
            $.getJSON(BASE_URL + "administrador/C_solicitudA/ajax_resumen_estados", function(res) {
                if (!res || !res.status) return;
                $('#r_total').text(res.total || 0);
                $('#r_pendiente').text(res.data.PENDIENTE || 0);
                $('#r_revision').text(res.data.EN_REVISION || 0);
                $('#r_aceptado').text(res.data.ACEPTADO || 0);
                $('#r_rechazado').text(res.data.RECHAZADO || 0);
            });
        }

        /*
        if (!$.fn.dataTable.isDataTable('#datatable_admin')) {
            $('#datatable_admin').DataTable({
                ajax: {
                    url: BASE_URL + "administrador/C_solicitudA/ajax_listar",
                    type: "GET",
                    dataSrc: "data"
                },
                order: [
                    [5, 'desc']
                ],
                responsive: true
            });
        }
        */

        // funcion para los colores de los estados
        function badgeEstado(estado) {
            var e = (estado || '').toString().toUpperCase();

            var cls = 'bg-otro';
            if (e === 'PENDIENTE') cls = 'bg-pendiente';
            else if (e === 'EN_REVISION') cls = 'bg-revision';
            else if (e === 'ACEPTADO') cls = 'bg-aceptado';
            else if (e === 'RECHAZADO') cls = 'bg-rechazado';

            return '<span class="badge-estado ' + cls + '">' + e + '</span>';
        }


        // Listar 
        if (!$.fn.dataTable.isDataTable('#datatable_admin')) {
            $('#datatable_admin').DataTable({
                ajax: {
                    url: BASE_URL + "administrador/C_solicitudA/ajax_listar",
                    type: "GET",
                    dataSrc: "data"
                },
                order: [
                    [5, 'desc']
                ],
                responsive: true,

                columnDefs: [{
                    targets: 4, 
                    className: 'text-center',
                    render: function(data, type, row) {
                        var estado = (row && row.estado) ? row.estado : data;
                        return badgeEstado(estado);
                    }
                }]
            });

            cargarResumenAdmin();
        }


        // VER carta
        $(document).off('click', '.btnVer').on('click', '.btnVer', function() {
            var id = $(this).data('id');
            $.ajax({
                url: BASE_URL + "administrador/C_solicitudA/ajax_ver_carta/" + id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        if (window.Swal) Swal.fire('Error', res.message || 'No se pudo cargar', 'error');
                        return;
                    }
                    var d = res.data;
                    $('#cartaFecha').text(d.fecha_formato || '');
                    $('#cartaDirector').text(d.director_nombre || '');

                    $('#cartaReferencia').text(d.referencia || '—');
                    $('#cartaDescripcion').text(d.descripcion || '');
                    $('#cartaProfesor').text(d.profesor_nombre || '');
                    $('#modalVerSolicitud').modal('show');
                }
            });
        });

        // Estado modal
        $(document).off('click', '.btnEstado').on('click', '.btnEstado', function() {
            $('#estado_id_solicitud').val($(this).data('id'));
            $('#estado_select').val(($(this).data('estado') || 'PENDIENTE').toString().toUpperCase());
            $('#modalEstado').modal('show');
        });

        $('#formEstado').off('submit').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: BASE_URL + "administrador/C_solicitudA/ajax_actualizar_estado",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        if (window.Swal) Swal.fire('Error', res.message || 'No se pudo guardar', 'error');
                        return;
                    }
                    if (window.Swal) Swal.fire('Éxito', res.message || 'Actualizado', 'success');
                    $('#modalEstado').modal('hide');
                    $('#datatable_admin').DataTable().ajax.reload(null, false);

                    cargarResumenAdmin();
                }
            });
        });

        // Observación modal
        $(document).off('click', '.btnObs').on('click', '.btnObs', function() {
            $('#obs_id_solicitud').val($(this).data('id'));
            $('#obs_text').val($(this).data('obs') || '');
            $('#modalObs').modal('show');
        });

        $('#formObs').off('submit').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: BASE_URL + "administrador/C_solicitudA/ajax_actualizar_observacion",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        if (window.Swal) Swal.fire('Error', res.message || 'No se pudo guardar', 'error');
                        return;
                    }
                    if (window.Swal) Swal.fire('Éxito', res.message || 'Guardado', 'success');
                    $('#modalObs').modal('hide');
                    $('#datatable_admin').DataTable().ajax.reload(null, false);

                    cargarResumenAdmin();
                }
            });
        });

        // Eliminar
        $(document).off('click', '.btnEliminar').on('click', '.btnEliminar', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar solicitud?',
                text: 'Se desactivará la solicitud.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {

                var confirmado = (result && (result.value === true || result.isConfirmed === true));
                if (!confirmado) return;

                $.ajax({
                    url: BASE_URL + "administrador/C_solicitudA/ajax_eliminar",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id_solicitud: id
                    },
                    success: function(res) {
                        if (!res || !res.status) {
                            Swal.fire('Error', (res && res.message) ? res.message : 'No se pudo eliminar', 'error');
                            return;
                        }
                        Swal.fire('Listo', res.message || 'Eliminado', 'success');
                        $('#datatable_admin').DataTable().ajax.reload(null, false);

                        cargarResumenAdmin();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'HTTP ' + xhr.status + ': ' + (xhr.responseText || 'error'), 'error');
                    }
                });
            });
        });

    })();
</script>