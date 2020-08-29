<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Brand_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
			$this->is_logged_in();
			$this->load->model("Brand_model");
		}
		
		
		function index() {
				
			$data['head'] = 'template/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'brand/v_content';
			$data['right_menu'] = 'brand/v_right_menu';
			$data['footer'] = 'brand/v_footer';
		
			$this->load->view('brand/v_brand', $data);
		}
		
		function all_list() {
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_brand_active'] = 'color:#FFF';

			$data['head'] = 'brand/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'brand/v_all_list';
			$data['right_menu'] = 'brand/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$data['list'] = $this->Brand_model->all_list();
			$this->load->view('brand/v_brand', $data);
		}
		
		function add() {
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_brand_active'] = 'color:#FFF';

			$data['head'] = 'brand/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'brand/v_add_new';
			$data['right_menu'] = 'brand/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$this->load->view('brand/v_brand', $data);
		}
		
		
		
		
	}

?>