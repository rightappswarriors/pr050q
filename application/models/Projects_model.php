<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends CI_Model {
	
	private $tablename = "projects";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		return $this->db->insert($this->tablename,$data);
	}
	
	public function insert_file($data){
		return $this->db->insert('projects_file',$data);
	}
	
	public function insert_note($data){
		return $this->db->insert('projects_note',$data);
	}
	
	public function file_list($project){
		return $this->db->query("select a.* from projects_file a where a.projectid = $project and a.status = 1 and a.deleted = 'no' order by a.dateadded desc");
	}
	
	public function note_list($project){
		return $this->db->query("select a.* from projects_note a where a.projectid = $project and a.status = 1 and a.deleted = 'no' order by a.dateadded desc");
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
	
	public function file_remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update('projects_file',$data);
	}
	
	public function note_remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update('projects_note',$data);
	}
	
	public function view_list($type=''){
        $this_type='';
        $thisproject = '';
        if(strlen(trim($type))>0){
            $this_type = "and a.projecttype = '$type'";
        }
        
        //if($this->session->userdata('pms_usertype')==6){
            //$this_type = '';
            //$thisproject = 'and a.id = '.$this->session->userdata('pms_project');
        //}
        
		return $this->db->query("select a.*,b.company as customername from projects a left join company b on b.id = a.customer where a.status = 1 and a.deleted = 'no' $thisproject $this_type order by a.dateadded desc");
	}
	
	public function view_info($id){
		return $this->db->query("select a.*,b.company as customername,concat(c.lastname,', ',c.firstname)as headproject from projects a left join company b on b.id = a.customer left join employees c on c.id = a.projecthead where a.id = $id");
	}
	
	public function project_materials($id,$page=1){
		$start = ($page*10)-10;
		$limit = "LIMIT 10 OFFSET $start";
		return $this->db->query("select sum(a.itemqty) as totalqty,b.itemdescr as itemname,b.itemunit,a.itemid from stocksout_detail a left join items b on b.id = a.itemid left join stocksout x on x.id = a.stocksout where x.project = $id and x.status = 1 and x.deleted = 'no' group by a.itemid order by b.itemdescr asc $limit");
	}
	
    public function project_materials_search($id,$txtitem){
		return $this->db->query("select sum(a.itemqty) as totalqty,b.itemdescr as itemname,b.itemunit,a.itemid from stocksout_detail a left join items b on b.id = a.itemid left join stocksout x on x.id = a.stocksout where b.itemdescr like '%$txtitem%' and x.project = $id and x.status = 1 and x.deleted = 'no' group by a.itemid order by b.itemdescr asc");
	}
	
	public function count_project_materials($id){
		return $this->db->query("select sum(a.itemqty) as totalqty,b.itemdescr as itemname,b.itemunit,a.itemid from stocksout_detail a left join items b on b.id = a.itemid left join stocksout x on x.id = a.stocksout where x.project = $id and x.status = 1 and x.deleted = 'no' group by a.itemid");
	}
	
	public function project_expenses($id){
		return $this->db->query("select * from (select sum(a.itemqty*a.itemprice) as totalamount,b.itemdescr as itemname,b.itemunit from stocksout_detail a left join items b on b.id = a.itemid left join stocksout x on x.id = a.stocksout where x.project = $id and x.status = 1 and x.deleted = 'no' group by a.itemid
        UNION ALL 
        select sum(c.itemqty*c.itemprice) as totalamount,d.itemdescr as itemname,d.itemunit from payables_detail c left join items d on d.id = c.itemid left join payables xx on xx.id = c.payables where xx.project = $id and xx.status = 1 and xx.deleted = 'no' group by c.itemid) pex order by pex.itemname asc");
	}
	
	public function project_expenses_total($id){
		$result=$this->db->query("select sum(a.itemqty*a.itemprice) as totalamount,b.itemdescr as itemname,b.itemunit from stocksout_detail a left join items b on b.id = a.itemid left join stocksout x on x.id = a.stocksout where x.project = $id and x.status = 1 and x.deleted = 'no' group by a.itemid
        UNION ALL 
        select sum(c.itemqty*c.itemprice) as totalamount,d.itemdescr as itemname,d.itemunit from payables_detail c left join items d on d.id = c.itemid left join payables xx on xx.id = c.payables where xx.project = $id and xx.status = 1 and xx.deleted = 'no' group by c.itemid");
		$total=0;
		if($result->num_rows()>0){
			foreach($result->result() as $row){
				$total += $row->totalamount;
			}
		}
		return $total;
	}
	
	public function material_history($projectid,$itemid){
		return $this->db->query("select a.itemqty,b.itemdescr as itemname,b.itemunit,a.itemid,x.dateout,x.refno from stocksout_detail a left join items b on b.id = a.itemid left join stocksout x on x.id = a.stocksout where x.project = $projectid and a.itemid = $itemid and x.status = 1 and x.deleted = 'no' order by x.dateout desc");
	}

}