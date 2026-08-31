<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends CI_Model {
	
	private $tablename = "inventory";
	
	function __construct(){
        parent::__construct();
	}
	
	public function view_list($location=''){
		$locationsearch = '';
		if(strlen(trim($location))>0){
			//$locationsearch = "and a.location = $location";
			$locationsearch = "where a.location = $location";
		}
		//return $this->db->query("select a.location,a.item,a.stocks,b.itemdescr as itemname,b.itemunit,c.location as locationname, count(dd.id)as stocksin_count, count(t.itemid)as transfers_count  from inventory a left join items b on b.id = a.item left join location c on c.id = a.location left join stocksin_detail d on d.itemid = a.item left join stocksin dd on d.stocksin = dd.id and dd.status = 1 and dd.location = a.location left join transfers_detail t on t.itemid = a.item left join transfers tt on tt.id = t.transfers $locationsearch group by a.item,a.location order by a.stocks desc,b.itemdescr asc");
        
        return $this->db->query("select a.*,b.location as locationname,c.itemdescr as itemname,c.itemunit from inventory a left join location b on b.id = a.location left join items c on c.id = a.item $locationsearch");
	}
	
	public function stocksin($item,$location,$qty){
		
		$qry = $this->db->query("select id,stocks from inventory where item = $item and location = $location");
		if($qry->num_rows()>0){
			
			$row = $qry->row();
			$qty = floatval($qty);
			$this->db->query("update inventory a set a.stocks = GREATEST((a.stocks + '$qty'),0) where a.id =".$row->id);
			
		}else{
			$data = array(
				'item'=>$item,
				'location'=>$location,
				'stocks'=>$qty
			);
			$this->db->insert($this->tablename,$data);
		}
		
	}
	
	public function stocksout($item,$location,$qty){
		$this->db->query("update inventory set stocks = (stocks - $qty) where item = $item and location = $location limit 1");
	}
	
	public function stocksin_delete($id){
		
		$result = $this->db->query("select a.itemid,a.itemqty,b.location from stocksin_detail a left join stocksin b on b.id = a.stocksin where a.stocksin = $id");
		foreach($result->result() as $row){
			$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks - '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->location." limit 1");
		}
		
	}
	
	public function stocksout_delete($id){
		
		$result = $this->db->query("select a.itemid,a.itemqty,b.location from stocksout_detail a left join stocksout b on b.id = a.stocksout where a.stocksout = $id");
		foreach($result->result() as $row){
			$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks + '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->location." limit 1");
		}
		
	}
	
	public function transfers_delete($id){
		
		$result = $this->db->query("select a.itemid,a.itemqty,b.fromlocation,b.tolocation from transfers_detail a left join transfers b on b.id = a.transfers where a.transfers = $id");
		foreach($result->result() as $row){
			// FROM 
			$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks + '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->fromlocation." limit 1");
			// TO
			$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks - '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->tolocation." limit 1");
		}
		
	}
	
	public function detailinfo($itemid,$location){
		return $this->db->query("SELECT b.itemprice,b.itemid,b.itemqty,b.stocksin as stocksinout,'in' as strinout,x.datereceived as strdate,l.location as locationame,sp.company as projectsupp,x.refno,'0' as tlocation,'0' as flocation,x.dateadded from stocksin_detail b LEFT JOIN stocksin x on x.id = b.stocksin LEFT JOIN location l on l.id = x.location LEFT JOIN company sp on sp.id = x.supplier WHERE x.location = $location and b.itemid = $itemid and x.status = 1 and x.deleted = 'no' 
		UNION ALL
		SELECT a.itemprice,a.itemid,a.itemqty,a.stocksout as stocksinout,'out' as strinout,y.dateout as strdate,ll.location as locationame,sp.projectname as projectsupp,y.refno,'0' as tlocation,'0' as flocation,y.dateadded from stocksout_detail a LEFT JOIN stocksout y on y.id = a.stocksout LEFT JOIN location ll on ll.id = y.location LEFT JOIN projects sp on sp.id = y.project WHERE y.location = $location and a.itemid = $itemid and y.status = 1 and y.deleted = 'no' 
		UNION ALL 
		SELECT c.itemprice,c.itemid,c.itemqty,c.transfers as stocksinout,'trans' as strinout,t.transferdate as strdate,loc.location as locationame,loc1.location as projectsupp,t.refno,t.tolocation as tlocation,t.fromlocation as flocation,t.dateadded from transfers_detail c LEFT JOIN transfers t on t.id = c.transfers LEFT JOIN location loc on loc.id = t.fromlocation LEFT JOIN location loc1 on loc1.id = t.tolocation WHERE  c.itemid = $itemid and t.status = 1 and t.deleted = 'no' and (t.fromlocation = $location or t.tolocation = $location)  
		ORDER BY strdate asc,dateadded asc");
	}
	
	public function delete_inventory($id,$stockinout,$itemid){
		
		if($stockinout=="in") $tblfld_name = "stocksin";
		if($stockinout=="out") $tblfld_name = "stocksout";
		if($stockinout=="trans") $tblfld_name = "transfers";
		
		if($stockinout=="in" or $stockinout=="out"){
		
			// IN or OUT
			$result = $this->db->query("select a.itemid,a.itemqty,b.location from ".$tblfld_name."_detail a left join $tblfld_name b on b.id = a.".$tblfld_name." where a.itemid = $itemid and a.".$tblfld_name." = $id limit 1");
			
			// UPDATE INVENTORY
			if($result->num_rows()>0){
				
				$row = $result->row();
				if($stockinout=="in"){
					$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks - '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->location." limit 1");
				}else{
					$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks + '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->location." limit 1");
				}
			}
		
		}else{
			
			// TRANSFER
			$result = $this->db->query("select a.itemid,a.itemqty,b.fromlocation,b.tolocation from ".$tblfld_name."_detail a left join $tblfld_name b on b.id = a.".$tblfld_name." where a.itemid = $itemid and a.".$tblfld_name." = $id limit 1");
			
			// UPDATE INVENTORY
			if($result->num_rows()>0){
				
				$row = $result->row();
				
				$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks + '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->fromlocation." limit 1");
				
				$this->db->query("update inventory a set a.stocks = GREATEST(a.stocks - '".floatval($row->itemqty)."',0) where a.item = ".$row->itemid." and a.location = ".$row->tolocation." limit 1");
			}	
			
		}
		
		// DELETE IN DETAIL	
		$this->db->query("delete from ".$tblfld_name."_detail where itemid = $itemid and ".$tblfld_name." = $id limit 1");
		
	}
	
	public function update_inventory($item,$location,$qty){
		$this->db->query("update inventory set stocks = $qty where item = $item and location = $location limit 1");
	}
    
    public function itemsumreport($item,$fromdate,$todate){
        return $this->db->query("select a.remarks,a.refno, a.datereceived, c.location as locationname, d.company as suppliername, s.itemqty, s.itemprice,i.itemunit    
		from stocksin a  
		left join location c on c.id = a.location 
		left join company d on d.id = a.supplier  
        left join stocksin_detail s on s.stocksin = a.id 
        left join items i on i.id = s.itemid 
		where a.status = 1 and a.deleted = 'no' and s.itemid = $item and (a.datereceived between '$fromdate' and '$todate') order by a.datereceived asc");
    }
	
}