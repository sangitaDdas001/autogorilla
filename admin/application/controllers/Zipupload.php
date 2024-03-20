<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zipupload extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
	}

	public function index()
	{
		$zipFiles 				= getZipFilesList('C:/xampp/htdocs/autogorilla_live/uploads/imagezip/');
		$data['zipFiles'] 		= $zipFiles;
		$data["page_title"] 	= "View Category Information";
        $page["layout_content"] = $this->load->view('pages/zip/zipdata', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function uploadZipfile(){
		$upload_folder = 'imagezip';
		$config['upload_path'] = UPLOAD_IMAGE_URL.$upload_folder; // Set the upload directory
		$config['allowed_types'] = 'zip';       // Allowed file types
		$config['max_size'] = 2048;   
		
		$this->load->library('upload', $config);          
		// Maximum file size in kilobytes
		if ($this->upload->do_upload('imagezip')) {
		    // File uploaded successfully
		    $data = $this->upload->data();
		    // Additional processing or database insertion can be done here
		    $this->session->set_flashdata('cat_success','Category added successfully');
			redirect('Zipupload/index');
		} else {
		    // File upload failed, handle the error
		    $error = $this->upload->display_errors();
		    // Handle or display the error as needed
		    $this->session->set_flashdata('cat_success','Category added successfully');
			redirect('Zipupload/index');
		}
	}

	public function extract_zip(){
	
		$zip_file_path = 'C:/xampp/htdocs/autogorilla_live/uploads/imagezip/forprojectuse_1.zip';
		$target_directory = 'C:/xampp/htdocs/autogorilla_live/uploads/zipextract_img/';

		$zip = new ZipArchive;

	    if ($zip->open($zip_file_path) === TRUE) {
    if (!is_dir($target_directory)) {
        mkdir($target_directory, 0777, true);
    }

    if ($zip->extractTo($target_directory)) {
        $zip->close();
        echo 'ZIP file extracted successfully.';
    } else {
        echo 'Failed to extract the ZIP file. Error: ' . $zip->getStatusString();
    }
} else {
    echo 'Failed to open the ZIP file.';
}


	} 

	
	
}

?>