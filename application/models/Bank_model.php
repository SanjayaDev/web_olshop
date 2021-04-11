<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_model extends CR_Model {

  public function __construct()
  {
    parent::__construct();
  }

  public function get_bank_account_list()
  {
    return $this->db->get("list_bank")->result();
  }

  public function add_bank($input)
  {
    $response = create_response();

    $data = [
      "bank_name" => $input->bank_name,
      "branch_name" => $input->branch_name,
      "bank_code" => $input->bank_code,
      "account_number" => $input->account_number,
      "holder_name" => $input->holder_name,
    ];

    $query = $this->db->insert("list_bank", $data);
      
    if ($query) {
      $response->success = TRUE;
      $response->message = "Success added new bank!";
    } else {
      $response->message = "Query failed!";
    }

    return $response;
  }

  public function get_bank_detail($id)
  {
    $resposne = create_response();
    $query = $this->db->get_where("list_bank", ["bank_id" => decrypt_url($id)]);
    if ($query->num_rows() == 1) {
      $resposne->success = TRUE;
      $resposne->data = $query->row();
    } else {
      $resposne->message = "Data not found!";
    }
    return $resposne;
  }

  public function edit_bank($input)
  {
    $response = create_response();
    $data = [
      "bank_name" => $input->bank_name,
      "branch_name" => $input->branch_name,
      "bank_code" => $input->bank_code,
      "account_number" => $input->account_number,
      "holder_name" => $input->holder_name,
      "is_active" => $input->is_active
    ];
    $where = ["bank_id" => decrypt_url($input->bank_id)];

    $query = $this->db->update("list_bank", $data, $where);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Success updated bank!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function delete_bank($id)
  {
    $response = create_response();
    $get_bank = $this->get_bank_detail($id);
    if ($get_bank->success) {
      $query = $this->db->delete("list_bank", ["bank_id" => decrypt_url($id)]);
      if ($query) {
        $response->success = TRUE;
        $response->message = "Success deleted bank account!";
      } else {
        $response->message = "Query failed!";
      }
    } else {
      $response->message = $get_bank->message;
    }

    return $response;
  }
}