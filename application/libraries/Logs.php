<?php

	/* 


	*/

defined('BASEPATH') OR exit('No direct script access allowed');

Class Logs {
	protected $CI;

	public function __construct() {
    	
    	$this->CI =& get_instance();
    	$this->db = $this->CI->load->database(); 
    	$this->CI->load->library(array("session"));
    	$this->CI->load->helper(array("cookie"));


    }

    function save($user_id,$activity)
    {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
      
        $this->CI->load->model("general_model");
        $data = array(
            'user_id' => $user_id,
            'activity' => $activity,
            'ip_address' =>$ipaddress,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        );
        $this->CI->general_model->save("ams_logs",$data);
    }   
}?>