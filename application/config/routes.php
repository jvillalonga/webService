<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['users/alta'] = 'users/alta';
$route['users/baja'] = 'users/baja';
$route['users/all'] = 'users/all';
$route['users'] = 'users/all';
$route['users/registrar'] = 'users/registrar';

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
