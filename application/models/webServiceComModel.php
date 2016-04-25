<?php

class webServiceComModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }

  public function setRequest(){

    // $user = $this->input->post('user');
    $data = array(
      'transaction' => $transaction,
      'msisdn' => $msisdn,
      'shortcode' => $shortcode,
      'text' => $text,
      'amount' => $amount,
      'token' => $token,
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


  public function getToken(){
    $req = '<?xml version="1.0" encoding="UTF-8"?>
    <request>
    <transaction>21a</transaction>
    </request>';

    $soap_do = curl_init();
    //Indicamos a donde deseamos enviar nuestro post
    curl_setopt($soap_do, CURLOPT_URL,"http://52.30.94.95/token" );
    //Indicamos lo que queremos enviar en nuestro post, en este caso un xml
    curl_setopt($soap_do, CURLOPT_POSTFIELDS,$req);
    //Configuramos los headers necesarios. En este caso es importante la definición de la longitud de los datos a enviar
    curl_setopt($soap_do, CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded', 'Content-Length: '.strlen($req),'http://52.30.94.95/token' ));
      //Añadimos una opción más para poder almacenar la respuesta en una variable
      curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, 1);
      //Ejecutamos el curl y almacenamos la respuesta en una variable
      $respuesta=curl_exec($soap_do);
      //Cerramos nuesta sesión
      curl_close($soap_do);
      echo '<script language="javascript">alert("'.$respuesta.'");</script>';
      echo $respuesta->asXML();
    }
  }
