<?php

class Users extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('usersModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');
    $this->load->helper('form');
    $this->load->library('session');

  }

  //funcion para registrar usuarios
  public function registrar() {
    $this->load->helper('form');
    $this->load->library('form_validation');
      
    $this->form_validation->set_message('required', '{field} requerido.');
    $this->form_validation->set_message('is_unique', 'Este {field} ya está en uso.');

    $this->form_validation->set_rules('user', 'Usuario', 'required|is_unique[users.user]');
    $this->form_validation->set_rules('pass', 'Password', 'required');
    $this->form_validation->set_rules('telefono', 'Teléfono', 'required');
    $this->form_validation->set_rules('fondos', 'Fondos', 'required');

    if ($this->form_validation->run() === FALSE) {

      $this->all();

    } else {
      $this->usersModel->setUser();
      $this->all();

    }
  }

  public function all()
  {
    $data['users'] = $this->usersModel->getAllUsers();
    $data['title'] = 'Todos los usuarios';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/menu');
    $this->load->view('users/all', $data);
    $this->load->view('users/registro');
    $this->load->view('templates/footer');
  }

  public function alta() {

    $this->usersModel->alta();

    $data['title'] = 'Todos los usuarios';
    $data['users'] = $this->usersModel->getAllUsers();

    $this->all();
  }

  public function baja() {

    $this->usersModel->baja();

    $data['title'] = 'Todos los usuarios';
    $data['users'] = $this->usersModel->getAllUsers();

    $this->all();
  }
}
