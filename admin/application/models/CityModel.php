<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CityModel extends CI_Model {

	/**
	 * Insert record based on table name and post data array. 
	*/
	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	// get state method
    public function getAllState($countryId) {
        $this->db->select(array('s.id as state_id', 's.country_id', 's.state_name'));
        $this->db->from('auto_state as s');
        $this->db->where(array('s.country_id'=>$countryId,'s.status'=>'A'));
        $query = $this->db->get();
        return $query->result_array();
    }

	public function getAllCityDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS catN.city_name, catN.city_image, catN.created_at, IF(catN.status = 'A','Active','Inactive') AS `status`,catN.id,catN.latitude,catN.longitude,country.country_name, state.state_name,catN.country_id,catN.state_id",FALSE);
		$this->db->from('auto_city catN');
		$this->db->join('auto_country country', 'country.id = catN.country_id','left');
		$this->db->join('auto_state state', 'state.id = catN.state_id','left');
		$this->db->where('catN.status !=', 'D');
		if (!empty($search)) {
 			$this->db->like('catN.city_name', $search, 'after');
		}
		$this->db->order_by('catN.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function changeStatus($data){
		$sql = "UPDATE auto_city SET status =  ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['city_id'])));
		return $query;
	}

	public function deleteCityDetails($data){
		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['city_id']));
		$res = $this->db->update('auto_city');
		return $this->db->affected_rows();
	}

	public function getCityDetailsById($id){
		$this->db->select("c.city_name, c.id, c.created_at,c.city_image,c.latitude,c.longitude,country.country_name, country.id as country_id,state.id as state_id, state.state_name");
		$this->db->from('auto_city c');
		$this->db->join('auto_country country', 'country.id = c.country_id','left');
		$this->db->join('auto_state state', 'state.id = c.state_id','left');
		$this->db->where(array('c.id'=>base64_decode($id)));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	public function updateCityDetails($data,$id){
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update('auto_city');
		return $res;
	}

	public function exportCityDetails(){
		$this->db->select("c.id, c.city_name, c.state_id, c.country_id, state.state_name, country.country_name");
		$this->db->from('auto_city c');
		$this->db->join('auto_country country', 'country.id = c.country_id','left');
		$this->db->join('auto_state state', 'state.id = c.state_id','left');
		$this->db->where(array('c.status'=>'A'));
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
	}
	
}

/* End of file CityModel.php */
/* Location: ./application/models/CityModel.php */