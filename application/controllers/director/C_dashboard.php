<?php
class C_dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('C_login');
        }
        if ($this->session->userdata('rol_id') != 2) {
            show_error('No tienes permisos', 403);
        }
    }
    public function index()
    {
        $this->load->view('director/V_dashboard');
    }
}
