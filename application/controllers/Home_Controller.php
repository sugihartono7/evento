<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Home_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
			$this->is_logged_in();
		}
		
		
		function index(){
			
			$data['head'] = 'template/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'home/v_content';
			$data['right_menu'] = 'home/v_right_menu';
			$data['footer'] = 'home/v_footer'; 
			
			$this->load->view('home/v_home', $data);
		}
		
		
		
	}

?>