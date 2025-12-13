<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function index()
	{
		$this->load->view('includes/header');
		$this->load->view('includes/sidebar');
		$this->load->view('home');
		$this->load->view('includes/footer');
	}

	public function hola(){
		//$resultado=$this->Site_model->getProfesores();
		$resultado=$this->Site_model->updateProfesor();
		//print_r($resultado);
	}
}
