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
            $btn_ver = '<button class="btn btn-sm btn-info btnVer" data-id="' . (int)$r->id_solicitud . '"><i class="fa fa-eye"></i>
</button>';

            $is_pendiente = (strtoupper(trim($r->estado_actual)) === 'PENDIENTE');
            $btn_editar = $is_pendiente
                ? '<button class="btn btn-sm btn-primary btnEditar" data-id="' . (int)$r->id_solicitud . '"><i class="fa fa-pencil"></i></button>'
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

    // FORMULARIO
    public function ajax_form_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $tipos = $this->M_solicitudP->get_tipos_activos();
        $directores = $this->M_solicitudP->get_directores_activos();

        echo json_encode([
            'status' => true,
            'tipos' => $tipos,
            'directores' => $directores
        ]);
    }

    public function ajax_crear()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $profesor_id = (int) $this->session->userdata('id_usuario');

        $director_id = (int) $this->input->post('director_id', true);
        $tipo_id     = (int) $this->input->post('tipo_solicitud_id', true);
        $referencia  = trim($this->input->post('referencia', true));
        $descripcion = trim($this->input->post('descripcion', true));

        // Validación mínima
        if ($director_id <= 0 || $tipo_id <= 0 || $descripcion === '') {
            echo json_encode([
                'status' => false,
                'message' => 'Completa: Director, Tipo y Descripción.'
            ]);
            return;
        }

        // Datos para solicitud
        $data_solicitud = [
            'profesor_id' => $profesor_id,
            'director_id' => $director_id,
            'tipo_solicitud_id' => $tipo_id,
            'referencia' => $referencia,
            'descripcion' => $descripcion,
            'estado_actual' => 'PENDIENTE',
            'observaciones' => null,
            'fecha_registro' => date('Y-m-d H:i:s'),
            'fecha_actualizacion' => date('Y-m-d H:i:s'),
            'activo' => true
        ];

        // Archivo opcional
        $data_documento = null;

        if (!empty($_FILES['archivo']['name'])) {
            $upload_path = FCPATH . 'uploads/solicitudes/';

            if (!is_dir($upload_path)) {
                @mkdir($upload_path, 0777, true);
            }

            $config = [
                'upload_path'   => $upload_path,
                'allowed_types' => 'pdf|jpg|jpeg|png',
                'max_size'      => 5120, // 5MB
                'encrypt_name'  => true
            ];

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('archivo')) {
                echo json_encode([
                    'status' => false,
                    'message' => strip_tags($this->upload->display_errors())
                ]);
                return;
            }

            $up = $this->upload->data();

            // guardamos ruta web (como dijiste)
            $ruta_web = '/uploads/solicitudes/' . $up['file_name'];

            $data_documento = [
                'archivo' => $ruta_web,
                'tipo_archivo' => $up['file_ext'], // ej: .pdf
                'descripcion' => 'Adjunto de solicitud',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'fecha_actualizacion' => date('Y-m-d H:i:s'),
            ];
        }

        $id = $this->M_solicitudP->crear_solicitud($data_solicitud, $data_documento);

        if (!$id) {
            echo json_encode(['status' => false, 'message' => 'No se pudo guardar la solicitud.']);
            return;
        }

        echo json_encode(['status' => true, 'message' => 'Solicitud registrada correctamente.']);
    }
}
