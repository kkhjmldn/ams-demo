<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
	protected $username = '';
	protected $role_id = '';
	protected $user_id = '';
 
	public function __construct(){
		parent::__construct();
		$this->load->library(array("session","notification","logs"));
		$this->load->helper("cookie");
		$this->load->model(array("general_model"));
			$this->user_id = $this->session->userdata("user_id");
		$this->role_id = $this->session->userdata("role_id");

	}


	public function index()
	{
		
		$this->logs->save($this->user_id,"Homepage");
		$this->load->view('layouts2/head');
		 
		$this->load->view('layouts2/footer');
	}
	public function dashboard()
	{
		$data = array(
				'css' => array(
						base_url("assets/breadcrumb/breadcrumb.css"),
						base_url("assets/easyui/themes/bootstrap/easyui.css"),
						
						base_url("assets/fonts/dashboard.woff"),
						base_url("assets/dashboard/sb-admin-2.css"),
					),
			'js'	=> array(
						base_url("assets/easyui/jquery.easyui.min.js"),
						base_url('assets/notify/bootstrap-notify.js'),
						base_url('assets/raphael/raphael.min.js'),
						base_url('assets/morrisjs/morris.min.js'),
						base_url('assets/dashboard/sb-admin-2.js'),
						base_url('assets/metisMenu/metisMenu.min.js'),
						base_url('assets/data/morris-data.js')
					),

			'breadcrumb' => array('Home'=>'#'),
			'title' => "Dashboard",
			
		);

		$this->load->view('layouts2/head',$data);
		if($this->role_id==1){
		 $this->load->view('dashboard/index');
		}elseif ($this->role_id==2) {
			$this->load->view('dashboard/index-teacher');
		}elseif ($this->role_id==3) {
			$this->load->view('dashboard/index-student');
		}
		$this->load->view('layouts2/footer');
	}


	function login()
	{
		$this->logs->save($this->user_id,"Login Page");
		$this->load->view('layouts2/login');
		 
		
	}

	function homepage()
	{
		$this->load->view('layouts2/head-no-sidebar');
		 
		
	}

	function login_process(){

		ob_start();
	    error_reporting(0);
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		//$password_hash = $this->password_hasher->enkrip($password,$username);

		$data_login = $this->general_model->getdata("ams_users",array("username"=>$username,"password"=>$password));
		if($data_login['total']>0){
			set_cookie('username',$username,'3600'); 
			set_cookie('user_id',$data_login['data'][0]->user_id,'3600'); 
			set_cookie('role_id',$data_login['data'][0]->role_id,'3600'); 
			
			$data_session = array('username'=>$username,
					'user_id'=> $data_login['data'][0]->user_id,"role_id"=> $data_login['data'][0]->role_id);
			$this->session->set_userdata($data_session);
			$this->logs->save($this->user_id,"Login Successs");
			redirect('app');
		}else{
			$this->logs->save($this->user_id,"Login Failed");
			redirect('app/login');
		}
	}

	function logout(){
		delete_cookie("user_id");
		delete_cookie("username");
		delete_cookie("role_id");
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('role_id');
		$this->session->sess_destroy();

		$url = site_url();
               echo'
               <script>
                window.location.href = "'.$url.'";
                </script>
                ';
	}
}
