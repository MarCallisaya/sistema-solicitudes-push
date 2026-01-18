<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_solicitudA extends CI_Model
{
    public function listar_todas()
    {
        $this->db->select("
            s.id_solicitud,
            ts.nombre AS tipo_solicitud,
            s.estado_actual,
            s.observaciones,
            to_char(s.fecha_registro, 'DD/MM/YYYY HH24:MI') AS fecha_registro,
            da.archivo,
            CONCAT(p.nombre,' ',p.apellido_paterno,' ',COALESCE(p.apellido_materno,'')) AS profesor_nombre,
            CONCAT(d.nombre,' ',d.apellido_paterno,' ',COALESCE(d.apellido_materno,'')) AS director_nombre
        ");
        $this->db->from('solicitud s');
        $this->db->join('tipo_solicitud ts', 'ts.id_tipo_solicitud = s.tipo_solicitud_id', 'inner');
        $this->db->join('usuario p', 'p.id_usuario = s.profesor_id', 'inner');
        $this->db->join('usuario d', 'd.id_usuario = s.director_id', 'inner');
        $this->db->join('documento_adjunto da', 'da.solicitud_id = s.id_solicitud', 'left');

        $this->db->where('s.activo', true);
        $this->db->order_by('s.fecha_registro', 'DESC');

        return $this->db->get()->result();
    }

    public function obtener_carta($id_solicitud)
    {

        $this->db->select("
            s.id_solicitud,
            s.referencia,
            s.descripcion,
            to_char(s.fecha_registro, 'DD \"de\" TMMonth YYYY HH24:MI') AS fecha_formato,
            CONCAT(p.nombre,' ',p.apellido_paterno,' ',COALESCE(p.apellido_materno,'')) AS profesor_nombre,
            CONCAT(d.nombre,' ',d.apellido_paterno,' ',COALESCE(d.apellido_materno,'')) AS director_nombre           
        ");
        $this->db->from('solicitud s');
        $this->db->join('usuario p', 'p.id_usuario = s.profesor_id', 'inner');
        $this->db->join('usuario d', 'd.id_usuario = s.director_id', 'inner');

        $this->db->where('s.id_solicitud', (int)$id_solicitud);
        $this->db->where('s.activo', true);

        return $this->db->get()->row();
    }

    public function actualizar_estado($id_solicitud, $estado)
    {
        $this->db->where('id_solicitud', (int)$id_solicitud);
        $this->db->where('activo', true);

        return $this->db->update('solicitud', [
            'estado_actual' => $estado,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }

    public function actualizar_observacion($id_solicitud, $obs)
    {
        $this->db->where('id_solicitud', (int)$id_solicitud);
        $this->db->where('activo', true);

        return $this->db->update('solicitud', [
            'observaciones' => $obs,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }



    public function eliminar_logico($id_solicitud)
    {
        $this->db->set('activo', false);
        $this->db->set('fecha_actualizacion', 'NOW()', false);
        $this->db->where('id_solicitud', (int)$id_solicitud);
        $this->db->where('activo', true);

        $ok = $this->db->update('solicitud');

        if (!$ok) {
            log_message('error', 'DB ERROR eliminar_logico: ' . print_r($this->db->error(), true));
            return 0;
        }

        return $this->db->affected_rows();
    }

    public function resumen_estados_admin()
    {
        $sql = "
            SELECT estado_actual, COUNT(*)::int AS total
            FROM solicitud
            WHERE activo = true
            GROUP BY estado_actual
        ";
        return $this->db->query($sql)->result_array();
    }
}
