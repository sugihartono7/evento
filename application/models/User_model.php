<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class User_model extends CI_Model {
		
		function __construct(){
			
		}
		
		function login($username, $pass) {
			$this->db->select('username, store_code, role, division_code, md_name');
			$this->db->from('mst_user');
			$this->db->where('username', $username);
			$this->db->where('password', md5($pass));
			$this->db->where('active', '1');
			$this->db->limit(1);
			
			$q = $this->db->get();
			
			if($q->num_rows()==1){
				return $q->result();
			} else return false;
			
		}
		
		function add_log($id_kry, $username){
			$id = $this->get_nextval();
			$date = date("Y-m-d H:i:s");
			
			$data = array(
					   'id' => $id ,
					   'id_karyawan' => $id_kry,
					   'username' => $username,
					   'last_log' => $date
					);
			
			$this->db->insert('mst_log', $data);
			
		}
		
		
		
		function add_user($pengguna){
			$username = $this->input->post('txt_username');
			$pass = md5($this->input->post('txt_pass'));
			$email = $this->input->post('txt_email');
			
			$disabled = $this->input->post('rb_stat');
			$superuser = $this->input->post('cb_level');

			$id_jbt = $this->input->post('cb_jbt');
			$nik = $this->input->post('txt_nik');
			$status = '0';
			
			$cabang = $this->input->post('cb_cabang');
			$dept = $this->input->post('cb_dept');
			$boss = $this->input->post('cb_boss');
			
			$date = date("Y-m-d H:i:s");
			$data = array(
					   'id' => '' ,
					   'username' => $username ,
					   'password' => $pass,
					   'email' => $email,
					   'disable_state' => $disabled,
					   'superuser_state' => $superuser,
					   'id_jbt' => $id_jbt,
					   'nik' => $nik,
					   'status' => $status,
					   'cabang_id' => $cabang,
					   'department_id' => $dept,
					   'boss_nik' => $boss,
					   'create_date' => $date,
					   'create_by' => $pengguna
					);
			$this->db->insert('user_session', $data); 
		}
		
		function edit_user($pass){
			$id = $this->input->post('txt_id');
			$username = $this->input->post('txt_username');
			//$pass = md5($this->input->post('txt_pass'));
			$email = $this->input->post('txt_email');
			
			$disabled = $this->input->post('rb_stat');
			$superuser = $this->input->post('cb_level');
			
			$id_jbt = $this->input->post('cb_jbt');
			$nik = $this->input->post('txt_nik');
			$status = '0';
			
			$cabang = $this->input->post('cb_cabang');
			$dept = $this->input->post('cb_dept');
			$boss = $this->input->post('cb_boss');
			
			$date = date("Y-m-d H:i:s");
			
			$data = array(
				'username' => $username,
				'password' => $pass,
				'email' => $email,
				'disable_state' => $disabled,
				'superuser_state' => $superuser,
				'id_jbt' => $id_jbt,
				'nik' => $nik,
				'cabang_id' => $cabang,
				'department_id' => $dept,
				'boss_nik' => $boss
			);
			
			$this->db->where('id', $id);
			$this->db->update('user_session', $data); 
			
		}
		
		function get_nextval(){
			$this->db->select("nextval('mst_log_id_seq') as next_value");
			$q = $this->db->get()->row();
			$r = $q->next_value;
			return $r;
		}
		 
		
		function get_user_list(){
			$this->db->select('us.boss_nik, us.disable_state, us.username, us.nik, c.nama_cabang, us.id, j.nama_jbt, d.nama_dept'); 
			$this->db->from('user_session us');
			$this->db->join('department d', 'd.id_dept = us.department_id');
			$this->db->join('cabang c', 'c.id_cabang = us.cabang_id');
			$this->db->join('jabatan j', 'j.id_jbt = us.id_jbt');
			$this->db->order_by('c.id_cabang ASC, us.username ASC'); 
			
			$ambil = $this->db->get();
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
		}	
			
		function get_gold_id($user_id){
			//$this->db->query("SELECT c.gold_id FROM cabang c JOIN user_session us ON (us.cabang_id=c.id_cabang) WHERE  us.id = '$user_id' ");
			$this->db->select('c.gold_id'); 
			$this->db->from('cabang c');
			$this->db->join('user_session us', 'us.cabang_id=c.id_cabang');
			$this->db->where('us.id', $user_id);
			
			$q = $this->db->get()->row();
			$r = $q->gold_id;
			return $r;		
		}
		
		function get_init_cabang($user_id){
			//$this->db->query("SELECT c.init_cabang  FROM cabang c, user_session us  WHERE c.id_cabang=us.cabang_id AND us.id = '$user_id'  ");
							
			$this->db->select('c.init_cabang'); 
			$this->db->from('cabang c');
			$this->db->join('user_session us', 'us.cabang_id=c.id_cabang');
			$this->db->where('us.id', $user_id);
			
			$q = $this->db->get()->row();
			$r = $q->init_cabang;
			return $r;		
		}
		
		function get_user_combo(){
			$this->db->select('us.id, us.username'); 
			$this->db->from('user_session us');
			$this->db->order_by('us.cabang_id', 'ASC'); 
			$this->db->order_by('us.department_id', 'ASC'); 
			
			$ambil = $this->db->get();
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
		}
		
		function get_update_data($id){
			$this->db->select('*'); 
			$this->db->from('user_session');
			$this->db->where('id', $id); 
			
			$ambil = $this->db->get();
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
			
		}
		
		function get_atasan_combo(){
			$this->db->select('id, nik, username'); 
			$this->db->from('user_session');
			$ambil = $this->db->get();
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
		}
		
		function cek_nik($nik){
			$this->db->select('nik');
			$this->db->from('user_session');
			$this->db->where('nik', $nik);
			$q = $this->db->get();

			if ($q->num_rows() > 0){
				$ada = 1;
			}else $ada = 0;

			return $ada;
		}
		
		
		function edit_password($id){
			$pass = md5($this->input->post('txt_pass'));
			$date = date("Y-m-d H:i:s");
			
			$data = array(
				'password' => $pass
			);
			
			$this->db->where('id', $id);
			$this->db->update('user_session', $data); 
			
		}

	}
	
	
	
	
?>