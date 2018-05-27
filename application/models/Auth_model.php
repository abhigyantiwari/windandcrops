<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		header("Content-type: application/json");
		$this->load->database();
	}

	public function find_user($email, $password){
		$query = $this->db->get_where('users', array('email' => $email));
		$row = $query->row_array();
		if($email == $row['email'] && password_verify($password, $row['password'])){
			return true;
		} else{
			return false;
		}
	}

	public function add_logged_user($email, $jwt){
		if($this->db->insert('logged_users', array('email' => $email, 'jwt' => $jwt))){
			return true;
		} else{
			return false;
		}
	}

	public function exists_logged_user($jwt){
		$query = $this->db->get_where('logged_users', array('jwt' => $jwt));
		if($query->num_rows() == 1){
			return true;
		}
		return false;
	}

	public function delete_logged_user($jwt){
		$this->db->delete('logged_users', array("jwt" => $jwt));
	}

	public function get_user($email){
		$query = $this->db->get_where('users', array('email' => $email));
		$row = $query->row();
		return $row->fullname;
	}

	public function get_user_data($email){
		$query = $this->db->get_where('users', array('email' => $email));
		$row = $query->row();
		return $row->savedata;
		
	}

	public function email_used($email){
		$query = $this->db->get_where('users', array('email' => $email));
		if($query->num_rows() > 0)
			return TRUE;
		else{
			return FALSE;
		}
	}
	public function register_user($fullname, $email, $password){
		$data = array(
			'fullname' => $fullname,
			'email' => $email,
			'password' => password_hash($password, PASSWORD_BCRYPT)
		);
		if($this->db->insert('users', $data)){
			return true;
		} else{
			return false;
		}
	}
}
