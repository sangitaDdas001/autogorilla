<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SeoModel extends CI_Model {

	/**
	 * Insert record based on table name and post data array. 
	*/
	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	public function getSeoInforation($limit = 10, $offset = 0, $search =''){
		$this->db->select("SQL_CALC_FOUND_ROWS seo_m.page_name,seo_m.page_url,IF(seo_m.status = 'A','Active','Inactive') AS `status`,seo_m.id",FALSE);
		$this->db->from('seo_meta_info seo_m');
		$this->db->where(array('seo_m.status'=>'A','seo_m.seo_page_type'=>'others'));
		if (!empty($search)) {
			$this->db->group_start();
 			$this->db->like('seo_m.page_name', $search, 'after');
 			$this->db->or_like('seo_m.page_url', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('seo_m.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function getSeoDetailsById($id){
		$this->db->select("seo_m.page_name,seo_m.page_url,seo_m.meta_title, seo_m.meta_description, seo_m.canonical_url , IF(seo_m.status = 'A','Active','Inactive') AS `status`,seo_m.id");
		$this->db->from('seo_meta_info seo_m');
		$this->db->where(array('seo_m.id'=>base64_decode($id)));
		$query = $this->db->get();
		$res   = $query->result_array();
		$finalArr =[];
		if(!empty($res)){
			foreach ($res as $key => $se_value) {
				$this->db->select("s_m.seo_meta_id,s_m.page_url, s_m.page_name, s_m.meta_name, s_m.meta_content, s_m.id as multiple_prim_id");
				$this->db->from('seo_multiple_meta_info s_m');
				$this->db->where(array('s_m.seo_meta_id'=>base64_decode($id)));
				$query = $this->db->get();
				$seo_muli_res   = $query->result_array();
				array_push($finalArr, $res[0]);
				$finalArr[$key]['seo_multimeta_info'] = !empty($seo_muli_res)?$seo_muli_res:[];

				#### get social_og_data
				$this->db->select("social_og.seo_meta_id,social_og.social_meta_og_property, social_og.social_meta_og_content, social_og.page_name, social_og.page_url, social_og.id as social_og_id");
				$this->db->from('seo_social_og_info social_og');
				$this->db->where(array('social_og.seo_meta_id'=>base64_decode($id)));
				$query = $this->db->get();
				$social_og   = $query->result_array();
				$finalArr[$key]['social_og_info'] = !empty($social_og)?$social_og:[];
			}
		}
        return $finalArr;
	}

	public function hardDelete($id){

		/*$this->db->where($condition);
    	$this->db->delete($table);*/
    	$this->db->delete('seo_meta_info', array('id' =>$id)); 
	}

}

/* End of file SeoModel.php */
/* Location: ./application/models/SeoModel.php */ ?>