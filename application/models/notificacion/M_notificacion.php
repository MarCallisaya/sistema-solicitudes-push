<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_notificacion extends CI_Model
{
    public function datatable_list($usuario_id, $start, $length, $search = '')
    {
        $this->db->from('historial_notificaciones hn');
        $this->db->join('solicitud s', 's.id_solicitud = hn.solicitud_id', 'left');
        $this->db->join('usuario u_prof', 'u_prof.id_usuario = s.profesor_id', 'left'); 

        $this->db->where('hn.usuario_id', (int)$usuario_id);

        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('s.referencia', $search);
            $this->db->or_like('hn.mensaje', $search);
            $this->db->or_like('u_prof.nombre', $search);
            $this->db->or_like('u_prof.apellido_paterno', $search);
            $this->db->or_like('u_prof.apellido_materno', $search);
            $this->db->group_end();
        }

        $filtered_count = $this->db->count_all_results('', false);


        $this->db->select("
            hn.id_notificacion,
            COALESCE(s.referencia, '-') AS referencia,
            COALESCE(
                TRIM(u_prof.nombre || ' ' || COALESCE(u_prof.apellido_paterno,'') || ' ' || COALESCE(u_prof.apellido_materno,'')),
                '-'
            ) AS remitente,
            hn.mensaje,
            hn.fecha_envio
        ", false);

        $this->db->order_by('hn.fecha_envio', 'DESC');

        if ((int)$length !== -1) {
            $this->db->limit((int)$length, (int)$start);
        }

        $rows = $this->db->get()->result_array();

        $total_count = $this->db->from('historial_notificaciones')
            ->where('usuario_id', (int)$usuario_id)
            ->count_all_results();

        return [
            'rows' => $rows,
            'total' => $total_count,
            'filtered' => $filtered_count
        ];
    }
}
