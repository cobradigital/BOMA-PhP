<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'C_user/check_data';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'C_dashboard';
// User
$route['login-action'] = 'Auth/authenticationuser';
$route['logout-action'] = 'Auth/logout_user';
$route['login'] = 'C_user';
$route['check-data'] = 'C_user/check_data';
$route['detail-data/(:any)'] = 'C_user/check_detail';
$route['user'] = 'C_datauser';
$route['user/add'] = 'C_datauser/view_add_user';
$route['user/add/(:any)'] = 'C_datauser/view_add_user';

// Proses
$route['active'] = 'C_dataactive';
$route['done'] = 'C_datadone';
$route['detail'] = 'C_datadetail';
$route['detail/(:any)'] = 'C_datadetail';
$route['print/(:any)'] = 'C_user/check_detail';
$route['comment_reply'] = 'CommentAct/reply';
$route['proses_olahan_data'] = 'MasterAct/save_olahan_data';
$route['proses_kepdis'] = 'MasterAct/save_keputusan_dinas';
$route['inquiry_data'] = 'MasterAct/get_permohonan_selesai';
$route['save_izin_req'] = 'MasterAct/save_izin_req';
$route['save_izin_simpulan'] = 'MasterAct/save_kesimpulan_bidang';
$route['delete_izin_req'] = 'MasterAct/delete_izin_req';
$route['add_user'] = 'MasterAct/add_user';
$route['get_jabatan'] = 'MasterAct/get_jabatan';
$route['delete_user'] = 'MasterAct/delete_user';
$route['kesimpulan_bidang/(:any)'] = 'MasterAct/actKesimpulanBidang';
$route['save_intensitas'] = 'MasterAct/actIntensitas';

// GetData
$route['inquery_request'] = 'NoAuth/getForJakevo';
$route['testreq'] = 'NoAuth/testGet';
$route['testmail'] = 'ServiceGis/asdsa';
$route['recaptcha'] = 'C_user/c_captcha';