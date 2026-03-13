<style>
    /* Colores para los Estados*/
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

    /*Colores para la cantidad de solicitudes por estado */
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
                                    <span class="sol-label">Aceptadas</span>
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

<!-- MODAL VER SOLICITUD -->
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
                <div id="cartaSolicitud" style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; ">
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
                <button class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>


<!-- MODAL CREAR SOLICITUD -->
<div class="modal fade" id="modalSolicitud" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form id="formSolicitud" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Formulario de Solicitud</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Director (Destinatario) *</label>
                            <select name="director_id" id="director_id" class="form-control" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Referencia</label>
                            <input type="text" name="referencia" id="referencia" class="form-control"
                                maxlength="200" placeholder="Ej: Solicitud de permiso" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s\/\-,\.:]/g, '')  .toUpperCase();">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="5" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\/\-_,\.:]/g, '')"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Tipo de solicitud *</label>
                            <select name="tipo_solicitud_id" id="tipo_solicitud_id" class="form-control" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Archivo adjunto (opcional)</label>
                            <input type="file" name="archivo" id="archivo" class="form-control"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">PDF/JPG/JPEG/PNG · Máx 5MB</small>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarSolicitud">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- MODAL EDITAR SOLICITUD -->
<div class="modal fade" id="modalEditarSolicitud" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form id="formSolicitudEditar" enctype="multipart/form-data">
                <input type="hidden" name="id_solicitud" id="edit_id_solicitud">

                <div class="modal-header">
                    <h5 class="modal-title">Editar Solicitud</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Director (Destinatario) *</label>
                            <select name="director_id" id="edit_director_id" class="form-control" required></select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Referencia</label>
                            <input type="text" name="referencia" id="edit_referencia" class="form-control" maxlength="200" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s\/\-,\.:]/g, '')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="5" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\/\-_,\.:]/g, '')"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Tipo de solicitud *</label>
                            <select name="tipo_solicitud_id" id="edit_tipo_solicitud_id" class="form-control" required></select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Reemplazar archivo (opcional)</label>
                            <input type="file" name="archivo" id="edit_archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted" id="edit_archivo_actual"></small>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnActualizarSolicitud">Actualizar</button>
                </div>
            </form>

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

    function toUpper(el) {
    if (!el) return;
    el.value = el.value.toUpperCase();
}
</script>


