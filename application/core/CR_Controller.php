<?php

defined('BASEPATH') or exit('No direct script access allowed');
class CR_Controller extends CI_Controller
{
  protected $session_token;

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Auth_model", "Auth");
    $this->load->model("CR_Model", "core_model");
    $this->session_token = $this->session->session_token != NULL ? $this->session->session_token : NULL;
    $check_session = $this->Auth->check_session_login();
    if ($check_session->success === FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('warning', 'Gagal!', '$check_session->message')</script>");
      redirect("login");
      exit;
    }
  }

  protected function generate_anti_cache()
  {
    return $this->Auth->generate_random_string(10);
  }

  protected function htaccess($rules, $user, $is_ajax)
  {
    $check_validate = $this->core_model->check_validate_access($rules, $user);
    if ($check_validate->success === FALSE) {
      if ($is_ajax) {
        $data = [
          "success" => 403,
          "link" => base_url("forbidden")
        ];
        echo json_encode($data);
      } else {
        redirect("forbidden");
      }
      exit;
    }
  }

  protected function push_breadcrumb($title, $link, $delete = FALSE)
  {
    $breadcrumb = $this->session->breadcrumb;

    if ($delete) {
      $breadcrumb = [
        ["link" => $link,
        "title" => $title]
      ];
      $this->session->set_userdata("breadcrumb", $breadcrumb);
    } else {
      if (!empty($breadcrumb)) {
        $index = array_search($title, array_column($breadcrumb, "title"));
        if ($index !== FALSE) {
          $breadcrumb = array_splice($breadcrumb, 0, $index);
        }
      }

      $data = [
        "link" => $link,
        "title" => $title
      ];

      array_push($breadcrumb, $data);
      $this->session->set_userdata("breadcrumb", $breadcrumb);
    }
  }

  protected function draw_breadcrumb($title, $link, $delete = FALSE)
  {
    $this->push_breadcrumb($title, $link, $delete);

    $data = $this->session->breadcrumb;
    $last = count($data);
    $html = "<nav aria-label='breadcrumb'>";
    $html .= "<ol class='breadcrumb'>";
    $i = 1;
    foreach ($data as $item) {
      if ($i != $last) {
        $html .= "<lu class='breadcrumb-item'><a href='#' onclick=\"navigateTo('" . $item["link"] . "', true)\">" . $item["title"] . "</a></lu>";
      } else {
        $html .= "<lu class='breadcrumb-item active'>" . $item["title"] . "</lu>";
      }
      $i++;
    }
    $html .= "</ol>";
    $html .= "</nav>";
    return $html;
  }

  protected function form_error_message()
  {
    $message = $this->form_validation->error_string();
    $message = str_replace("<p>", "-", $message);
    $message = str_replace("</p>", ".", $message);
    return $message;
  }
}
