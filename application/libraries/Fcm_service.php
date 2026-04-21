<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Google\Auth\Credentials\ServiceAccountCredentials;

class Fcm_service
{

    // 13 1 25

    protected $CI;
    protected $project_id;
    protected $sa_path;

    public function __construct()
    {
        $this->CI = &get_instance();

        // Cargar config
        $this->project_id = $this->CI->config->item('fcm_project_id');
        $this->sa_path    = $this->CI->config->item('fcm_sa_path');

        // Cargar Composer
        $autoload = FCPATH . 'vendor/autoload.php';
        if (!file_exists($autoload)) {
            throw new Exception("No existe vendor/autoload.php");
        }
        require_once $autoload;

        if (!$this->project_id) {
            throw new Exception("fcm_project_id no configurado");
        }

        if (!file_exists($this->sa_path)) {
            throw new Exception("Service Account JSON no encontrado");
        }
    }

    private function get_access_token()
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $credentials = new ServiceAccountCredentials(
            $scopes,
            $this->sa_path
        );

        $token = $credentials->fetchAuthToken();

        if (!isset($token['access_token'])) {
            throw new Exception("No se pudo obtener access token");
        }

        return $token['access_token'];
    }

    public function send_to_token($token, $title, $body, $data = [])
    {
        $access_token = $this->get_access_token();

        $url = "https://fcm.googleapis.com/v1/projects/{$this->project_id}/messages:send";

        $payload = [
            "message" => [
                "token" => $token,
                "notification" => [
                    "title" => $title,
                    "body"  => $body
                ],
                "data" => (object)$data
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'ok' => ($httpCode >= 200 && $httpCode < 300),
            'http' => $httpCode,
            'response' => $response
        ];
    }

 

    private function es_token_invalido($httpCode, $responseBody)
    {
        if ($httpCode == 404 || $httpCode == 400) {
            return true;
        }

        if (is_string($responseBody) && stripos($responseBody, 'UNREGISTERED') !== false) {
            return true;
        }

        return false;
    }

    public function send_to_user(
        $usuario_id,
        $titulo,
        $mensaje,
        $tipo_evento,
        $solicitud_id = null,
        $extra_data = []
    ) {
        $this->CI->load->model('M_push');

        // 1 Obtener tokens activos
        $tokens = $this->CI->M_push->get_tokens_activos($usuario_id);

        if (!$tokens || count($tokens) === 0) {
            // Registrar historial como fallo
            $this->CI->M_push->registrar_historial(
                $usuario_id,
                $titulo,
                $mensaje,
                $tipo_evento,
                false,
                $solicitud_id
            );
            return false;
        }

        $exito = false;

        // 2 Enviar a cada token
        foreach ($tokens as $t) {
            $res = $this->send_to_token(
                $t->token,
                $titulo,
                $mensaje,
                array_merge($extra_data, [
                    'tipo_evento' => $tipo_evento,
                    'usuario_id' => (string)$usuario_id
                ])
            );

            if ($res['ok']) {
                $exito = true;
            } else {
                // 3 Si token inválido → desactivar
                if ($this->es_token_invalido($res['http'], $res['response'])) {
                    $this->CI->M_push->desactivar_token($t->token);
                }
            }
        }

        // 4 - Registrar historial (1 por evento)
        $this->CI->M_push->registrar_historial(
            $usuario_id,
            $titulo,
            $mensaje,
            $tipo_evento,
            $exito,
            $solicitud_id
        );

        return $exito;
    }
}
