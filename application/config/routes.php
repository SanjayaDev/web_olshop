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

// Bank Account
$route["bank_account"] = "Bank/view_bank_account_management";
$route["add_bank"] = "Bank/view_bank_account_management";
$route["get_bank_detail"] = "Bank/ajax_get_bank_detail";
$route["edit_bank"] = "Bank/validate_bank_edit";
$route["delete_bank"] = "Bank/process_bank_delete";

// Rajaongkir
$route["get_district"] = "Rajaongkir/get_district";

// Customer Management
$route["customer"] = "Customer/view_customer_management";
$route["get_customer"] = "Customer/get_customer_listed";
$route["add_customer"] = "Customer/view_add_customer";
$route["customer_detail/(:any)"] = "Customer/view_customer_detail/$1";
$route["customer_edit/(:any)"] = "Customer/view_customer_edit/$1";