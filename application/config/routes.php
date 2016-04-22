<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['users/all'] = 'users/all';
$route['users'] = 'users/all';
$route['users/registrar'] = 'users/registrar';
$route['registros/regAltaBaja'] = 'registros/regAltaBaja';

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
