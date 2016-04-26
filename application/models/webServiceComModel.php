<?php

class webServiceComModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }
//Registra el Request al WS
  public function setRequest($datos){

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
  //obtiene registros
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

  //obtiene registros
  public function getResponse() {
    $query = $this->db->get('WSResponse');
    return $query->result_array();
  }

  public function getId(){
    $this->db->select_max('id');
    $query = $this->db->get('wsrequest');
    return $query->result_array()[0]['id']+1;
  }

  //peticion de token al WS
  public function getToken(){
    $id = $this->getId();

    $tran = base_convert( $id, 10, 36 );

    $data['id'] = $id;
    $data['tipo'] = 'ObtencionToken';
    $data['transaction'] = $tran;
    $data['msisdn'] = NULL;
    $data['shortcode'] = NULL;
    $data['text'] = NULL;
    $data['amount'] = NULL;
    $data['token'] = NULL;

    $this->setRequest($data);

    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
    <transaction>'.$tran.'</transaction>
    </request>';

    $url = "http://52.30.94.95/token";

    return $output = $this->requestWS($url, $req);
  }

  //peticion de cobro al WS
  public function peticionCobro ($token){
    $id = $this->getId();
    $tran = base_convert( $id, 10, 36 );
    //cambiar por variable/input
    $msisdn = '666666666';
    //cambiar por variable/input
    $amount = 1;

    $data['id'] = $id;
    $data['tipo'] = 'PeticionCobro';
    $data['transaction'] = $tran;
    $data['msisdn'] = $msisdn;
    $data['shortcode'] = NULL;
    $data['text'] = NULL;
    $data['amount'] = $amount;
    $data['token'] = $token;

    $this->setRequest($data);

    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
    <transaction>'.$tran.'</transaction>
    <msisdn>'.$msisdn.'</msisdn>
    <amount>'.$amount.'</amount>
    <token>'.$token.'</token>
    </request>';

    $url = "http://52.30.94.95/bill";

    return $output = $this->requestWS($url, $req);
  }

  //envio de sms
  public function sendSms(){$tran = $this->getTransaction();

  $data['tipo'] = 'EnvioSms';
  $data['transaction'] = $tran;
  $data['msisdn'] = $msisdn;
  $data['shortcode'] = $shortcode;
  $data['text'] = $text;
  $data['amount'] = NULL;
  $data['token'] = NULL;

  $this->setRequest($data);

    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
      <shortcode>'.$shortcode.'</shortcode>
      <text>'.$text.'</text>
      <msisdn>'.$msisdn.'</msisdn>
      <transaction>'.$tran.'</transaction>
    </request>';

    $url = "http://52.30.94.95/send_sms";

    return $output = $this->requestWS($url, $req);
  }

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
