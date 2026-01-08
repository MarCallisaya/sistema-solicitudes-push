<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_usuariosD extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios/M_usuariosD');
    }

    public function index()
    {
        $data['roles']    = $this->M_usuariosD->get_roles();
        $data['unidades'] = $this->M_usuariosD->get_unidades();

        $this->load->view('includes/header');
        $this->load->view('includes/sidebarD');
        $this->load->view('usuarios/V_usuarioD');
        $this->load->view('includes/footer');
    }

    public function lista_usuarios()
    {
        echo json_encode($this->M_usuariosD->get_usuarios());
    }

    
   
    


}
