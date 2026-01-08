<div class="content-body">
    <div class="container-fluid">
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">USUARIOS</h4>
                        
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
                }
                
            ]
        });

    })();

  
    


</script>