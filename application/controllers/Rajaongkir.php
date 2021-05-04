<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rajaongkir extends CI_Controller
{
  public function get_district()
  {
    // echo "Satu";
    $this->load->model("Rajaongkir_model", "rajaongkir");
    $province_id = $this->input->get("province_id");
    // echo json_encode(["province_id" => $province_id]);
    $get_district = $this->rajaongkir->get_district($province_id);
    if ($get_district->success) {
      $response = [
        "success" => TRUE,
        "data" => $get_district->option
      ];
    } else {
      $response = [
        "success" => FALSE,
        "message" => "Error!"
      ];
    }

    echo json_encode($response);
  }
}
