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

class M_login extends CI_Model {
    public function loginUser($username, $password){
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $this->db->where('activo', true);
        return $this->db->get('usuario')->row();
    }
}



