<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bank extends CR_Controller
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
    $this->load->model("Bank_model", "model");
  }

  public function view_bank_account_management()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
    $this->form_validation->set_rules("bank_name", "Bank Name", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("branch_name", "Branch Name", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("bank_code", "Bank Code", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("account_number", "Account Number", "required");
    $this->form_validation->set_rules("holder_name", "Holder Name", "required");


    if ($this->form_validation->run() == FALSE) {
      if (!empty(validation_errors())) {
        $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', 'Input not completed!')</script>");
      }
      $data = [
        "title" => "Bank Account",
        "content" => "admin/v_bank",
        "breadcrumb" => $this->draw_breadcrumb("Bank Account", base_url("bank_aacount"), TRUE),
        "list_bank" => $this->model->get_bank_account_list()
      ];
      $this->load->view("layout/wrapper", $data);
    } else {
      $this->process_bank_add();
    }
  }

  private function process_bank_add()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $input = (object) html_escape($this->input->post());
    $check = $this->model->add_bank($input);
    if ($check->success) {
      $this->session->set_flashdata("message", "<script>sweet('success', 'Success!', '$check->message!')</script>");
      redirect("bank_account");
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', '$check->message!')</script>");
      redirect("bank_account");
    }
  }

  public function ajax_get_bank_detail()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], TRUE);
    $id = html_escape($this->input->get("id"));
    $check = $this->model->get_bank_detail($id);
    if ($check->success) {
      $data = [
        "success" => 200,
        "data" => $check->data
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $check->message
      ];
    }

    echo json_encode($data);
  }

  public function validate_bank_edit()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
    $this->form_validation->set_rules("bank_id", "Bank ID", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("bank_name", "Bank Name", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("branch_name", "Branch Name", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("bank_code", "Bank Code", "required|alpha_numeric_spaces");
    $this->form_validation->set_rules("account_number", "Account Number", "required");
    $this->form_validation->set_rules("holder_name", "Holder Name", "required");
    $this->form_validation->set_rules("is_active", "status", "required|numeric");


    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', 'Input not completed!')</script>");
      redirect("bank_account");
    } else {
      $this->process_bank_edit();
    }
  }

  private function process_bank_edit()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $input = (object) html_escape($this->input->post());
    $check = $this->model->edit_bank($input);
    if ($check->success) {
      $this->session->set_flashdata("message", "<script>sweet('success', 'Success!', '$check->message!')</script>");
      redirect("bank_account");
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', '$check->message!')</script>");
      redirect("bank_account");
    }
  }

  public function process_bank_delete()
  {
    $this->htaccess("WHITE_LIST", ["System Administrator|100|1"], FALSE);
    $id = html_escape($this->input->get("id"));
    $check = $this->model->delete_bank($id);
    if ($check->success) {
      $this->session->set_flashdata("message", "<script>sweet('success', 'Success!', '$check->message!')</script>");
      redirect("bank_account");
    } else {
      $this->session->set_flashdata("message", "<script>sweet('error', 'Failed!', '$check->message!')</script>");
      redirect("bank_account");
    }
  }
}
