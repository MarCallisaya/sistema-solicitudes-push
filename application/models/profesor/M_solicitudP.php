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

    // FORMULARIO 
    public function get_tipos_activos()
    {
        return $this->db->select('id_tipo_solicitud, nombre')
            ->from('tipo_solicitud')
            ->where('activo', true)
            ->order_by('nombre', 'ASC')
            ->get()->result();
    }

    public function get_directores_activos()
    {
        return $this->db->select("
            id_usuario,
            CONCAT(nombre,' ',apellido_paterno,' ',COALESCE(apellido_materno,'')) AS nombre_completo
        ")
            ->from('usuario')
            ->where('activo', true)
            ->where('rol_id', 2) // Director
            ->order_by('nombre', 'ASC')
            ->get()->result();
    }

    public function crear_solicitud($data_solicitud, $data_documento = null)
    {
        $this->db->trans_begin();

        $this->db->insert('solicitud', $data_solicitud);
        $id_solicitud = $this->db->insert_id();

        if ($data_documento) {
            $data_documento['solicitud_id'] = $id_solicitud;
            $this->db->insert('documento_adjunto', $data_documento);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $id_solicitud;
    }
}
