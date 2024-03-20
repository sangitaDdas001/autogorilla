<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardModel extends CI_Model {

	public function getUserCount()
	{
		$sql = $this->db->select("id")->from("credit_user")->where(array("status"=>'A'))->get();
		$activeCount = $sql->num_rows();

		$sql = $this->db->select("id")->from("credit_user")->where(array("status"=>'I'))->get();
		$inactiveCount = $sql->num_rows();

		$sql = $this->db->select("id")->from("credit_user")->get();
		$totalUsers = $sql->num_rows();

		$sql = $this->db->select("id")->from("credit_all_bank_name")->where(array("status"=>'A'))->get();
		$bankCount = $sql->num_rows();

		$sql = $this->db->select("id")->from("credit_user_loan_application")->where(array("status"=>'A'))->get();
		$loanAppliedCount = $sql->num_rows();

		return array('active'=>$activeCount, 'inactive'=>$inactiveCount, 'totalUsers'=>$totalUsers, 'banks'=>$bankCount, 'loan'=>$loanAppliedCount);
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

	public function fetchDetails($tablename,$conditions) {
		$sql = $this->db->get_where($tablename, $conditions);
		$res = $sql->result_array();
		return $res;
	}
}

/* End of file DashboardModal.php */
/* Location: ./application/models/DashboardModal.php */

?>