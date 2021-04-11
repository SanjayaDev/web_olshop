<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer_model extends CR_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  private function query_get_customer()
  {
    $column_order = ["lc.`customer_name`", "lc.`customer_phone`", "lc.`customer_email`", "lcs.`customer_status`"];
    $column_search = ["lc.`customer_name`", "lc.`customer_phone`", "lc.`customer_email`", "lcs.`customer_status`"];
    $order = ["lc.`customer_id`" => "DESC"];

    $query = $this->db->select("*")
      ->from("list_customer lc")
      ->join("list_customer_status lcs", "lc.`customer_status_id` = lcs.`customer_status_id`");
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

  public function get_customer_listed()
  {
    $query = $this->query_get_customer();
    $length = $this->input->post("length");
    if ($length != -1) {
      $start = $this->input->post("start");
      $limit = $query->limit($length, $start)->get();

      return $limit->result();
    }
  }

  public function count_customer_filtered()
  {
    $query = $this->query_get_customer()->get();
    return $query->num_rows();
  }
}
