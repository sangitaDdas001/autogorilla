<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backgroundjob extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('AreaModel'));
	}

	public function index(){
		$data = array();
		// Add a job to the queue for background processing
		$job_data = ['csv_file' => $csv_file]; // Pass necessary data to process the CSV
		$this->queue->push('csv_import_job', $job_data);

		$page["layout_content"] 	= 	$this->load->view('pages/job_b/background_job', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}
}
?>