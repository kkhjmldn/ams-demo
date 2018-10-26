<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {

	protected $username = '';
	protected $role_id = '';
	protected $user_id = '';


	public function __construct(){
		parent::__construct();
		$this->load->library(array("session","notification"));
		$this->load->model(array("general_model"));
		$this->load->helper('cookie');
		$this->user_id = $this->session->userdata("user_id");
		$this->role_id = $this->session->userdata("role_id");

	}

	public function index()
	{
		if($this->user_id == NULL)
		{
			redirect("../web");
		}
		//print_r($this->user_id);exit;
		if($this->role_id == 2){
			$data_schedule = $this->schedule_model->getDataScheduleFromMe(array("a.instructure_id"=>$this->user_id));
		}elseif($this->role_id==3){
			$data_schedule = $this->schedule_model->getDataScheduleForMe($this->user_id);
		}

		
		$data_assets = array(

			'css' => array(
				base_url('assets/css/bootstrap.min.css'),
				base_url('assets/css/bootstrap-responsive.min.css'),
				"http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600",
				base_url('assets/css/font-awesome.css'),
				base_url('assets/css/style.css'),
				base_url('assets/css/pages/dashboard.css'),
				base_url('assets/datatable/datatables.css'),
				base_url('assets/js/full-calendar/fullcalendar.css'),
				),
			'js'	=> array(
				base_url('assets/js/jquery-1.7.2.min.js'),
				base_url('assets/js/bootstrap.js'),
				base_url('assets/datatable/datatables.js'),
				base_url('assets/js/full-calendar/fullcalendar.min.js'),
			)

		);
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Schedule' => site_url('shcedule')),
			'title' => "Schedules",
			'data_schedule' => $data_schedule
		);


		$this->load->view('head',$data_assets);
		if($this->role_id==2){
			$this->load->view("page/schedule/index",$data);
		}elseif($this->role_id==3){
			$this->load->view("page/schedule/index-student",$data);
		}
		$this->load->view('footer');
	}

	public function new()
	{
		$data_student = $this->user_model->getDataUserStudent();

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
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Schedule' => site_url('shcedule')),
			'title' => "Add Schedule",
			'data_student' => $data_student['data'],
			
		);


		$this->load->view('head',$data_assets);
	
			$this->load->view("page/schedule/add",$data);
		
		$this->load->view('footer');
	}

	public function edit()
	{
		$schedule_id = $_POST['schedule_id'];
		$data_schedule = $this->schedule_model->getData(array("a.schedule_id"=>$schedule_id));
		$data_student = $this->user_model->getDataUserStudent();

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

		

		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Schedule' => site_url('shcedule')),
			'title' => "Edit Schedule",
			'data_schedule' => $data_schedule,
			'data_student' => $data_student['data'],
			'participants' => array_filter(explode(",", $data_schedule['data'][0]->student_id)),
			
		);


		$this->load->view('head',$data_assets);
	
			$this->load->view("page/schedule/edit",$data);
		
		$this->load->view('footer');
	}

	public function approval()
	{
		$schedule_id = $_POST['schedule_id'];
		$data_schedule = $this->schedule_model->getData(array("a.schedule_id"=>$schedule_id));
		$data_instructure = $this->user_model->getDataUserInstructure();

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
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Schedule' => site_url('shcedule'),'Request Schedule'=>site_url('shcedule/new_request')),
			'title' => "Request Schedule",
			'data_schedule'	=> $data_schedule,
			'data_instructure' => $data_instructure['data'],
			'instructure' => $data_schedule['data'][0]->instructure_id
		);


		$this->load->view('head',$data_assets);
	
			$this->load->view("page/schedule/approval-schedule",$data);
		
		$this->load->view('footer');
	}

	public function new_request()
	{
		$data_instructure = $this->user_model->getDataUserInstructure();

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
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Schedule' => site_url('shcedule'),'Request Schedule'=>site_url('shcedule/new_request')),
			'title' => "Request Schedule",
			'data_instructure' => $data_instructure['data'],
			
		);


		$this->load->view('head',$data_assets);
	
			$this->load->view("page/schedule/add-request",$data);
		
		$this->load->view('footer');
	}

	public function edit_request()
	{
		$schedule_id = $_POST['schedule_id'];
		$data_schedule = $this->schedule_model->getData(array("a.schedule_id"=>$schedule_id));
		$data_instructure = $this->user_model->getDataUserInstructure();

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
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Schedule' => site_url('shcedule'),'Request Schedule'=>site_url('shcedule/new_request')),
			'title' => "Request Schedule",
			'data_schedule'	=> $data_schedule,
			'data_instructure' => $data_instructure['data'],
			'instructure' => $data_schedule['data'][0]->instructure_id
		);


		$this->load->view('head',$data_assets);
	
			$this->load->view("page/schedule/edit-request",$data);
		
		$this->load->view('footer');
	}

	public function add_process()
	{	
		$students = $_POST['students'];
		$student_id = '';

		foreach ($students as $key => $value) {
			if(($key+1)==sizeof($students)){
				$student_id.=$value;
			}else{
				$student_id.=$value.",";
			}
			$schedule_id = "SCL".date("YmdHis").$key;
		}
		$data = array(
			'schedule_id' => $schedule_id,
			'instructure_id' => $this->user_id,
			'student_id'	=> $student_id,
			'schedule'		=> $_POST['schedule'],
			'start_time'	=> date("Y-m-d H:i:s",strtotime($_POST['start_time'])),
			'end_time'	=> date("Y-m-d H:i:s",strtotime($_POST['end_time'])),
			'created_at'	=> date("Y-m-d H:i:s"),
			'updated_at'	=> date("Y-m-d H:i:s"),
			'created_by'	=> $this->user_id,
			'updated_by'	=> $this->user_id,
			'approved'		=> 1
		);

		if($this->schedule_model->save($data)){

			foreach ($students as $key => $value) {

				$this->notification->save_notification($value,date("Y-m-d H:i:s"),"You Get New Schedule Information!",("schedule"));
			}
			
			
		}

		redirect('schedule');
	}

	public function edit_process()
	{	
		$schedule_id = $_POST['schedule_id'];

		$students = $_POST['students'];
		
		$student_id = '';

		foreach ($students as $key => $value) {
			if(($key+1)==sizeof($students)){
				$student_id.=$value;
			}else{
				$student_id.=$value.",";
			}
			
		}
		//print_r($student_id);exit;
		$data = array(
			
			'instructure_id' => $this->user_id,
			'student_id'	=> $student_id,
			'schedule'		=> $_POST['schedule'],
			'start_time'	=> date("Y-m-d H:i:s",strtotime($_POST['start_time'])),
			'end_time'	=> date("Y-m-d H:i:s",strtotime($_POST['end_time'])),			
			'updated_at'	=> date("Y-m-d H:i:s"),			
			'updated_by'	=> $this->user_id,
			'approved'		=> 1
		);

		if($this->schedule_model->update($data,array("a.schedule_id"=>$schedule_id))){

			
			
			
		}

		redirect('schedule');
	}

	public function add_request_process()
	{	
		$instructure_id = $_POST['instructure_id'];			
		$schedule_id = "SCL".date("YmdHis");
		
		$data = array(
			'schedule_id' => $schedule_id,
			'student_id' => $this->user_id,
			'instructure_id'	=> $instructure_id,
			'schedule'		=> $_POST['schedule'],
			'start_time'	=> date("Y-m-d H:i:s",strtotime($_POST['start_time'])),
			'end_time'	=> date("Y-m-d H:i:s",strtotime($_POST['end_time'])),
			'created_at'	=> date("Y-m-d H:i:s"),
			'updated_at'	=> date("Y-m-d H:i:s"),
			'created_by'	=> $this->user_id,
			'updated_by'	=> $this->user_id,
			'approved'		=> 0
		);

		if($this->schedule_model->save($data)){

			

				$this->notification->save_notification($instructure_id,date("Y-m-d H:i:s"),"You Get New Request Schedule!",("schedule"));
			
			
			
		}

		redirect('schedule');
	}

	public function edit_request_process()
	{	
		$schedule_id = $_POST['schedule_id'];

		$instructure_id = $_POST['instructure_id'];
		
	
		//print_r($student_id);exit;
		$data = array(
			
			'student_id' => $this->user_id,
			'instructure_id'	=> $instructure_id,
			'schedule'		=> $_POST['schedule'],
			'start_time'	=> date("Y-m-d H:i:s",strtotime($_POST['start_time'])),
			'end_time'	=> date("Y-m-d H:i:s",strtotime($_POST['end_time'])),			
			'updated_at'	=> date("Y-m-d H:i:s"),			
			'updated_by'	=> $this->user_id,
			'approved'		=> 0
		);

		if($this->schedule_model->update($data,array("a.schedule_id"=>$schedule_id))){

			

			
			
			
		}

		redirect('schedule');
	}

	


}?>