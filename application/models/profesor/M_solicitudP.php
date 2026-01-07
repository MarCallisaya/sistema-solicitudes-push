<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_solicitudP extends CI_Model
{
    public function listar_solicitudes_profesor($profesor_id)
    {
        $this->db->select("
            s.id_solicitud,
            ts.nombre AS tipo_solicitud,
            s.estado_actual,
            s.observaciones,
            to_char(s.fecha_registro, 'DD/MM/YYYY HH24:MI') AS fecha_registro,
            da.archivo,
            CONCAT(u.nombre,' ',u.apellido_paterno,' ',COALESCE(u.apellido_materno,'')) AS director_nombre
        ");
        $this->db->from('solicitud s');
        $this->db->join('tipo_solicitud ts', 'ts.id_tipo_solicitud = s.tipo_solicitud_id', 'inner');
        $this->db->join('usuario u', 'u.id_usuario = s.director_id', 'inner');
        $this->db->join('documento_adjunto da', 'da.solicitud_id = s.id_solicitud', 'left');

        $this->db->where('s.profesor_id', $profesor_id);
        $this->db->where('s.activo', true);

        // "orden de llegada" = primero los más antiguos

        $this->db->order_by('s.fecha_registro', 'DESC');


        return $this->db->get()->result();
    }

    // OBTENER CARTA
    public function obtener_carta($id_solicitud, $profesor_id)
    {
        $this->db->select("
        s.id_solicitud,
        s.referencia,
        s.descripcion,
        to_char(s.fecha_registro, 'DD \"de\" TMMonth YYYY HH24:MI') AS fecha_formato,
        CONCAT(d.nombre,' ',d.apellido_paterno,' ',COALESCE(d.apellido_materno,'')) AS director_nombre
    ");
        $this->db->from('solicitud s');
        $this->db->join('usuario d', 'd.id_usuario = s.director_id', 'inner');

        $this->db->where('s.id_solicitud', (int)$id_solicitud);
        $this->db->where('s.profesor_id', (int)$profesor_id);
        $this->db->where('s.activo', true);

        return $this->db->get()->row();
    }
}
