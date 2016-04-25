<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['users/all'] = 'users/all';
$route['users/registrar'] = 'users/registrar';
$route['registros/altasBajas'] = 'registros/regAltaBaja';
$route['registros/regCobros'] = 'registros/regCobros';

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
