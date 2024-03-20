<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class SupplierServiceModel extends CI_Model {

	public function allServiceSupplierDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS vs.image_1, 
			CONCAT(UPPER(SUBSTRING(vs.service_name,1,1)),
			LOWER(SUBSTRING(vs.service_name,2))) as service_name,  
			vs.currency_type,
			vs.service_price, 
			vs.currency_type, 
			CONCAT(vs.currency_type,' ', vs.service_price) as service_price , 
			date_format(vs.created_at,'%d-%m-%y %h:%i:%s %p') as created_at,
			CASE WHEN vs.status = '1' THEN 'Approved' WHEN vs.status = '3' THEN 'Reject' ELSE 'Pending' END AS `status`,
			vs.id as service_id,
			vs.service_mapped, 
			p_s.total_score, 
			v.vendor_catalog_url_slug, 
			v.email,v.password , 
			CONCAT(UPPER(SUBSTRING(v.company_name,1,1)),
			LOWER(SUBSTRING(v.company_name,2))) as company_name ",
			FALSE );

		$this->db->from('vendor_service vs');
		$this->db->join('vendor_info v' ,'v.id = vs.vendor_id','left');
		$this->db->join('vendor_service_score_table p_s' ,'p_s.service_id = vs.id','left');
		$this->db->where(array('vs.status !=' =>2));
		
		if (!empty($search)) {
			$this->db->group_start();
				$this->db->like('vs.service_name', $search, 'after');
				$this->db->or_like('v.company_name', $search, 'both');
				$this->db->group_end();
		}
		$this->db->group_by('vs.id');
		$this->db->order_by('vs.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
	  
	    $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
	    $data['total_count'] = $countQuery->row()->Count;
	    $data['fetch_count'] = $query->num_rows();
	    $data['fetch_data']  = $query->result_array();
	    return $data;
	}

	public function changeServiceApprovedStatus($data){
		$sql = "UPDATE vendor_service SET status = 1 WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['service_id'])));
		return $query;
	}
	public function changeServiceRejectedStatus($data){
		$sql = "UPDATE vendor_service SET status = 3 WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['service_id'])));
		return $query;
	}

	public function getSupplierServiceDetailsById($serviceId){
		$this->db->select("serv.service_name,serv.service_url_slug,serv.service_vendor_cat_id,serv.service_code,
			serv.hsn_code,sup_cat.category_name,serv.status,serv.gst,serv.service_price,serv.currency_type,
			serv.unit,serv.unit_type,serv.id,serv.image_1,serv.image_2,serv.image_3,serv.service_vedio_1,serv.service_video_2,serv.service_video_3,sup_cat.id as category_id,serv.service_description,serv.vendor_id");
		$this->db->from('vendor_service serv');
		$this->db->join('vendor_category sup_cat', 'sup_cat.id = serv.service_vendor_cat_id','left');
		$this->db->where(array('serv.id' =>$serviceId));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function getSupplierServiceSpecificationById($serviceId){
		$this->db->select("sep.service_sp_title,sep.service_sp_des,sep.id");
		$this->db->from('vendor_service_specification sep');
		$this->db->where(array('sep.service_id' =>$serviceId));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	public function getsupplierCategoy($vendor_id){
		$this->db->select("sep.service_sp_title,sep.service_sp_des,sep.id");
		$this->db->from('vendor_service_specification sep');
		$this->db->where(array('sep.service_id' =>$serviceId));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function getMicroCategoryNameBySubCat(){
		$this->db->select("catN.category_name,catN.id,catN.sub_cat_id,catN.parent_cat_id,`catN2`.`category_name` as subcatname ,`catN3`.`category_name` as parentcatname");
		$this->db->from('auto_category catN');
		$this->db->join('auto_category catN2', 'catN.sub_cat_id = `catN2`.`id`','left');
		$this->db->join('auto_category catN3', 'catN.parent_cat_id = `catN3`.`id`','left');
		$this->db->where(array('catN.status '=>'A','catN.cat_level'=>3,'catN.category_type'=>2));
		$this->db->order_by('catN.id','ASC');
		$query 			= $this->db->get();
		$numrows_pCat 	= $query->num_rows();
        $result 		= $query->result_array();
		return $result;
	}
	
	public function getSellerMappingcategoryByServiceId($serviceId){
		$this->db->select("map.autogorila_parent_cat_id, map.autogorila_parent_cat_id,map.id, map.autogorila_micro_cat_id,map.vendor_id");
		$this->db->from('vendor_service_mapping_tbl map');
		$this->db->where(array('map.service_id' =>$serviceId));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	public function getServiceScoreById($serviceId,$vendorId){
		$this->db->select("sc.service_id, sc.id");
		$this->db->from('vendor_service_score_table sc');
		$this->db->where(array('sc.service_id' =>$serviceId ,'sc.vendor_id'=>$vendorId));
		$query  = $this->db->get();
		$data 	= $query->num_rows();
		return $data;
	}

	

	public function delete_service($id,$table){
		$this->db->where('service_id', base64_decode($id));
		$this->db->delete($table);
	}

	public function update($data,$id,$table){
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update($table);
		return $res;
	}

	public function getSupplierServiceCategory($vendor_id){
		$this->db->select("cat.category_name ,cat.id");
		$this->db->from('vendor_category cat');
		$this->db->where(array('cat.category_type' => 'service','cat.supplier_login_id' => $vendor_id, 'cat.status'=>1));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
}


/* End of file SupplierServiceModel.php */
/* Location: ./application/models/SupplierServiceModel.php */ ?>