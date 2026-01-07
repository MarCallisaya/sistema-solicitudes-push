<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_solicitudP extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('C_login');
        }
        if ($this->session->userdata('rol_id') != 1) {
            show_error('No tienes permisos', 403);
        }

        $this->load->model('profesor/M_solicitudP');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $this->load->view('includes/header');
        $this->load->view('includes/sidebarP');
        $this->load->view('profesor/V_solicitudP');
        $this->load->view('includes/footer');
    }

    // ✅ DataTable: listar solicitudes del profesor
    public function ajax_listar()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $profesor_id = (int) $this->session->userdata('id_usuario');
        $rows = $this->M_solicitudP->listar_solicitudes_profesor($profesor_id);

        $data = [];
        foreach ($rows as $r) {

            // Archivo: Abrir / —
            $archivo_html = '—';
            if (!empty($r->archivo)) {
                // si guardas "/uploads/solicitudes/xxx.pdf" o "uploads/solicitudes/xxx.pdf"
                $path = ltrim($r->archivo, '/');
                $url  = base_url($path);

                $archivo_html = '<a href="' . $url . '" target="_blank">Abrir</a>';
            }

            // Acciones: Ver + Editar (solo pendiente)
            $btn_ver = '<button class="btn btn-sm btn-info btnVer" data-id="' . (int)$r->id_solicitud . '">Ver</button>';

            $is_pendiente = (strtoupper(trim($r->estado_actual)) === 'PENDIENTE');
            $btn_editar = $is_pendiente
                ? '<button class="btn btn-sm btn-primary btnEditar" data-id="' . (int)$r->id_solicitud . '">Editar</button>'
                : '<button class="btn btn-sm btn-secondary" disabled>Editar</button>';

            $acciones = $btn_ver . ' ' . $btn_editar;

            $data[] = [
                $acciones,
                $r->tipo_solicitud,
                $archivo_html,
                $r->director_nombre,
                $r->estado_actual,
                $r->fecha_registro,
                $r->observaciones ?? ''
            ];
        }

        echo json_encode([
            "data" => $data
        ]);
    }

    //OBTENER CARTA
    public function ajax_ver_carta($id_solicitud)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $profesor_id = (int) $this->session->userdata('id_usuario');
        $row = $this->M_solicitudP->obtener_carta((int)$id_solicitud, $profesor_id);

        if (!$row) {
            echo json_encode([
                'status' => false,
                'message' => 'Solicitud no encontrada'
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'data' => $row
        ]);
    }
}
