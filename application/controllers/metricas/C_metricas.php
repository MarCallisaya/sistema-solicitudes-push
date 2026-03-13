<?php
// Creado: 13 1 26
defined('BASEPATH') or exit('No direct script access allowed');
class C_metricas extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('rol_id') != 3) {
            redirect('login');
        }
        $this->load->model('metricas/M_metricas');
    } 

    public function index()
    {
        $this->load->view('includes/header');
        $this->load->view('includes/sidebar');
        $this->load->view('metricas/V_metricas');
        $this->load->view('includes/footer');
    }

    public function por_estado()
    {
        echo json_encode($this->M_metricas->por_estado());
    }
    public function por_tipo()
    {
        echo json_encode($this->M_metricas->por_tipo());
    }
    public function por_dia()
    {
        echo json_encode($this->M_metricas->por_dia());
    }
    public function ultimos_30_dias()
    {
        echo json_encode($this->M_metricas->ultimos_30_dias());
    }
}
