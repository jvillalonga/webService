<?php

class WebServiceComunication extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('usersModel');
    $this->load->model('registroModel');
    $this->load->model('wsComunicationModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');

  }

  //carga views de los registros de comunicacion con el WS
  public function wsComunication() {

    $data['regRequest'] = $this->wsComunicationModel->getWsComunication();
    $data['title'] = 'WS Registros';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/menu');
    $this->load->view('registros/wsComunication', $data);
    $this->load->view('templates/footer');
  }

  //
  public function getToken() {
    //cambiar per inputs al model
    $data['msisdn'] = $this->input->post('tel');
    $data['shortcode'] = '654';
    $data['amount'] = 5;

    $responseToken = $this->webServiceComModel->getToken($data);

    $this->webServiceComModel->setWsComunication($responseToken);

    $this->webServiceComModel->switchResponse($responseToken);
  }

  //peticion de cobro
  public function peticionCobro($data) {

    $responseBill = $this->webServiceComModel->peticionCobro($data);
    $this->webServiceComModel->setWsComunication($responseBill);

    $this->webServiceComModel->switchResponse($responseBill);
  }

  public function envioSms($data) {

    $responseSms = $this->webServiceComModel->sendSms($data);
    $this->webServiceComModel->setWsComunication($responseSms);

    $this->webServiceComModel->switchResponse($responseSms);

      //registrar sms
  }

}
