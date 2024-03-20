<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	protected $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model('DashboardModel');
	}

	public function index() {

		//$data['active_users'] = $this->DashboardModel->getUserCount();
        $data["page_title"]   = "Dashboard";
        $page["layout_content"] = $this->load->view('pages/dashboard', '', true);
        // $page["script_files"] = $this->load->view('scripts','', true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function editPassword(){
		try {
			$data = $this->input->post();
			if(!empty($data)) {
				$data = validatePostData($data);
				if(empty($data)) {
					$this->session->flashdata('error_msg','Please add password');
		        	redirect('dashboard/editPassword','refresh');
				}

				$this->form_validation->set_data($data);
		        $this->form_validation->set_rules('oldpassword', 'current password','trim|required');
		        $this->form_validation->set_rules('newpassword', 'New password','trim|required');
		        if ($this->form_validation->run() == FALSE) {
		            redirect('dashboard/editPassword','refresh');
		        }	

		     	$oldpassword = $data['oldpassword'];
				$newpassword = $data['newpassword'];

		        $adminDetails = $this->DashboardModel->fetchDetails('auto_admin',array('email'=>$this->sessionDetails['email']));
		        if(!empty($adminDetails)) {
		        	$curPass = $adminDetails[0]['password'];
		        	if(password_verify($oldpassword, $curPass)) {
		        		$new = password_hash($newpassword,PASSWORD_DEFAULT);
		        		$update = $this->DashboardModel->update('auto_admin',array('password'=>$new),array('email'=>$this->sessionDetails['email']));

		        		if($update) {
		        			$this->session->set_flashdata('success_msg','Password updated successfully');
		        			redirect('dashboard/editPassword');
		        		} else {
		        		    $this->session->set_flashdata('error_msg','Failed to update password');
		        			redirect('dashboard/editPassword');
		        		}
		        	} else {
		        		$this->session->set_flashdata('error_msg','Invalid current password');
		        		redirect('dashboard/editPassword');
		        	}
		        }	
			}
	        $data["page_title"] = "Change Password";
	        $page["layout_content"] = $this->load->view('pages/update_password', $data, true);
	        $this->load->view('layouts/dashboard_layout', $page);	
		} catch (Exception $e) {
			redirect('dashboard/editPassword');
		}
		
	}

}
