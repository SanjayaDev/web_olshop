<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function index()
	{
		$data = [
			"title" => "Under Construction"
		];
		$this->load->view('public_index', $data);
	}

	public function view_404_page()
	{
		$this->load->view("v_404");
	}
}
