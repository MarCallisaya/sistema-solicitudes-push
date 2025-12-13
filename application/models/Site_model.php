<?php
class Site_model extends CI_Model{
    public function login(){

    }

    public function insertProfesor(){
        $array=array(
            "rol_id"=>2,
            "nombre"=>"Kevin",
            "apellido_paterno"=>"Quisbert",
            "apellido_materno"=>"Vilela",
            "username"=>"kevin123",
            "password"=>"12345678"

        );
        $this->db->insert("usuario",$array);
    }

    public function getProfesores(){
        $this->db->select("*");
        $this->db->from("usuario");
        //$this->db->where("rol_id",2); //para que me muestre los datos de los profesores

        $query=$this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }else{
            return NULL;
        }
    }

    public function updateProfesor(){
        $array=array(
            "rol_id"=>2,
            "nombre"=>"Kevin Oscar",
            "apellido_paterno"=>"Quisbert",
            "apellido_materno"=>"Vilela",
            "username"=>"kevin123",
            "password"=>"12345678"

        );
        $this->db->where("id_usuario",8);
        $this->db->update("usuario",$array);
    }
}