<script>
    (function initSolicitudesProfesor() {

        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
            return setTimeout(initSolicitudesProfesor, 50);
        }

        var $ = window.jQuery;


        function cargarResumenAdmin() {
            $.getJSON(BASE_URL + "profesor/C_solicitudP/ajax_resumen_estados", function(res) {
                if (!res || !res.status) return;
                $('#r_total').text(res.total || 0);
                $('#r_pendiente').text(res.data.PENDIENTE || 0);
                $('#r_revision').text(res.data.EN_REVISION || 0);
                $('#r_aceptado').text(res.data.ACEPTADO || 0);
                $('#r_rechazado').text(res.data.RECHAZADO || 0);
            });
        }


        function badgeEstado(estado) {
            var e = (estado || '').toString().toUpperCase();
            var cls = 'bg-otro';

            if (e === 'PENDIENTE') cls = 'bg-pendiente';
            else if (e === 'EN_REVISION') cls = 'bg-revision';
            else if (e === 'ACEPTADO') cls = 'bg-aceptado';
            else if (e === 'RECHAZADO') cls = 'bg-rechazado';

            return '<span class="badge-estado ' + cls + '">' + e + '</span>';
        }

        /*if (!$.fn.dataTable.isDataTable('#datatable_prof')) {
            console.log('DataTable solicitudes inicializando...');

            $('#datatable_prof').DataTable({
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
        }*/
        if (!$.fn.dataTable.isDataTable('#datatable_prof')) {
            console.log('DataTable solicitudes inicializando...');

            $('#datatable_prof').DataTable({
                ajax: {
                    url: BASE_URL + "profesor/C_solicitudP/ajax_listar",
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
                    render: function(data) {
                        return badgeEstado(data);
                    }
                }]
            });

            cargarResumenAdmin();
        }


        //  VER (carta)
        $(document).off('click', '.btnVer').on('click', '.btnVer', function() {
            var id = $(this).data('id');

            $.ajax({
                url: BASE_URL + "profesor/C_solicitudP/ajax_ver_carta/" + id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
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

        // modal formulario
        $(document).off('click', '[data-target="#modalSolicitud"]').on('click', '[data-target="#modalSolicitud"]', function() {
            $('#formSolicitud')[0].reset();
            $('#director_id').html('<option value="">Cargando...</option>');
            $('#tipo_solicitud_id').html('<option value="">Cargando...</option>');

            $.ajax({
                url: BASE_URL + "profesor/C_solicitudP/ajax_form_data",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        if (window.Swal) Swal.fire('Error', 'No se pudo cargar el formulario', 'error');
                        return;
                    }

                    var optsD = '<option value="">Seleccione...</option>';
                    (res.directores || []).forEach(function(d) {
                        optsD += '<option value="' + d.id_usuario + '">' + d.nombre_completo + '</option>';
                    });
                    $('#director_id').html(optsD);

                    var optsT = '<option value="">Seleccione...</option>';
                    (res.tipos || []).forEach(function(t) {
                        optsT += '<option value="' + t.id_tipo_solicitud + '">' + t.nombre + '</option>';
                    });
                    $('#tipo_solicitud_id').html(optsT);
                },
                error: function() {
                    if (window.Swal) Swal.fire('Error', 'Error del servidor al cargar datos', 'error');
                    else alert('Error del servidor al cargar datos');
                }
            });
        });

        //  Guardar Solicitud 
        $('#formSolicitud').off('submit').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $('#btnGuardarSolicitud').prop('disabled', true).text('Guardando...');

            $.ajax({
                url: BASE_URL + "profesor/C_solicitudP/ajax_crear",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        if (window.Swal) Swal.fire('Error', res.message || 'No se pudo guardar', 'error');
                        else alert(res.message || 'No se pudo guardar');
                        return;
                    }

                    if (window.Swal) Swal.fire('Éxito', res.message || 'Guardado', 'success');

                    $('#modalSolicitud').modal('hide');
                    $('#formSolicitud')[0].reset();

                    $('#datatable_prof').DataTable().ajax.reload(null, false);
                    cargarResumenAdmin();
                },
                error: function() {
                    if (window.Swal) Swal.fire('Error', 'Error del servidor al guardar', 'error');
                    else alert('Error del servidor al guardar');
                },
                complete: function() {
                    $('#btnGuardarSolicitud').prop('disabled', false).text('Guardar');
                }
            });
        });


        //PARA EDITAR LA SOLICITUD

        $(document).off('click', '.btnEditar').on('click', '.btnEditar', function() {
            var id = $(this).data('id');

            $.ajax({
                url: BASE_URL + "profesor/C_solicitudP/ajax_form_data",
                type: "GET",
                dataType: "json",
                success: function(resFD) {
                    if (!resFD.status) {
                        if (window.Swal) Swal.fire('Error', 'No se pudo cargar datos', 'error');
                        return;
                    }

                    var optsD = '<option value="">Seleccione...</option>';
                    (resFD.directores || []).forEach(function(d) {
                        optsD += '<option value="' + d.id_usuario + '">' + d.nombre_completo + '</option>';
                    });
                    $('#edit_director_id').html(optsD);

                    var optsT = '<option value="">Seleccione...</option>';
                    (resFD.tipos || []).forEach(function(t) {
                        optsT += '<option value="' + t.id_tipo_solicitud + '">' + t.nombre + '</option>';
                    });
                    $('#edit_tipo_solicitud_id').html(optsT);

                    $.ajax({
                        url: BASE_URL + "profesor/C_solicitudP/ajax_get_editar/" + id,
                        type: "GET",
                        dataType: "json",
                        success: function(res) {
                            if (!res.status) {
                                if (window.Swal) Swal.fire('Error', res.message || 'No se pudo cargar', 'error');
                                return;
                            }

                            var d = res.data;

                            $('#edit_id_solicitud').val(d.id_solicitud);
                            $('#edit_director_id').val(d.director_id);
                            $('#edit_tipo_solicitud_id').val(d.tipo_solicitud_id);
                            $('#edit_referencia').val(d.referencia || '');
                            $('#edit_descripcion').val(d.descripcion || '');

                            if (d.archivo) {
                                $('#edit_archivo_actual').html('Archivo actual: <a href="' + (BASE_URL + d.archivo.replace(/^\/+/, '')) + '" target="_blank">Abrir</a>');
                            } else {
                                $('#edit_archivo_actual').text('Archivo actual: —');
                            }

                            $('#modalEditarSolicitud').modal('show');
                        }
                    });
                }
            });
        });

        // actualizar
        $('#formSolicitudEditar').off('submit').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $('#btnActualizarSolicitud').prop('disabled', true).text('Actualizando...');

            $.ajax({
                url: BASE_URL + "profesor/C_solicitudP/ajax_actualizar",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        if (window.Swal) Swal.fire('Error', res.message || 'No se pudo actualizar', 'error');
                        else alert(res.message || 'No se pudo actualizar');
                        return;
                    }

                    if (window.Swal) Swal.fire('Éxito', res.message || 'Actualizado', 'success');

                    $('#modalEditarSolicitud').modal('hide');
                    $('#datatable_prof').DataTable().ajax.reload(null, false);

                    cargarResumenAdmin();
                },
                error: function() {
                    if (window.Swal) Swal.fire('Error', 'Error del servidor al actualizar', 'error');
                    else alert('Error del servidor al actualizar');
                },
                complete: function() {
                    $('#btnActualizarSolicitud').prop('disabled', false).text('Actualizar');
                }
            });
        });

    })();
</script>