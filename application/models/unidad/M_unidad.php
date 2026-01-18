<?php
class M_unidad extends CI_Model
{
    /*
    public function get_unidades()
    {
        return $this->db

            ->select('id_unidad_educativa, nombre, descripcion')
            ->from('unidad_educativa')
            ->where('activo', true)
            ->order_by('id_unidad_educativa', 'DESC')
            ->get()
            ->result_array();
    }*/

    public function get_unidades()
    {
        return $this->db
            ->select("
            ue.id_unidad_educativa,
            ue.nombre,
            ue.descripcion,
            COUNT(DISTINCT u.id_usuario) FILTER (
                WHERE a.activo = true
                  AND u.activo = true
                  AND u.rol_id = 1
            )::int AS total_docentes
        ", false)
            ->from('unidad_educativa ue')
            ->join('asignacion a', 'a.unidad_educativa_id = ue.id_unidad_educativa', 'left')
            ->join('usuario u', 'u.id_usuario = a.usuario_id', 'left')
            ->where('ue.activo', true)
            ->group_by(['ue.id_unidad_educativa', 'ue.nombre', 'ue.descripcion'])
            ->order_by('ue.id_unidad_educativa', 'DESC')
            ->get()
            ->result_array();
    }


    public function insert_unidad($data)
    {
        return $this->db->insert('unidad_educativa', $data);
    }

    public function get_unidad_by_id($id)
    {
        return $this->db
            ->where('id_unidad_educativa', $id)
            ->where('activo', true)
            ->get('unidad_educativa')
            ->row_array();
    }

    public function update_unidad($id, $data)
    {
        return $this->db
            ->where('id_unidad_educativa', $id)
            ->update('unidad_educativa', $data);
    }

    public function soft_delete_unidad($id)
    {
        return $this->db
            ->where('id_unidad_educativa', $id)
            ->update('unidad_educativa', ['activo' => false]);
    }


    public function get_docentes_by_unidad($unidad_id)
    {
        $sql = "
            SELECT DISTINCT
                u.id_usuario,
                u.nombre,
                u.apellido_paterno,
                u.apellido_materno,
                u.ci,
                u.telefono,
                u.email,
                u.username
            FROM asignacion a
            INNER JOIN usuario u ON u.id_usuario = a.usuario_id
            WHERE a.activo = true
                AND u.activo = true
                AND u.rol_id = 1
                AND a.unidad_educativa_id = ?
            ORDER BY u.apellido_paterno ASC, u.apellido_materno ASC, u.nombre ASC
        ";
        return $this->db->query($sql, [(int)$unidad_id])->result_array();
    }
}
