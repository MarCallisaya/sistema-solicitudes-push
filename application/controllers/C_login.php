<?php
/*
defined('BASEPATH') OR exit('No direct script access allowed');
class C_login extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('M_login');
    }
    public function login(){
        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            if ($username && $password) {
                $login = $this->M_login->loginUser($username, $password);
                if ($login) {
					$array = array(
						"id_usuario"                => $login->id_usuario,
						"nombre"            => $login->nombre,
						"apellido_paterno"  => $login->apellido_paterno,
						"apellido_materno"  => $login->apellido_materno,
						"username"          => $login->username,
						"telefono"          => $login->telefono,
						"logged_in"         => TRUE
					);
					$this->session->set_userdata($array);
					print_r($this->session->userdata());
				}
            }
        }
        $this->load->view('login');
    }
}
*/

defined('BASEPATH') or exit('No direct script access allowed');
class C_login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_login');
    }
    public function index()
    {
        $this->load->view('login');
    }

    public function login()
    {

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if (!$username || !$password) {
            redirect('C_login');
        }

        $user = $this->M_login->loginUser($username, $password);

        if ($user) {

            $session = array(
                'id_usuario' => $user->id_usuario,
                'rol_id'     => $user->rol_id,
                'nombre'     => $user->nombre,
                'username'   => $user->username,
                'logged_in'  => true
            );

            $this->session->set_userdata($session);

            // REDIRECCIÓN POR ROL
            switch ($user->rol_id) {
                case 1:
                    redirect('profesor/C_dashboard');
                    break;
                case 2:
                    redirect('director/C_dashboard');
                    break;
                case 3:
                    redirect('administrador/C_dashboard');
                    break;
                default:
                    $this->session->sess_destroy();
                    redirect('C_login');
            }
        } else {
            $this->session->set_flashdata('error', 'Usuario o contraseña incorrectos');
            redirect('C_login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('C_login');
    }
}
