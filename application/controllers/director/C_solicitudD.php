<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_solicitudD extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) redirect('C_login');
        if ((int)$this->session->userdata('rol_id') !== 2) show_error('No tienes permisos', 403);

        $this->load->model('director/M_solicitudD');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $this->load->view('includes/header');
        $this->load->view('includes/sidebarD');
        $this->load->view('director/V_solicitudD');
        $this->load->view('includes/footer');
    }

    public function ajax_listar()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $director_id = (int)$this->session->userdata('id_usuario');
        $rows = $this->M_solicitudD->listar_solicitudes_director($director_id);

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

            //$acciones = $btn_estado . ' ' . $btn_obs;
            $acciones = '
            <div class="d-flex gap-1 justify-content-center">
                ' . $btn_estado . '
                ' . $btn_obs . '
            </div>';


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

        $director_id = (int)$this->session->userdata('id_usuario');
        $row = $this->M_solicitudD->obtener_carta((int)$id_solicitud, $director_id);

        if (!$row) {
            echo json_encode(['status' => false, 'message' => 'Solicitud no encontrada']);
            return;
        }

        echo json_encode(['status' => true, 'data' => $row]);
    }

    public function ajax_actualizar_estado()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $director_id  = (int)$this->session->userdata('id_usuario');
        $id_solicitud = (int)$this->input->post('id_solicitud', true);
        $estado       = strtoupper(trim($this->input->post('estado', true)));

        $permitidos = ['PENDIENTE', 'EN_REVISION', 'ACEPTADO', 'RECHAZADO'];
        if (!in_array($estado, $permitidos, true)) {
            echo json_encode(['status' => false, 'message' => 'Estado no válido']);
            return;
        }

        $ok = $this->M_solicitudD->actualizar_estado($id_solicitud, $director_id, $estado);

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

        echo json_encode([
            'status'  => (bool)$ok,
            'message' => $ok ? 'Estado actualizado' : 'No se pudo actualizar'
        ]);
    }



    public function ajax_actualizar_observacion()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $director_id  = (int)$this->session->userdata('id_usuario');
        $id_solicitud = (int)$this->input->post('id_solicitud', true);
        $obs          = trim($this->input->post('observaciones', true));

        if ($obs === '') {
            echo json_encode(['status' => false, 'message' => 'Escribe una observación.']);
            return;
        }

        $ok = $this->M_solicitudD->actualizar_observacion($id_solicitud, $director_id, $obs);

        echo json_encode([
            'status' => (bool)$ok,
            'message' => $ok ? 'Observación guardada' : 'No se pudo guardar'
        ]);
    }

    public function ajax_resumen_estados()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $director_id = (int)$this->session->userdata('id_usuario');

        $rows = $this->M_solicitudD->resumen_estados_director($director_id);

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

        echo json_encode(['status' => true, 'data' => $out, 'total' => array_sum($out)]);
    }
}
