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

    echo '<p>'.$responseToken.'</p>';
    
    $xml=simplexml_load_string($responseToken) or die("Error: Cannot create object");
    $data['statusCode'] = $xml->statusCode;
    $data['statusMessage'] = $xml->statusMessage;
    $data['txId'] = $xml->txId;
    $data['token'] = $xml->token;
    $data['tipo'] = 'ObtencionToken';

    $this->webServiceComModel->setResponse($data);

    if ($data['statusCode'] == 'TOKEN_SUCCESS') {

      echo 'ok';
      $responseBill = $this->webServiceComModel->peticionCobro($data['token']);
      echo '<p>'.$responseBill.'</p>';
      $xml=simplexml_load_string($responseBill) or die("Error: Cannot create object");
      $data['statusCode'] = $xml->statusCode;
      $data['statusMessage'] = $xml->statusMessage;
      $data['txId'] = $xml->txId;
      $data['token'] = NULL;
      $data['tipo'] = 'PeticionCobro';

      $this->webServiceComModel->setResponse($data);

    } else {
      $this->getToken();
      echo 'no ok';
    }
    $this->wsComunication();
  }

  // public function peticionCobro() {
  //   $responseToken = $this->webServiceComModel->getToken();
  //
  //   $data['statusCode'] = $xml->statusCode;
  //   $data['statusMessage'] = $xml->statusMessage;
  //   $data['txId'] = $xml->txId;
  //   $data['token'] = $xml->token;
  //   $data['tipo'] = 'ObtencionToken';
  //
  //   $this->webServiceComModel->setResponse($data);
  //   $this->wsComunication();
  //}

}
