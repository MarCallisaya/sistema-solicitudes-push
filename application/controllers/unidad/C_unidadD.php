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
        $data = []; // ✅ evita undefined variable

        $this->load->view('includes/header');
        $this->load->view('includes/sidebarD');
        $this->load->view('unidad/V_unidadD', $data);
        $this->load->view('includes/footer', $data);
    }

    public function lista()
    {
        echo json_encode($this->M_unidadD->get_unidades());
    }


}
