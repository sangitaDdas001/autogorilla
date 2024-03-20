<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class LoginModel extends CI_Model {

	public function checkLogin($data=array())
	{
		if(empty($data)){
			return;
		}
		$sql = $this->db->select("name,email,password,id")->from("auto_admin")->where(array("email"=>$data["email"], 'status'=>'A'))->get();
		$res = $sql->result_array();
		if(empty($res)) {
			return array('status'=>false, 'message'=>'Account not found');
		} else {
			$password = $res[0]['password'];
			if(password_verify($data['password'], $password)) {
				return array('status'=>true, 'data'=>$res[0]);
			} else {
				return array('status'=>false, 'message'=>'Incorrect password');
			}
		}
	}
	
}

/* End of file LoginModal.php */
/* Location: ./application/models/LoginModal.php */

?>