<?php

class WebServiceComunication extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('registroModel');
    $this->load->model('webServiceComModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');

  }

  //carga views de los registros de comunicacion con el WS
  public function wsComunication() {

    $data['regRequest'] = $this->webServiceComModel->getWsComunication();
    // $data['regRequest'] = $this->webServiceComModel->getRequest();
    // $data['regResponse'] = $this->webServiceComModel->getResponse();
    $data['title'] = 'WS Registros';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/menu');
    $this->load->view('registros/wsComunication', $data);
    // $this->load->view('registros/wsRequest', $data);
    // $this->load->view('registros/wsResponse', $data);
    $this->load->view('templates/footer');
  }

  //
  public function getToken() {
    //cambiar per inputs al model
    $data['msisdn'] = '654321987';
    $data['shortcode'] = '654';
    $data['amount'] = 5;

    $responseToken = $this->webServiceComModel->getToken($data);

    $this->webServiceComModel->setWsComunication($responseToken);

    $this->switchResponse($responseToken);
    //
//si cobro SUCCESS/NO_FUNDS enviar SMS
    //   if ($data['statusCode'] == 'SUCCESS') {
    //     $text = 'Se ha realizado el cobro de la suscripció.';
    //     $responseSms = $this->webServiceComModel->sendSms($text);
    //
    //     $xml=simplexml_load_string($responseSms) or die("Error: Cannot create object");
    //     $data['statusCode'] = $xml->statusCode;
    //     $data['statusMessage'] = $xml->statusMessage;
    //     $data['txId'] = $xml->txId;
    //     $data['token'] = NULL;
    //     $data['tipo'] = 'EnvioSms';
    //
    //     $this->webServiceComModel->setResponse($data);
    //     //alert response cobro
    //     echo '<script language="javascript">alert("petCobro response: '.$responseSms.'");</script>';
    //
    //   } elseif ($data['statusCode'] == 'NO_FUNDS') {
    //     $text = 'No tiene fondos suficientes. Serà dado de baja.';
    //     $responseSms = $this->webServiceComModel->sendSms($text);
    //
    //     $xml=simplexml_load_string($responseSms) or die("Error: Cannot create object");
    //     $data['statusCode'] = $xml->statusCode;
    //     $data['statusMessage'] = $xml->statusMessage;
    //     $data['txId'] = $xml->txId;
    //     $data['token'] = NULL;
    //     $data['tipo'] = 'EnvioSms';
    //
    //     $this->webServiceComModel->setResponse($data);
    //     //alert response cobro
    //     echo '<script language="javascript">alert("petCobro response: '.$responseSms.'");</script>';
    //
    //   } else {
    //     //alert no sms sent
    //     echo '<script language="javascript">alert("No SMS sent.");</script>';
    //  }
    //
    // }
  }

  //peticion de cobro
  public function peticionCobro($data) {

    $responseBill = $this->webServiceComModel->peticionCobro($data);
    $this->webServiceComModel->setWsComunication($responseBill);

    $this->switchResponse($responseBill);

  }

  //switchCase para statusCode de Response del WS
  public function switchResponse ($data) {

    $statusCode = $data['statusCode'];
    $statusMessage = $data['statusMessage'];

    switch ($statusCode) {
      case "SUCCESS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        if ($data['Tipo'] == 'PeticionCobro'){
          $data['text'] = 'Se ha realizado un cobro de ' . $data['amount']. ' por la suscripció.';
          $this->sendSms($data);
        } elseif ($data['Tipo'] == 'EnvioSms'){

        }
        $this->wsComunication();
        //$text = 'Se ha realizado el cobro de la suscripció.';
        //$responseSms = $this->webServiceComModel->sendSms($text);
        break;
      case "BAD_REQUEST_TYPE":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "NO_REQUEST":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "SYSTEM_ERROR":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "INVALID_XML":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "MISSING_PROPERTY":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "MISSING_CREDENTIALS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "INVALID_CREDENTIALS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "TOKEN_SUCCESS":
        //usar token para peticion cobro
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.'.");</script>';
        $this->peticionCobro($data);
        break;
      case "TOKEN_ALREADY_USED":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.'.");</script>';
        //pedir nuevo token
        $this->getToken();
        break;
      case "INVALID_TOKEN":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.'.");</script>';
        //pedir nuevo token
        $this->getToken();
        break;
      case "NO_FUNDS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.'.");</script>';
        //enviar sms "Sin fondos", dar baja.
          //$text = 'No tiene fondos suficientes. Serà dado de baja.';
          //$responseSms = $this->webServiceComModel->sendSms($text);
        break;
      case "CHARGING_ERROR":
        //
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->wsComunication();
        break;
      case "DUPLICATED_TR":
        //nueva transaccion
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->getToken();
        break;
        break;
      default:
        echo '<script language="javascript">alert("switchResponse Error.");</script>';
        $this->wsComunication();
    }
  }

}
