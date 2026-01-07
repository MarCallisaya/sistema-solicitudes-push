<div class="content-body">
    <div class="container-fluid">
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">USUARIOS</h4>
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalUsuario">
                                + Nuevo Usuario
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_usu" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Telefono</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Unidad Educativa</th>
                                        <th>Ultima Sesion</th>
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

<!-- Modal Registrar Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formUsuario">

                <input type="hidden" name="id_usuario" id="id_usuario" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioTitle">Registrar Usuario</h5>

                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-4">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Apellido Materno</label>
                            <input type="text" name="apellido_materno" id="apellido_materno" class="form-control">
                        </div>

                        <!-- CI -->
                        <div class="col-md-4 mt-2">
                            <label>CI</label>
                            <input type="text" name="ci" id="ci" class="form-control">
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-4 mt-2">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control">
                        </div>

                        <!-- Email -->
                        <div class="col-md-4 mt-2">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>

                        <!-- Rol -->
                        <div class="col-md-6 mt-2">
                            <label>Rol</label>
                            <select name="rol_id" id="rol_id" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r->id_rol ?>">
                                        <?= $r->nombre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Unidad Educativa -->
                        <div class="col-md-6 mt-2">
                            <label>Unidad Educativa</label>
                            <select name="unidad_educativa_id" id="unidad_educativa_id" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($unidades as $u): ?>
                                    <option value="<?= $u->id_unidad_educativa ?>">
                                        <?= $u->nombre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Usuario -->
                        <div class="col-md-6 mt-2">
                            <label>Usuario</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mt-2">
                            <label>Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="btnUsuarioSubmit" class="btn btn-success">
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    console.log('JS USUARIOS CARGADO');

    (function initUsuariosDT() {

        if (!window.jQuery || !jQuery.fn || !jQuery.fn.DataTable) {
            return setTimeout(initUsuariosDT, 50);
        }

        if ($.fn.dataTable.isDataTable('#datatable_usu')) {
            return;
        }

        console.log('DataTable inicializando');

        $('#datatable_usu').DataTable({
            processing: true,
            responsive: true,
            ajax: {
                url: '<?= base_url("usuarios/C_usuarios/lista_usuarios"); ?>',
                dataSrc: ''
            },
            columns: [{
                    data: 'usuario'
                },
                {
                    data: 'telefono'
                },
                {
                    data: 'email'
                },
                {
                    data: 'rol'
                },
                {
                    data: 'unidad'
                },
                {
                    data: 'last_login'
                },
                {
                    data: 'id_usuario',
                    render: function(data) {
                        return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-warning btn-edit-usu" data-id="${data}">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-del-usu" data-id="${data}">
                                ><i class="fa fa-trash"</i>
                            </button>
                        </div>
                        `;
                    }
                }
            ]
        });

    })();

    // GUARDAR
    (function initGuardarUsuarioUnico() {

        if (!window.jQuery) return setTimeout(initGuardarUsuarioUnico, 50);

        $('#formUsuario').on('submit', function(e) {
            e.preventDefault();

            const id = $('#id_usuario').val();
            const esEditar = (id && id !== '');

            const url = esEditar ?
                '<?= base_url("usuarios/C_usuarios/actualizar"); ?>' :
                '<?= base_url("usuarios/C_usuarios/guardar"); ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(resp) {
                    if (resp.status) {
                        Swal.fire({
                            icon: 'success',
                            title: esEditar ? 'Actualización exitosa' : 'Registro exitoso',
                            text: esEditar ? 'El usuario fue actualizado correctamente' : 'El usuario fue registrado correctamente',
                            timer: 1800,
                            showConfirmButton: false
                        });

                        $('#modalUsuario').modal('hide');
                        $('#formUsuario')[0].reset();
                        $('#id_usuario').val('');
                        $('#password').prop('required', true);
                        $('#datatable_usu').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.msg || (esEditar ? 'No se pudo actualizar' : 'No se pudo registrar')
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

    })();




    // MODAL USUARIO - (botón Nuevo Usuario)
    (function initModalUsuarioModo() {

        if (!window.jQuery) return setTimeout(initModalUsuarioModo, 50);
        $('[data-target="#modalUsuario"]').on('click', function() {
            $('#modalUsuarioTitle').text('Registrar Usuario');
            $('#btnUsuarioSubmit').text('Guardar');
            $('#id_usuario').val('');
            $('#formUsuario')[0].reset();
            $('#password').prop('required', true);
        });

    })();

    // EDITAR
    (function initEditarMismoModal() {

        if (!window.jQuery) return setTimeout(initEditarMismoModal, 50);

        $(document).on('click', '.btn-edit-usu', function() {
            const id = $(this).data('id');

            $.ajax({
                url: '<?= base_url("usuarios/C_usuarios/obtener_usuario"); ?>',
                type: 'GET',
                data: {
                    id_usuario: id
                },
                dataType: 'json',
                success: function(resp) {
                    if (!resp.status) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.msg || 'No se pudo obtener el usuario'
                        });
                        return;
                    }

                    const u = resp.data;
                    $('#modalUsuarioTitle').text('Editar Usuario');
                    $('#btnUsuarioSubmit').text('Actualizar');
                    $('#id_usuario').val(u.id_usuario);

                    $('#nombre').val(u.nombre);
                    $('#apellido_paterno').val(u.apellido_paterno);
                    $('#apellido_materno').val(u.apellido_materno);
                    $('#ci').val(u.ci);
                    $('#telefono').val(u.telefono);
                    $('#email').val(u.email);
                    $('#rol_id').val(u.rol_id);
                    $('#unidad_educativa_id').val(u.unidad_educativa_id);
                    $('#username').val(u.username);

                    $('#password').val('');
                    $('#password').prop('required', false);

                    $('#modalUsuario').modal('show');
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

    })();


    // ELIMINAR
    (function initEliminarUsuario() {
        if (!window.jQuery) {
            return setTimeout(initEliminarUsuario, 50);
        }

        console.log('JS ELIMINAR LISTO');
        $(document).off('click', '.btn-del-usu'); 
        $(document).on('click', '.btn-del-usu', function() {

            const id = $(this).data('id');
            console.log('CLICK ELIMINAR ID =', id);

            Swal.fire({
                title: '¿Desactivar usuario?',
                text: 'El usuario quedará desactivado (no se borra).',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {

                const confirmed = (result.isConfirmed === true) || (result.value === true);

                if (!confirmed) return;

                $.ajax({
                    url: '<?= base_url("usuarios/C_usuarios/eliminar"); ?>',
                    type: 'POST',
                    data: {
                        id_usuario: id
                    },
                    dataType: 'json',
                    success: function(resp) {
                        console.log('RESPUESTA ELIMINAR =', resp);

                        if (resp.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Desactivado',
                                text: 'Usuario desactivado correctamente',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            $('#datatable_usu').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: resp.msg || 'No se pudo desactivar'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log('ERROR AJAX =', xhr.responseText);
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