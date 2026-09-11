<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
	
	function __construct(){
        parent::__construct();
	}
	
	public function check_login($username,$password){
		
		$qry = $this->db->query("select a.* from sysusers a where a.username = '$username' and a.password = '".md5($password)."' and a.deleted = 'no' and a.status = 1 ");
		
		if ($query->num_rows() === 1) {
			$user = $query->row_array();
			print_r($user);
		} else {
			var_dump( 'No matching user.');
		}
		exit; die();
		return $qry;
	}
	
	public function update_password($id,$data){
		$this->db->where('id', $id);
		$this->db->limit(1);
		$this->db->update('sysusers', $data);
	}
	
	public function user_update($id,$data){
		$this->db->where('id', $id);
		$this->db->limit(1);
		$this->db->update('sysusers', $data);
	}

}