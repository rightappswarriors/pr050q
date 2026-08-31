<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items_model extends CI_Model {
	
	private $tablename = "items";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		return $this->db->insert($this->tablename,$data);
	}
	
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function view_list($itemscat=""){
		if($itemscat >1){ 
			$cat_search = "and a.itemscat = $itemscat";
		}elseif($itemscat==0){	
			$cat_search = "";
		}else{
			$cat_search = "and a.itemscat = 0";
		}
		return $this->db->query("select a.*, b.itemscat as catname, t.titles as title from items a left join itemscat b on b.id = a.itemscat left join titles t on t.id = a.accounting where a.status = 1 and a.deleted = 'no' $cat_search");
	}
	
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
	
	public function items_search($itemsearch,$location=''){
		if(strlen(trim($location))>0){ 
			return $this->db->query("select a.stocks,a.location,b.* from inventory a left join items b on b.id = a.item where (b.item like '%$itemsearch%' or b.itemdescr like '%$itemsearch%') and a.location = $location");
		}else{
			return $this->db->query("select a.* from items a where (a.item like '%$itemsearch%' or a.itemdescr like '%$itemsearch%') and a.status = 1 and a.deleted = 'no'");
		}
	}
    
    public function latest_price($item,$location,$strdate){
		return $this->db->query("select a.itemprice as price from stocksin_detail a left join stocksin b on b.id = a.stocksin where a.itemid = $item and b.location = $location and DATE(b.datereceived) <= '$strdate' order by b.datereceived desc limit 1");
	}
    
    public function latest_price_transfer($item,$location,$strdate){
		return $this->db->query("select a.itemprice as price from transfers_detail a left join transfers b on b.id = a.transfers where a.itemid = $item and b.tolocation = $location and DATE(b.transferdate) <= '$strdate' order by b.transferdate desc limit 1");
	}

}