<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		
		
	class Public_Controller extends MY_Controller {
		
		//public $menu_active = "dcjq-parent active";
		//public $submenu_active = "color:#FFF";
		
		function __construct(){
			parent::__construct();
			//$this->is_logged_in();
			
		}
		
		function is_logged_in(){
			if(!isset($this->session->userdata['event_logged_in']['id']) || $this->session->userdata['event_logged_in']['id'] != true) {
				show_404();
				//echo 'silakan login dahulu .	';
			}
		}

/*		
		function set_menu_active(){
			$this->menu_active = "";
		}
		
		function get_menu_active(){
			return $this->menu_active;
		}
		
		// param = 1
		function success_msg(){
			$msg = "<div class='alert alert-success alert-dismissable'>
						<button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
						Data tersimpan.
					</div>";	
			return $msg;
		}
		
		// param = 2
		function success_msg_edit(){
			$msg = "<div class='alert alert-success alert-dismissable'>
						<button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
						Data berhasil diubah.
					</div>";	
			return $msg;
		}
		
		// param = 3
		function success_msg_delete(){
			$msg = "<div class='alert alert-success alert-dismissable'>
						<button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
						Data berhasil dihapus.
					</div>";	
			return $msg;
		}
		
	
		function show_login_error() { 
			//$this->output->set_status_header('404'); 
			$data['message'] = 'please contact administrator .'; // View name 
			$this->load->view('error_login', $data);//loading in my template 
		} 
	*/	
		
	}
?>