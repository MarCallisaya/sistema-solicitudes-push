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
                        <div class="table-responsive">
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
                    <textarea class="form-control" name="observaciones" id="obs_text" rows="4" required></textarea>
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
                }
            });
        });

        // ✅ ELIMINAR (borrado lógico)
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
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'HTTP ' + xhr.status + ': ' + (xhr.responseText || 'error'), 'error');
                    }
                });
            });
        });

    })();
</script>