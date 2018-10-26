<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

	protected $username = '';
	protected $role_id = '';
	protected $user_id = '';


	public function __construct(){
		parent::__construct();
		$this->load->library(array("session","notification","auth_user"));
		$this->load->model(array("general_model"));
		$this->load->helper('cookie');
		$this->user_id = '1';//$this->session->userdata("user_id");
		$this->role_id = $this->session->userdata("role_id");

		

	}

	public function index()
	{
		
		
		
		
		
		$data_assignment = '';
		$data = array(
			'css' => array(
						base_url("assets/breadcrumb/breadcrumb.css"),
						base_url("assets/easyui/themes/bootstrap/easyui.css"),
					),
			'js'	=> array(
						base_url("assets/easyui/jquery.easyui.min.js"),
						base_url('assets/notify/bootstrap-notify.js'),
					),

			'breadcrumb' => array('Home'=> base_url(), 'Roles' => '#'),
			'title' => "Roles",
			
		);

		$this->load->view("layouts2/head",$data);
		$this->load->view("role/index");
		$this->load->view("layouts2/footer");
		
	}
	function save()
	{
		$role_id = $this->input->get('role_id');
		if($role_id!=NULL){

			$role = $this->input->post("role");
			$description = $this->input->post("description");

			

			$data_to_save = array(
				
				"role"		=>	$role,
				"description"	=> 	$description,
			
				"updated_at"		=> 	date("Y-m-d H:i:s"),
				
				"updated_by"	=>	$this->user_id,
			);

			if($this->general_model->edit("ams_roles",array("role_id"=>$role_id),$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}else{

			$role = $this->input->post("role");
			$description = $this->input->post("description");

			$role_id = "CTG".date("YmdHis");

			$data_to_save = array(
				"role_id" 	=>	$role_id,
				"role"		=>	$role,
				"description"	=> 	$description,
				"created_at"		=> 	date("Y-m-d H:i:s"),
				"updated_at"		=> 	date("Y-m-d H:i:s"),
				"created_by"	=> 	$this->user_id,
				"updated_by"	=>	$this->user_id,
			);

			if($this->general_model->save("ams_roles",$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}

		}

	}

	function getRolesJson(){
		$role = ($this->input->post('role')!= NULL) ? $_POST['role'] : '';
		$description = ($this->input->post('description')!= NULL) ? $_POST['description'] : '';
		
		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$offset = ($page-1)*$limit;
		
		if($role!='' || $description!='' ){
			$params = 
				"role like '%".$role."%' or description like '%".$description."%' " ;
			
			$data_role = $this->general_model->getDataGrid("ams_roles",$params,($offset),$limit);
			$total = $this->general_model->getdata("ams_roles",$params)['total'];
		}else{
			$data_role = $this->general_model->getDataGrid("ams_roles",NULL,($offset),$limit);
			$total = $this->general_model->getdata("ams_roles")['total'];
		}
		
		$result = array(
			'total'	=> $total,
			'rows'	=> $data_role['data'],
		);

		echo json_encode($result);
	}
	function getRolesJSONCOmboBox()
	{
		$data_categories = $this->general_model->getdata("ams_roles");

		echo json_encode($data_categories['data']);
	}

	function delete(){
		$role_id = $this->input->post("role_id");
		if($this->general_model->delete("ams_roles",array("role_id"=>$role_id))){
			echo "success";
		}else{
			echo "failed";
		}

		
	}
}?>