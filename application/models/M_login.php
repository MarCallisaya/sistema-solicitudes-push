<?php
/*
class M_login extends CI_Model{
    public function loginUser($username, $password){
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get('usuario');
        if ($query->num_rows() > 0) {
            return $query->row(); // solo un usuario
        } else {
            return false;
        }
    }
}

*/


defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{

    public function getUserByUsername($username)
    {
        return $this->db
            ->where('username', $username)
            ->where('activo', true)
            ->get('usuario')
            ->row();
    }

    public function updatePasswordHash($id_usuario, $hash)
    {
        return $this->db
            ->where('id_usuario', $id_usuario)
            ->update('usuario', ['password' => $hash]);
    }
}
