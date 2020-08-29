<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Pic_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
			$this->is_logged_in();
			$this->load->model("Pic_model");
		}
		
		function index() {
			$data['head'] = 'template/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'pic/v_content';
			$data['right_menu'] = 'pic/v_right_menu';
			$data['footer'] = 'pic/v_footer';
			
			$this->load->view('pic/v_pic', $data);
		}
		
		function all_list() {
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_pic_active'] = 'color:#FFF';

			$data['head'] = 'pic/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'pic/v_all_list';
			$data['right_menu'] = 'pic/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$data['list'] = $this->Pic_model->all_list();
			$this->load->view('pic/v_pic', $data);
		}
		
		function do_edit() {
			$usr = $this->session->userdata['event_logged_in']['username'];
			$r = $this->Pic_model->do_edit($usr);
			
			if ($r){
				echo "<script>
						alert('Data updated .');
						location.href = '".base_url()."pic/all_list';
					</script>";
			} else {
				echo "<script>
						alert('Data error .');
						location.href = '".base_url()."pic/all_list';
					</script>";
			}
		}
		
		
		
		
	}

?>