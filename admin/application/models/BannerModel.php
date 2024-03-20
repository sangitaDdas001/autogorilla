<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BannerModel extends CI_Model {

	/**
	 * Insert record based on table name and post data array. 
	*/
	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}
	/** 
		This function used commonly in ths website, this fuction return all data from a table table.
		This function fetch all data from particular one table.
	**/

	public function getDetailsByConditions($tablename, $conditions,$fetchList = null) {
		$sql = '';
		if(!is_null($fetchList)) {
			$this->db->select($fetchList);
			$this->db->from($tablename);
			$this->db->where($conditions);
			$sql = $this->db->get();
		} else {
			$sql = $this->db->get_where($tablename,$conditions);
		}
		$res = $sql->result_array();
		return $res;
	}
	/**
	 * Update record based on table name, value to be update in udpateArr array and condition
	 * that will be placed in condition array
	*/
	public function update($table = '', $updateArr = array() ,$condition = array()) {
		if(empty($table) || empty($updateArr) || empty($condition)) {
			return false;
		}
		$update = $this->db->update($table,$updateArr,$condition);
		return $update;
	}

	/*	get all Banner details */
	public function getBannerList($limit = 10, $offset = 0, $search='') {
		
		$this->db->select("SQL_CALC_FOUND_ROWS bList.bannerImage,bList.banner_text, IF(bList.status = 'A','Active','Inactive') AS `status`,bList.id",FALSE);
		$this->db->from('auto_banner bList');
		$this->db->where(array('bList.status !='=>'D','bList.banner_type'=>'Home'));

		if (!empty($search)) {
 			$this->db->like('bList.banner_text', $search, 'after');
		}
		$this->db->order_by('bList.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data'] = $query->result_array();
        return $data;

		
	}	

	/*	get banner details by id */
	public function getBannerDetailsById($id) 
	{
		$this->db->select("bList.bannerImage,bList.banner_text,bList.id as banner_id");
		$this->db->from('auto_banner bList');
		$this->db->where(array('id'=>base64_decode($id)));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	/* update bank details */
	public function updateBannerDetails($data,$id) {
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update('auto_banner');
		//echo $this->db->last_query();die;
		return $this->db->affected_rows();
	}

	/*	Change book status */
	public function changeBanerStatus($data) {

		$sql = "UPDATE auto_banner SET status = ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		return $query;
	}

	public function deleteBanner($data) {
		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['banner_id']));
		$res = $this->db->update('auto_banner');
		return $this->db->affected_rows();
	}


	/*	get all Banner details */
	public function getPromotionBannerList($limit = 10, $offset = 0, $search='') {
		
		$this->db->select("SQL_CALC_FOUND_ROWS bList.bannerImage,bList.id,bList.vendor_id, busInfo.company_name,IF(bList.status = 'A','Active','Inactive') AS `status`,bList.id",FALSE);
		$this->db->from('auto_banner bList');
		$this->db->join('vendor_info busInfo','busInfo.id = bList.vendor_id','left');
		$this->db->where(array('bList.status !=' =>'D','bList.banner_type'=>'Promo'));

		if (!empty($search)) {
 			$this->db->like('busInfo.company_name', $search, 'both');
		}
		$this->db->order_by('bList.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data'] = $query->result_array();
        return $data;
	}	

	public function getPromoBannerDetailsById($id){
		$this->db->select("bList.bannerImage,bList.id as banner_id,bList.vendor_id, busInfo.company_name");
		$this->db->from('auto_banner bList');
		$this->db->join('vendor_info busInfo','busInfo.id = bList.vendor_id','left');
		$this->db->where(array('bList.id'=>base64_decode($id),'bList.banner_type'=>'Promo'));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	
}

/* End of file BankModel.php */
/* Location: ./application/models/BankModel.php */