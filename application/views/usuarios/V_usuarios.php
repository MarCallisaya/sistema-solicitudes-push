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
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-4">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Apellido Materno</label>
                            <input type="text" name="apellido_materno" class="form-control">
                        </div>

                        <!-- CI -->
                        <div class="col-md-4 mt-2">
                            <label>CI</label>
                            <input type="text" name="ci" class="form-control">
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-4 mt-2">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>

                        <!-- Email -->
                        <div class="col-md-4 mt-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <!-- Rol -->
                        <div class="col-md-6 mt-2">
                            <label>Rol</label>
                            <select name="rol_id" class="form-control" required>
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
                            <select name="unidad_educativa_id" class="form-control" required>
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
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mt-2">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
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
                    render: function() {
                        return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning me-1">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        
                    `;
                    }
                }
            ]
        });

    })();
</script>

<script>
(function initGuardarUsuario() {

    if (!window.jQuery) {
        return setTimeout(initGuardarUsuario, 50);
    }

    $('#formUsuario').on('submit', function (e) {
        e.preventDefault(); // 🚫 evita recarga

        console.log('Enviando formulario...');

        $.ajax({
            url: '<?= base_url("usuarios/C_usuarios/guardar"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (resp) {
                if (resp.status) {
                    alert('Usuario registrado correctamente');

                    $('#modalUsuario').modal('hide');
                    $('#formUsuario')[0].reset();

                    $('#datatable_usu').DataTable().ajax.reload();
                } else {
                    alert(resp.msg);
                }
            },
            error: function () {
                alert('Error al guardar usuario');
            }
        });
    });

})();
</script>

