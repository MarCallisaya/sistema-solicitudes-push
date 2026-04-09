<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_solicitudA extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) redirect('C_login');
        if ((int)$this->session->userdata('rol_id') !== 3) show_error('No tienes permisos', 403);

        $this->load->model('administrador/M_solicitudA');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $this->load->view('includes/header');
        $this->load->view('includes/sidebar');
        $this->load->view('administrador/V_solicitudA');
        $this->load->view('includes/footer');
    }

    public function ajax_listar()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $rows = $this->M_solicitudA->listar_todas();

        $data = [];
        foreach ($rows as $r) {

            $archivo_html = '—';
            if (!empty($r->archivo)) {
                $path = ltrim($r->archivo, '/');
                $archivo_html = '<a href="' . base_url($path) . '" target="_blank">Abrir</a>';
            }

            $btn_ver = '<button class="btn btn-sm btn-info btnVer" data-id="' . (int)$r->id_solicitud . '"><i class="fa fa-eye"></i></button>';

            $btn_estado = '<button class="btn btn-sm btn-primary btnEstado" data-id="' . (int)$r->id_solicitud . '" data-estado="' . htmlspecialchars($r->estado_actual) . '"><i class="fa fa-check-circle"></i></button>';

            $btn_obs    = '<button class="btn btn-sm btn-warning btnObs" data-id="' . (int)$r->id_solicitud . '" data-obs="' . htmlspecialchars($r->observaciones ?? '') . '"><i class="fa fa-pencil"></i></button>';

            $btn_del    = '<button class="btn btn-sm btn-danger btnEliminar" data-id="' . (int)$r->id_solicitud . '"><i class="fa fa-trash"></i></button>';

            $acciones = $btn_estado . ' ' . $btn_obs . ' ' . $btn_del;

            $data[] = [
                $btn_ver,
                $r->tipo_solicitud,
                $archivo_html,
                $r->profesor_nombre,
                $r->estado_actual,
                $r->fecha_registro,
                $r->observaciones ?? '',
                $acciones
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function ajax_ver_carta($id_solicitud)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $row = $this->M_solicitudA->obtener_carta((int)$id_solicitud);
        if (!$row) {
            echo json_encode(['status' => false, 'message' => 'Solicitud no encontrada']);
            return;
        }
        echo json_encode(['status' => true, 'data' => $row]);
    }

    public function ajax_actualizar_estado()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $id_solicitud = (int)$this->input->post('id_solicitud', true);
        $estado       = strtoupper(trim($this->input->post('estado', true)));

        $permitidos = ['PENDIENTE', 'EN_REVISION', 'ACEPTADO', 'RECHAZADO'];
        if (!in_array($estado, $permitidos, true)) {
            echo json_encode(['status' => false, 'message' => 'Estado no válido']);
            return;
        }

        $ok = $this->M_solicitudA->actualizar_estado($id_solicitud, $estado);

        /* ---- 9-4-26 Inicio poniendo en comentario 
        if ($ok) {
            $this->load->model('M_push');
            $this->load->library('Fcm_service');

            $profesor_id = $this->M_push->get_profesor_id_by_solicitud($id_solicitud);

            if ($profesor_id > 0) {
                $map = [
                    'EN_REVISION' => 'Tu solicitud está en revisión.',
                    'ACEPTADO'    => 'Tu solicitud fue aceptada.',
                    'RECHAZADO'   => 'Tu solicitud fue rechazada.',
                    'PENDIENTE'   => 'Tu solicitud volvió a pendiente.'
                ];

                $this->fcm_service->send_to_user(
                    (int)$profesor_id,
                    'Estado de solicitud',
                    $map[$estado] ?? ('Estado actualizado: ' . $estado),
                    'SOLICITUD_ESTADO',
                    (int)$id_solicitud
                );
            }
        }
        ---- 9-4-26 FIN poniendo en comentario  */

        if ($ok) {
            try {
                $this->load->model('M_push');
                $this->load->library('Fcm_service');

                $profesor_id = $this->M_push->get_profesor_id_by_solicitud($id_solicitud);

                if ($profesor_id > 0) {
                    $map = [
                        'EN_REVISION' => 'Tu solicitud está en revisión.',
                        'ACEPTADO'    => 'Tu solicitud fue aceptada.',
                        'RECHAZADO'   => 'Tu solicitud fue rechazada.',
                        'PENDIENTE'   => 'Tu solicitud volvió a pendiente.'
                    ];

                    $this->fcm_service->send_to_user(
                        (int)$profesor_id,
                        'Estado de solicitud',
                        $map[$estado] ?? ('Estado actualizado: ' . $estado),
                        'SOLICITUD_ESTADO',
                        (int)$id_solicitud
                    );
                }
            } catch (Throwable $e) {
                log_message('error', 'Error push admin ajax_actualizar_estado: ' . $e->getMessage());
            }
        }

        echo json_encode([
            'status'  => (bool)$ok,
            'message' => $ok ? 'Estado actualizado' : 'No se pudo actualizar'
        ]);
    }


    public function ajax_actualizar_observacion()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $id_solicitud = (int)$this->input->post('id_solicitud', true);
        $obs          = trim($this->input->post('observaciones', true));

        if ($obs === '') {
            echo json_encode(['status' => false, 'message' => 'Escribe una observación.']);
            return;
        }

        $ok = $this->M_solicitudA->actualizar_observacion($id_solicitud, $obs);

        echo json_encode(['status' => (bool)$ok, 'message' => $ok ? 'Observación guardada' : 'No se pudo guardar']);
    }


    public function ajax_eliminar()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $id_solicitud = (int)$this->input->post('id_solicitud', true);

        if ($id_solicitud <= 0) {
            echo json_encode(['status' => false, 'message' => 'ID inválido']);
            return;
        }

        $afectadas = $this->M_solicitudA->eliminar_logico($id_solicitud);

        if ($afectadas > 0) {
            echo json_encode(['status' => true, 'message' => 'Solicitud eliminada correctamente.']);
            return;
        }

        $err = $this->db->error();
        echo json_encode([
            'status'  => false,
            'message' => 'No se elimino la solicitud: ' . ($err['message'] ?? 'sin error')
        ]);
    }


    public function ajax_resumen_estados()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $rows = $this->M_solicitudA->resumen_estados_admin();

        $out = [
            'PENDIENTE'   => 0,
            'EN_REVISION' => 0,
            'ACEPTADO'    => 0,
            'RECHAZADO'   => 0
        ];

        foreach ($rows as $r) {
            $k = strtoupper(trim($r['estado_actual']));
            if (isset($out[$k])) $out[$k] = (int)$r['total'];
        }

        $total = array_sum($out);

        echo json_encode(['status' => true, 'data' => $out, 'total' => $total]);
    }
}
