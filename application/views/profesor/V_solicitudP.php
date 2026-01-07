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
                                maxlength="200" placeholder="Ej: Solicitud de permiso">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="5" required></textarea>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarSolicitud">Guardar</button>
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
</script>


<script>
    (function initSolicitudesProfesor() {

        // ✅ esperar a jQuery + DataTable
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
            return setTimeout(initSolicitudesProfesor, 50);
        }

        var $ = window.jQuery;

        // ✅ 1) Inicializar DataTable SOLO una vez
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
                responsive: true
            });
        }

        // ✅ 2) VER (carta)
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

        // ✅ 3) Abrir modal Formulario + cargar selects
        // (usamos document.on para que funcione aunque el botón esté en header)
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

        // ✅ 4) Guardar Solicitud (AJAX + archivo)
        // Evitar doble bind si CI recarga vista o algo
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

                    // ✅ recargar tabla
                    $('#datatable_prof').DataTable().ajax.reload(null, false);
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

    })();
</script>