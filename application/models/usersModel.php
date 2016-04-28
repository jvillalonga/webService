<?php

class usersModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  //cobrar a un usuario
  public function cobrar(){
    $cobro = $this->input->post('cantidad');
    $user = $this->input->post('user');
    $tel = $this->input->post('tel');
    //getToken...
    // $this->db->where('user', $user);
    // $this->db->update('users');
  }

  //cobrar a todos los usuarios suscritos
  public function cobrarSuscritos(){
    $cobro = $this->input->post('cantidad');

    //da de baja a los usuarios con saldo insuficiente
    // $this->db->set('estado', 'Baja');
    // $this->db->where('estado', 'Alta');
    // $this->db->update('users');

    //cobra a los usuarios de alta
    // $this->db->where('estado', 'Alta');
    // $this->db->update('users');
  }

  //da de alta al usuario
  public function alta(){
    $telefono = $this->input->post('tel');
    $this->db->set('estado', 'Alta');
    $this->db->where('telefono', $telefono);
    $this->db->update('users');
  }

  //da de baja al usuario
  public function baja(){
    $telefono = $this->input->post('tel');
    $this->db->set('estado', 'Baja');
    $this->db->where('telefono', $telefono);
    $this->db->update('users');

  }

  //obtiene datos de todos los usuario
  public function getAllUsers() {
    $query = $this->db->get('users');
    return $query->result_array();
  }

  //obtiene datos de todos los usuario dados de alta*
  public function getAltaUsers() {
    $this->db->where('estado', 'Alta');
    $query = $this->db->get('users');
    return $query->result_array();
  }

  //obtiene datos de todos los usuario sin dar de alta*
  public function getBajaUsers() {
    $this->db->where('estado', 'Baja');
    $query = $this->db->get('users');
    return $query->result_array();
  }

  //obtiene datos de un usuario*
  public function getUser($user) {
    $this->db->where('user', $user);
    $query = $this->db->get('users');
    return $query->result_array();
  }

  // comprueba si user y pass son correctos*
  public function getCountUser() {
    $user = $this->input->post('user');
    $pass = $this->input->post('pass');
    $this->db->count_all_results('users');
    $this->db->where('user', $user);
    $this->db->where('pass', MD5($pass));
    $this->db->from('users');
    $query = $this->db->count_all_results();
    return $query;
  }
  //insert de usuario
  public function setUser() {
    $this->load->helper('url');
    $user = $this->input->post('user');
    $pass = $this->input->post('pass');
    $telefono = $this->input->post('telefono');
      $data = array(
        'user' => $user,
        'pass' => MD5($pass),
        'telefono' => $telefono
      );
    return $this->db->insert('users', $data);
  }

}
