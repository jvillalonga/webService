<?php

class webServiceComModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  public function setRequest($datos){

    // $user = $this->input->post('user');
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

  public function setResponse(){

    // $user = $this->input->post('user');
    $data = array(
      'Tipo' => $tipo,
      'txId' => $txId,
      'statusCode' => $statusCode,
      'statusMessage' => $statusMessage,
      'token' => $token,
      'fecha' => standard_date('DATE_W3C', now())
    );
    return $this->db->insert('wsresponse', $data);
  }

  //obtiene registros
  public function getResponse() {
    $query = $this->db->get('WSResponse');
    return $query->result_array();
  }

  public function getTransaction(){
    $this->db->select_max('transaction');
    $query = $this->db->get('wsrequest');
    return $query->result_array()[0]['transaction']+1;
  }

  //peticion de token al WS
  public function getToken(){
    $tran = 2; //$this->getTransaction();

    $data['tipo'] = 'ObtencionToken';
    $data['transaction'] = $tran;
    $data['msisdn'] = NULL;
    $data['shortcode'] = NULL;
    $data['text'] = NULL;
    $data['amount'] = NULL;
    $data['token'] = NULL;

    //$this->setRequest($data);
    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
    <transaction>'.$tran.'</transaction>
    </request>';

    $username = 'jvillalonga';
    $password = 'KJP5uwgc';

    $URL = "http://52.30.94.95/token";

    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$req");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }

  //peticion de cobro al WS
  public function getCobro(){
  $tran = $this->getTransaction();

  $data['tipo'] = 'ObtencionToken';
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

    $username = 'jvillalonga';
    $password = 'KJP5uwgc';

    $URL = "http://52.30.94.95/bill";

    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$req");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }

  //envio de sms
  public function sendSms(){$tran = $this->getTransaction();

  $data['tipo'] = 'ObtencionToken';
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

    $username = 'jvillalonga';
    $password = 'KJP5uwgc';

    $URL = "http://52.30.94.95/send_sms";

    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$req");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }
}
