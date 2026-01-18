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
            ->where('rol_id', 2) 
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

    // PARA EDITAR LA SOLICITUD
    public function obtener_para_editar($id_solicitud, $profesor_id)
    {
        $this->db->select("
            s.id_solicitud,
            s.director_id,
            s.tipo_solicitud_id,
            s.referencia,
            s.descripcion,
            s.estado_actual,
            da.archivo
        ");
        $this->db->from('solicitud s');
        $this->db->join('documento_adjunto da', 'da.solicitud_id = s.id_solicitud', 'left');
        $this->db->where('s.id_solicitud', (int)$id_solicitud);
        $this->db->where('s.profesor_id', (int)$profesor_id);
        $this->db->where('s.activo', true);

        return $this->db->get()->row();
    }

    public function actualizar_solicitud($id_solicitud, $profesor_id, $data_solicitud)
    {
        $this->db->where('id_solicitud', (int)$id_solicitud);
        $this->db->where('profesor_id', (int)$profesor_id);
        $this->db->where('activo', true);
        $this->db->where('estado_actual', 'PENDIENTE');
        return $this->db->update('solicitud', $data_solicitud);
    }

    public function upsert_documento($id_solicitud, $ruta_web, $tipo_archivo)
    {
        $existe = $this->db->select('id_documento_adjunto')
            ->from('documento_adjunto')
            ->where('solicitud_id', (int)$id_solicitud)
            ->get()->row();

        $data = [
            'archivo' => $ruta_web,
            'tipo_archivo' => $tipo_archivo,
            'descripcion' => 'Adjunto de solicitud',
            'fecha_actualizacion' => date('Y-m-d H:i:s'),
        ];

        if ($existe) {
            $this->db->where('solicitud_id', (int)$id_solicitud);
            return $this->db->update('documento_adjunto', $data);
        } else {
            $data['solicitud_id'] = (int)$id_solicitud;
            $data['fecha_registro'] = date('Y-m-d H:i:s');
            return $this->db->insert('documento_adjunto', $data);
        }
    }


    public function resumen_estados_profesor($profesor_id)
    {
        $sql = "
            SELECT estado_actual, COUNT(*)::int AS total
            FROM solicitud
            WHERE activo = true
                AND profesor_id = ?
            GROUP BY estado_actual
        ";
        return $this->db->query($sql, [$profesor_id])->result_array();
    }
}
