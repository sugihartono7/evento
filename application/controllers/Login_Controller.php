<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Login_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
		}
		
		
		function index() {
			$this->load->view('login/v_login');
			
		}
		
		function do_login(){
			$this->load->model('User_model');
			$username = $this->input->post('txt_username');
			$pass = $this->input->post('txt_pass');
			$res = $this->User_model->login($username, $pass);
			
			if ($res){
				$sess_array = array();
				foreach($res as $row){
					$sess_array = array(
						'username' => $row->username,
						'role' => $row->role,
						'store_code' => $row->store_code,
						'division_code' => $row->division_code,
						'md_name' => $row->md_name
					);
					$this->session->set_userdata('event_logged_in', $sess_array);
				}
				
				redirect('home');
				
			} else {
				redirect('login');
			}
			
		}
		
		function logout() {
			$this->session->unset_userdata('event_logged_in');
			session_destroy();
			redirect('login', 'refresh');
		}
		
		
		
	}

?>