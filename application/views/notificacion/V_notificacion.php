<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">NOTIFICACIONES</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_notificacion" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Solicitud</th>
                                        <th>Remitente</th>
                                        <th>Contenido</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.URL_NOTI_LISTAR = "<?= site_url('notificacion/C_notificacion/ajax_listar'); ?>";
</script>


<script>
    (function initNotificaciones() {
        if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.DataTable) {
            return setTimeout(initNotificaciones, 50);
        }
        var $ = window.jQuery;

        if ($.fn.dataTable.isDataTable('#datatable_notificacion')) return;
        // Listar las notificaciones
        $('#datatable_notificacion').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.URL_NOTI_LISTAR,
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            },
            order: [
                [3, 'desc']
            ]
        });

    })();
</script>