<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {

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
		
		
		$data_users= $this->general_model->getdata("ams_users","user_id <> ".$this->user_id);
		$data = array(
			'css' => array(
						base_url("assets/breadcrumb/breadcrumb.css"),
						base_url("assets/easyui/themes/bootstrap/easyui.css"),
					),
			'js'	=> array(
						base_url("assets/easyui/jquery.easyui.min.js"),
						base_url('assets/notify/bootstrap-notify.js'),
					),

			'breadcrumb' => array('Home'=> base_url(), 'All Messages' => '#'),
			'title' => "All Messages",
			'data_users'	=> $data_users,
			'user_id'	=> $this->user_id,
			
		);

		$this->load->view("layouts2/head",$data);
		$this->load->view("message/index");
		$this->load->view("layouts2/footer");
		
	}

	public function get_message()
	{
		
		$senderid=$this->user_id;

		$receiverid=$this->input->get("receiver_id");
		$data=$this->general_model->query("select * from ams_chat  where (sender_id='$senderid' and receiver_id='$receiverid') or (sender_id='$receiverid' and receiver_id='$senderid')  order by time ASC");
		echo json_encode($data);
	}

	function send_message()
	{
		$receiver_id = $this->input->post('receiver_id');
		$message = $this->input->post('message');
		$data=array(
			'chat_id' => 'MSG'.date('YmdHis'),
			'message'	=> $message,
			'sender_id'	=> $this->user_id,
			'receiver_id' => $receiver_id,
			'time'		=> date('Y-m-d H:i:s'),
			'is_read' => 0,
			'file' => ''
		); 
		if($this->general_model->save("ams_chat",$data)){
			echo "success";
		}else{
			echo "failed";
		}
	}

	
	

	function delete(){
		$notification_id = $this->input->post("notification_id");
		if($this->general_model->delete("ams_notifications",array("notification_id"=>$notification_id))){
			echo "success";
		}else{
			echo "failed";
		}

		
	}

	function getUnreadMessages()
	{
		$receiver_id = $this->input->post("receiver_id");
		$sender_id = $this->input->post("sender_id");
		if($sender_id==NULL){
			$data = $this->general_model->getdata("ams_chat a",array("a.receiver_id"=>$receiver_id,"a.is_read"=>0));
		}else{
			$data = $this->MModel->getdata("ams_chat a",array("a.receiver_id"=>$receiver_id,"a.sender_id"=>$sender_id,"a.is_read"=>0));
		}
		echo json_encode($data);
	}
}?>