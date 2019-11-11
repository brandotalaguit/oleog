<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "site/user/login";
$route['404_override'] = '';

$route['gradebook/(:num)/gradesheet']	= 	"gradebook/gradesheet/$1";
$route['gradebook/(:num)/gradesheetleclab']	= 	"gradebook/gradesheetleclab/$1";
$route['gradebook/(:num)/confirm_grades']	= 	"gradebook/confirm_grades/$1";
$route['gradebook/(:num)/confirm_graduate_grades']	= 	"gradebook/confirm_graduate_grades/$1";
$route['gradebook/(:num)/print_gradesheet']	= 	"gradebook/print_gradesheet/$1";
$route['gradebook/(:num)/finish_later']	= 	"gradebook/finish_later/$1";

$route['hsu/(:num)/gradesheet']	= 	"hsu/gradesheet/$1";
$route['hsu/(:num)/gradesheetleclab']	= 	"hsu/gradesheetleclab/$1";
$route['hsu/(:num)/confirm_grades']	= 	"hsu/confirm_grades/$1";
$route['hsu/(:num)/confirm_graduate_grades']	= 	"hsu/confirm_graduate_grades/$1";
$route['hsu/(:num)/print_gradesheet']	= 	"hsu/print_gradesheet/$1";
$route['hsu/(:num)/finish_later']	= 	"hsu/finish_later/$1";

$route['site/stat/(:num)/late_encode']	= 	"site/stat/late_encode/$1";
$route['site/stat/(:num)/on_time_encode']	= 	"site/stat/on_time_encode/$1";
$route['site/stat/(:num)/not_encoded']	= 	"site/stat/not_encoded/$1";

$route['site/hsu_stat/(:num)/late_encode']	= 	"site/hsu_stat/late_encode/$1";
$route['site/hsu_stat/(:num)/on_time_encode']	= 	"site/hsu_stat/on_time_encode/$1";
$route['site/hsu_stat/(:num)/not_encoded']	= 	"site/hsu_stat/not_encoded/$1";


/* End of file routes.php */
/* Location: ./application/config/routes.php */