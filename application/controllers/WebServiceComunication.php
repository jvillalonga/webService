<?php

class WebServiceComunication extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('usersModel');
    $this->load->model('registroModel');
    $this->load->model('webServiceComModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');
    $this->load->helper('form');
    $this->load->library('session');

  }
  public function wsComunication()
  {
    $data['regRequest'] = $this->webServiceComModel->getRequest();
    $data['regResponse'] = $this->webServiceComModel->getResponse();
    $data['title'] = 'WS Registros';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/menu');
    $this->load->view('registros/wsRequest', $data);
    $this->load->view('registros/wsResponse', $data);
    $this->load->view('templates/footer');
  }

  public function getToken(){
    $this->webServiceComModel->getToken();
  }

}
