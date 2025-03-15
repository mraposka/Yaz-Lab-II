<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'web';
$route['(:any)'] = 'web/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
