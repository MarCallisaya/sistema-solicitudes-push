<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_unidad extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('unidad/M_unidad');
    }

    public function index()
    {
        $data = []; // ✅ evita undefined variable

        $this->load->view('includes/header');
        $this->load->view('includes/sidebar');
        $this->load->view('unidad/V_unidad', $data);
        $this->load->view('includes/footer', $data);
    }

    public function lista()
    {
        echo json_encode($this->M_unidad->get_unidades());
    }

    public function guardar()
    {
        $nombre = trim($this->input->post('nombre'));
        if ($nombre === '') {
            echo json_encode(['status' => false, 'msg' => 'Nombre requerido']);
            return;
        }

        $descripcion = $this->input->post('descripcion');

        $ok = $this->M_unidad->insert_unidad([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'activo' => true
        ]);


        echo json_encode(['status' => (bool)$ok]);
    }

    public function obtener()
    {
        $id = (int)$this->input->get('id_unidad_educativa');
        if (!$id) {
            echo json_encode(['status' => false, 'msg' => 'ID requerido']);
            return;
        }

        $u = $this->M_unidad->get_unidad_by_id($id);

        if ($u) echo json_encode(['status' => true, 'data' => $u]);
        else echo json_encode(['status' => false, 'msg' => 'No encontrada']);
    }

    public function actualizar()
    {
        $id = (int)$this->input->post('id_unidad_educativa');
        $nombre = trim($this->input->post('nombre'));

        if (!$id) {
            echo json_encode(['status' => false, 'msg' => 'ID requerido']);
            return;
        }
        if ($nombre === '') {
            echo json_encode(['status' => false, 'msg' => 'Nombre requerido']);
            return;
        }

        $descripcion = $this->input->post('descripcion');

        $ok = $this->M_unidad->update_unidad($id, [
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ]);


        echo json_encode(['status' => (bool)$ok]);
    }

    public function eliminar()
    {
        $id = (int)$this->input->post('id_unidad_educativa');
        if (!$id) {
            echo json_encode(['status' => false, 'msg' => 'ID requerido']);
            return;
        }

        $ok = $this->M_unidad->soft_delete_unidad($id);

        echo json_encode(['status' => (bool)$ok]);
    }
}
