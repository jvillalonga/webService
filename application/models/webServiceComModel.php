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

  //Registra el Request al WS
  public function setRequest($datos) {

    $data = array(
      'transaction' => $datos['transaction'],
      'msisdn' => $datos['msisdn'],
      'Tipo' => $datos['tipo'],
      'shortcode' => $datos['shortcode'],
      'text' => $datos['text'],
      'amount' => $datos['amount'],
      'token' => $datos['token'],
      'fecha' => standard_date('DATE_W3C', now())
    );
    return $this->db->insert('wsrequest', $data);
  }
  //obtiene registros Request
  public function getRequest() {
    $query = $this->db->get('WSRequest');
    return $query->result_array();
  }

  //Registra Response del WS
  public function setResponse($datos){

    $data = array(
      'Tipo' => $datos['tipo'],
      'txId' => $datos['txId'],
      'statusCode' => $datos['statusCode'],
      'statusMessage' => $datos['statusMessage'],
      'token' => $datos['token'],
      'fecha' => standard_date('DATE_W3C', now())
    );
    return $this->db->insert('wsresponse', $data);
  }

  //obtiene registros Response
  public function getResponse() {
    $query = $this->db->get('WSResponse');
    return $query->result_array();
  }

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

    //xml
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

    //xml
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

    //xml
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
}
