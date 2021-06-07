<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rajaongkir_model extends CR_Model
{
  public static function get_province()
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "key: 34316eb45f72c89acc7dd76bc813e883"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    // dd($response);
    curl_close($curl);

    if ($err) {
      return FALSE;
    } else {
      $list = json_decode($response, TRUE);
      // dd($list["rajaongkir"]["results"]);
      return $list["rajaongkir"]["results"];
    }
  }

  public static function get_district($province_id)
  {
    $response = \create_response();
    $curl = curl_init();
    $array = explode(":", $province_id);
    $id = $array[0];

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=$id",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "key: 34316eb45f72c89acc7dd76bc813e883"
      ),
    ));

    $results = curl_exec($curl);
    $err = curl_error($curl);
    // dd($id);
    curl_close($curl);

    if ($err) {
      $response->messaget = $err;
    } else {
      $response->success = TRUE;
      $list = json_decode($results, TRUE);
      $list_district = $list["rajaongkir"]["results"];
      $response->option = [];
      foreach ($list_district as $item) {
        $data = [
          "city_id" => $item["city_id"],
          "city_name" => $item["type"] . " " . $item["city_name"]
        ];
        \array_push($response->option, $data);
      }
    }

    return $response;
  }
}
