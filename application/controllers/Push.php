<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Push extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_push');

        $this->load->library('Fcm_service');
    }
    public function ping()
    {
        echo "PUSH OK";
    }

    // POST: token
    public function guardar_token()
    {
        // Solo AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // tener sesión activa 
        $usuario_id = (int) $this->session->userdata('id_usuario');
        if ($usuario_id <= 0) {
            echo json_encode(['status' => false, 'message' => 'No autenticado']);
            return;
        }

        $token = trim((string) $this->input->post('token', true));
        if ($token === '') {
            echo json_encode(['status' => false, 'message' => 'Token vacío']);
            return;
        }

        $ok = $this->M_push->upsert_token($usuario_id, $token);

        if ($ok) {
            echo json_encode(['status' => true, 'message' => 'Token guardado']);
        } else {
            echo json_encode(['status' => false, 'message' => 'No se pudo guardar']);
        }
    }

    public function enviar_prueba()
    {
        $usuario_id = (int) $this->session->userdata('id_usuario');
        if ($usuario_id <= 0) {
            echo "No autenticado";
            return;
        }

        $tokens = $this->M_push->get_tokens_activos($usuario_id);
        if (!$tokens || count($tokens) === 0) {
            echo "No hay tokens activos";
            return;
        }

        // tomamos el primer token activo
        $token = $tokens[0]->token;

        $res = $this->fcm_service->send_to_token(
            $token,
            "Prueba FCM ✅",
            "Tu sistema está enviando notificaciones correctamente.",
            ['tipo_evento' => 'PRUEBA']
        );

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($res);
    }

    public function enviar_prueba_core()
    {
        $usuario_id = (int)$this->session->userdata('id_usuario');
        if ($usuario_id <= 0) {
            echo "No autenticado";
            return;
        }

        $ok = $this->fcm_service->send_to_user(
            $usuario_id,
            'Prueba CORE FCM',
            'Core completo funcionando correctamente.',
            'PRUEBA_CORE',
            null
        );

        echo $ok ? 'OK' : 'FALLO';
    }
}
