<?php
class M_unidadD extends CI_Model
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

    public function get_unidad_by_id($id)
    {
        return $this->db
            ->where('id_unidad_educativa', $id)
            ->where('activo', true)
            ->get('unidad_educativa')
            ->row_array();
    }

    

    
}
