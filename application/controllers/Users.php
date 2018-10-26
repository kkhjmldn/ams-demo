<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	protected $username = '';
	protected $role_id = '';
	protected $user_id = '';


	public function __construct(){
		parent::__construct();
		$this->load->library(array("session","notification","auth_user"));
		$this->load->model(array("general_model"));
		$this->load->helper('cookie');
		$this->user_id = $this->session->userdata("user_id");
		$this->role_id = $this->session->userdata("role_id");


		

	}

	public function index()
	{
		$data = array(
			'css' => array(
						base_url("assets/breadcrumb/breadcrumb.css"),
						base_url("assets/easyui/themes/bootstrap/easyui.css"),
					),
			'js'	=> array(
						base_url("assets/easyui/jquery.easyui.min.js"),
						base_url('assets/notify/bootstrap-notify.js'),
					),

			'breadcrumb' => array('Home'=> base_url(), 'Users' => '#'),
			'title' => "Users",
			
		);

		$this->load->view("layouts2/head",$data);
		$this->load->view("user/index");
		$this->load->view("layouts2/footer");
	}

	function getTeacherJSONComboBox()
	{
		$data_instructors = $this->general_model->getdata("ams_users",array("role_id"=>2));

		echo json_encode($data_instructors['data']);
	}

	function geUserJsonComboGrid(){

			$data_users = $this->general_model->getdata("ams_users");
			
			$data = array('total' => $data_users['total'] , 'rows'=> $data_users['data']);
			echo json_encode($data);
	}

	function getStudentJsonComboGrid(){
			$data_users = $this->general_model->getdata("ams_users",array("role_id"=>3));
			
			$data = array('total' => $data_users['total'] , 'rows'=> $data_users['data']);
			echo json_encode($data);
	}
	function save()
	{
		$user_id = $this->input->get('user_id');
		if($user_id!=NULL){

			$username = $this->input->post("username");
			$name = $this->input->post("name");
			$password = $this->input->post("password");
			//$user_id = "USR".date("YmdHis");
			$role_id = $this->input->post("role_id");
			$email = $this->input->post("email");
			//$name = $this->input->post("name");
			$data_to_save = array(
				//"user_id" 	=>	$user_id,
				"username"		=>	$username,
				"name"	=> 	$name,
				"password"	=> 	$password,
				"email"	=> 	$email,
				"role_id"	=> 	$role_id,
				"avatar"	=> ''
				
			);

			if($this->general_model->edit("ams_users",array("user_id"=>$user_id),$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}else{

			$username = $this->input->post("username");
			$name = $this->input->post("name");
			$password = $this->input->post("password");
			$user_id = "USR".date("YmdHis");
			$role_id = $this->input->post("role_id");
			$email = $this->input->post("email");
			//$name = $this->input->post("name");
			$data_to_save = array(
				"user_id" 	=>	$user_id,
				"username"		=>	$username,
				"name"	=> 	$name,
				"password"	=> 	$password,
				"email"	=> 	$email,
				"role_id"	=> 	$role_id,
				"avatar"	=> ''
				
			);

			if($this->general_model->save("ams_users",$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}

		}

	}

	function getUsersJson(){

		$name = ($this->input->post('name')!= NULL) ? $_POST['name'] : '';
		$username = ($this->input->post('username')!= NULL) ? $_POST['username'] : '';
		
		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$offset = ($page-1)*$limit;
		
		if($name!='' || $username!='' ){
			$params = 
				"name like '%".$name."%' or username like '%".$username."%' " ;
			
			$data_role = $this->general_model->getDataGrid("ams_users",$params,($offset),$limit);
			$total = $this->general_model->getdata("ams_users",$params)['total'];
		}else{
			$data_role = $this->general_model->getDataGrid("ams_users",NULL,($offset),$limit);
			$total = $this->general_model->getdata("ams_users")['total'];
		}
		foreach ($data_role['data'] as $key => $value) {
			$value->role = $this->getRoleId($value->role_id)->role;
		}
		
		$result = array(
			'total'	=> $total,
			'rows'	=> $data_role['data'],
		);

		echo json_encode($result);
	}

	function delete(){
		$user_id = $this->input->post("user_id");
		if($this->general_model->delete("ams_users",array("user_id"=>$user_id))){
			echo "success";
		}else{
			echo "failed";
		}

		
	}

	function getRoleId($role_id){
		$data_roles = $this->general_model->getdata("ams_roles",array("role_id"=>$role_id));
		return $data_roles['data'][0];

	}
}?>