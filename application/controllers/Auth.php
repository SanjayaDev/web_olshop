<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Auth_model", "Auth");
  }

  public function view_login_page()
  {
    $data = [
      "title" => "Login"
    ];
    $this->load->view("v_login", $data);
  }

  public function process_validation_login()
  {
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|alpha_numeric_spaces');
    
    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', 'Data input tidak lengkap/valid!')</script>");
      // var_dump($this->session->flashdata());
      redirect("login");
    } else {
      $this->process_login();
    }
  }

  private function process_login()
  {
    $input = (object) html_escape($this->input->post());
    $check = $this->Auth->process_login($input);
    if ($check->success === TRUE) {
      redirect($check->url);
    } else {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$check->message')</script>");
      redirect("login");
    }
  }

  public function process_logout()
  {
    $this->Auth->process_logout();
    $this->session->set_flashdata("pesan", "<script>sweet('success', 'Sukses!', 'Anda berhasil logout!')</script>");
    redirect("login");
  }

  public function view_forbidden()
  {
    $this->load->view("v_forbidden");
  }
}