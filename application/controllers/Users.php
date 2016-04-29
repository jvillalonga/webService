<?php

class Users extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('usersModel');
    $this->load->model('registroModel');
    $this->load->model('wsComunicationModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');
    $this->load->helper('form');
    $this->load->library('session');

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

  //funcion para registrar usuarios
  public function registrar() {
    $this->load->helper('form');
    $this->load->library('form_validation');

    $this->form_validation->set_message('required', '{field} requerido.');
    $this->form_validation->set_message('is_unique', 'Este {field} ya está registrado.');

    $this->form_validation->set_rules('user', 'Usuario', 'required|is_unique[users.user]');
    $this->form_validation->set_rules('pass', 'Password', 'required');
    $this->form_validation->set_rules('telefono', 'Teléfono', 'required|is_unique[users.telefono]');

    if ($this->form_validation->run() === FALSE) {
      $this->all();
    } else {
      $this->usersModel->setUser();
      $this->all();
    }
  }

  public function peticionAlta() {
    $data = $this->cobrar();
    $cobroResult = $data['statusCode'];
    if ($cobroResult == 'SUCCESS'){

      $this->usersModel->alta();
      $this->registroModel->registrarAlta();

      $data['codigo'] = 'altaOk';
      $this->wsComunicationModel->sendSms($data);

    } elseif ($cobroResult == 'NO_FUNDS') {

      $data['codigo'] = 'noAlta';
      $this->wsComunicationModel->sendSms($data);

    }
    $this->all();
  }

  public function peticionCobro() {
    $data = $this->cobrar();
    $cobroResult = $data['statusCode'];
    if ($cobroResult == 'SUCCESS'){

      $data['codigo'] = 'cobroOk';
      $this->wsComunicationModel->sendSms($data);
      
      $this->all();

    } elseif ($cobroResult == 'NO_FUNDS') {

      $data['codigo'] = 'noCobro';
      $this->wsComunicationModel->sendSms($data);
      $this->baja($data['msisdn']);
    }
  }

  public function baja($telefono) {
    $this->usersModel->baja($telefono);

    $this->registroModel->registrarBaja();

    $this->all();
  }

  public function cobrar(){
    $data = $this->wsComunicationModel->getToken();
    $data = $this->wsComunicationModel->peticionCobro($data);
    if ($data['statusCode'] == 'SUCCESS'){
      $this->registroModel->registrarCobro();
      $this->usersModel->cobrado();
    }
    return $data;
  }

  public function cobrarSuscritos(){
    $this->usersModel->cobrarSuscritos();

    //$this->registroModel->registrarCobro();

    $this->all();
  }
}
