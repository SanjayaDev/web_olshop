<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CR_Model {

  public function __construct()
  {
    parent::__construct();
  }

  public function process_login($input)
  {
    $response = create_response();
    $check = $this->check_login($input->email);
    if ($check->success === TRUE) {
      $data = $check->data;
      if (password_verify($input->password, $data->admin_password)) {
        if ($data->admin_statusId == 1) {
          $check_user_login = $this->check_login_user($data->admin_id);
          if ($check_user_login->success === TRUE) {
            $create_session = $this->create_session_token($data->admin_id);
            if ($create_session->success === TRUE) {
              $session = [
                "admin_name" => $data->admin_name,
                "admin_id" => encrypt_url($data->admin_id),
                "admin_name" => $data->admin_name,
                "division_name" => $data->division_name,
                "access_level" => $data->access_level,
                "admin_tier_id" => $data->admin_tier_id,
                "session_token" => $create_session->session_token,
                "is_login" => TRUE
              ];
              $this->session->set_userdata($session);
              $response->success = TRUE;
              $response->url = "dashboard";
              $this->log_activity(1);
            } else {
              $response->message = $create_session->message;
            }
          } else {
            $response->message = $check_user_login->message;
          }
        } else {
          $response->message = "Saat ini, akun sedang dinonaktifkan/dibanned!";
        }
      } else {
        $response->message = "Password salah!";
      }
    } else {
      $response->message = $check->message;
    }
    return $response;
  }

  private function check_login($email)
  {
    $response = create_response();
    $query = $this->db->select("*")->from("list_admin la")->join("list_access_control lac", "lac.`admin_tier_id` = la.`admin_tierId`")
            ->join("list_division ld", "ld.`division_id` = lac.`access_divisionId`")
            ->where("admin_email", $email)->get();
    if ($query) {
      if ($query->num_rows() == 1) {
        $response->success = TRUE;
        $response->data = $query->row();
      } else {
        $response->message = "Akun belum diregistrasi!";
      }
    } else {
      $response->message = "Query check login email failed!";
    }
    return $response;
  }

  public function process_logout()
  {
    $this->log_activity(2);
    $this->db->update("list_session_token", ["is_login" => 0], ["session_token" => $this->session_token]);
  }
}