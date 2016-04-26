<?php

class WebServiceComunication extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('registroModel');
    $this->load->model('webServiceComModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');

  }
  public function wsComunication() {

    $data['regRequest'] = $this->webServiceComModel->getRequest();
    $data['regResponse'] = $this->webServiceComModel->getResponse();
    $data['title'] = 'WS Registros';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/menu');
    $this->load->view('registros/wsRequest', $data);
    $this->load->view('registros/wsResponse', $data);
    $this->load->view('templates/footer');
  }

  public function getToken() {
    $responseToken = $this->webServiceComModel->getToken();

    echo $responseToken;
    $this->wsComunication();
  }

}
