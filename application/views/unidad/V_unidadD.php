<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">UNIDADES EDUCATIVAS</h4>

                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_unidad" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Docentes</th>
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

<!--modal para ver a los docentes por unidad-->
<div class="modal fade" id="modalDocentes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Docentes asignados</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="tablaDocentes">
                        <thead>
                            <tr>
                                <th>Docente</th>
                                <th>CI</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="docentesBody"></tbody>
                    </table>
                </div>

                <div id="docentesEmpty" class="text-muted" style="display:none;">
                    No hay docentes asignados a esta unidad.
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    (function initUnidadPage() {

        if (!window.jQuery) return setTimeout(initUnidadPage, 50);

        // DATATABLE
        function initDT() {
            if (!$.fn || !$.fn.DataTable) return setTimeout(initDT, 50);
            if ($.fn.dataTable.isDataTable('#datatable_unidad')) return;

            $('#datatable_unidad').DataTable({
                processing: true,
                responsive: true,
                ajax: {
                    url: '<?= base_url("unidad/C_unidadD/lista"); ?>',
                    dataSrc: ''
                },
                columns: [{
                        data: 'nombre'
                    },
                    {
                        data: 'descripcion'
                    },
                    {
                        data: 'total_docentes',
                        className: 'text-center',
                        render: function(n, type, row) {
                            n = parseInt(n || 0, 10);
                            const id = row.id_unidad_educativa;

                            return `
                                <a href="#" class="btnDocentes" data-id="${id}" style="font-weight:700; text-decoration:none;">
                                    <span class="badge badge-info">${n}</span>
                                </a>
                            `;
                        }
                    }

                ]
            });
        }
        initDT();

        //Lista de docentes
        $(document).off('click', '.btnDocentes').on('click', '.btnDocentes', function(e) {
            e.preventDefault();

            const unidadId = $(this).data('id');

            $('#docentesBody').html('');
            $('#docentesEmpty').hide();

            $.ajax({
                url: '<?= base_url("unidad/C_unidadD/ajax_docentes/"); ?>' + unidadId,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (!res || !res.status) {
                        if (window.Swal) Swal.fire('Error', res.message || 'No se pudo cargar', 'error');
                        return;
                    }

                    if (res.data.length === 0) {
                        $('#docentesEmpty').show();
                    } else {
                        let html = '';
                        res.data.forEach(function(d) {
                            const nombre = [d.apellido_paterno, d.apellido_materno, d.nombre]
                                .filter(Boolean).join(' ');
                            html += `
                                <tr>
                                <td>${nombre}</td>
                                <td>${d.ci || ''}</td>
                                <td>${d.telefono || ''}</td>
                                <td>${d.email || ''}</td>
                                <td>${d.username || ''}</td>
                                </tr>
                            `;
                        });
                        $('#docentesBody').html(html);
                    }

                    $('#modalDocentes').modal('show');
                }
            });
        });



    })();
</script>