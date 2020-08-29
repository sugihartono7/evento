<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Pic_model extends CI_Model {
		
		function __construct(){
			date_default_timezone_set("Asia/Jakarta");
		}
		
		function all_list(){	
			/*
			$sql = "select distinct a.id, a.name AS pic_name, b.supp_desc AS supplier_name, a.created_date
					from mst_pic a left join mst_supplier b ON(a.supplier_code=b.supp_code)
					";
			*/		
			$sql = "select string_agg(x.pic_name, ',') AS pic_name, x.supplier_name, x.supplier_code AS supplier_code, x.created_date
					from
					(
						select  distinct a.supplier_code, a.name AS pic_name,  b.supp_desc AS supplier_name, b.created_date
						from mst_pic a join mst_supplier b ON(a.supplier_code=b.supp_code)
						where a.name is not null
					) as x
					group by x.supplier_name, x.supplier_code, x.created_date";

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

		function get_nextval_pic(){
			$this->db->select("nextval('mst_pic_id_seq') as next_value");
			$q = $this->db->get()->row();
			$r = $q->next_value;
			return $r;
		}

		function do_edit($username){
			$supplier_code = $this->input->post('idToUpdate');
			$pic_name = $this->input->post('txt_pic');
			$update_at = date("Y-m-d H:i:s");

			//delete pic
			$del = "DELETE FROM mst_pic WHERE supplier_code='$supplier_code' ";
			$this->db->query($del);

			for($i = 0; $i <= count($pic_name)-1; $i++){
				$data = array(
					   'id' => $this->get_nextval_pic(),
					   'name' => $pic_name[$i],
					   'supplier_code' => $supplier_code,
					   'created_date' => null,
					   'created_by' => $username,
					   'updated_at' => null,
					   'updated_by' => null
					);
			
				$this->db->insert('mst_pic', $data);
			}

			return true;
		}



	}
?>