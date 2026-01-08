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
                                        <th>Descripcigit ón</th>
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
                    url: '<?= base_url("unidad/C_unidadD/lista"); ?>',
                    dataSrc: ''
                },
                columns: [
                    {
                        data: 'nombre'
                    },
                    { 
                        data: 'descripcion' 
                    }
                ]
            });
        }
        initDT();

    })();
</script>