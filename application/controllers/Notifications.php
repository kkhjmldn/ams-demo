<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

	protected $username = '';
	protected $notification_id = '';
	protected $user_id = '';


	public function __construct(){
		parent::__construct();
		$this->load->library(array("session","notification","auth_user"));
		$this->load->model(array("general_model"));
		$this->load->helper('cookie');
		$this->user_id = '1';//$this->session->userdata("user_id");
		$this->notification_id = $this->session->userdata("notification_id");

		

	}

	public function index()
	{
		
		
		$data_notifications = $this->general_model->getdata("ams_notifications",array("user_id"=>$this->user_id));
		$data = array(
			'css' => array(
						base_url("assets/breadcrumb/breadcrumb.css"),
						base_url("assets/easyui/themes/bootstrap/easyui.css"),
					),
			'js'	=> array(
						base_url("assets/easyui/jquery.easyui.min.js"),
						base_url('assets/notify/bootstrap-notify.js'),
					),

			'breadcrumb' => array('Home'=> base_url(), 'All Notifications' => '#'),
			'title' => "All Notifications",
			'data_notifications' => $data_notifications
			
		);

		$this->load->view("layouts2/head",$data);
		$this->load->view("notification/index");
		$this->load->view("layouts2/footer");
		
	}
	function save()
	{
		$notification_id = $this->input->get('notification_id');
		if($notification_id!=NULL){

			$role = $this->input->post("role");
			$description = $this->input->post("description");

			

			$data_to_save = array(
				
				"role"		=>	$role,
				"description"	=> 	$description,
			
				"updated_at"		=> 	date("Y-m-d H:i:s"),
				
				"updated_by"	=>	$this->user_id,
			);

			if($this->general_model->edit("ams_notifications",array("notification_id"=>$notification_id),$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}else{

			$role = $this->input->post("role");
			$description = $this->input->post("description");

			$notification_id = "NTF".date("YmdHis");

			$data_to_save = array(
				"notification_id" 	=>	$notification_id,
				"role"		=>	$role,
				"description"	=> 	$description,
				"created_at"		=> 	date("Y-m-d H:i:s"),
				"updated_at"		=> 	date("Y-m-d H:i:s"),
				"created_by"	=> 	$this->user_id,
				"updated_by"	=>	$this->user_id,
			);

			if($this->general_model->save("ams_notifications",$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}

		}

	}

	function getNotificationsJson(){
		$role = ($this->input->post('role')!= NULL) ? $_POST['role'] : '';
		$description = ($this->input->post('description')!= NULL) ? $_POST['description'] : '';
		
		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$offset = ($page-1)*$limit;
		
		if($role!='' || $description!='' ){
			$params = 
				"role like '%".$role."%' or description like '%".$description."%' " ;
			
			$data_role = $this->general_model->getDataGrid("ams_notifications",$params,($offset),$limit);
			$total = $this->general_model->getdata("ams_notifications",$params)['total'];
		}else{
			$data_role = $this->general_model->getDataGrid("ams_notifications",NULL,($offset),$limit);
			$total = $this->general_model->getdata("ams_notifications")['total'];
		}
		
		$result = array(
			'total'	=> $total,
			'rows'	=> $data_role['data'],
		);

		echo json_encode($result);
	}
	

	function delete(){
		$notification_id = $this->input->post("notification_id");
		if($this->general_model->delete("ams_notifications",array("notification_id"=>$notification_id))){
			echo "success";
		}else{
			echo "failed";
		}

		
	}
}?>