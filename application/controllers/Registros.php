<?php

class Registros extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('registroModel');
    $this->load->helper('url_helper');
    $this->load->helper('date');
    $this->load->helper('form');

  }

  public function regAltaBaja()
  {
    $data['regs'] = $this->registroModel->getAltaBaja();
    $data['title'] = 'Altas y Bajas';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/menu');
    $this->load->view('registros/regAltaBaja', $data);
    $this->load->view('templates/footer');
  }

  public function cobros()
  {
    $data['regs'] = $this->registroModel->getCobros();
    $data['title'] = 'Cobros';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/menu');
    $this->load->view('registros/regCobros', $data);
    $this->load->view('templates/footer');
  }

}
