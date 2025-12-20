<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_usuarios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarios/M_usuarios');
    }

    public function index()
    {
        $data['roles']    = $this->M_usuarios->get_roles();
        $data['unidades'] = $this->M_usuarios->get_unidades();

        $this->load->view('includes/header');
        $this->load->view('includes/sidebar');
        $this->load->view('usuarios/V_usuarios', $data);
        $this->load->view('includes/footer', $data);
    }

    public function lista_usuarios()
    {
        echo json_encode($this->M_usuarios->get_usuarios());
    }

public function guardar()
{
    $data = [
        'nombre'            => $this->input->post('nombre'),
        'apellido_paterno'  => $this->input->post('apellido_paterno'),
        'apellido_materno'  => $this->input->post('apellido_materno'),
        'ci'                => $this->input->post('ci'),
        'telefono'          => $this->input->post('telefono'),
        'email'             => $this->input->post('email'),
        'rol_id'            => $this->input->post('rol_id'),
        'username'          => $this->input->post('username'),
        'password'          => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'activo'            => true
    ];

    $usuario_id = $this->M_usuarios->insert_usuario($data);

    if ($usuario_id) {
        // asignación
        $this->M_usuarios->insert_asignacion([
            'usuario_id' => $usuario_id,
            'unidad_educativa_id' => $this->input->post('unidad_educativa_id'),
            'activo' => true
        ]);

        echo json_encode(['status' => true]);
    } else {
        echo json_encode(['status' => false, 'msg' => 'No se pudo guardar']);
    }
}



}
