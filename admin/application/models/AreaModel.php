<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AreaModel extends CI_Model {

	public function getAreaData($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS area.area_name,area.created_at,city.city_name, IF(area.status = 'A','Active','Inactive') AS `status`,area.id",FALSE);
		$this->db->from('auto_city_area area');
		$this->db->join('auto_city city','city.id = area.city_id','left');
		$this->db->where(array('area.status !='=>'D'));

		if (!empty($search)) {
 			$this->db->like('area.area_name', $search, 'after');
		}
		$this->db->order_by('area.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function changeStatus($data) {
		$sql = "UPDATE auto_city_area SET status = ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		return $query;
	}

	public function getAreaDetailsById($id){
		$this->db->select("area.area_name,area.created_at,city.city_name, area.id,area.city_id ");
		$this->db->from('auto_city_area area');
		$this->db->join('auto_city city','city.id = area.city_id','left');
		$this->db->where(array('area.id'=>base64_decode($id)));
		$query = $this->db->get(); 
		$data  = $query->result_array();
        return $data;
	}

	public function fetchCityRecord(){
		$this->db->select("catN.city_name, catN.id ");
		$this->db->from('auto_city catN');
		$this->db->where('catN.status', 'A');
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
	}

	public function update($data,$id,$table) {
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update($table);
		return $this->db->affected_rows();
	}

	public function deleteArea($data){
		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['id']));
		$res = $this->db->update('auto_city_area');
		return $this->db->affected_rows();
	}

	
	/**
	 * Insert record based on table name and post data array. 
	*/
	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	public function exportAreaDetails(){
		$this->db->select("area.id,area.area_name,city.city_name,area.city_id ");
		$this->db->from('auto_city_area area');
		$this->db->join('auto_city city','city.id = area.city_id','left');
		$this->db->where(array('area.status'=>'A'));
		$query = $this->db->get(); 
		$data  = $query->result_array();
        return $data;
	}
}

/* End of file AreaModel.php */
/* Location: ./application/models/AreaModel.php */