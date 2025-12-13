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
        $this->load->view('includes/header');
		$this->load->view('includes/sidebarD');
		$this->load->view('home');
		$this->load->view('includes/footer');
    }
}
