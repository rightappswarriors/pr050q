<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_active'))
{
    function is_active($menu = '')
    {
        $current_url =& get_instance();
		$current = $current_url->router->fetch_class(); 
		$current_method = $current_url->router->fetch_method(); 
		$menus = explode(",",$menu);
		foreach($menus as $m){
			if(($current == $m and $current_method == 'index') or ($current_method == $m or $current == $m)){
				return 'active';
			}else '';
		}
    }   
}

if ( ! function_exists('is_active_menu'))
{
    function is_active_menu($menu = '')
    {
        $current_url =& get_instance();
		$current = $current_url->router->fetch_class(); 
		$current_method = $current_url->router->fetch_method(); 
		$menus = explode(",",$menu);
		foreach($menus as $m){
			if(($current == $m and $current_method == 'index') or $current_method == $m){
				return 'menu-open';
			}else '';
		}
    }   
}

if ( ! function_exists('history_logs_menu'))
{
    function history_logs_menu($userid)
    {
        $CI = get_instance();
		$CI->load->model('historylog_model');
		$logs = $CI->historylog_model->view_list_user($userid);
		
		$ctr = 0;
		$ctr_new = 0;
		$ctr_upd = 0;
		$ctr_del = 0;
		$last_added = '';
		$last_updated = '';
		$last_deleted = '';
		
		if($logs->num_rows()>0){
			
			foreach($logs->result() as $row){
				
				//ADDED
				$posadd = strpos($row->description,"added");
				if($posadd==TRUE){
					if(!$ctr_new) $last_added = $row->dateadded;		
					$ctr_new++;
				}
				//UPDATED
				$posedit = strpos($row->description,"updated");
				if($posedit==TRUE){
					if(!$ctr_upd) $last_updated = $row->dateadded;	
					$ctr_upd++;
				}
				//DELETED
				$posdel = strpos($row->description,"deleted");
				if($posdel==TRUE){
					if(!$ctr_del) $last_deleted = $row->dateadded;	
					$ctr_del++;
				}	
			}
			
			$ctr = ($ctr_new+$ctr_upd+$ctr_del);
			
		}
		
		echo '<a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">'.$ctr.'</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">'.$ctr.' New System Logs</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-plus"></i> '.$ctr_new.' Added Records
            <span class="float-right text-muted text-sm">'.calculate_time_span($last_added).'</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-edit"></i> '.$ctr_upd.' Updated Info
            <span class="float-right text-muted text-sm">'.calculate_time_span($last_updated).'</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-trash"></i> '.$ctr_del.' Deleted
            <span class="float-right text-muted text-sm">'.calculate_time_span($last_deleted).'</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="'.site_url("logs").'" class="dropdown-item dropdown-footer">See All System Logs</a>
        </div>';
		
    }

	function calculate_time_span($date){
		
		if(!strlen(trim($date))) return '';
		
		$seconds  = strtotime(date('Y-m-d H:i:s')) - strtotime($date);

        $months = floor($seconds / (3600*24*30));
        $day = floor($seconds / (3600*24));
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        if($seconds < 60)
            $time = $secs." seconds ago";
        else if($seconds < 60*60 )
            $time = $mins." min ago";
        else if($seconds < 24*60*60)
            $time = $hours." hours ago";
        else if($seconds < 24*60*60)
            $time = $day." day ago";
        else
            $time = $months." month ago";

        return $time;
	}
	
}


