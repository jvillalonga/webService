<?php

class usersModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  //da de alta al usuario
  public function altaUser($user){

  }

  //da de baja al usuario
  public function bajaUser(){

  }

  //obtiene datos del usuario
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
      $data = array(
        'user' => $user,
        'pass' => MD5($pass)
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
