<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_push extends CI_Model
{

    // Guarda token: si existe lo reactiva/actualiza, si no existe lo inserta
    public function upsert_token($usuario_id, $token)
    {

        // 1) Si el token ya existe, lo actualizamos
        $existe = $this->db->select('id_token')
            ->from('tokens_push')
            ->where('token', $token)
            ->limit(1)
            ->get()
            ->row();

        $data_update = [
            'usuario_id' => $usuario_id,
            'activo' => true,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ];

        if ($existe) {
            $this->db->where('id_token', (int)$existe->id_token);
            return $this->db->update('tokens_push', $data_update);
        }

        // 2) Si no existe, insertamos
        $data_insert = [
            'usuario_id' => $usuario_id,
            'token' => $token,
            'activo' => true,
            'fecha_registro' => date('Y-m-d H:i:s'),
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('tokens_push', $data_insert);
    }

    // (opcional) obtener tokens activos de un usuario
    public function get_tokens_activos($usuario_id)
    {
        return $this->db->select('token')
            ->from('tokens_push')
            ->where('usuario_id', (int)$usuario_id)
            ->where('activo', true) // PostgreSQL boolean
            ->get()
            ->result();
    }


    public function registrar_historial(
        $usuario_id,
        $titulo,
        $mensaje,
        $tipo_evento,
        $enviado_exitoso,
        $solicitud_id = null
    ) {
        $data = [
            'usuario_id' => (int)$usuario_id,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo_evento' => $tipo_evento,
            'fecha_envio' => date('Y-m-d H:i:s'),
            'enviado_exitoso' => (bool)$enviado_exitoso,
            'solicitud_id' => $solicitud_id
        ];

        return $this->db->insert('historial_notificaciones', $data);
    }


    public function desactivar_token($token)
    {
        return $this->db->where('token', $token)
            ->update('tokens_push', [
                'activo' => false,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ]);
    }

    // Devuelve IDs de usuarios activos por rol
    public function get_user_ids_by_rol($rol_id)
    {
        $rows = $this->db->select('id_usuario')
            ->from('usuario')
            ->where('rol_id', (int)$rol_id)
            ->where('activo', true)
            ->get()->result_array();

        // devolver solo array de ints
        return array_map(function ($r) {
            return (int)$r['id_usuario'];
        }, $rows);
    }

    // Atajo para admins (rol 3)
    public function get_admin_ids()
    {
        return $this->get_user_ids_by_rol(3);
    }

    public function get_profesor_id_by_solicitud($id_solicitud)
{
    $row = $this->db->select('profesor_id')
        ->from('solicitud')
        ->where('id_solicitud', (int)$id_solicitud)
        ->get()->row_array();

    return (int)($row['profesor_id'] ?? 0);
}

}
