<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {

	protected $username = '';
	protected $role_id = '';
	protected $user_id = '';


	public function __construct(){
		parent::__construct();
		$this->load->library(array("auth_user","session"));
		$this->load->model(array("general_model"));
		$this->user_id = $this->auth_user->getUserId();
		$this->role_id = $this->auth_user->getRoleId();
		$this->username = $this->auth_user->getUserName();

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

			'breadcrumb' => array('Home'=> base_url(), 'Groups' => '#' ),
			'title' => "Groups",
			
		);

		$this->load->view("layouts2/head",$data);
		$this->load->view("group/index");
		$this->load->view("layouts2/footer");
	}
	
	public function new()
	{
		
		$data_user = $this->MModel->getData("Select * from ams_users where role_id != 1 order by user_id ASC ");
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
				),
			'js'	=> array(
				base_url('assets/js/jquery-1.7.2.min.js'),
				base_url('assets/js/bootstrap.js'),
				base_url('assets/chosen/chosen.jquery.js'),
				base_url("assets/ckeditor/ckeditor.js"),
				base_url('assets/summernote/dist/summernote.js'),
				base_url('assets/fileinput/js/fileinput.min.js'),
			)

		);


		$data = array(
			'breadcrumb' => array('Home'=> base_url(), 'Group Chat' => site_url('group'),'Add Group'=> site_url('group/new')),
			'title' => "Group Chat",
			"user" => $data_user
        );

		
		
		$this->load->view('head',$data_assets);
		$this->load->view("page/group/add",$data);
		$this->load->view('footer');
	}

	public function save()
	{
		$group_id 	= $this->input->get('group_id');

		if($group_id!=NULL){
			
			$group_name 					= $this->input->post('group_name');
			
			$user_ids 			= $this->input->post('user_id');
			$users = '';
			foreach ($user_ids as $key => $value) {
				if($key!=sizeof($user_ids)){
					$users.=$value.",";
				}else{
					$users.=$value;
				}
			}
				//print_r($personel_id);exit;

			
			$data = array(
				
				'group_name'	=> $group_name,
				'users'			=> $users,
				
				'updated_at'	=> date('Y-m-d H:i:s'),
				
				'updated_by'	=> 1,//$this->user->id,
				

			);

			if($this->general_model->edit("ams_group_chat",array("group_id"=>$group_id),$data)){

				
				echo json_encode("success");

			}else{
				echo json_encode("failed");
			}
		}else{

		
		$group_name 					= $this->input->post('group_name');
		
		$user_ids 			= $this->input->post('user_id');
		$users = '';
		foreach ($user_ids as $key => $value) {
			if($key!=sizeof($user_ids)){
				$users.=$value.",";
			}else{
				$users.=$value;
			}
		}
			//print_r($personel_id);exit;

		$group_id = "GRP".date('YmdHis');
		$data = array(
			'group_id'		=> $group_id,
			'group_name'	=> $group_name,
			'users'			=> $users,
			'created_at'	=> date('Y-m-d H:i:s'),
			'updated_at'	=> date('Y-m-d H:i:s'),
			'created_by'	=> 1, //$this->user->id,
			'updated_by'	=> 1,//$this->user->id,
			

		);

		if($this->general_model->save("ams_group_chat",$data)){

			
			echo json_encode("success");

		}else{
			echo json_encode("failed");
		}
	}
	}

	

	

	public function destroy()
	{
		$this->MModel->hapus("group_id",$this->input->post("group_id"),"ams_group_chat
		");
		echo json_encode(array("status"=>TRUE));

	}

	public function getGroupJson()
	{
		$id = ($this->input->post('id')!= NULL) ? $_POST['id'] : '';


		$group = ($this->input->post('name')!= NULL) ? $_POST['name'] : '';
		$description = ($this->input->post('description')!= NULL) ? $_POST['description'] : '';
		
		$page = $this->input->post('page');
		$limit = $this->input->post('rows');
		$offset = ($page-1)*$limit;
		
		if($group!=''  || $description!='' ){
			$params = 
			"group_name like '%".$group."%' " ;
			
			$data_group = $this->general_model->getDataGrid("ams_group_chat",$params,($offset),$limit);
			$total = $this->general_model->getdata("ams_group_chat",$params)['total'];
		}else{
			$data_group = $this->general_model->getDataGrid("ams_group_chat",NULL,($offset),$limit);
			$total = $this->general_model->getdata("ams_group_chat")['total'];
		}
		foreach ($data_group['data'] as $key => $value) {
			$user_ids = explode(",", $value->users);
			$user_name = '';
			$data_personel = array();
			if($user_ids!=''){
				foreach ($user_ids as $k => $v) {
					if($v!=''){
						$data_personel['data'][$k] = $this->general_model->getdata("ams_users",array("user_id"=>$v))['data'][0];
					}
				}	
				$data_personel['total']= sizeof($data_personel['data']);
			}else{
				$data_personel['total'] = 0;
			}
			//print_r($data_personel);exit;		

			if($data_personel['total'] > 0){
				$value->state = 'closed';
				$value->total_member = $data_personel['total'];
				foreach ($data_personel['data'] as $k => $val) {
					$val->group_id = $val->user_id;
					$val->group_name = $val->name;
					if($k!=sizeof($data_personel['data'])){
						$user_name .= $val->name.",";
					}else{
						$user_name .= $val->name;
					}
					$val->action = '  <button type="button" onclick="dropPersonel('."'".$val->user_id."'".')" class="easyui-linkbutton"><i class="fa fa-trash"></i> Drop</button>';
				}
				$value->children = $data_personel['data'];
				$value->user_name = $user_name;
			}else{
				$value->state = 'open';
				$value->total_member =0;
			}

			/*if($value->is_active==1){
				$value->status = "<span class='label label-success'> A</span>";
			}elseif($value->is_active==0){
				$value->status = "<span class='label label-default'> N/A </span>";
			}*/
			
			
		}
		$result = array(
			'total'	=> $total,
			'rows'	=> $data_group['data'],
		);

		echo json_encode($result);
	}

	function delete(){
		$group_id = $this->input->post("group_id");
		if($this->general_model->delete("ams_group_chat",array("group_id"=>$group_id))){
			echo "success";
		}else{
			echo "failed";
		}

		
	}

	

}

?>