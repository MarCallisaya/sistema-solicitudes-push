<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_unidadD extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('unidad/M_unidadD');
    }

    public function index()
    {
        $data = [];

        $this->load->view('includes/header');
        $this->load->view('includes/sidebarD');
        $this->load->view('unidad/V_unidadD', $data);
        $this->load->view('includes/footer', $data);
    }

    public function lista()
    {
        echo json_encode($this->M_unidadD->get_unidades());
    }

    //para ver los docentes por unidad
    public function ajax_docentes($unidad_id)
    {
        if (!$this->input->is_ajax_request()) show_404();

        $unidad_id = (int)$unidad_id;
        if ($unidad_id <= 0) {
            echo json_encode(['status' => false, 'message' => 'ID inválido']);
            return;
        }

        $rows = $this->M_unidadD->get_docentes_by_unidad($unidad_id);

        echo json_encode(['status' => true, 'data' => $rows]);
    }
}
