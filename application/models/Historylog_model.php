<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historylog_model extends CI_Model {
	
	private $tablename = "historylog";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function view_list($datesearch){
		
		return $this->db->query("select a.*,b.displayname as username from ".$this->tablename." a left join sysusers b on b.id = a.user where a.status = 1 and DATE(a.dateadded) = '$datesearch' order by a.dateadded desc");
		
	}
	
	public function view_list_user($userid){
		
		$lastlogin = $this->session->userdata('pms_lastlogin');
		return $this->db->query("select a.*,b.displayname as username from ".$this->tablename." a left join sysusers b on b.id = a.user where a.status = 1 and a.dateadded > '$lastlogin' and a.user <> $userid order by a.dateadded desc");
		
	}
	
}