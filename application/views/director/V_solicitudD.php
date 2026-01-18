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
              <table id="datatable_dir" class="display" style="min-width: 845px">
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
                <tbody></tbody>
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
            <strong>Lic. <?= htmlspecialchars($this->session->userdata('nombre')); ?> <?= htmlspecialchars($this->session->userdata('apellido_paterno')); ?> <?= htmlspecialchars($this->session->userdata('apellido_materno')); ?></strong><br>
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
          <textarea class="form-control" name="observaciones" id="obs_text" rows="4" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\/\-,\.]/g, '')"></textarea>
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
  const NOMBRE_DIRECTOR = <?= json_encode(trim(
                            $this->session->userdata('nombre') . ' ' .
                              $this->session->userdata('apellido_paterno') . ' ' .
                              $this->session->userdata('apellido_materno')
                          )); ?>;
</script>


<script>
  (function initSolicitudesDirector() {

    if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
      return setTimeout(initSolicitudesDirector, 50);
    }

    function cargarResumenAdmin() {
      $.getJSON(BASE_URL + "director/C_solicitudD/ajax_resumen_estados", function(res) {
        if (!res || !res.status) return;
        $('#r_total').text(res.total || 0);
        $('#r_pendiente').text(res.data.PENDIENTE || 0);
        $('#r_revision').text(res.data.EN_REVISION || 0);
        $('#r_aceptado').text(res.data.ACEPTADO || 0);
        $('#r_rechazado').text(res.data.RECHAZADO || 0);
      });
    }


    var $ = window.jQuery;

    function badgeEstado(estado) {
      var e = (estado || '').toString().toUpperCase();
      var cls = 'bg-otro';

      if (e === 'PENDIENTE') cls = 'bg-pendiente';
      else if (e === 'EN_REVISION') cls = 'bg-revision';
      else if (e === 'ACEPTADO') cls = 'bg-aceptado';
      else if (e === 'RECHAZADO') cls = 'bg-rechazado';

      return '<span class="badge-estado ' + cls + '">' + e + '</span>';
    }

    // listar 
    /*
        if (!$.fn.dataTable.isDataTable('#datatable_dir')) {
          $('#datatable_dir').DataTable({
            ajax: {
              url: BASE_URL + "director/C_solicitudD/ajax_listar",
              type: "GET",
              dataSrc: "data"
            },
            order: [
              [5, 'desc']
            ],
            responsive: true
          });
        }*/
    if (!$.fn.dataTable.isDataTable('#datatable_dir')) {
      $('#datatable_dir').DataTable({
        ajax: {
          url: BASE_URL + "director/C_solicitudD/ajax_listar",
          type: "GET",
          dataSrc: "data"
        },
        order: [
          [5, 'desc']
        ],
        responsive: true,
        columnDefs: [{
          targets: 4, // Estado
          className: 'text-center',
          render: function(data) {
            return badgeEstado(data);
          }
        }]
      });
      cargarResumenAdmin();
    }


    // VER carta
    $(document).off('click', '.btnVer').on('click', '.btnVer', function() {
      var id = $(this).data('id');

      $.ajax({
        url: BASE_URL + "director/C_solicitudD/ajax_ver_carta/" + id,
        type: "GET",
        dataType: "json",
        success: function(res) {
          if (!res.status) {
            if (window.Swal) Swal.fire('Error', res.message || 'No se pudo cargar', 'error');
            return;
          }
          var d = res.data;
          $('#cartaFecha').text(d.fecha_formato || '');
          $('#cartaReferencia').text(d.referencia || '—');
          $('#cartaDescripcion').text(d.descripcion || '');
          $('#cartaProfesor').text(d.profesor_nombre || '');
          $('#modalVerSolicitud').modal('show');
        }
      });
    });

    // Abrir modal estado
    $(document).off('click', '.btnEstado').on('click', '.btnEstado', function() {
      $('#estado_id_solicitud').val($(this).data('id'));
      $('#estado_select').val(($(this).data('estado') || 'PENDIENTE').toString().toUpperCase());
      $('#modalEstado').modal('show');
    });

    // Guardar estado
    $('#formEstado').off('submit').on('submit', function(e) {
      e.preventDefault();

      $('#btnGuardarEstado').prop('disabled', true).text('Guardando...');

      $.ajax({
        url: BASE_URL + "director/C_solicitudD/ajax_actualizar_estado",
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
          $('#datatable_dir').DataTable().ajax.reload(null, false);

          cargarResumenAdmin();
        },
        complete: function() {
          $('#btnGuardarEstado').prop('disabled', false).text('Guardar');
        }
      });
    });

    // Abrir modal observación
    $(document).off('click', '.btnObs').on('click', '.btnObs', function() {
      $('#obs_id_solicitud').val($(this).data('id'));
      $('#obs_text').val($(this).data('obs') || '');
      $('#modalObs').modal('show');
    });

    // Guardar observación
    $('#formObs').off('submit').on('submit', function(e) {
      e.preventDefault();

      $('#btnGuardarObs').prop('disabled', true).text('Guardando...');

      $.ajax({
        url: BASE_URL + "director/C_solicitudD/ajax_actualizar_observacion",
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
          $('#datatable_dir').DataTable().ajax.reload(null, false);

          cargarResumenAdmin();
        },
        complete: function() {
          $('#btnGuardarObs').prop('disabled', false).text('Guardar');
        }
      });
    });

  })();
</script>