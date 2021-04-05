<?php
  defined('BASEPATH') or exit('No direct script access allowed');
  require_once("core_config.php");

  class CR_Model extends CI_Model
  {
    protected $session_token;

    public function __construct()
    {
      parent::__construct();
      date_default_timezone_set("Asia/Jakarta");
      $this->session_token = $this->session->session_token != NULL ? $this->session->session_token : NULL;
    }

    public function protect_login()
    {
      $response = create_response();
      if ($this->session->is_login === TRUE) {
        $response->success = TRUE;
      } else {
        $response->message = "Anda wajib melakukan login dahulu!";
      }
      return $response;
    }

    public function check_session_login()
    {
      $response = create_response();
      if ($this->session->is_login != NULL) {
        $query = $this->db->get_where("list_session_token", ["session_token" => $this->session_token]);
        if ($query) {
          if ($query->num_rows() == 1) {
            $data = $query->row();
            $date_now = date("Y-m-d H:i:s");
            if ($data->expire_time < $date_now) {
              $response->message = "Session is expired!";
            } else {
              $update_session = $this->update_session_login();
              if ($update_session->success === TRUE) {
                $response->success = TRUE;
              } else {
                $response->message = $update_session->message;
              }
            }
          } else {
            $response->message = "Session is not valid!";
          }
        } else {
          $response->message = "Query failed!";
        }
      } else {
        $response->success = TRUE;
      }
      return $response;
    }

    private function update_session_login()
    {
      $response = create_response();
      $data = [
        "active_time" => date("Y-m-d H:i:s"),
        "expire_time" => date("Y-m-d H:i:s", strtotime("+15 minutes"))
      ];
      $query = $this->db->update("list_session_token", $data, ["session_token" => $this->session_token]);
      if ($query) {
        $response->success = TRUE;
      } else {
        $response->message = "Query update session failed!";
      }
      return $response;
    }

    protected function check_login_user($admin_id)
    {
      $response = create_response();
      if (MULTI_USER === FALSE) {
        $query = $this->db->select("*")->from("list_session_token")->where("admin_id", $admin_id)
          ->order_by("session_id", "DESC")->limit(1)->get();
        // var_dump($this->db->last_query());
        if ($query) {
          if ($query->num_rows() == 1) {
            $data = $query->row();
            if ($data->is_login == 1) {
              $expire_time = $data->expire_time;
              $now = date("Y-m-d H:i:s");
              if ($expire_time >= $now) {
                $response->message = "Saat ini user sedang digunakan!";
              } else {
                $response->success = TRUE;
              }
            } else {
              $response->success = TRUE;
            }
          } else {
            $response->success = TRUE;
          }
        } else {
          $response->message = "Query check login user failed!";
        }
      } else {
        $response->success = TRUE;
      }
      return $response;
    }

    protected function create_session_token($admin_id)
    {
      $response = create_response();
      $error = FALSE;
      $duplicate_token = TRUE;
      do {
        $random_string = $this->generate_random_string();
        $query = $this->db->get_where("list_session_token", ["session_token" => $random_string]);
        if ($query) {
          if ($query->num_rows() == 0) {
            $duplicate_token = FALSE;
          } else {
            $duplicate_token = TRUE;
          }
        } else {
          $response->message = "Query check duplicate token failed!";
          $error = TRUE;
          break;
        }
      } while ($duplicate_token === TRUE);

      if ($error === FALSE) {
        $data = [
          "session_token" => $random_string,
          "admin_id" => $admin_id,
          "active_time" => date("Y-m-d H:i:s"),
          "expire_time" => date("Y-m-d H:i:s", strtotime("+15 minutes")),
          "is_login" => 1
        ];
        $query = $this->db->insert("list_session_token", $data);
        if ($query) {
          $response->success = TRUE;
          $response->session_token = $random_string;
        } else {
          $response->message = "Query create session failed!";
        }
      }

      return $response;
    }

    public function get_admin_detail($admin_id)
    {
      return $this->db->get_where("list_admin", ["admin_id" => $admin_id])->row();
    }

    public function generate_random_string($size = FALSE)
    {
      $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $length = strlen($characters);
      $random_string = "";
      if ($size === FALSE or !is_integer($size)) {
        $size = 26;
      }
      for ($i = 0; $i < $size; $i++) {
        $random_string .= $characters[rand(0, $length - 1)];
      }
      return $random_string;
    }

    public function check_validate_access($rules, $user)
    {
      $response = create_response();
      $division_name = $this->session->division_name;
      $access_level = $this->session->access_level;
      for ($i = 0; $i < count($user); $i++) {
        $array = explode("|", $user[$i]);
        if ($rules == "WHITE_LIST") {
          if ($division_name == $array[0]) {
            if (end($array) == 0) {
              if ($access_level == $array[1]) {
                $response->success = TRUE;
              }
            } else {
              if ($access_level >= $array[1]) {
                $response->success = TRUE;
              }
            }
          }
        } else if ($rules == "BLACK_LIST") {
          if ($division_name == $array[0]) {
            if (end($array) == 0) {
              if ($access_level != $array[1]) {
                $response->success = TRUE;
              }
            } else {
              if ($access_level < $array[1]) {
                $response->success = TRUE;
              }
            }
          }
        }
      }
      return $response;
    }

    public function check_unique($table, $column, $value)
    {
      $query = $this->db->select("*")->from($table)->where($column, $value)->get();
      if ($query) {
        if ($query->num_rows() == 1) {
          return TRUE;
        } else {
          return FALSE;
        }
      } else {
        return "Query failed!";
      }
    }

    public function get_currently_admin_online()
    {
      $sql = "SELECT la.`admin_name`, lst.`active_time`, la.`admin_id` "
        .  "FROM `list_session_token` lst "
        .  "JOIN `list_admin` la ON la.`admin_id` = lst.`admin_id` "
        .  "WHERE lst.`active_time` > (NOW() - INTERVAL 15 MINUTE) AND lst.`is_login` = 1 "
        .  "GROUP BY lst.`admin_id`;";
      $query = $this->db->query($sql);
      if ($query) {
        return $query->result();
      } else {
        return FALSE;
      }
    }

    protected function log_activity($action_id, $meta_data = NULL)
    {
      $admin_id = decrypt_url($this->session->admin_id);
      $admin = $this->get_admin_detail($admin_id);
      $this->load->library("user_agent");
      $get_action = $this->get_action($action_id);
      $data = [
        "action_id" => $action_id,
        "admin_id" => $admin_id,
        "platform" => $this->agent->platform,
        "browser" => $this->agent->browser,
        "ip_address" => $this->input->ip_address(),
        "time" => date("Y-m-d H:i:s"),
        "description" => "$admin->admin_name telah melakukan $get_action->action kedalam sistem",
        "meta_data" => $meta_data
      ];
      $this->db->insert("list_activity", $data);
    }

    private function get_action($action_id)
    {
      $response = create_response();
      $query = $this->db->get_where("list_action", ["action_id" => $action_id]);
      if ($query->num_rows() == 1) {
        $response->success = TRUE;
        $response->action = $query->row()->action;
      } else {
        $response->message = "Action not valid!";
      }
      return $response;
    }

    public function get_access_listed()
    {
      return $this->db->get("list_access_control")->result();
    }

    protected function query_get_admin()
    {
      $column_order = ["la.`admin_name`", "la.`admin_email`", "la.`admin_phone`", "lac.`access_levelName`"];
      $column_search = ["la.`admin_name`", "la.`admin_email`", "la.`admin_phone`"];
      $order = ["la.`admin_id`" => "DESC"];

      $query = $this->db->select("la.`admin_name`, la.`admin_email`, la.`admin_phone`, la.`admin_id`, lac.`access_levelName`")
        ->from("list_admin la")->join("list_access_control lac", "la.`admin_tierId` = lac.`admin_tier_id`")
        ->join("list_division ld", "ld.`division_id` = lac.`access_divisionId`");
      $i = 1;
      $search = $this->input->post("search");
      foreach ($column_search as $item) {
        if ($search["value"]) {
          if ($i == 1) {
            $query->group_start();
            $query->like($item, $search["value"]);
          } else {
            $query->or_like($item, $search["value"]);
          }

          if (count($column_search) == $i) {
            $query->group_end();
          }
        }
        $i++;
      }

      $get_order = $this->input->post("order");
      if (isset($get_order) && $get_order != NULL) {
        $query->order_by($column_order[$_POST["order"]["0"]["column"]], $_POST["order"]["0"]["dir"]);
      } else if (isset($order)) {
        $query->order_by(key($order), $order[key($order)]);
      }

      return $query;
    }

    public function get_admin_listed()
    {
      $query = $this->query_get_admin();
      $length = $this->input->post("length");
      if ($length != -1) {
        $start = $this->input->post("start");
        $limit = $query->limit($length, $start)->get();

        return $limit->result();
      }
    }

    public function count_admin_filtered()
    {
      $query = $this->query_get_admin()->get();
      return $query->num_rows();
    }

    public function count_admin_all()
    {
      $query = $this->query_get_admin();
      return $query->count_all_results();
    }

    public function get_admin_status_listed()
    {
      return $this->db->get("list_admin_status")->result();
    }

    public function add_admin($input)
    {
      $response = create_response();
      $error = FALSE;
      $file_name = $_FILES["admin_photo"]["name"];
      $path_image = "";

      if (!empty($file_name)) {
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);

        $i = 1;
        // Upload Image
        do {
          $random_name = $this->generate_random_string(7);
          $new_file_name = "$random_name.$extension";

          $upload_image = $this->upload_files("admin_photo", $new_file_name, "./assets/image/admin_photo/");
          if ($upload_image->success === TRUE) {
            $success_upload = TRUE;
            $error = FALSE;
            $path_image = base_url("assets/image/admin_photo/$new_file_name");
          } else {
            $success_upload = FALSE;
            $error = TRUE;
            $response->message = $upload_image->message;
          }

          if ($i > 5) {
            $error = TRUE;
            $response->message = $upload_image->message;
            break;
          }
          $i++;
        } while ($success_upload === FALSE);
      }

      if ($error === FALSE) {
        $data = [
          "admin_name" => $input->admin_name,
          "admin_password" => password_hash($input->admin_password, PASSWORD_DEFAULT),
          "admin_email" => $input->admin_email,
          "admin_phone" => $input->admin_phone,
          "created_date" => date("Y-m-d H:i:s"),
          "admin_statusId" => decrypt_url($input->admin_statusId),
          "admin_tierId" => decrypt_url($input->admin_tierId),
          "admin_photo" => $path_image
        ];

        $query = $this->db->insert("list_admin", $data);
        if ($query) {
          $id = $this->db->insert_id();
          $this->log_activity(3, $id);
          $response->success = TRUE;
          $response->message = "Sukses menambahkan admin!";
        } else {
          $response->message = "Query failed!";
        }
      }

      return $response;
    }

    public function edit_admin($input)
    {
      $response = create_response();
      $error = FALSE;
      $data = [
        "admin_name" => $input->admin_name,
        "admin_email" => $input->admin_email,
        "admin_phone" => $input->admin_phone,
        "updated_date" => date("Y-m-d H:i:s"),
        "admin_statusId" => decrypt_url($input->admin_statusId),
        "admin_tierId" => decrypt_url($input->admin_tierId)
      ];

      if (!empty($input->admin_password)) {
        if ($input->admin_password == $input->admin_repassword) {
          $data["admin_password"] = password_hash($input->admin_password, PASSWORD_DEFAULT);
        } else {
          $error = TRUE;
          $response->message = "Passowrd tidak sesuai!";
        }
      }

      $file_name = $_FILES["admin_photo"]["name"];
      $path_image = "";

      if (!empty($file_name)) {
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);

        $i = 1;
        // Upload Image
        do {
          $random_name = $this->generate_random_string(7);
          $new_file_name = "$random_name.$extension";

          $upload_image = $this->upload_files("admin_photo", $new_file_name, "./assets/image/admin_photo/");
          if ($upload_image->success === TRUE) {
            $success_upload = TRUE;
            $error = FALSE;
            $path_image = base_url("assets/image/admin_photo/$new_file_name");
          } else {
            $success_upload = FALSE;
            $error = TRUE;
            $response->message = $upload_image->message;
          }

          if ($i > 5) {
            $error = TRUE;
            $response->message = $upload_image->message;
            break;
          }
          $i++;
        } while ($success_upload === FALSE);

        $data["admin_photo"] = $path_image;
        if ($error === FALSE) {
          $delete_old_image = $this->delete_old_file("list_admin", "admin_id", decrypt_url($input->admin_id), "./assets/image/admin_photo", "admin_photo");
          if ($delete_old_image->success === FALSE) {
            $error = TRUE;
            $response->message = $delete_old_image->message;
          }
        }
      }

      if ($error === FALSE) {
        $where = ["admin_id" => decrypt_url($input->admin_id)];
        $query = $this->db->update("list_admin", $data, $where);

        if ($query) {
          $response->success = TRUE;
          $response->message = "Success edited admin!";
          $this->log_activity(4, decrypt_url($input->admin_id));
        } else {
          $response->message = "Query failed!";
        }
      }

      return $response;
    }

    public function get_admin_detail_relate($id)
    {
      $query = $this->db->select("la.*, lac.`access_levelName`, las.`admin_status`")
        ->from("list_admin la")
        ->join("list_access_control lac", "lac.`admin_tier_id` = la.`admin_tierId`")
        ->join("list_admin_status las", "las.`admin_status_id` = la.`admin_statusId`")
        ->where("la.`admin_id`", $id)->get();
      if ($query) {
        return $query->row();
      } else {
        return FALSE;
      }
    }

    public function get_admin_activity_listed($id)
    {
      return $this->db->select("*")->from("list_activity la")->join("list_action laa", "laa.`action_id` = la.`action_id`")
        ->where("la.`admin_id`", $id)->order_by("la.`time`", "DESC")->get()->result();
    }

    protected function upload_files($input_name, $file_name, $upload_path, $check_old_files = TRUE, $replace = FALSE)
    {
      $response = create_response();
      $exists = FALSE;
      $error = FALSE;
      if ($check_old_files === TRUE) {
        if (file_exists("$upload_path$file_name")) {
          $response->exists = TRUE;
          $response->message = "File exists!";
          $exists = TRUE;
        }
      }

      if ($replace === FALSE && $exists === TRUE) {
        $error = TRUE;
      }

      if ($error === FALSE) {
        $config['upload_path']    = $upload_path;
        $config['allowed_types']  = "png|jpeg|jpg|gif|pdf|doc|docx|xls|xlsx";
        $config['max_size']       = '10048';
        $config['max_width']      = '5023';
        $config['max_height']     = '5024';
        $config['file_name']      = $file_name;
        $config['detect_mime']    = TRUE;
        $config["overwrite"]      = TRUE;

        $this->load->library("upload");
        $this->upload->initialize($config);

        if ($this->upload->do_upload($input_name)) {
          $response->success = TRUE;
        } else {
          $response->message = $this->upload->display_errors();
        }
      }

      return $response;
    }

    protected function delete_old_file($table, $where_column, $where_value, $path, $column_path)
    {
      $response = create_response();
      $get_data = $this->db->get_where($table, [$where_column => $where_value]);
      if ($get_data->num_rows() == 1) {
        $data = $get_data->row();
        $path_db = $data->$column_path;

        if (!empty($path_db)) {
          $array = explode("/", $path_db);

          $old_path = "$path/" . end($array);
          if (file_exists($old_path)) {
            if (!unlink($old_path)) {
              $response->message = "Failed delete old file";
            } else {
              $response->success = TRUE;
            }
          } else {
            $response->message = "File not found!";
          }
        } else {
          $response->success = TRUE;
        }
      } else {
        $response->message = "Empty data!";
      }
      return $response;
    }

    protected function update_last_login_admin($id)
    {
      $value = ["`admin_lastLogin`" => date("Y-m-d H:i:s")];
      $where = ["`admin_id`" => $id];
      $this->db->update("list_admin", $value, $where);
    }
  }
