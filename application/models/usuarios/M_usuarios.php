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

    public function get_usuario_by_id($id_usuario)
    {
        $this->db->select("
        u.id_usuario,
        u.nombre,
        u.apellido_paterno,
        u.apellido_materno,
        u.ci,
        u.telefono,
        u.email,
        u.rol_id,
        u.username,
        a.unidad_educativa_id
    ", false);

        $this->db->from('usuario u');
        $this->db->join('asignacion a', 'a.usuario_id = u.id_usuario AND a.activo = TRUE', 'left', false);
        $this->db->where('u.id_usuario', $id_usuario);
        $this->db->where('u.activo', true);

        return $this->db->get()->row_array();
    }

    public function update_usuario($id_usuario, $data)
    {
        $this->db->where('id_usuario', $id_usuario);
        return $this->db->update('usuario', $data);
    }

    public function update_asignacion_activa($usuario_id, $unidad_educativa_id)
    {
        $asig = $this->db
            ->where('usuario_id', $usuario_id)
            ->where('activo', true)
            ->get('asignacion')
            ->row();

        if ($asig) {
            // Si tu PK no es id_asignacion, aquí se cambia
            $this->db->where('id_asignacion', $asig->id_asignacion);
            return $this->db->update('asignacion', [
                'unidad_educativa_id' => $unidad_educativa_id
            ]);
        }

        return $this->db->insert('asignacion', [
            'usuario_id' => $usuario_id,
            'unidad_educativa_id' => $unidad_educativa_id,
            'activo' => true
        ]);
    }
}
