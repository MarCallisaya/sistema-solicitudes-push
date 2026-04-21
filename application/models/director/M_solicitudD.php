<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_solicitudD extends CI_Model
{
    /*public function listar_solicitudes_director($director_id)
    {
        $this->db->select("
            s.id_solicitud,
            ts.nombre AS tipo_solicitud,
            s.estado_actual,
            s.observaciones,
            to_char(s.fecha_registro, 'DD/MM/YYYY HH24:MI') AS fecha_registro,
            da.archivo,
            CONCAT(p.nombre,' ',p.apellido_paterno,' ',COALESCE(p.apellido_materno,'')) AS profesor_nombre
        ");
        $this->db->from('solicitud s');
        $this->db->join('tipo_solicitud ts', 'ts.id_tipo_solicitud = s.tipo_solicitud_id', 'inner');
        $this->db->join('usuario p', 'p.id_usuario = s.profesor_id', 'inner');
        $this->db->join('documento_adjunto da', 'da.solicitud_id = s.id_solicitud', 'left');

        $this->db->where('s.director_id', (int)$director_id);
        $this->db->where('s.activo', true);

        $this->db->order_by('s.fecha_registro', 'DESC');

        return $this->db->get()->result();
    }*/
    public function listar_solicitudes_director($director_id)
    {
        $this->db->select("
        s.id_solicitud,
        ts.nombre AS tipo_solicitud,
        s.estado_actual,
        s.observaciones,
        to_char(s.fecha_registro, 'DD/MM/YYYY HH24:MI') AS fecha_registro,
        to_char(s.fecha_registro, 'YYYY-MM-DD HH24:MI:SS') AS fecha_orden,
        da.archivo,
        CONCAT(p.nombre,' ',p.apellido_paterno,' ',COALESCE(p.apellido_materno,'')) AS profesor_nombre
    ");
        $this->db->from('solicitud s');
        $this->db->join('tipo_solicitud ts', 'ts.id_tipo_solicitud = s.tipo_solicitud_id', 'inner');
        $this->db->join('usuario p', 'p.id_usuario = s.profesor_id', 'inner');
        $this->db->join('documento_adjunto da', 'da.solicitud_id = s.id_solicitud', 'left');

        $this->db->where('s.director_id', (int)$director_id);
        $this->db->where('s.activo', true);

        $this->db->order_by('s.fecha_registro', 'DESC');

        return $this->db->get()->result();
    }

    public function obtener_carta($id_solicitud, $director_id)
    {
        $this->db->select("
            s.id_solicitud,
            s.referencia,
            s.descripcion,
            to_char(s.fecha_registro, 'DD \"de\" TMMonth YYYY HH24:MI') AS fecha_formato,
            CONCAT(p.nombre,' ',p.apellido_paterno,' ',COALESCE(p.apellido_materno,'')) AS profesor_nombre
        ");
        $this->db->from('solicitud s');
        $this->db->join('usuario p', 'p.id_usuario = s.profesor_id', 'inner');

        $this->db->where('s.id_solicitud', (int)$id_solicitud);
        $this->db->where('s.director_id', (int)$director_id);
        $this->db->where('s.activo', true);

        return $this->db->get()->row();
    }

    public function actualizar_estado($id_solicitud, $director_id, $estado)
    {
        $this->db->where('id_solicitud', (int)$id_solicitud);
        $this->db->where('director_id', (int)$director_id);
        $this->db->where('activo', true);

        return $this->db->update('solicitud', [
            'estado_actual' => $estado,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }

    public function actualizar_observacion($id_solicitud, $director_id, $obs)
    {
        $this->db->where('id_solicitud', (int)$id_solicitud);
        $this->db->where('director_id', (int)$director_id);
        $this->db->where('activo', true);

        return $this->db->update('solicitud', [
            'observaciones' => $obs,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }


    public function get_profesor_id($id_solicitud)
    {
        return $this->db->select('profesor_id')
            ->from('solicitud')
            ->where('id_solicitud', (int)$id_solicitud)
            ->where('activo', true)
            ->get()
            ->row_array();
    }

    public function resumen_estados_director($director_id)
    {
        $sql = "
            SELECT estado_actual, COUNT(*)::int AS total
            FROM solicitud
            WHERE activo = true
                AND director_id = ?
            GROUP BY estado_actual
        ";
        return $this->db->query($sql, [$director_id])->result_array();
    }
}
