<?php

class Users extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('transaccionModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');
    $this->load->helper('form');

  }
}
