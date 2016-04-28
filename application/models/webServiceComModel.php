<?php

class webServiceComModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

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

    return $tran = base_convert( $id, 10, 36 );
  }

  //peticion de token al WS
  public function getToken($data){
    $tran = $this->getTransac();
    //datos para request
    $data['transaction'] = $tran;
    $data['tipo'] = 'ObtencionToken';
    $data['text'] = NULL;
    $data['token'] = NULL;

    //xml peticion token
    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
      <transaction>'.$tran.'</transaction>
    </request>';

    $url = "http://52.30.94.95/token";

    //return
    $responseToken = $this->requestWS($url, $req);

    $xml = simplexml_load_string($responseToken) or die("Error: Cannot create object");

    $data['txId'] = $xml->txId;
    $data['statusCode'] = $xml->statusCode;
    $data['statusMessage'] = $xml->statusMessage;
    $data['token'] = $xml->token;

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

    $responseBill = $this->requestWS($url, $req);

    $xml = simplexml_load_string($responseBill) or die("Error: Cannot create object");

    $data['txId'] = $xml->txId;
    $data['statusCode'] = $xml->statusCode;
    $data['statusMessage'] = $xml->statusMessage;

    return $data;
  }

  //envio de sms
  public function sendSms($data){
    $tran = $this->getTransac();

    $data['tipo'] = 'EnvioSms';
    $data['transaction'] = $tran;

    //xml envio sms
    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
      <shortcode>000</shortcode>
      <text>'.$data['text'].'</text>
      <msisdn>'.$data['msisdn'].'</msisdn>
      <transaction>'.$tran.'</transaction>
    </request>';

    $url = "http://52.30.94.95/send_sms";

    $responseSms = $this->requestWS($url, $req);

    $xml = simplexml_load_string($responseSms) or die("Error: Cannot create object");

    $data['txId'] = $xml->txId;
    $data['statusCode'] = $xml->statusCode;
    $data['statusMessage'] = $xml->statusMessage;

    return $data;
  }

  //conexion Request ws
  public function requestWS ($url, $xml) {

    $username = 'jvillalonga';
    $password = 'KJP5uwgc';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;

  }

  //switchCase para statusCode de Response del WS
  public function switchResponse ($data) {

    $statusCode = $data['statusCode'];
    $statusMessage = $data['statusMessage'];

    switch ($statusCode) {
      case "SUCCESS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        if ($data['tipo'] == 'PeticionCobro'){
          $data['text'] = 'Se ha realizado un cobro de ' . $data['amount']. '$ por la suscripció.';
          $this->envioSms($data);
        } elseif ($data['tipo'] == 'EnvioSms'){
          $this->registroModel->registrarSms($data);
          $this->wsComunication();
        }
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
        if ($data['tipo'] == 'PeticionCobro'){
          $this->peticionCobro($data);
        } elseif ($data['tipo'] == 'EnvioSms'){
          $this->envioSms($data);
        } elseif ($data['tipo'] == 'ObtencionToken'){
          $this->getToken($data);
        }
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
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        //usar token para peticion cobro
        $this->peticionCobro($data);
        break;
      case "TOKEN_ALREADY_USED":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        //pedir nuevo token
        $this->getToken();
        break;
      case "INVALID_TOKEN":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        //pedir nuevo token
        $this->getToken();
        break;
      case "NO_FUNDS":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $data['text'] = 'No tiene fondos para la suscripción.';
        $this->envioSms($data);
        // $this->input->post('telefono') = $data['telefono'];
        // $this->input->post('estado') = 'baja';
        $this->usersModel->baja();
        $this->registroModel->registrarBaja();
        break;
      case "CHARGING_ERROR":
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->getToken($data);
        break;
      case "DUPLICATED_TR":
        //nueva transaccion
        echo '<script language="javascript">alert("switchResponse: '.$statusCode.': '.$statusMessage.'.");</script>';
        $this->getToken();
        break;
      default:
        echo '<script language="javascript">alert("switchResponse Error.");</script>';
        $this->wsComunication();
    }
  }
}
