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

  public function registro() {
    $data['title'] = 'Registro';

    $this->load->view('templates/header', $data);
		$this->load->view('templates/menu');
    $this->load->view('users/registro');
    $this->load->view('templates/footer');
  }

  public function alta() {
    $data['title'] = 'Alta';

    $this->load->view('templates/header', $data);
		$this->load->view('templates/menu');
    $this->load->view('users/alta');
    $this->load->view('templates/footer');
  }

  public function baja() {
    $data['title'] = 'Baja';

    $this->load->view('templates/header', $data);
		$this->load->view('templates/menu');
    $this->load->view('users/baja');
    $this->load->view('templates/footer');
  }
}
