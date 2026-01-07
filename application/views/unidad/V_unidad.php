<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">UNIDADES EDUCATIVAS</h4>
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalUnidad">
                                + Nueva Unidad Educativa
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_unidad" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
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

<!-- MODAL ÚNICO (Registrar / Editar) -->
<div class="modal fade" id="modalUnidad" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <form id="formUnidad">
                <input type="hidden" name="id_unidad_educativa" id="id_unidad_educativa">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalUnidadTitle">Registrar Unidad Educativa</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <div class="modal-body">
                    <label class="mt-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
                </div>



                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnUnidadSubmit">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    (function initUnidadPage() {

        if (!window.jQuery) return setTimeout(initUnidadPage, 50);

        // NUEVO
        $('[data-target="#modalUnidad"]').off('click').on('click', function() {
            $('#modalUnidadTitle').text('Registrar Unidad Educativa');
            $('#btnUnidadSubmit').text('Guardar');
            $('#formUnidad')[0].reset();
            $('#id_unidad_educativa').val('');
            $('#descripcion').val('');

        });

        // DATATABLE
        function initDT() {
            if (!$.fn || !$.fn.DataTable) return setTimeout(initDT, 50);
            if ($.fn.dataTable.isDataTable('#datatable_unidad')) return;

            $('#datatable_unidad').DataTable({
                processing: true,
                responsive: true,
                ajax: {
                    url: '<?= base_url("unidad/C_unidad/lista"); ?>',
                    dataSrc: ''
                },
                columns: [
                    {
                        data: 'nombre'
                    },
                    { 
                        data: 'descripcion' 
                    },
                    {
                        data: 'id_unidad_educativa',
                        render: function(id) {
                            return `
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="${id}">
                                <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-del" data-id="${id}">
                                <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            `;
                        }
                    }
                ]
            });
        }
        initDT();

        // GUARDAR / ACTUALIZAR
        $('#formUnidad').off('submit').on('submit', function(e) {
            e.preventDefault();

            const id = $('#id_unidad_educativa').val();
            const esEditar = (id && id !== '');

            const url = esEditar ?
                '<?= base_url("unidad/C_unidad/actualizar"); ?>' :
                '<?= base_url("unidad/C_unidad/guardar"); ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(resp) {
                    if (resp.status) {
                        Swal.fire({
                            icon: 'success',
                            title: esEditar ? 'Actualizado' : 'Registrado',
                            timer: 1400,
                            showConfirmButton: false
                        });

                        $('#modalUnidad').modal('hide');
                        $('#formUnidad')[0].reset();
                        $('#id_unidad_educativa').val('');

                        $('#datatable_unidad').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.msg || 'Ocurrió un error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de servidor'
                    });
                }
            });
        });

        // EDITAR
        $(document).off('click', '.btn-edit').on('click', '.btn-edit', function() {
            const id = $(this).data('id');

            $.ajax({
                url: '<?= base_url("unidad/C_unidad/obtener"); ?>',
                type: 'GET',
                data: {
                    id_unidad_educativa: id
                },
                dataType: 'json',
                success: function(resp) {
                    if (!resp.status) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.msg || 'No se pudo obtener'
                        });
                        return;
                    }

                    $('#modalUnidadTitle').text('Editar Unidad Educativa');
                    $('#btnUnidadSubmit').text('Actualizar');

                    $('#id_unidad_educativa').val(resp.data.id_unidad_educativa);
                    $('#nombre').val(resp.data.nombre);
                    $('#descripcion').val(resp.data.descripcion);

                    $('#modalUnidad').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de servidor'
                    });
                }
            });
        });

        // ELIMINAR (soft delete)
        $(document).off('click', '.btn-del').on('click', '.btn-del', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Desactivar unidad?',
                text: 'La unidad quedará desactivada.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                const confirmed = (result.isConfirmed === true) || (result.value === true);
                if (!confirmed) return;

                $.ajax({
                    url: '<?= base_url("unidad/C_unidad/eliminar"); ?>',
                    type: 'POST',
                    data: {
                        id_unidad_educativa: id
                    },
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Desactivado',
                                timer: 1200,
                                showConfirmButton: false
                            });
                            $('#datatable_unidad').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: resp.msg || 'No se pudo desactivar'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error de servidor'
                        });
                    }
                });
            });
        });

    })();
</script>