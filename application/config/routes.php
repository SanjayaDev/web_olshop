<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'welcome/view_404_page';
$route['translate_uri_dashes'] = FALSE;

$route["login"] = "Auth/view_login_page";
$route["validation_login"] = "Auth/process_validation_login";

$route["dashboard"] = "Admin/view_dashboard_page";
$route["logout"] = "Auth/process_logout";
$route["forbidden"] = "Auth/view_forbidden";

// Admin Management
$route["admin_management"] = "Admin/view_admin_management";
$route["get_admin"] = "Admin/get_admin_listed";
$route["add_admin"] = "Admin/validate_admin_add";
$route["admin_detail"] = "Admin/view_admin_detail";
$route["edit_admin"] = "Admin/process_admin_edit";