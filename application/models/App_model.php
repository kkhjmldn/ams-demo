<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model {



	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function menu($role_id){
		$sql = $this->db->where(array("a.role_id = '0'"))->get("sino_menus a");

		$result = array(
			'total' => $sql->num_rows(),
			'data'	=> $sql->result(),
		);
		return $result;
	}
}