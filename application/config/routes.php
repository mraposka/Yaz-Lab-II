<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'admin';
$route['login'] = 'admin/login';
$route['login_user'] = 'admin/login_user';
$route['signup'] = 'admin/signup';
$route['signup_user'] = 'admin/signup_user';
$route['admin/(:any)'] = 'admin/$1';
$route['jury/(:any)'] = 'jury/$1';
$route['user/(:any)'] = 'user/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
