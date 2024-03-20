<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesModel extends CI_Model {

	public function getStateList(){
		$this->db->select("state.state_name, state.id");
		$this->db->from('auto_state state');
		$this->db->where(array('state.status' =>'A'));
		$this->db->order_by('state.id','DESC');
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function stateWiseCityList($stateId){
		$this->db->select("city.city_name, city.id as cityId,state.id as stateId");
		$this->db->from('auto_city city');
		$this->db->join('auto_state state','state.id = city.state_id','left');
		$this->db->where(array('city.status' =>'A', 'city.state_id'=>$stateId));
		$this->db->order_by('city.id','DESC');
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}
	public function cityWiseAreaList($cityId){
		$this->db->select("area.area_name,area.city_id,area.id areaId");
		$this->db->from('auto_city_area area');
		$this->db->join('auto_city city','city.id = area.city_id','left');
		$this->db->where(array('area.status' =>'A', 'area.city_id'=>$cityId));
		$this->db->order_by('area.id','DESC');
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function getAllSalesUser(){
		$this->db->select("aa.id,aa.name,aa.email,dep.department_name");
		$this->db->from('auto_admin aa');
		$this->db->join('department dep','dep.id = aa.department_id','left');
		$this->db->where(array('aa.status' =>'A', 'aa.manager_id !=' =>''));
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function getAllSalesUserWithOutEditId($id){
		$this->db->select("aa.id,aa.name,aa.email,dep.department_name");
		$this->db->from('auto_admin aa');
		$this->db->join('department dep','dep.id = aa.department_id','left');
		$this->db->where(array('aa.status' =>'A', 'aa.manager_id !=' =>'' , 'aa.id !=' => base64_decode($id)));
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}


	public function get_users_list_treeview($user_id,$department_id,$manager_id)
    {
       	$sql = "SELECT `user`.`id`, `user`.`name`, `user`.`email`,`user`.`department_id` ,`user`.`post` ,`p`.`post_name`
		FROM `auto_admin` as `user` LEFT JOIN `user_post` as `p` ON `p`.`id` = `user`.`post`
		WHERE `user`.`department_id` IN ('$user_id','$department_id') AND `user`.`manager_id` = $manager_id AND `user`.`status`= 'A' ";       
        $query = $this->db->query($sql,false);        
        $arr = array();
		if($query){
			if($query->num_rows())
			{	
				foreach($query->result() as $row)
				{
					$text='';
					$action_btn='';
					
					if($row->id!=1){
						$sql2  = "SELECT `cm`.`id` FROM `company_permission_tbl` as `cm` WHERE `cm`.`admin_user_id` =".$row->id;
						$query = $this->db->query($sql2,false);    
						$res   = $query->result();

						$menuPermission = fetchParentMenu(); $menu_arr =[]; $menu_id =[];
						if(!empty($menuPermission)){
						    foreach ($menuPermission as $key => $menu_value) {
						        if(!empty($menu_value['permissionType'])){
						            if($menu_value['id'] == 64 && $menu_value['permissionType'] == 1){
						              array_push($menu_arr, $menu_value['permissionType']);
						          }
						        }
						    }
						}
						if(!in_array('1',$menu_arr)){
							if(!empty($res)){
								$action_btn ='[ 
									<a href="JavaScript:void(0);" title="Assigned Company List" class="assigned_company_list" data-uid="'.$row->id.'" style="color:#f99900"><i class="fa fa-building-o" aria-hidden="true"></i></a> 
									| 
									<a href="JavaScript:void(0);" title="Permission" class="permission_update_row text-success" data-uid="'.$row->id.'"><i class="fa fa-list" aria-hidden="true"></i></a> 
									|
									<a href="JavaScript:void(0)" title="Edit" id="edit_user_view" class="text-primary edit_user_view" data-uid="'.$row->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									|
									<a href="JavaScript:void(0)" title="Delete" id="delete_user" class="text-danger delete_user" data-uid="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
									]';
								} else {
									$subM = [];
									$sql3  = "SELECT `mPT`.`id`,`mPT`.`sub_menu`,`mPT`.`parent_menu_id` FROM `menu_previllage_tbl` as `mPT` WHERE `mPT`.`admin_user_id` =".$row->id;
									$query = $this->db->query($sql3,false);    
									$res_1   = $query->result();
								
									if(!empty($res_1)){
									    foreach ($res_1 as $key2 => $submenu_val) {
									        if(!empty($submenu_val->sub_menu)){
									            if($submenu_val->parent_menu_id == 19 && $submenu_val->sub_menu == 'all_1'){
									              array_push($subM, $submenu_val->sub_menu);
									          }
									        }
									    }
									}
									
									if(in_array('all_1',$subM)){
										$action_btn ='[ 
										<a href="JavaScript:void(0);" title="Permission" style="color:#1f1f9b"><b>All Supplier</b><span class="permission-text ms-2 text-danger">{ permission }</span></a> 
										|<a href="JavaScript:void(0);" title="Permission" class="permission_update_row text-success" data-uid="'.$row->id.'"><i class="fa fa-list" aria-hidden="true"></i></a> 
										|
										<a href="JavaScript:void(0)" title="Edit" id="edit_user_view" class="text-primary edit_user_view" data-uid="'.$row->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										|
										<a href="JavaScript:void(0)" title="Delete" id="delete_user" class="text-danger delete_user" data-uid="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
										]';
									} else {
										$action_btn ='[ 
										<a href="JavaScript:void(0);" title="Permission" class="permission_update_row text-success" data-uid="'.$row->id.'"><i class="fa fa-list" aria-hidden="true"></i></a> 
										|
										<a href="JavaScript:void(0)" title="Edit" id="edit_user_view" class="text-primary edit_user_view" data-uid="'.$row->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										|
										<a href="JavaScript:void(0)" title="Delete" id="delete_user" class="text-danger delete_user" data-uid="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
										]';
									}
								}
							}

						$text .='<b>'.$row->name.'</b>('.$row->post_name.') '. '<b>('. $row->email.')</b>'.$action_btn;	
					 } else {
						/*$action_btn ='[ 
							<a href="JavaScript:void(0)" title="Edit" id="edit_user_view" class="text-primary" data-uid="'.$row->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							]';*/
						$text .='<b>'.$row->name.'</b>('.$row->email.') '.$action_btn;
						$action_btn ='';
					 }

					$arr[] = array(
							'text'=>$text,
							'id'=>$row->id,
							'children'=>$this->get_users_list_treeview($user_id,$department_id,$row->id)
						);
				}
			}  
		}		

        
        return $arr;   
    }

    public function getSalesDetailsById($id){
    	$this->db->select("a_ad.department_id,a_ad.name,a_ad.first_name, a_ad.middle_name, a_ad.last_name, a_ad.email, a_ad.mobile,a_ad.password_hint,a_ad.id,d.department_name,a_ad.manager_id,a_ad.state_id,a_ad.city_id,a_ad.area_id,a_ad.post,a_ad.user_img");
		$this->db->from('auto_admin a_ad');
		$this->db->join('department d','d.id = a_ad.department_id','left');
		$this->db->where(array('a_ad.id'=>base64_decode($id)));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
    }
    
    public function departmentWisePostList($dep_id){
    	$this->db->select("p.post_name,p.department_id,p.status,p.id");
		$this->db->from('user_post p');
		$this->db->where(array('p.department_id'=>$dep_id));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
    }

    public function getCityListStateWise($stateid){
    	$st_arr = array(); $implode_data = [];
    	$stateArr = explode(',', $stateid);
    	if(!empty($stateArr)){
    		foreach ($stateArr as $key => $svalue) {
    			$this->db->select("city.id,city.city_name,city.state_id");
				$this->db->from('auto_city city');
				$this->db->where('city.state_id',$svalue);
				$query = $this->db->get();
				$data = $query->result_array();
				$st_arr[] = $data ; 
    		}
    		
    	}
		return $st_arr;
    }

	public function getAreaListcityWise($cityid){
    	$ci_arr = array(); $implode_data = [];
    	$cityArr = explode(',', $cityid);
    
    	if(!empty($cityArr)){
    		foreach ($cityArr as $key => $cvalue) {
    			$this->db->select("area.id,area.area_name,area.city_id");
				$this->db->from('auto_city_area area');
				$this->db->where('area.city_id',$cvalue);
				$query = $this->db->get();
				$data  = $query->result_array();
				$ci_arr[] = $data ; 
    		}
    		
    	}
		return $ci_arr;
	}

	public function update($data,$id) {
		$sql = '';
		if(!empty($data['user_img'])){ 
			$sql = "UPDATE auto_admin SET 
			`manager_id`	= ".$data['manager_id'].",
			`name`			= '".$data['name']."' ,
			`first_name`	= '".$data['first_name']."' ,
			`middle_name` 	= '".$data['middle_name']."',
			`last_name` 	= '".$data['last_name']."', 
			`email` 		= '".$data['email']."', 
			`mobile` 		= '".$data['mobile']."' , 
			`password` 		= '".$data['password']."', 
			`password_hint` = '".$data['password_hint']."',
			`department_id` = ".$data['department_id']." , 
			`state_id` 		= '".$data['state_id']."' , 
			`city_id` 		= '".$data['city_id']."',
			`area_id` 		= '".$data['area_id']."',
			`post` 			= '".$data['post']."',
			`user_img` 		= '".$data['user_img']."'

			WHERE `id` = ? ";
		} else {
			$sql = "UPDATE auto_admin SET 
			`manager_id`	= ".$data['manager_id'].",
			`name`			= '".$data['name']."' ,
			`first_name`	= '".$data['first_name']."' ,
			`middle_name` 	= '".$data['middle_name']."',
			`last_name` 	= '".$data['last_name']."', 
			`email` 		= '".$data['email']."', 
			`mobile` 		= '".$data['mobile']."' , 
			`password` 		= '".$data['password']."', 
			`password_hint` = '".$data['password_hint']."',
			`department_id` = ".$data['department_id']." , 
			`state_id` 		= '".$data['state_id']."' , 
			`city_id` 		= '".$data['city_id']."',
			`area_id` 		= '".$data['area_id']."',
			`post` 			= '".$data['post']."'

			WHERE `id` = ? ";  
		}
		
   		$query = $this->db->query($sql, array(base64_decode($id)));
   		return $query;
	}

	public function deleteUser($id){
		$res = $this->db->delete('auto_admin', array('id' => base64_decode($id))); 
		if($res){
			$res2 = $this->db->delete('auto_admin', array('manager_id' => base64_decode($id))); 
			$res = $this->db->delete('menu_previllage_tbl', array('admin_user_id ' => base64_decode($id))); 
			$res = $this->db->delete('company_permission_tbl', array('admin_user_id ' => base64_decode($id))); 
		}
	}

	public function getCompanyInfoById($limit = 10, $offset = 0, $search='',$userId=''){
		$this->db->select("SQL_CALC_FOUND_ROWS cpt.company_id,v.company_name,v.id, v.name, v.email ,date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at,v.autogorilla_verified, v.featured_company,st.state_name",FALSE);
		$this->db->from('company_permission_tbl cpt');
		$this->db->join('vendor_info v','v.id = cpt.company_id','left');
		$this->db->join('auto_state st','st.id = v.state_id','left');
		$this->db->where(array('v.status !='=>'D' ,'v.approved_status'=>1 ,'cpt.admin_user_id' =>base64_decode($userId),'cpt.status'=>1 ));
		if (!empty($search)) {
 			$this->db->group_start();
 			$this->db->like('v.email', $search, 'both');
 			$this->db->or_like('v.company_name', $search, 'both');
 			$this->db->or_like('v.name', $search, 'both');
 			$this->db->group_end();
		}
		$this->db->order_by('v.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

 		$countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();	
        return $data;
	}

	public function exportCsvInfo($userId){
		$this->db->select("SQL_CALC_FOUND_ROWS cpt.company_id,v.company_name, st.state_name,ad.name as user_name , v.email ",FALSE);
		$this->db->from('company_permission_tbl cpt');
		$this->db->join('vendor_info v','v.id = cpt.company_id','left');
		$this->db->join('auto_state st','st.id = v.state_id','left');
		$this->db->join('auto_admin ad','ad.id = cpt.admin_user_id','left');
		$this->db->where(array('v.status !='=>'D' ,'v.approved_status'=>1 ,'cpt.admin_user_id' =>base64_decode($userId),'cpt.status'=>1 ));
		$query = $this->db->get();
        $data['fetch_count'] = $query->num_rows();
        $data  = $query->result_array();	
        return $data;
	}

}


/* End of file SalesModel.php */
/* Location: ./application/models/SalesModel.php */?>