<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_model extends CI_Model {



	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function getdata($table,$params=NULL,$joins=NULL,$select = NULL)
	{
		
		if($select!=NULL){
			$this->db->select($select);
		}
		if($params!=NULL){
			$this->db->where($params);
		}
		if($joins!=NULL){
			foreach ($joins as $key => $value) {
				// if($value->type!=NULL){
				// 	$this->db->join($value->table,$value->relation,$value->type);
				// }else{
					$this->db->join($value);
				//}
			}
			
		}


		$sql = $this->db->get($table);

		$result = array(
			'total' => $sql->num_rows(),
			'data'	=> $sql->result(),
		);
		return $result;
	}
	public function getDataGrid($table,$params=NULL,$offset,$limit,$joins=NULL,$select = NULL)
	{
		if($select!=NULL){
			$this->db->select($select);
		}
		if($params!=NULL){
			$this->db->where($params);
		}
		if($joins!=NULL){
			foreach ($joins as $key => $value) {
				if($value->type!=NULL){
					$this->db->join($value->table,$value->relation,$value->type);
				}else{
					$this->db->join($value->table,$value->relation);
				}
			}
			
		}
		
		$sql = $this->db->limit($limit,$offset)->get($table);
		
		
		$result = array(
			'total' => $sql->num_rows(),
			'data'	=> $sql->result(),
		);
		return $result;
	}

	public function save($table,$data)
	{
		return $this->db->insert($table,$data);
	}

	public function query($query)
	{
		$sql =  $this->db->query($query);
		$result = array(
			'total' => $sql->num_rows(),
			'data'	=> $sql->result(),
		);
		return $result;
	}

	public function edit($table,$params,$data)
	{
		
		return $this->db->where($params)->update($table,$data);
	}
	public function delete($table,$params)
	{
		
		return $this->db->where($params)->delete($table);
	}
}