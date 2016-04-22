<?php

class transaccionModel extends CI_Model {

  public function __construct() {
    $this->load->helper('date');
    $this->load->database();

  }
}
