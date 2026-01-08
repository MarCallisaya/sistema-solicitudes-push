<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_documento extends CI_Model
{
    public function listar_documentos()
    {
        $this->db->select("
            CONCAT(u.nombre,' ',u.apellido_paterno,' ',COALESCE(u.apellido_materno,'')) AS profesor_nombre,
            COALESCE(s.referencia,'—') AS motivo,
            da.archivo,
            to_char(s.fecha_registro, 'DD/MM/YYYY HH24:MI') AS fecha_registro
        ");
        $this->db->from('solicitud s');
        $this->db->join('usuario u', 'u.id_usuario = s.profesor_id', 'inner');
        $this->db->join('documento_adjunto da', 'da.solicitud_id = s.id_solicitud', 'inner');

        // Solo activos (si quieres incluir también los desactivados lo quitamos)
        $this->db->where('s.activo', true);

        $this->db->order_by('s.fecha_registro', 'DESC');

        return $this->db->get()->result();
    }
}
