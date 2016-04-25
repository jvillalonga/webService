<?php

class registroModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  //registra altas y bajas
  public function registrarAltaBaja(){

      $user = $this->input->post('user');
      $tipo = $this->input->post('tipo');
        $data = array(
          'user' => $user,
          'tipo' => $tipo,
          'fecha' => standard_date('DATE_W3C', now())
        );
      return $this->db->insert('regAltaBaja', $data);
    }

    //obtiene registros de Altas y Bajas
    public function getAltaBaja() {
      $query = $this->db->get('regAltaBaja');
      return $query->result_array();
    }

    //registra cobro
    public function registrarCobro(){
      $user = $this->input->post('user');
      $cantidad = $this->input->post('cantidad');
        $data = array(
          'user' => $user,
          'cantidad' => $cantidad,
          'fecha' => standard_date('DATE_W3C', now())
        );
      return $this->db->insert('cobros', $data);
    }

    //obtiene registros de Cobros
    public function getCobros() {
      $query = $this->db->get('cobros');
      return $query->result_array();
    }

}
