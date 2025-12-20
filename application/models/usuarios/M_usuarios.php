<?php
class M_usuarios extends CI_Model
{
    public function get_roles()
    {
        return $this->db
            ->where('activo', true)
            ->get('roles')
            ->result();
    }

    public function get_unidades()
    {
        return $this->db
            ->where('activo', true)
            ->get('unidad_educativa')
            ->result();
    }

    public function get_usuarios()
    {
        $this->db->select("
        u.id_usuario,
        CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS usuario,
        u.telefono,
        u.email,
        r.nombre AS rol,
        ue.nombre AS unidad,
        u.last_login
    ", false);

        $this->db->from('usuario u');
        $this->db->join('roles r', 'r.id_rol = u.rol_id');
        $this->db->join(
            'asignacion a',
            'a.usuario_id = u.id_usuario AND a.activo = TRUE',
            'inner',
            false
        );
        $this->db->join('unidad_educativa ue', 'ue.id_unidad_educativa = a.unidad_educativa_id');
        $this->db->where('u.activo = TRUE', null, false);

        return $this->db->get()->result_array();
    }

public function insert_usuario($data)
{
    $this->db->insert('usuario', $data);
    return $this->db->insert_id();
}

public function insert_asignacion($data)
{
    return $this->db->insert('asignacion', $data);
}



}
