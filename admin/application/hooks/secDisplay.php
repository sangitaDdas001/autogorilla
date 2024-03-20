<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class secDisplay {
	function commonDisplay() {
		
		$CI =& get_instance();
		$buffer = $CI->output->get_output();
		$search = array(
			  '{__CSSLINKS__}',
		      '{__HEADER__}',
			  '{__FOOTER__}',
			  '{__SIDEBAR__}'
		);
		$data=array();
		$title="Dashboard";
		$replace = array(
			$CI->load->view('include/csslinks',$data,true),
			$CI->load->view('include/header',$data,true),
			$CI->load->view('include/footer',$data,true),
			$CI->load->view('include/sidebar',$data,true),
			$title
		);
		$buffer = preg_replace($search, $replace, $buffer);
		$CI->output->set_output($buffer);
		$buffer = $CI->output->get_output();
		$CI->output->_display();
	}
	
}
