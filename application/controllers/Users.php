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


  public function all() {
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

      $data['user'] = $this->input->post('user');
      $data['msisdn'] = $this->input->post('tel');
      $data['shortcode'] = substr($this->input->post('tel'),0,3);
      $data['amount'] = $this->input->post('cantidad');

    $data = $this->cobrar($data);
    $cobroResult = $data['statusCode'];
    if ($cobroResult == 'SUCCESS'){

        echo '<script language="javascript">alert("Alta con éxito");</script>';
      $this->usersModel->alta();
      $this->registroModel->registrarAlta($data);

      $data['codigo'] = 'altaOk';
      $this->wsComunicationModel->sendSms($data);

    } elseif ($cobroResult == 'NO_FUNDS') {
      echo '<script language="javascript">alert("Usuario sin fondos. No será dado de alta");</script>';

      $data['codigo'] = 'noAlta';
      $this->wsComunicationModel->sendSms($data);

    }
    $this->all();
  }


  public function peticionCobro() {

      $data['user'] = $this->input->post('user');
      $data['msisdn'] = $this->input->post('tel');
      $data['shortcode'] = substr($this->input->post('tel'),0,3);
      $data['amount'] = $this->input->post('cantidad');

    $data = $this->cobrar($data);
    $cobroResult = $data['statusCode'];
    if ($cobroResult == 'SUCCESS'){
      echo '<script language="javascript">alert("Cobro realizado con éxito.");</script>';

      $data['codigo'] = 'cobroOk';
      $this->wsComunicationModel->sendSms($data);


    } elseif ($cobroResult == 'NO_FUNDS') {
      echo '<script language="javascript">alert("Cobro no realizado. Usuario sin fondos. Será dado de baja.");</script>';

      $this->baja($data);
      $data['codigo'] = 'noCobro';
      $this->wsComunicationModel->sendSms($data);
    }
    $this->all();
  }

  public function peticionBaja(){
    $data['user'] = $this->input->post('user');
    $data['msisdn'] = $this->input->post('tel');
    $data['shortcode'] = substr($this->input->post('tel'),0,3);
    $this->baja($data);

    $data['codigo'] = 'bajaOk';
    $this->wsComunicationModel->sendSms($data);

    echo '<script language="javascript">alert("Baja realizada con éxito.");</script>';
    $this->all();
  }

  //
  public function baja($data) {
    $this->usersModel->baja($data['msisdn']);

    $this->registroModel->registrarBaja($data);

  }


  public function cobrar($data){

    $data = $this->wsComunicationModel->getToken($data);
    $data = $this->wsComunicationModel->peticionCobro($data);
    if ($data['statusCode'] == 'SUCCESS'){
      $this->registroModel->registrarCobro($data);
      $this->usersModel->cobrado($data['msisdn']);
    }
    return $data;
  }

  //
  public function cobrarSuscritos(){
    $users = $this->usersModel->getSinCobrar();

    foreach ($users as $user_item):

      $data['user'] = $user_item['user'];
      $data['msisdn'] = $user_item['telefono'];
      $data['shortcode'] = substr($user_item['telefono'],0,3);
      $data['amount'] = $this->input->post('cantidad');

      $data = $this->cobrar($data);
      $cobroResult = $data['statusCode'];
      if ($cobroResult == 'SUCCESS'){

        $data['codigo'] = 'cobroOk';
        $this->wsComunicationModel->sendSms($data);

        $this->all();

      } elseif ($cobroResult == 'NO_FUNDS') {
        $this->baja($data);
        $data['codigo'] = 'noCobro';
        $this->wsComunicationModel->sendSms($data);
      }

    endforeach;

    $this->all();
  }
}
