<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['about'] = 'welcome/about';


$route['home'] = 'Pages/index';
$route['about'] = 'Pages/about';
$route['blog/(:num)'] = 'Pages/blog/$1';


$route['student'] = 'Student/studentName';
$route['student/(:num)'] = 'Student/studentInfoByID/$1';

// -------------------------------------------------

$route['employee'] = 'Frontend/Employee/index';
$route['employee/create'] = 'Frontend/Employee/create';
$route['employee/store'] = 'Frontend/Employee/store';
$route['employee/edit/(:num)'] = 'Frontend/Employee/edit/$1';
$route['employee/update/(:num)'] = 'Frontend/Employee/update/$1';
$route['employee/delete/(:num)'] = 'Frontend/Employee/delete/$1';
$route['employee/deleteEmployee/(:num)'] = 'Frontend/Employee/deleteEmployee/$1';