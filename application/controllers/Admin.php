<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CR_Controller {

  public function __construct()
  {
    parent::__construct();
    $protect_login = $this->Auth->protect_login();
    if ($protect_login->success === FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$protect_login->message')</script>");
      redirect("login");
      exit;
    }
  }

  public function view_dashboard_page()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $data = [
      "title" => "Dashboard",
      "content" => "admin/v_dashboard",
      "breadcrumb" => $this->draw_breadcrumb("Dashboard", base_url("dashboard"), TRUE),
      "admin_online" => $this->core_model->get_currently_admin_online()
    ];
    $this->load->view("layout/wrapper", $data);
  }

  public function view_admin_management()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $data = [
      "title" => "Admin Management",
      "content" => "admin/v_admin",
      "list_access" => $this->core_model->get_access_listed(),
      "breadcrumb" => $this->draw_breadcrumb("Admin Management", base_url("admin_management"), TRUE),
      "list_admin_status" => $this->core_model->get_admin_status_listed(),
      "admin_login" => $this->core_model->get_admin_detail(decrypt_url($this->session->admin_id))
    ];
    $this->load->view("layout/wrapper", $data);
  }

  public function get_admin_listed()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|0"], TRUE);
    $list = $this->core_model->get_admin_listed();
    $data = [];
    $no = $this->input->post("start");
    foreach ($list as $item) {
      $no++;
      $row = [];
      $row[] = $no;
      $row[] = $item->admin_name;
      $row[] = $item->admin_email;
      $row[] = $item->admin_phone;
      $row[] = $item->access_levelName;
      $row[] = "<button class='btn btn-primary btn-sm' onclick=\"navigateTo('admin_detail?id=".encrypt_url($item->admin_id)."')\"><i class='fas fa-search mr-2'></i>Detail</button>";
      $data[] = $row;
    }
    $output = [
      "draw" => $this->input->post("draw"),
      "recordsTotal" => $this->core_model->count_admin_all(),
      "recordsFiltered" => $this->core_model->count_admin_filtered(),
      "data" => $data
    ];

    echo json_encode($output);
  }

  public function view_admin_detail()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1", "Staff|90|1", "User Demo|50|0"], FALSE);
    $admin_id = $this->input->get("id");
    $id = html_escape(decrypt_url($admin_id));
    $admin_detail = $this->core_model->get_admin_detail_relate($id);
    if ($admin_detail !== FALSE) {
      $data = [
        "title" => "Admin Detail",
        "content" => "admin/v_admin_detail",
        "breadcrumb" => $this->draw_breadcrumb("Admin Detail", base_url("admin_detail?id=$admin_id")),
        "admin_info" => $admin_detail,
        "list_activity" => $this->core_model->get_admin_activity_listed($id),
        "list_access" => $this->core_model->get_access_listed(),
        "list_admin_status" => $this->core_model->get_admin_status_listed(),
        "admin_login" => $this->core_model->get_admin_detail(decrypt_url($this->session->admin_id))
      ];
      $this->load->view("layout/wrapper", $data);
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Gagal!', 'Data tidak ditemukan!')</script>");
      redirect("dashboard");
    }
  }

  public function validate_admin_add()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], TRUE);
    $this->form_validation->set_rules('admin_name', 'Nama', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('admin_email', 'Email', 'required|valid_email|is_unique[list_admin.admin_email]');
    $this->form_validation->set_rules('admin_phone', 'Phone', 'required|numeric');
    $this->form_validation->set_rules('admin_tierId', 'Access', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('admin_statusId', 'Status', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('admin_password', 'Password', 'required|alpha_numeric_spaces|matches[admin_repassword]');
    $this->form_validation->set_rules('admin_repassword', 'RePassword', 'required|alpha_numeric_spaces|matches[admin_password]');
    
    if ($this->form_validation->run() === FALSE) {
      $response = [
        "success" => 201,
        "csrf_hash" => $this->security->get_csrf_hash(),
        "message" => $this->form_error_message()
      ];
      echo json_encode($response);
    } else {
      $this->process_admin_add();
    }
  }

  private function process_admin_add()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], TRUE);
    $input = (object) html_escape($this->input->post());
    $check = $this->core_model->add_admin($input);
    if ($check->success === TRUE) {
      $response = [
        "success" => 200,
        "csrf_hash" => $this->security->get_csrf_hash(),
        "message" => $check->message
      ];
    } else {
      $response = [
        "success" => 201,
        "csrf_hash" => $this->security->get_csrf_hash(),
        "message" => $check->message
      ];
    }

    echo json_encode($response);
  }

  public function process_admin_edit()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1", "Staff|90|1"], FALSE);
    $input = (object) $this->input->post();
    $check = $this->core_model->edit_admin($input);
    if ($check->success === TRUE) {
      $this->session->set_flashdata("message", "<script>sweet('success', 'Sukses!', '$check->message')</script>");
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Gagal!', '$check->message')</script>");
    }
    redirect("admin_detail?id=$input->admin_id");
  }
}