<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {

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
		$data_categories = $this->general_model->getdata("ams_categories");
		
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

			'breadcrumb' => array('Home'=> base_url(), 'Courses' => '#' ),
			'title' => "Courses",
			'data_categories' => $data_categories
		);

		$this->load->view("layouts2/head",$data);
		$this->load->view("course/index");
		$this->load->view("layouts2/footer");
	}

	public function new()
	{
		$category_id = ($this->input->get('i')!='') ? $_GET['i'] : ''  ;
		$course_id = $this->uri->segment(3)
		  ;

		if($category_id!=''){
			$data_categories = $this->general_model->getdata("ams_categories",array("category_id"=>$category_id));
		}else{
			$data_categories = $this->general_model->getdata("ams_categories");
		}

		if($course_id!=''){
			$data_courses = $this->general_model->getdata("ams_courses",array("course_id"=>$course_id));
		}else{
			$data_courses = NULL;
		}

		$data_instructors = $this->general_model->getdata("ams_users",array("role_id"=>2));

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
				base_url('assets/dropzone/dropzone.css'),
				
				base_url('assets/datepicker/bootstrap-datetimepicker.min.css'),
				),
			'js'	=> array(
				base_url('assets/js/jquery-1.7.2.min.js'),
				base_url('assets/js/bootstrap.js'),
				base_url('assets/chosen/chosen.jquery.js'),
				base_url("assets/ckeditor/ckeditor.js"),
				base_url('assets/summernote/dist/summernote.js'),
				base_url('assets/fileinput/js/fileinput.min.js'),
				
				base_url('assets/notify/bootstrap-notify.js'),
				base_url('assets/dropzone/dropzone.js'),
				base_url('assets/jquery-mask/jquery.mask.min.js'),
				base_url('assets/datepicker/bootstrap-datetimepicker.min.js'),
			)

		);
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Categories' => site_url('categories'),'Manage Course' => '#'),
			'title' => "Manage Course",	
			'data_categories'	=> 	$data_categories['data'],
			'data_courses'		=>	$data_courses['data'],
			'data_instructors'	=> $data_instructors['data']		
			
		);


		$this->load->view('head',$data_assets);
		if($this->role_id==2){
			$this->load->view("page/courses/add",$data);
		}elseif($this->role_id==3){
			redirect("../web");
		}
		$this->load->view('footer');
	}

	function save(){
		$course_id = $this->input->get("course_id");
		if($course_id!=NULL){
			$course = $this->input->post("course");
			$category_id = $this->input->post("category_id");
			$teacher_id = $this->input->post("teacher_id");
			
			$start_time = date('Y-m-d',strtotime($this->input->post("start_time")));
			$end_time = date('Y-m-d',strtotime($this->input->post("end_time")));
			$file_ = $_FILES['file'];
			//print_r($file_);exit;
			$file = '';
			if(($file_['name'])!= NULL){
				$config['upload_path']          = $_SERVER["DOCUMENT_ROOT"].'/ams-demo/assets/uploads/courses';
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
		                $file = "assets/uploads/".$course_id."-".$data['upload_data']['file_name'];
		        }
		    }
			$data = array(
				
				'course'	=> $course,	
				'category_id'	=> $category_id,	
				'teacher_id'	=> $teacher_id,	
				'start_time'	=> $start_time,	
				'end_time'	=> $end_time,	
				'file'	=> $file,					
				'updated_by'	=> '1',//$this->user_id,					
				'updated_at'	=> date("Y-m-d H:i:s"),	
			);

			if($this->general_model->edit("ams_courses",array("course_id"=>$course_id),$data)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}else{
			$course = $this->input->post("course");

			$category_id = $this->input->post("category_id");
			$teacher_id = $this->input->post("teacher_id");
			
			$start_time = date('Y-m-d',strtotime($this->input->post("start_time")));
			$end_time = date('Y-m-d',strtotime($this->input->post("end_time")));
			
			$file_ = $_FILES['file'];
			//print_r($file_);exit;
			$file = '';
			if(($file_['name'])!= NULL){
				$config['upload_path']          = $_SERVER["DOCUMENT_ROOT"].'/ams-demo/assets/uploads/courses';
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
		                $file = "assets/uploads/".$course_id."-".$data['upload_data']['file_name'];
		        }
		    }
			$data = array(
				'course_id'  => 'CRS'.date('YmdHis'),
				'course'	=> $course,	
				'category_id'	=> $category_id,	
				'teacher_id'	=> $teacher_id,	
				'start_time'	=> $start_time,	
				'end_time'	=> $end_time,	
				'file'	=> $file,	
				'created_by'	=> '1',//$this->user_id,	
				'updated_by'	=> '1',//$this->user_id,	
				'created_at'	=> date("Y-m-d H:i:s"),	
				'updated_at'	=> date("Y-m-d H:i:s"),	
			);
			

			if($this->general_model->save("ams_courses",$data)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}

	}

	public function getCourseJson()
	{
		$id = ($this->input->post('id')!= NULL) ? $_POST['id'] : '';


		$name = ($this->input->post('category')!= NULL) ? $_POST['category'] : '';
		
		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$offset = ($page-1)*$limit;
		
		if($name!=''  ){
			$params = 
			"course like '%".$name."%'   " ;
			
			$data_group = $this->general_model->getDataGrid("ams_categories",$params,($offset),$limit);
			$total = $this->general_model->getdata("ams_categories",$params)['total'];
		}else{
			$data_group = $this->general_model->getDataGrid("ams_categories",NULL,($offset),$limit);
			$total = $this->general_model->getdata("ams_categories")['total'];
		}
		foreach ($data_group['data'] as $key => $value) {
			
			$par = array("category_id"=>$value->category_id);
			$data_personel = $this->general_model->query("SELECT * FROM ams_courses a LEFT JOIN ams_users b ON a.teacher_id = b.user_id WHERE a.category_id = '".$value->category_id."' AND b.role_id  = 2 ");

			if($data_personel['total'] > 0){
				//$value->state = 'closed';
				$value->total_member = $data_personel['total'];
				foreach ($data_personel['data'] as $k => $val) {
					$val->category_id = $val->course_id;
					$val->category = $val->course;
					$val->action = '  <button type="button" onclick="dropPersonel('."'".$val->course_id."'".')" class="easyui-linkbutton"><i class="fa fa-trash"></i> Drop</button>';
				}
				$value->children = $data_personel['data'];
				$value->state = 'closed';
			}else{
				
				$value->total_member =0;
			}

			
			
			
		}
		$result = array(
			'total'	=> $total,
			'rows'	=> $data_group['data'],
		);

		echo json_encode($result);
	}


	function get_course_by_category_id()
	{
		$category_id = $this->input->post('category_id');
		$data_courses = $this->general_model->getdata("ams_courses",array("category_id" => $category_id));

		echo json_encode($data_courses);
	}

	function getCourseJSONComboBox()
	{
		//$category_id = $this->input->post('category_id');
		$data_courses = $this->general_model->getdata("ams_courses");

		echo json_encode($data_courses['data']);
	}

	function upload_file()
	{
		$files = array();

			$ds          = DIRECTORY_SEPARATOR;  //1
 
			$storeFolder ="../../uploads/courses";   //2
			 
			if (!empty($_FILES)) {
			     
			    $tempFile = $_FILES['file']['tmp_name'];          //3             
			      
			    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
			     
			    $targetFile =  $targetPath. $_FILES['file']['name'];  //5
			 	

			    move_uploaded_file($tempFile,$targetFile); //6
			   // chmod($targetFile,777) ;
			   

			    $data = array(
			    	'file_id' => 'FMT'.date("YmdHis"),
			    	'file'		=> $_FILES['file']['name']
			    );

			     echo json_encode($data);
			    
			}
	}

	function delete()
	{
		$course_id = $this->input->post("course_id");
		if($this->general_model->delete("ams_courses",array("course_id"=>$course_id))){
			echo "success";
		}else{
			echo "failed";
		}
	}

	function test()
	{
		print_r($_SERVER["DOCUMENT_ROOT"]);
	}

	public function getCourseJsonTree()
	{
		$id = ($this->input->post('id')!= NULL) ? $_POST['id'] : '';


		$name = ($this->input->post('category')!= NULL) ? $_POST['category'] : '';
		
		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$offset = ($page-1)*$limit;
		
		if($name!=''  ){
			$params = 
			"course like '%".$name."%'   " ;
			
			$data_group = $this->general_model->getDataGrid("ams_categories",$params,($offset),$limit);
			$total = $this->general_model->getdata("ams_categories",$params)['total'];
		}else{
			$data_group = $this->general_model->getDataGrid("ams_categories",NULL,($offset),$limit);
			$total = $this->general_model->getdata("ams_categories")['total'];
		}
		$data_tree = array();
		if($this->role_id==3){
			$data_tree[0] = array('id'=>'all','text' => 'All Assignment','state'=>'open');
		}elseif ($this->role_id==2) {
			$data_tree[0] = array('id'=>'all_answer','text' => 'All Answers','state'=>'open');
			$data_tree[1] = array('id'=>'all','text' => 'All Assignment','state'=>'open');
		}
		foreach ($data_group['data'] as $key => $value) {
			$value->id = $value->category_id;
			$value->text = $value->category;
			$par = array("category_id"=>$value->category_id);
			$data_personel = $this->general_model->query("SELECT * FROM ams_courses a LEFT JOIN ams_users b ON a.teacher_id = b.user_id WHERE a.category_id = '".$value->category_id."' AND b.role_id  = 2 ");

			if($data_personel['total'] > 0){
				//$value->state = 'closed';
				$value->total_member = $data_personel['total'];
				foreach ($data_personel['data'] as $k => $val) {
					$val->category_id = $val->course_id;
					$val->category = $val->course;
					$val->id = $val->course_id;
					$val->text = $val->course;
			
				}
				$value->children = $data_personel['data'];
				$value->state = 'closed';
			}else{
				
				$value->total_member =0;
			}
			if($this->role_id==3){
				$data_tree[($key+1)] =$value;
			}elseif ($this->role_id==2) {
				$data_tree[($key+2)] =$value;
			}
			
			
		}
		
		array_push($data_tree, $data_group['data']);
		$result = array(
			'total'	=> $total,
			'rows'	=> $data_tree,
		);

		echo json_encode($result['rows']);
	}




}?>