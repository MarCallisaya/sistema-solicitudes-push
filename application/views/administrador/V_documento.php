<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="card-title">DOCUMENTOS</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_documento" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Profesor</th>
                                        <th>Motivo</th>
                                        <th>Archivo</th>
                                        <th>Fecha</th>
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
    const BASE_URL = <?= json_encode(base_url()); ?>;
</script>

<script>
    (function initDocumentoAdmin() {
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
            return setTimeout(initDocumentoAdmin, 50);
        }
        var $ = window.jQuery;
        
        //Listar los documentos
        if (!$.fn.dataTable.isDataTable('#datatable_documento')) {
            $('#datatable_documento').DataTable({
                ajax: {
                    url: BASE_URL + "administrador/C_documento/ajax_listar",
                    type: "GET",
                    dataSrc: "data"
                },
                order: [
                    [3, 'desc']
                ],
                responsive: true
            });
        }
    })();
</script>