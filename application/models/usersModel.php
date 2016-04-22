<?php

class usersModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  //da de alta al usuario
  public function alta(){
    $user = $this->input->post('user');

    $this->db->set('alta', 1);
    $this->db->where('user', $user);
    $this->db->update('users');
  }

  //da de baja al usuario
  public function baja(){
    $user = $this->input->post('user');

    $this->db->set('alta', 0);
    $this->db->where('user', $user);
    $this->db->update('users');

  }

  //obtiene datos de todos los usuario
  public function getAllUsers() {
    $query = $this->db->get('users');
    return $query->result_array();
  }

  //obtiene datos de todos los usuario dados de alta
  public function getAltaUsers() {
    $this->db->where('alta', 1);
    $query = $this->db->get('users');
    return $query->result_array();
  }

  //obtiene datos de todos los usuario sin dar de alta
  public function getBajaUsers() {
    $this->db->where('alta', 0);
    $query = $this->db->get('users');
    return $query->result_array();
  }

  //obtiene datos de un usuario
  public function getUser() {
    $user = $this->input->post('user');
    $this->db->where('user', $user);
    $query = $this->db->get('users');
    return $query->result_array();
  }

  // comprueba si el user i pass son correctos
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
    $fondos = $this->input->post('fondos');
      $data = array(
        'user' => $user,
        'pass' => MD5($pass),
        'telefono' => $telefono,
        'fondos' => $fondos
      );
      return $this->db->insert('users', $data);
  }
  //comprueba si existe el nombre de usuario
  public function getUserName() {
    $user = $this->input->post('user');
    $this->db->count_all_results('users');
    $this->db->where('user', $user);
    $this->db->from('users');
    $query = $this->db->count_all_results();
    return $query;
  }
}
