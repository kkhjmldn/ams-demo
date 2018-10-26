<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

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

			'breadcrumb' => array('Home'=> base_url(), 'Categories' => '#'),
			'title' => "Categories",
			'data_categories' => $data_categories
		);

		$this->load->view("layouts2/head",$data);
		$this->load->view("category/index");
		$this->load->view("layouts2/footer");
		
	}
	

	public function new()
	{
		$category_id = ($this->input->get('i')!='') ? $_GET['i'] : ''  ;

		if($category_id!=''){
			$data_categories = $this->general_model->getdata("ams_categories",array("category_id"=>$category_id));
		}else{
			$data_categories = NULL;
		}

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
				base_url('assets/notify/bootstrap-notify.js'),
				base_url('assets/datepicker/bootstrap-datetimepicker.min.js'),
			)

		);
		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Categories' => site_url('categories'),'Manage Category' => '#'),
			'title' => "Manage Category",			
			
		);


		$this->load->view('head',$data_assets);
		if($this->role_id==2){
			$this->load->view("page/categories/add",$data);
		}elseif($this->role_id==3){
			redirect("../web");
		}
		$this->load->view('footer');
	}

	function save()
	{
		$category_id = $this->input->get('category_id');
		if($category_id!=NULL){

			$category = $this->input->post("category");
			$description = $this->input->post("description");

			

			$data_to_save = array(
				
				"category"		=>	$category,
				"description"	=> 	$description,
			
				"updated_at"		=> 	date("Y-m-d H:i:s"),
				
				"updated_by"	=>	$this->user_id,
			);

			if($this->general_model->edit("ams_categories",array("category_id"=>$category_id),$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}
		}else{

			$category = $this->input->post("category");
			$description = $this->input->post("description");

			$category_id = "CTG".date("YmdHis");

			$data_to_save = array(
				"category_id" 	=>	$category_id,
				"category"		=>	$category,
				"description"	=> 	$description,
				"created_at"		=> 	date("Y-m-d H:i:s"),
				"updated_at"		=> 	date("Y-m-d H:i:s"),
				"created_by"	=> 	$this->user_id,
				"updated_by"	=>	$this->user_id,
			);

			if($this->general_model->save("ams_categories",$data_to_save)){
				echo json_encode("success");
			}else{
				echo json_encode("failed");
			}

		}

	}

	function getCategoriesJson(){
		$category = ($this->input->post('category')!= NULL) ? $_POST['category'] : '';
		$description = ($this->input->post('description')!= NULL) ? $_POST['description'] : '';
		
		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$offset = ($page-1)*$limit;
		
		if($category!='' || $description!='' ){
			$params = 
				"category like '%".$category."%' or description like '%".$description."%' " ;
			
			$data_role = $this->general_model->getDataGrid("ams_categories",$params,($offset),$limit);
			$total = $this->general_model->getdata("ams_categories",$params)['total'];
		}else{
			$data_role = $this->general_model->getDataGrid("ams_categories",NULL,($offset),$limit);
			$total = $this->general_model->getdata("ams_categories")['total'];
		}
		
		$result = array(
			'total'	=> $total,
			'rows'	=> $data_role['data'],
		);

		echo json_encode($result);
	}

	function test(){
		$this->notification->notification_success("success");
	}

	function getCategory(){
		$id = $this->input->post("id");
		$data_categories = $this->general_model->getdata("ams_categories",array("category_id"=>$id));

		echo json_encode($data_categories);
	}

	function getCategoriesJSONCOmboBox()
	{
		$data_categories = $this->general_model->getdata("ams_categories");

		echo json_encode($data_categories['data']);
	}

	function delete(){
		$category_id = $this->input->post("category_id");
		if($this->general_model->delete("ams_categories",array("category_id"=>$category_id))){
			echo "success";
		}else{
			echo "failed";
		}

		
	}

}?>