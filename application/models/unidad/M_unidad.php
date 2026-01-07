<?php
class M_unidad extends CI_Model
{
    public function get_unidades()
    {
        return $this->db

            ->select('id_unidad_educativa, nombre, descripcion')
            ->from('unidad_educativa')
            ->where('activo', true)
            ->order_by('id_unidad_educativa', 'DESC')
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
}
