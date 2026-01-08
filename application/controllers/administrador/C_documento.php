<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_documento extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) redirect('C_login');
        if ((int)$this->session->userdata('rol_id') !== 3) show_error('No tienes permisos', 403);

        $this->load->model('administrador/M_documento');
        $this->load->helper(['url']);
    }

    public function index()
    {
        $this->load->view('includes/header');
        $this->load->view('includes/sidebar');
        $this->load->view('administrador/V_documento');
        $this->load->view('includes/footer');
    }

    public function ajax_listar()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $rows = $this->M_documento->listar_documentos();

        $data = [];
        foreach ($rows as $r) {

            $archivo_html = '—';
            if (!empty($r->archivo)) {
                $path = ltrim($r->archivo, '/');
                $archivo_html = '<a href="' . base_url($path) . '" target="_blank">Abrir</a>';
            }

            $data[] = [
                $r->profesor_nombre,
                $r->motivo,
                $archivo_html,
                $r->fecha_registro
            ];
        }

        echo json_encode(['data' => $data]);
    }
}
