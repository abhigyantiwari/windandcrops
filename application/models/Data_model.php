<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		header("Content-type: application/json");
		$this->load->database();
	}

	public function get_population(){
		$query = $this->db->query("SELECT * FROM popdata order by city limit 100");
		$result = $query->result();
		return $result; 
	}

	public function get_user_data($jwt){
		$query = $this->db->get_where('logged_users', array("jwt" => $jwt));
		if($query->num_rows() == 1){
			$row = $query->row();
			$query2 = $this->db->get_where('users', array("email" => $row->email));
			$row2 = $query2->row();
			return $row2;
		} else{
			return "NULL";
		}
	}

	public function getPopData($city){
		$query = $this->db->get_where('popdata', array("city" => $city));
		$row = $query->row();
		return $row;
	}
	
}
