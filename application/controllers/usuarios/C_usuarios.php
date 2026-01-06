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

    public function obtener_usuario()
    {
        $id = $this->input->get('id_usuario');
        if (!$id) {
            echo json_encode(['status' => false, 'msg' => 'No llegó el ID']);
            return;
        }

        $u = $this->M_usuarios->get_usuario_by_id($id);
        if ($u) {
            echo json_encode(['status' => true, 'data' => $u]);
        } else {
            echo json_encode(['status' => false, 'msg' => 'Usuario no encontrado']);
        }
    }

    public function actualizar()
    {
        $id_usuario = $this->input->post('id_usuario');
        if (!$id_usuario) {
            echo json_encode(['status' => false, 'msg' => 'No llegó el ID']);
            return;
        }

        $data = [
            'nombre'           => $this->input->post('nombre'),
            'apellido_paterno' => $this->input->post('apellido_paterno'),
            'apellido_materno' => $this->input->post('apellido_materno'),
            'ci'               => $this->input->post('ci'),
            'telefono'         => $this->input->post('telefono'),
            'email'            => $this->input->post('email'),
            'rol_id'           => $this->input->post('rol_id'),
            'username'         => $this->input->post('username'),
        ];

        // password opcional
        $pass = $this->input->post('password');
        if (!empty($pass)) {
            $data['password'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        $okUsuario = $this->M_usuarios->update_usuario($id_usuario, $data);

        $unidad_id = $this->input->post('unidad_educativa_id');
        $okAsig = $this->M_usuarios->update_asignacion_activa($id_usuario, $unidad_id);

        if ($okUsuario && $okAsig) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'msg' => 'No se pudo actualizar']);
        }
    }
}
