<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CR_Controller
{
  public function __construct()
  {
    parent::__construct();
    $protect_login = $this->Auth->protect_login();
    if ($protect_login->success === FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$protect_login->message')</script>");
      redirect("login");
      exit;
    }
    $this->load->model("Customer_model", "model");
    $this->load->model("Rajaongkir_model", "rajaongkir");
  }

  public function view_customer_management()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $data = [
      "title" => "Customer Management",
      "content" => "admin/v_customer",
      "breadcrumb" => $this->draw_breadcrumb("Customer Management", base_url("customer"), TRUE),
      "csrf" => $this->security->get_csrf_hash()
    ];
    $this->load->view("layout/wrapper", $data);
  }

  public function get_customer_listed()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|0"], TRUE);
    $list = $this->model->get_customer_listed();
    $data = [];
    $no = $this->input->post("start");
    foreach ($list as $item) {
      $no++;
      $row = [];
      $row[] = $no;
      $row[] = $item->customer_name;
      $row[] = $item->customer_phone;
      $row[] = $item->customer_email;
      $row[] = $item->customer_status;
      $row[] = "<button class='btn btn-primary btn-sm' onclick=\"navigateTo('customer_detail/" . encrypt_url($item->customer_id) . "')\"><i class='fas fa-search mr-2'></i>Detail</button>";
      $data[] = $row;
    }
    $output = [
      "draw" => $this->input->post("draw"),
      "recordsTotal" => $this->model->count_customer_filtered(),
      "recordsFiltered" => $this->model->count_customer_filtered(),
      "data" => $data
    ];

    echo json_encode($output);
  }

  public function view_add_customer()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
    $this->form_validation->set_rules("customer_name", "Customer Name", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("customer_email", "Customer Email", "required|valid_email|is_unique[list_customer.customer_email]");
    $this->form_validation->set_rules("customer_phone", "Customer Phone", "required|numeric");
    $this->form_validation->set_rules("province_id", "Province", "required");
    $this->form_validation->set_rules("district_id", "District", "required");
    $this->form_validation->set_rules("customer_address", "Full Address", "required");
    $this->form_validation->set_rules("customer_password", "Password", "required");
    $this->form_validation->set_rules("customer_password2", "Password", "required|matches[customer_password]");

    if ($this->form_validation->run() == FALSE) {
      if (!empty(validation_errors())) {
        $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', 'Input not completed!')</script>");
      }
      $data = [
        "title" => "Customer Management",
        "content" => "admin/v_customer_add",
        "breadcrumb" => $this->draw_breadcrumb("Add Customer", base_url("add_customer")),
        "list_province" => $this->rajaongkir->get_province(),
        "list_customer_status" => $this->model->get_customer_status_listed()
      ];
      $this->load->view("layout/wrapper", $data);
    } else {
      $this->process_customer_add();
    }
  }

  private function process_customer_add()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $input = (object) $this->input->post();
    $check = $this->model->add_customer($input);
    if ($check->success) {
      $this->session->set_flashdata("message", "<script>sweet('success', 'Success!', '$check->message')</script>");
      $id = encrypt_url($check->id);
      redirect("customer_detail/$id");
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', '$check->message')</script>");
      redirect("add_customer");
    }
  }

  public function view_customer_detail($customer_id)
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $id = decrypt_url($customer_id);
    $check = $this->model->get_customer_detail($id);
    if ($check->success) {
      $data = [
        "title" => "Customer Detail",
        "content" => "admin/v_customer_detail",
        "breadcrumb" => $this->draw_breadcrumb("Detail Customer", base_url("customer_detail/$customer_id")),
        "customer" => $check->data
      ];
      $this->load->view("layout/wrapper", $data);
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', '$check->message')</script>");
      redirect("customer");
    }
  }

  public function view_customer_edit($customer_id)
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE); 
    $id = decrypt_url($customer_id);
    $check = $this->model->get_customer_detail($id);
    if ($check->success) {
      $data = [
        "title" => "Customer Edit",
        "content" => "admin/v_customer_edit",
        "breadcrumb" => $this->draw_breadcrumb("Edit Customer", base_url("view_customer_detail/$customer_id")),
        "list_customer_status" => $this->model->get_customer_status_listed(),
        "list_province" => $this->rajaongkir->get_province(),
        "customer" => $check->data
      ];
      $this->load->view("layout/wrapper", $data);
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', '$check->message')</script>");
      redirect("customer");
    }
  }
}
