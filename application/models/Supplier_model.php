<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Supplier_model extends CI_Model {
		
		function __construct(){
			date_default_timezone_set("Asia/Jakarta");
		}
		
		function all_list(){	
			$sql = "SELECT DISTINCT supp_code, supp_desc, city FROM mst_supplier";

			$query = $this->db->query($sql);
			return $query->result();
			
		}
		
		function add_new($username){
            $txt_kode = $this->input->post('txt_kode');
            $txt_gold = $this->input->post('txt_gold');
			$nama = $this->input->post('txt_nama');
			$txt_alamat = $this->input->post('txt_alamat');
			$txt_kontak = $this->input->post('txt_kontak');
			$active = '1';
			$date = date("Y-m-d H:i:s");
			
			$data = array(
					   'kode' => $txt_kode ,
					   'init' => $txt_gold,
					   'nama' => $nama,
					   'alamat' => $txt_alamat,
					   'kontak' => $txt_kontak,
					   'active' => $active,
					   'create_by' => $username,
					   'create_at' => $date,
					   'update_by' => '',
					   'update_at' => null
					);
			
			$this->db->insert('mst_supplier', $data);
			
		}

		function cek_kode($kode){
            $q = $this->db->query("select kode from mst_supplier where LOWER(kode)='".strtolower($kode)."' ");
			if ($q->num_rows() >= 1){
				$ada = 1;
			}else $ada = 0;

			return $ada;
		}

        function cek_kode_gold($init){
            $q = $this->db->query("select init from mst_supplier where LOWER(init)='".strtolower($init)."' ");

            if ($q->num_rows() >= 1){
                $ada = 1;
            }else $ada = 0;

            return $ada;
        }

		function show_modal($kode){
			$sql = "SELECT kode, init, nama, alamat, kontak, active from mst_supplier WHERE kode='$kode' ";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0){
				return $query;
			}
		}

		function edit($username){
			$kode = $this->input->post('txt_kode_show');
			$nama = $this->input->post('txt_nama_show');
			$alamat = $this->input->post('txt_alamat_show');
			$kontak = $this->input->post('txt_kontak_show');
			$active = $this->input->post('rb_active_show');

			$update_at = date("Y-m-d H:i:s");

			$data = array(
						'nama' => $nama,
						'alamat' => $alamat,
						'kontak' => $kontak,
						'active' => $active,
						'update_by' => $username,
						'update_at' => $update_at
					);
			$this->db->where('kode', $kode);
			$this->db->update('mst_supplier',$data);
		}



	}
?>