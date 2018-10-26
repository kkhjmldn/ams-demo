<?php

	/* 


	*/

defined('BASEPATH') OR exit('No direct script access allowed');

Class Auth_user {
	protected $CI;

	public function __construct() {
    	
    	$this->CI =& get_instance();
    	$this->db = $this->CI->load->database(); 
    	$this->CI->load->library(array("session"));
    	$this->CI->load->helper(array("cookie"));


    }

    public function getUserId()
    {
    	$user_id = $this->CI->session->userdata('user_id') != NULL ? $this->CI->session->userdata('user_id') :
    		get_cookie('user_id');

    	return $user_id;
    }
    public function getUserName()
    {
    	$username = $this->CI->session->userdata('username') != NULL ? $this->CI->session->userdata('username') :
    		get_cookie('username');

    	return $username;
    }
    public function getRoleId()
    {
    	$role_id = $this->CI->session->userdata('role_id') != NULL ? $this->CI->session->userdata('role_id') :
    		get_cookie('role_id');

    	return $role_id;
    }

    function getAuth()
    {
        $user_id = $this->getUserId();
        $username = $this->getUserName();

        if($username==NULL || $user_id==NULL){
            $result = FALSE;
        }else{
            $result = TRUE;
        }
        return $result;
    }

    
}?>