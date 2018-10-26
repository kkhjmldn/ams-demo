<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment_answer extends CI_Controller {
	protected $username = '';
	protected $role_id = '';
	protected $user_id = '';

	public function __construct(){
		parent::__construct();
		$this->load->library(array("template","session","notification"));
		$this->load->model(array("assignment_model","user_model","assignment_answer_model","assignment_answer_reply_model"));
		$this->load->helper('cookie');

		$this->user_id = $this->session->userdata("user_id");
		$this->role_id=  $this->session->userdata("role_id");

	}


	public function save()
	{
		
		$assignment_id = $this->input->post("assignment_id");
		$assignment_answer_id = "ASA".date("YmdHis");
		$file = '';	
		$student_id = $this->input->post("student_id");
		$answer = $this->input->post("answer");

		$file_ = $_FILES['file'];
		//print_r($file);exit;
		$file = '';
		if(($file_['name'])!= NULL){
			$config['upload_path']          = './uploads/';
	        $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|ppt|pptx|mp3|mp4|wav|swf|flv';
	        $config['max_size']             = 0;
	        $config['max_width']            = 0;
	        $config['max_height']           = 0;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('file'))
	        {
	                $error = array('error' => $this->upload->display_errors());

	                print_r($error);
	        }
	        else
	        {
	                $data = array('upload_data' => $this->upload->data());
	                $file = base_url()."uploads/".$data['upload_data']['file_name'];
	        }
	    }


		$data = array(
			'assignment_answer_id' => $assignment_answer_id,
			'assignment_id' => $assignment_id,
			'answer'			=> $answer,
			'created_at'		=> date("Y-m-d H:i:s"),
			'updated_at'		=> date("Y-m-d H:i:s"),
			'file'		=> $file,
			'student_id'	=> $student_id,
			'score'		=> 0
			
		);

		if($this->assignment_answer_model->save($data)){
			$data_assignment = $this->assignment_model->getData(array("a.assignment_id"=>$assignment_id));

			foreach ($data_assignment['data'] as $key => $value) {
				$this->notification->save_notification($value->from,date("Y-m-d H:i:s"),"New Answer assignment submitted!",("assignment"));
			}

			redirect('assignment');
		
		}else{
			
		}

		//echo json_encode($response);
	}

	public function save_reply()
	{
		$assignment_id = $this->input->post("assignment_id");
		$assignment_answer_reply_id = "AAR".date("YmdHis");
		$assignment_answer_id = $this->input->post("assignment_answer_id");

		$file_ = $_FILES['file'];
		//print_r($file);exit;
		$file = '';
		if(($file_['name'])!= NULL){
			$config['upload_path']          = './uploads/';
	        $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|ppt|pptx|mp3|mp4|wav|swf|flv';
	        $config['max_size']             = 0;
	        $config['max_width']            = 0;
	        $config['max_height']           = 0;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('file'))
	        {
	                $error = array('error' => $this->upload->display_errors());

	                print_r($error);
	        }
	        else
	        {
	                $data = array('upload_data' => $this->upload->data());
	                $file = base_url()."uploads/".$data['upload_data']['file_name'];
	        }
	    }
		$score = $this->input->post("score");
		$student_id = $this->input->post("student_id");
		$reply = $this->input->post("reply");
		$data = array(
			'reply_id'	=> $assignment_answer_reply_id,
			'assignment_answer_id' => $assignment_answer_id,
			'assignment_id' => $assignment_id,
			'reply'			=> $reply,
			'from'			=> $this->user_id,
			'to'			=> $student_id,
			'time'			=> date("Y-m-d H:i:s"),
			'created_at'		=> date("Y-m-d H:i:s"),
			'updated_at'		=> date("Y-m-d H:i:s"),
			'file'		=> $file,
		);

		if($score!=''){
			$this->assignment_answer_model->save_score($assignment_answer_id,array("a.score"=>$score));
			$this->notification->save_notification($student_id,date("Y-m-d H:i:s"),"You Get New Score!",("assignment"));
		}

		if($this->assignment_answer_reply_model->save_reply($data)){
			$this->notification->save_notification($student_id,date("Y-m-d H:i:s"),"You Get New Reply!",("assignment"));

		}else{
			
		}

		redirect('assignment');

		
	}

	


}?>