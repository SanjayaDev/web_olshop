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
      $row[] = "<button class='btn btn-primary btn-sm' onclick=\"navigateTo('customer_detail?id=".encrypt_url($item->customer_id)."')\"><i class='fas fa-search mr-2'></i>Detail</button>";
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
}
