<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('usersModel');
		$this->load->helper('url_helper');
		$this->load->helper('date');
		$this->load->helper('form');
		$this->load->library('session');

	}
	public function index()
	{
		$data['title'] = 'Inicio';

		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('templates/footer');
	}
}
