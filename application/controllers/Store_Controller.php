<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Store_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
			$this->is_logged_in();
			$this->load->model("Store_model");
		}
		
		
		function index() {
				
			$data['head'] = 'template/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'store/v_content';
			$data['right_menu'] = 'store/v_right_menu';
			$data['footer'] = 'store/v_footer';
			
			$this->load->view('store/v_store', $data);
		}
		
		function all_list() {
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_store_active'] = 'color:#FFF';

			$data['head'] = 'store/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'store/v_all_list';
			$data['right_menu'] = 'store/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$data['list'] = $this->Store_model->all_list();
			$this->load->view('store/v_store', $data);
		}
		
		function add() {
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_store_active'] = 'color:#FFF';

			$data['head'] = 'store/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'store/v_add_new';
			$data['right_menu'] = 'store/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$this->load->view('store/v_store', $data);
		}
		
		
		
		
	}

?>