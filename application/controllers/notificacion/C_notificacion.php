<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_notificacion extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $this->load->model('notificacion/M_notificacion');
    }

    public function index()
    {
        $data['title'] = 'Notificaciones';
        $this->load->view('includes/header', $data);
        $this->load->view('includes/sidebar');
        $this->load->view('notificacion/V_notificacion');
        $this->load->view('includes/footer');
    }

    public function ajax_listar()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $usuario_id = (int)$this->session->userdata('id_usuario');

        $draw   = (int)$this->input->post('draw');
        $start  = (int)$this->input->post('start');
        $length = (int)$this->input->post('length');

        $search = $this->input->post('search');
        $search_value = is_array($search) ? trim($search['value'] ?? '') : '';

        $res = $this->M_notificacion->datatable_list($usuario_id, $start, $length, $search_value);

        $data = [];
        foreach ($res['rows'] as $r) {
            $data[] = [
                $r['referencia'],
                $r['remitente'],
                $r['mensaje'],
                date('d/m/Y H:i', strtotime($r['fecha_envio']))
            ];
        }

        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $res['total'],
            'recordsFiltered' => $res['filtered'],
            'data' => $data
        ]);
    }
}
