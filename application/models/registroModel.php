<?php

class registroModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  //registra altas
  public function registrarAlta(){

    $user = $this->input->post('user');
    $tel = $this->input->post('tel');
    $data = array(
      'user' => $user,
      'tipo' => 'Alta',
      'telefono' => $tel,
      'fecha' => standard_date('DATE_W3C', now())
    );
    return $this->db->insert('regAltaBaja', $data);
  }

  //registra bajas
  public function registrarBaja(){

    $user = $this->input->post('user');
    $tel = $this->input->post('tel');
    $data = array(
      'user' => $user,
      'tipo' => 'Baja',
      'telefono' => $tel,
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
    $tel = $this->input->post('tel');
    $data = array(
      'user' => $user,
      'cantidad' => $cantidad,
      'telefono' => $tel,
      'fecha' => standard_date('DATE_W3C', now())
    );
    return $this->db->insert('regCobros', $data);
  }

  //obtiene registros de Cobros
  public function getCobros() {
    $query = $this->db->get('regCobros');
    return $query->result_array();
  }

  //registra sms enviado
  public function registrarSms($data){
    $data = array(
      'user' => $data['user'],
      'texto' => $data['text'],
      'telefono' => $data['msisdn'],
      'fecha' => standard_date('DATE_W3C', now())
    );
    return $this->db->insert('regSms', $data);
  }

  public function getSms() {
    $query = $this->db->get('regSms');
    return $query->result_array();
  }

}
