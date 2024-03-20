<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('LoginModel');
		if($this->session->userdata('adminsessiondetails')) {
			redirect('dashboard');
		} 
	}

	public function index() {
		$submit = $this->input->post('submit');
		if(!empty($submit)){
			unset($_POST['submit']);
			try {
				$data = validatePostData($this->input->post());
			} catch (Exception $e) {
				$this->session->set_flashdata('login_error','Invalid credentials');
				redirect('login');
			}
			
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_checkEmail');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('login_error',validation_errors());
				redirect('login');
	        }

			$result = $this->LoginModel->checkLogin($data);
			if(empty($result)) {
				$this->session->set_flashdata('login_error','Invalid credentials');
				redirect('login');
			} else {
				if($result['status']) {
					$arr = array('email'=>$result['data']['email'], 'name' => $result['data']['name'],'id' => $result['data']['id']);
					$this->session->set_userdata('adminsessiondetails',$arr);
					redirect('dashboard');
				} else {
					$this->session->set_flashdata('login_error',$result['message']);
					redirect('login');
				}
			}
		}
		$data["page_title"] = "Login";
        $page["layout_content"] = $this->load->view('pages/login', $data, true);
        $page["script_files"] = $this->load->view('scripts/login/login_scripts','', true);
        $this->load->view('layouts/login_layout', $page);
	}


	public function checkEmail($str) {
        if(!empty($str)) {
            if(!filter_var($str, FILTER_VALIDATE_EMAIL)) {
            	$this->form_validation->set_message('checkEmail', 'Invalid %s format');
            	return false;
            }
            return true;
        }
    }

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */