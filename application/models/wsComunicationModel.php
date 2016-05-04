<?php

class wsComunicationModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();
    $this->load->library('ws');
    $this->load->library('textsms');

  }

  //registra Request y Response del WS
  public function setWsComunication($datos){
    $data = array(
      'transaction' => $datos['transaction'],
      'msisdn' => $datos['msisdn'],
      'Tipo' => $datos['tipo'],
      'shortcode' => $datos['shortcode'],
      'text' => $datos['text'],
      'amount' => $datos['amount'],
      'token' => $datos['token'],
      'txId' => $datos['txId'],
      'statusCode' => $datos['statusCode'],
      'statusMessage' => $datos['statusMessage'],
      'fecha' => standard_date('DATE_W3C', now())
    );
    return $this->db->insert('wscomunication', $data);
  }

  //obtiene registros wscomunication
  public function getWsComunication() {
    $query = $this->db->get('wscomunication');
    return $query->result_array();
  }

  //obtiene el código para la nueva transacción
  public function getTransac(){
    $this->db->select_max('id');
    $query = $this->db->get('wscomunication');
    $id = $query->result_array()[0]['id']+1;

    return 'h'.$tran = base_convert( $id, 10, 36 );
  }

  //peticion de token al WS
  public function getToken($data){
    $tran = $this->getTransac();
    //xml peticion token
    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
      <transaction>'.$tran.'</transaction>
    </request>';

    $url = "http://52.30.94.95/token";

    //return
    $responseToken = $this->ws->requestWS($url, $req);

    $xml = simplexml_load_string($responseToken) or die("Error: Cannot create object");

    $data['transaction'] = $tran;
    $data['tipo'] = 'ObtencionToken';
    $data['text'] = NULL;
    $data['txId'] = $xml->txId;
    $data['statusCode'] = $xml->statusCode;
    $data['statusMessage'] = $xml->statusMessage;
    $data['token'] = $xml->token;

    $this->setWsComunication($data);

    $data = $this->switchResponse($data);

    return $data;
  }

  //peticion de cobro al WS
  public function peticionCobro($data){
    $tran = $this->getTransac();

    $data['tipo'] = 'PeticionCobro';
    $data['transaction'] = $tran;

    //xml peticion cobro
    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
      <transaction>'.$tran.'</transaction>
      <msisdn>'.$data['msisdn'].'</msisdn>
      <amount>'.$data['amount'].'</amount>
      <token>'.$data['token'].'</token>
    </request>';

    $url = "http://52.30.94.95/bill";

    $responseBill = $this->ws->requestWS($url, $req);

    $xml = simplexml_load_string($responseBill) or die("Error: Cannot create object");

    $data['txId'] = $xml->txId;
    $data['statusCode'] = $xml->statusCode;
    $data['statusMessage'] = $xml->statusMessage;

    $this->setWsComunication($data);

    $data = $this->switchResponse($data);

    return $data;
  }

  //envio de sms
  public function sendSms($data){
    $data['text'] = $this->textsms->switchMensaje($data);

    $tran = $this->getTransac();
    //xml envio sms
    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
      <shortcode>'.$data['shortcode'].'</shortcode>
      <text>'.$data['text'].'</text>
      <msisdn>'.$data['msisdn'].'</msisdn>
      <transaction>'.$tran.'</transaction>
    </request>';

    $url = "http://52.30.94.95/send_sms";

    $responseSms = $this->ws->requestWS($url, $req);

    $xml = simplexml_load_string($responseSms) or die("Error: Cannot create object");

    $data['transaction'] = $tran;
    $data['amount'] = $this->input->post('cantidad');
    $data['tipo'] = 'EnvioSms';

    $data['txId'] = $xml->txId;
    $data['statusCode'] = $xml->statusCode;
    $data['statusMessage'] = $xml->statusMessage;
    $data['token'] = $xml->token;

    $this->setWsComunication($data);

    $data = $this->switchResponse($data);

    $this->registroModel->registrarSms($data);

    return $data;
  }

  //switchCase para statusCode de Response del WS
  public function switchResponse ($data) {

    $statusCode = $data['statusCode'];
    $statusMessage = $data['statusMessage'];

    switch ($statusCode) {
      case "SUCCESS":
        if ($data['tipo'] == 'PeticionCobro'){
          return $data;
        } elseif ($data['tipo'] == 'EnvioSms'){
          return $data;
        }
        break;
      case "BAD_REQUEST_TYPE":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        break;
      case "NO_REQUEST":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        break;
      case "SYSTEM_ERROR":
        if ($data['tipo'] == 'ObtencionToken'){
          $data = $this->getToken($data);
        } elseif ($data['tipo'] == 'PeticionCobro'){
          $data = $this->peticionCobro($data);
        } elseif ($data['tipo'] == 'EnvioSms'){
          $data = $this->sendSms($data);
        }
        return $data;
        break;
      case "INVALID_XML":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        break;
      case "MISSING_PROPERTY":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        break;
      case "MISSING_CREDENTIALS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        break;
      case "INVALID_CREDENTIALS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        break;
      case "TOKEN_SUCCESS":
        return $data;
        break;
      case "TOKEN_ALREADY_USED":
        //pedir nuevo token
        return $this->getToken($data);
        break;
      case "INVALID_TOKEN":
        //pedir nuevo token
        return $this->getToken($data);
        break;
      case "NO_FUNDS":
        return $data;
        break;
      case "CHARGING_ERROR":
        $data = $this->getToken($data);
        $data = $this->peticionCobro($data);
        return $data;
        break;
      case "DUPLICATED_TR":
        //nueva transaccion
        $data = $this->getToken($data);
        return $data;
        break;
      default:
        echo '<script language="javascript">alert("switchResponse Error.");</script>';
    }
  }
  //switchCase para statusCode de Response del WS mostrando alers.
    // public function switchResponse ($data) {
    //
    //   $statusCode = $data['statusCode'];
    //   $statusMessage = $data['statusMessage'];
    //
    //   switch ($statusCode) {
    //     case "SUCCESS":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       if ($data['tipo'] == 'PeticionCobro'){
    //         return $data;
    //       } elseif ($data['tipo'] == 'EnvioSms'){
    //         return $data;
    //       }
    //       break;
    //     case "BAD_REQUEST_TYPE":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       break;
    //     case "NO_REQUEST":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       break;
    //     case "SYSTEM_ERROR":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       if ($data['tipo'] == 'ObtencionToken'){
    //         $data = $this->getToken($data);
    //       } elseif ($data['tipo'] == 'PeticionCobro'){
    //         $data = $this->peticionCobro($data);
    //       } elseif ($data['tipo'] == 'EnvioSms'){
    //         $data = $this->sendSms($data);
    //       }
    //       return $data;
    //       break;
    //     case "INVALID_XML":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       break;
    //     case "MISSING_PROPERTY":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       break;
    //     case "MISSING_CREDENTIALS":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       break;
    //     case "INVALID_CREDENTIALS":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       break;
    //     case "TOKEN_SUCCESS":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       return $data;
    //       break;
    //     case "TOKEN_ALREADY_USED":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       //pedir nuevo token
    //       return $this->getToken($data);
    //       break;
    //     case "INVALID_TOKEN":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       //pedir nuevo token
    //       return $this->getToken($data);
    //       break;
    //     case "NO_FUNDS":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       return $data;
    //       break;
    //     case "CHARGING_ERROR":
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       $data = $this->getToken($data);
    //       $data = $this->peticionCobro($data);
    //       return $data;
    //       break;
    //     case "DUPLICATED_TR":
    //       //nueva transaccion
    //       echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
    //       $data = $this->getToken($data);
    //       return $data;
    //       break;
    //     default:
    //       echo '<script language="javascript">alert("switchResponse Error.");</script>';
    //   }
    // }

}
