<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment extends CI_Controller {

	protected $username = '';
	protected $role_id = '';
	protected $user_id = '';


	public function __construct(){
		parent::__construct();
		$this->load->library(array("session","notification","logs"));
		$this->load->model(array("general_model"));
		$this->load->helper('cookie');
		$this->user_id = $this->session->userdata("user_id");
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

			'breadcrumb' => array('Home'=> base_url(), 'Assignment' => site_url('assignment')),
			'title' => "Assignments",
			'data_assignment' => $data_assignment
		);
		$this->logs->save($this->user_id,"Assignment Page");
		$this->load->view("layouts2/head",$data);
		if($this->role_id ==3 ){
			$this->load->view("assignment/index");
		}else{
			$this->load->view("assignment/index-instructor");
		}
		$this->load->view("layouts2/footer");
		
	}

	public function new()
	{
		

		$data_assets = array(

			'css' => array(
				base_url('assets/css/bootstrap.min.css'),
				base_url('assets/css/bootstrap-responsive.min.css'),
				"http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600",
				base_url('assets/css/font-awesome.css'),
				base_url('assets/css/style.css'),
				base_url('assets/css/pages/dashboard.css'),
				base_url('assets/chosen/chosen.css'),
				base_url('assets/summernote/dist/summernote.css'),
				base_url('assets/fileinput/css/fileinput.min.css'),
				base_url('assets/datepicker/bootstrap-datetimepicker.min.css'),
				),
			'js'	=> array(
				base_url('assets/js/jquery-1.7.2.min.js'),
				base_url('assets/js/bootstrap.js'),
				base_url('assets/chosen/chosen.jquery.js'),
				base_url("assets/ckeditor/ckeditor.js"),
				base_url('assets/summernote/dist/summernote.js'),
				base_url('assets/fileinput/js/fileinput.min.js'),
				base_url('assets/datepicker/bootstrap-datetimepicker.min.js'),
			)

		);


		//GET DATA STUDENTS ==> ROLE_ID=3
		$data_student = $this->user_model->getDataUserStudent();
		$data_group = $this->user_model->getDataUserStudent();



		
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Assignment' => site_url('assignment'),'New' => site_url('assignment/new'),
			),
			'title' => "Add New Assignment",
			'data_student' => $data_student['data'],
			
		);
		$this->load->view('head',$data_assets);
		$this->load->view("page/assignment/add",$data);
		$this->load->view('footer');
	}

	public function delete()
	{

	}

	public function save()
	{
		
		$assignment_id = $this->input->post("assignment_id");
		$assignment_answer_id = "ASA".date("YmdHis");
		$file = '';	
		$student_id = $this->user_id;
		$answer = $this->input->post("answer");

		$file_ = $_FILES['file'];
		//print_r($file);exit;
		$file = '';
		if(($file_['name'])!= NULL){
			$config['upload_path']          = './assets/uploads/answer/';
	        $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|ppt|pptx|mp3|mp4|wav|swf|flv';
	        $config['max_size']             = 0;
	        $config['max_width']            = 0;
	        $config['max_height']           = 0;

	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);

	        if ( ! $this->upload->do_upload('file'))
	        {
	                $error = array('error' => $this->upload->display_errors());

	                print_r($error);
	        }
	        else
	        {
	                $data = array('upload_data' => $this->upload->data());
	                $file = "assets/uploads/answer/".$data['upload_data']['file_name'];
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

		if($this->general_model->save("ams_assignment_answers",$data)){
			$data_assignment = $this->general_model->getdata("ams_assignments a",array("a.assignment_id"=>$assignment_id));

			foreach ($data_assignment['data'] as $key => $value) {
				$this->notification->save_notification($value->from,date("Y-m-d H:i:s"),"New Answer assignment submitted!",("assignment"));
			}
			$this->logs->save($this->user_id,"Save Assignment Answer");
			echo json_encode("success");
		
		}else{
			echo json_encode("failed");
		}

		//echo json_encode($response);
	}

	public function add()
	{
		
		$assignment_id = $this->input->get('assignment_id');

		if($assignment_id=='')
		{
			$assignee_id = $this->input->post("student_id");					
			$assignment_id = "ASS".date("YmdHis");
			$file_ = $_FILES['file'];
			
			$file = '';
			if(($file_['name'])!= NULL){
				$config['upload_path']          = './assets/uploads/assignment/';
		        $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|ppt|pptx|mp3|mp4|wav|swf|flv';
		        $config['max_size']             = 0;
		        $config['max_width']            = 0;
		        $config['max_height']           = 0;

		        $this->load->library('upload', $config);
		        $this->upload->initialize($config);
		        if ( ! $this->upload->do_upload('file'))
		        {
		                $error = array('error' => $this->upload->display_errors());

		                print_r($error);
		        }
		        else
		        {
		                $data = array('upload_data' => $this->upload->data());
		                $file = "assets/uploads/assignment/".$data['upload_data']['file_name'];
		        }
		    }
	      
			$content = $this->input->post("content");
			
			$data = array(
				'assignment_id' => $assignment_id,
				'from'			=> $this->user_id,
				'course_id'			=> $this->input->post('course_id'),
				'name'			=> $this->input->post('name'),			
				'content'		=> $content,
				'file'			=> $file,
				'minimum_grade'	=> $this->input->post('minimum_grade'),
				'end_time'		=> date("Y-m-d H:i:s",strtotime($this->input->post('end_time'))),
				'completion_type' => 1
			);

			if($this->general_model->save("ams_assignments",$data)){

				foreach ($assignee_id as $key => $value) {
					$data_target = array(
						"assignment_target_id" => 'AST'.date('YmdHis').$key,
						'assignment_id' => $assignment_id,
						'student_id'	=> $value
					);
					if($this->general_model->save("ams_assignment_targets",$data_target)){
						$this->notification->save_notification($value,date("Y-m-d H:i:s"),"You Get New assignment!",("assignment"));
						$data_student = $this->user_model->getData(array("a.user_id"=>$value));
						$this->kirim_email($data_student['data'][0]->email,site_url('assignment'));
					}
					
				}
				$this->logs->save($this->user_id,"Save Assignment");
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}else{
			$assignee_id = $this->input->post("student_id");					
			
			$file_ = $_FILES['file'];
			
			$file = '';
			if(($file_['name'])!= NULL){
				$config['upload_path']          = './assets/uploads/assignment/';
		        $config['allowed_types']        = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|ppt|pptx|mp3|mp4|wav|swf|flv';
		        $config['max_size']             = 0;
		        $config['max_width']            = 0;
		        $config['max_height']           = 0;

		        $this->load->library('upload', $config);
		        $this->upload->initialize($config);
		        if ( ! $this->upload->do_upload('file'))
		        {
		                $error = array('error' => $this->upload->display_errors());

		                print_r($error);
		        }
		        else
		        {
		                $data = array('upload_data' => $this->upload->data());
		                $file = "assets/uploads/assignment/".$data['upload_data']['file_name'];
		        }
		    }
	      
			$content = $this->input->post("content");
			
			$data = array(
				
				'from'			=> $this->user_id,
				'course_id'			=> $this->input->post('course_id'),
				'name'			=> $this->input->post('name'),			
				'content'		=> $content,
				'file'			=> $file,
				'minimum_grade'	=> $this->input->post('minimum_grade'),
				'end_time'		=> date("Y-m-d H:i:s",strtotime($this->input->post('end_time'))),
				'completion_type' => 1
			);

			if($this->general_model->edit("ams_assignments",array("assignment_id"=>$assignment_id),$data)){
				if($this->general_model->delete("ams_assignment_targets",array("assignment_id"=>$assignment_id))){
					foreach ($assignee_id as $key => $value) {
						$data_target = array(
							"assignment_target_id" => 'AST'.date('YmdHis').$key,
							'assignment_id' => $assignment_id,
							'student_id'	=> $value
						);
						if($this->general_model->save("ams_assignment_targets",$data_target)){
							$this->notification->save_notification($value,date("Y-m-d H:i:s"),"You Get New assignment!",("assignment"));
							$data_student = $this->user_model->getData(array("a.user_id"=>$value));
							$this->kirim_email($data_student['data'][0]->email,site_url('assignment'));
						}
						
					}
				}
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}


		

	}

	public function check()
	{
		$assignment_answer_id = $this->uri->segment(3); 

		$params = array("a.assignment_answer_id" => $assignment_answer_id);
		$data_assignment = $this->assignment_answer_model->getDataAssignmentAnswer($params);
		
		$data_assets = array(

			'css' => array(
				base_url('assets/css/bootstrap.min.css'),
				base_url('assets/css/bootstrap-responsive.min.css'),
				"http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600",
				base_url('assets/css/font-awesome.css'),
				base_url('assets/css/style.css'),
				base_url('assets/css/pages/dashboard.css'),
			
			//	base_url('assets/summernote/dist/summernote.css'),
				base_url('assets/fileinput/css/fileinput.min.css'),
				),
			'js'	=> array(
				base_url('assets/js/jquery-1.7.2.min.js'),
				base_url('assets/js/bootstrap.js'),
				base_url("assets/ckeditor/ckeditor.js"),
				base_url('assets/summernote/dist/summernote.js'),
				base_url('assets/fileinput/js/fileinput.min.js'),
			)

		);

		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Assignment' => site_url('assignment'),'Check Answer' => "#",
			),
			'title' => "Check Answer",
			'data_assignment'	=> $data_assignment['data']
			
		);
		$this->load->view('head',$data_assets);
		$this->load->view("page/assignment/check-answer",$data);
		$this->load->view('footer');
	}

	


	function getAssigmentAnswer($assignment_id)
	{
		
		
		$data_assignment = $this->general_model->query("SELECT * FROM ams_assignment_answers a LEFT JOIN ams_assignments b ON a.assignment_id = b.assignment_id  LEFT JOIN ams_assignment_targets c ON b.assignment_id = c.assignment_id WHERE c.student_id = '".$this->user_id."' AND a.assignment_id = '".$assignment_id."' " );
		return $data_assignment;
	}

	function getAssignmentJsonTree()
	{
		$course_id = $this->input->post('course_id')!='' ? $this->input->post('course_id') : 'all';
		if($course_id=='all')
		{
			if($this->role_id==3){
				$data_assignment = $this->general_model->query("SELECT * FROM ams_assignments a LEFT JOIN ams_assignment_targets b ON a.assignment_id = b.assignment_id where b.student_id = '$this->user_id' ");
			}else if($this->role_id==2){
				$data_assignment = $this->general_model->getdata('ams_assignments', array('from' =>$this->user_id ));
			}
			foreach ($data_assignment['data'] as $key => $value) {
				$value->id=$value->assignment_id;
				$value->text=$value->name;
				$value->tree_node =" <tr><td width='90%' ><a href='#' onclick='load_assignment(".'"'.$value->id.'"'.")'  ><i class='fa fa-file-o' ></i> ".$value->name."</a></td></tr>";
			}

		}
		elseif ($course_id=='all_answer') {
			if($this->role_id==2){
				$data_assignment = $this->general_model->query("SELECT * FROM ams_assignment_answers a LEFT JOIN ams_assignments b ON a.assignment_id = b.assignment_id WHERE b.from ='$this->user_id'");
			}
			foreach ($data_assignment['data'] as $key => $value) {
				$value->id=$value->assignment_answer_id;
				$value->text=$value->name;
				$value->tree_node =" <tr><td width='90%' ><a href='#' onclick='load_assignment_answer(".'"'.$value->id.'"'.")'  ><i class='fa fa-file-o' ></i> ".$value->name."</a></td></tr>";
			}
		}
		else{
			if($this->role_id==3){
				$data_assignment = $this->general_model->query("SELECT * FROM ams_assignments a LEFT JOIN ams_assignment_targets b ON a.assignment_id = b.assignment_id where b.student_id ='$this->user_id' AND a.course_id = '$course_id' ");
			}else if($this->role_id==2){
				$data_assignment = $this->general_model->getdata('ams_assignments', array('from' =>$this->user_id,'course_id'=>$course_id ));
			}
			foreach ($data_assignment['data'] as $key => $value) {
				$value->id=$value->assignment_id;
				$value->text=$value->name;
				$value->tree_node =" <tr><td width='90%' ><a href='#' onclick='load_assignment(".'"'.$value->id.'"'.")'  ><i class='fa fa-file-o' ></i> ".$value->name."</a></td></tr>";
			}
		}


		
		echo json_encode($data_assignment['data']);
	}

	function getAssignmentTargets($assignment_id)
	{
		$data_targets = $this->general_model->query("SELECT * FROM ams_assignment_targets a LEFT JOIN ams_users b ON a.student_id = b.user_id WHERE a.assignment_id ='$assignment_id'");
		return $data_targets;
	}

	function getAssignmentJsonById(){

		$assignment_id = $this->input->post('assignment_id');
		if($this->role_id==3){
			$data_assignment = $this->general_model->query(" SELECT *,a.name as assignment_name , b.name as teacher_name FROM ams_assignments a LEFT JOIN ams_users b ON a.from = b.user_id LEFT JOIN ams_assignment_targets c ON a.assignment_id = c.assignment_id WHERE c.student_id = '".$this->user_id."' AND a.assignment_id = '".$assignment_id."' "); 
			foreach ($data_assignment['data'] as $key => $value) {
				$file = ($value->file!='') ? "<a href='".base_url()."assets/uploads/".$value->file."' target='_blank'>Clik to see File</a>" : '-';
				$answer = $this->getAssigmentAnswer($value->assignment_id);
				$answer_detail = '';
				$action = "<a class='btn btn-primary' style='color:white;' onclick='load_form_answer(".'"'.$value->assignment_id.'"'.")'  ><i class='fa fa-reply'></i> Answer</a>";
				
				if(date("Y-m-d H:i:s") > $value->end_time){
					$action = "<h5><b>This Assignment Has Reached End Time</b></h5><p><a>You are not able to answer</a></p>";
				}else{
					if($answer['total'] > 0){
						$answer_detail = "";
						$action = "<h5><b>You Submitted an Answer</b></h5><p><a href='#' onclick='load_assignment_answer(".'"'.$answer['data'][0]->assignment_answer_id.'"'.")' >Clik to See your Answer</a></p>";
					}
				}
				
				$value->assignment_loader = "<div class='row'><h5><b>Assignment Detail</b></h5></div><div class='row'>  <table width='100%'><tr><td><b>Assignment Name</b></td><td>:</td><td>".$value->assignment_name."</td></tr><tr><td><b>Teacher/Instructor</b></td><td>:</td><td>".$value->teacher_name."</td></tr><tr><td><b>Assign To</b></td><td>:</td><td>".$this->username."</td></tr><tr><td valign='top' ><b>Assignment Content</b></td><td>:</td><td>".$value->content."</td></tr><tr><td><b>Assignment Deadline</b></td><td>:</td><td><span class='text-danger'><b>".$value->end_time."</b></span></td></tr><tr><td><b>File</b></td><td>:</td><td>".$file."</td></tr></table> </div><br /><div class='row'><div id='form_answer_loader'></div></div><div class='row justify-content-center' align='center' ><div class='pull-right'>".$action."</div></div>" ;
			}
		}elseif ($this->role_id==2) {
			$data_assignment = $this->general_model->query(" SELECT *,a.name as assignment_name , b.name as teacher_name FROM ams_assignments a LEFT JOIN ams_users b ON a.from = b.user_id  WHERE a.from = '".$this->user_id."' AND a.assignment_id = '".$assignment_id."' ");
			foreach ($data_assignment['data'] as $key => $value) {
				$assign_to = '';
				$assign_to_id  = $this->getAssignmentTargets($value->assignment_id);
				if(isset($assign_to_id)){
					foreach ($assign_to_id['data'] as $k => $v) {
						$assign_to.= $v->name.",";
					}
				}
				$file = ($value->file!='') ? "<a href='".base_url()."assets/uploads/".$value->file."' target='_blank'>Clik to see File</a>" : '-';
				$answer = $this->getAssigmentAnswer($value->assignment_id);
				$answer_detail = '';
				$action = "<a class='btn btn-primary' style='color:white;' onclick='load_form_answer(".'"'.$value->assignment_id.'"'.")'  ><i class='fa fa-reply'></i> Answer</a>";
				
				if(date("Y-m-d H:i:s") > $value->end_time){
					$action = "<h5><b>This Assignment Has Reached End Time</b></h5><p><a>You are not able to answer</a></p>";
				}else{
					if($answer['total'] > 0){
						$answer_detail = "";
						$action = "<h5><b>You Submitted an Answer</b></h5><p><a href='#' onclick='load_assignment_answer(".'"'.$answer['data'][0]->assignment_answer_id.'"'.")' >Clik to See your Answer</a></p>";
					}
				}
				
				$value->assignment_loader = "<div class='row'><h5><b>Assignment Detail</b></h5></div><div class='row'>  <table width='100%'><tr><td><b>Assignment Name</b></td><td>:</td><td>".$value->assignment_name."</td></tr><tr><td><b>Teacher/Instructor</b></td><td>:</td><td>".$value->teacher_name."</td></tr><tr><td><b>Assign To</b></td><td>:</td><td>".$assign_to."</td></tr><tr><td valign='top' ><b>Assignment Content</b></td><td>:</td><td>".$value->content."</td></tr><tr><td><b>Assignment Deadline</b></td><td>:</td><td><span class='text-danger'><b>".$value->end_time."</b></span></td></tr><tr><td><b>File</b></td><td>:</td><td>".$file."</td></tr></table> </div><br /><div class='row'><div id='form_answer_loader'></div></div><div class='row justify-content-center' align='center' ><div class='pull-right'></div></div>" ;
			}
		}
			
				
		echo json_encode($data_assignment);
	}

	function getAssignmentAnswerJsonById()
	{
		$assignment_answer_id = $this->input->post('assignment_answer_id');
		if($this->role_id==3){
			$data_assignment = $this->general_model->query("SELECT *,a.file as answer_file, b.file as assignment_file,b.name as assignment_name,c.name as teacher_name FROM ams_assignment_answers a LEFT JOIN ams_assignments b ON a.assignment_id = b.assignment_id LEFT JOIN ams_users c ON b.from  = c.user_id  LEFT JOIN ams_assignment_targets d ON b.assignment_id = d.assignment_id WHERE d.student_id = '".$this->user_id."' AND a.assignment_answer_id = '".$assignment_answer_id."' " );
				
			
			foreach ($data_assignment['data'] as $key => $value) {
				$file = ($value->assignment_file!='') ? "<a href='".base_url().$value->assignment_file."' target='_blank'>Clik to see File</a>" : '-';	
				$answer_file = ($value->answer_file!='') ? "<a href='".base_url().$value->answer_file."' target='_blank'>Clik to see File</a>" : '-';	
				$action = "<div class='d-flex flex-row-reverse'><a onclick='edit_answer(".'"'.$value->assignment_answer_id.'"'.")' class='btn btn-default btn-sm p-2'><i class='fa fa-pencil'></i> Edit</a><a onclick='delete_answer(".'"'.$value->assignment_answer_id.'"'.")'class='btn btn-defaul btn-sm p-2'><i class='fa fa-trash'></i> Delete</a></div>";
				
				$value->assignment_loader = "<div class='row'><h5><b>Your Answer Detail</b></h5></div><div class='row'><table width='100%'><tr><td><b>Assignment Name</b></td><td>:</td><td>".$value->assignment_name."</td></tr><tr><td><b>Teacher/Instructor</b></td><td>:</td><td>".$value->teacher_name."</td></tr><tr><td valign='top' ><b>Assignment Content</b></td><td>:</td><td>".$value->content."</td></tr><tr><td><b>Assignment Deadline</b></td><td>:</td><td><span class='text-danger'><b>".$value->end_time."</b></span></td></tr><tr><td><b>Assignment File</b></td><td>:</td><td>".$file."</td></tr><tr><td valign='top' ><b>Answer</b></td><td>:</td><td>".$value->answer."</td></tr><tr><td><b>Answer File</b></td><td>:</td><td>".$answer_file."</td></tr></table> </div><br /><div class='row'><div id='form_answer_loader'></div></div><div class='row justify-content-center' align='center' ><div class='pull-right'></div></div>" ;
			}
		}elseif ($this->role_id==2) {
			$data_assignment = $this->general_model->query("SELECT *,a.file as answer_file, b.file as assignment_file,b.name as assignment_name,c.name as teacher_name FROM ams_assignment_answers a LEFT JOIN ams_assignments b ON a.assignment_id = b.assignment_id  LEFT JOIN ams_assignment_targets d ON b.assignment_id = d.assignment_id LEFT JOIN ams_users c ON d.student_id  = c.user_id  WHERE b.from = '".$this->user_id."' AND a.assignment_answer_id = '".$assignment_answer_id."' " );
				
			
			foreach ($data_assignment['data'] as $key => $value) {
				$file = ($value->assignment_file!='') ? "<a href='".base_url().$value->assignment_file."' target='_blank'>Clik to see File</a>" : '-';	
				$answer_file = ($value->answer_file!='') ? "<a href='".base_url().$value->answer_file."' target='_blank'>Clik to see File</a>" : '-';	
				$action = "<a class='btn btn-success' style='color:white;' onclick='load_form_answer(".'"'.$value->assignment_id.'"'.")'  ><i class='fa fa-reply'></i> Check</a>";
				
				$value->assignment_loader = "<div class='row'><h5><b>Answer Detail</b></h5></div><div class='row'><table width='100%'><tr><td width='30%' ><b>Assignment Name</b></td><td>:</td><td>".$value->assignment_name."</td></tr><tr><td  width='30%'><b>StudentName</b></td><td>:</td><td>".$value->teacher_name."</td></tr><tr><td  width='30%' valign='top' ><b>Assignment Content</b></td><td>:</td><td>".$value->content."</td></tr><tr><td  width='30%'><b>Assignment Deadline</b></td><td>:</td><td><span class='text-danger'><b>".$value->end_time."</b></span></td></tr><tr><td  width='30%'><b>Assignment File</b></td><td>:</td><td>".$file."</td></tr><tr><td valign='top' ><b>Answer</b></td><td>:</td><td>".$value->answer."</td></tr><tr><td><b>Answer File</b></td><td>:</td><td>".$answer_file."</td></tr></table> </div><br /><div class='row'><div id='form_answer_loader'></div></div><div class='row justify-content-center' align='center' >".$action."<div class='pull-right'></div></div>" ;
			}
		}
		echo json_encode($data_assignment);
	}

	function email()
	{
		$this->kirim_email("kkhjmldn@gmail.com",site_url('assignment'));
	}

	function kirim_email($to = NULL,$link = NULL)
	{
		
    	$ipaddress = $this->input->ip_address();

		ini_set("SMTP","ssl://smtp.gmail.com");
		ini_set("smtp_port","465");

		
		$config['useragent']    = 'CodeIgniter';
		$config['protocol']     = 'smtp';
		$config['smtp_host']    = 'ssl://smtp.googlemail.com';
		$config['smtp_user']    = 'mail.ams.demo@gmail.com'; // Your gmail id
		$config['smtp_pass']    = "AmsAms1234"; // Your gmail app Password
		$config['smtp_port']    = 465;
		$config['wordwrap']     = TRUE;    
		$config['wrapchars']    = 76;
		$config['mailtype']     = 'html';
		$config['charset']      = 'iso-8859-1';
		$config['validate']     = FALSE;
		$config['priority']     = 3;
		$config['newline']      = "\r\n";
		$config['crlf']         = "\r\n";

		$this->load->library('email');
		$this->email->initialize($config);
		//$this->load->library('email',$config);
		//$link = site_url("peserta/verifyme")."/"."PES22424";
		$this->email->from('mail.ams.demo@gmail.com', 'Admin')
						->to($to)
						//->to($to)
						->subject('Notificaton Email')
						->message("You just got a new Assignment. For more detail please click  \r\n http://".$ipaddress.$link);
		// 				->send();
		
		if($this->email->send())
	     {
	       
	     }
	     else
	     {
	      show_error($this->email->print_debugger());
	     }


	}

}
