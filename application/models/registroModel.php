<?php

class registroModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  public function registrarAltaBaja(){

      $user = $this->input->post('user');
      $tipo = $this->input->post('alta');
        $data = array(
          'user' => $user,
          'pass' => $pass,
          'fecha' => standard_date('DATE_W3C', now())
        );
        return $this->db->insert('users', $data);
    }

}
