<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DepartmentModel extends CI_Model {

	public function fetchDepartments($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS dp.id, dp.department_name, date_format(dp.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, IF(dp.status = 1,'Active','Inactive') AS `status`",FALSE);
		$this->db->from('department dp');
		$this->db->where(array('dp.status !='=>'3'));

		if (!empty($search)) {
 			$this->db->like('dp.department_name', $search, 'after');
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by('dp.id','desc');
		$query = $this->db->get();
        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	public function deleteDepartment($data){
		$sql   = "DELETE FROM department WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		return $this->db->affected_rows();
	}

	public function getDepartmentDetailsById($id){
		$this->db->select("dp.id, dp.department_name");
		$this->db->from('department dp');
		$this->db->where(array('dp.id' =>base64_decode($id)));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	} 

	public function update($data,$id){
		$sql1 = "UPDATE department SET department_name =  '".$data['department_name']."' WHERE id = ?";
		$query1 = $this->db->query($sql1, array(base64_decode($id)));
		return $this->db->affected_rows();
	}

	public function changeStatus($data){
		$sql = "UPDATE department SET status =  ( CASE WHEN STATUS = 1 THEN 0 ELSE 1 END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		return $query;
	}

}

/* End of file DepartmentModel.php */
/* Location: ./application/models/DepartmentModel.php */