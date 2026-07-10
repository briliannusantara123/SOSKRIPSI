<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_model extends CI_model {
		
	public function getpaketso()
		{
				$this->db->select('i.*');
				$this->db->from('sh_m_item i');
				$this->db->where('i.is_paket_so',1);
				return $this->db->get()->result();
		}
		public function getDataPaket($item_code, $sub_category)
		{
		    $ic = $this->session->userdata('id');

		    if (!empty($sub_category)) {
		        $where1 = "LOWER(v.varian_category) = '" . strtolower(urldecode($sub_category)) . "'";
		    } else {
		        $where1 = "1=1"; // Dummy condition if $sub_category is empty
		    }

		    $this->db->select('i.is_sold_out, i.with_option, i.no, i.image_path, i.stock, i.need_stock, i.harga_weekday, i.harga_weekend, i.harga_holiday, i.sub_category, i.description, v.*');
		    $this->db->from('sh_m_varian_option v');
		    $this->db->join('sh_m_item i', 'i.no = v.item_code', 'inner');
		    $this->db->where($where1);
		    $this->db->where('v.is_active', 1);
		    $this->db->where('v.parent_code', $item_code);
		    $this->db->group_by('v.item_code'); // Menampilkan hanya satu item_code yang unik
		    $this->db->order_by('v.id', 'asc');
		    
		    return $this->db->get()->result();
		}

		public function getCodeItem($item_code, $sub_category)
		{
		    $ic = $this->session->userdata('id');

		    if ($sub_category != '') {
		        $where = "LOWER(sub_category) = '" . strtolower(urldecode($sub_category)) . "'";
		        $where1 = "LOWER(varian_category) = '" . strtolower(urldecode($sub_category)) . "'";
		    } else {
		        $where = "1=1"; // A dummy condition if $sub_category is empty
		        $where1 = "1=1"; // A dummy condition if $sub_category is empty
		    }

		    $this->db->select('v.id');
		    $this->db->from('sh_m_varian_option v');
		    $this->db->where($where1);
		    $this->db->where('v.is_active', 1);
		    $this->db->order_by('v.id', 'asc');
		    $query2 = $this->db->get()->result_array();  // Get result as array

		    // Extract the 'id' values and convert to comma-separated string
		    $ids = array_column($query2, 'id');
		    $idString = implode(',', $ids);

		    return $idString;
		}

		public function getCode($item_code, $sub_category)
		{
		    $ic = $this->session->userdata('id');

		    if ($sub_category != '') {
		        $where = "LOWER(sub_category) = '" . strtolower(urldecode($sub_category)) . "'";
		        $where1 = "LOWER(varian_category) = '" . strtolower(urldecode($sub_category)) . "'";
		    } else {
		        $where = "1=1"; // A dummy condition if $sub_category is empty
		        $where1 = "1=1"; // A dummy condition if $sub_category is empty
		    }

		    $this->db->select('v.item_code');
		    $this->db->from('sh_m_varian_option v');
		    $this->db->where($where1);
		    $this->db->where('v.is_active', 1);
		    $this->db->order_by('v.id', 'asc');
		    $query2 = $this->db->get()->result_array();  // Get result as array

		    // Extract the 'id' values and convert to comma-separated string
		    $ids = array_column($query2, 'id');
		    $idString = implode(',', $ids);

		    return $idString;
		}


		public function cekDataPaket($item_code)
		{
		    // Query untuk menghitung jumlah total per 'sub_category' dari sh_m_item
		    $this->db->select('i.sub_category, COUNT(i.sub_category) as jml');
		    $this->db->from('sh_m_item i');
		    $this->db->like('i.item_code', $item_code);
		    $this->db->group_by('i.sub_category');
		    $query1 = $this->db->get()->result();

		    // Query untuk menghitung jumlah total per 'sub_category' dari sh_m_varian_option
		    $this->db->select('i.varian_category as sub_category, COUNT(i.varian_category) as jml');
		    $this->db->from('sh_m_varian_option i');
		    $this->db->join('sh_m_item a', 'a.no = i.item_code', 'inner');
		    $this->db->like('i.parent_code', $item_code);
		    $this->db->where('i.is_active', 1);
		    $this->db->group_by('i.varian_category');
		    $query2 = $this->db->get()->result();

		    // Query untuk menghitung jumlah 'sold_out' per 'sub_category' dari sh_m_item
		    $this->db->select('i.sub_category, COUNT(i.sub_category) as sold_out');
		    $this->db->from('sh_m_item i');
		    $this->db->like('i.item_code', $item_code);
		    $this->db->where('i.is_sold_out', 1);
		    $this->db->group_by('i.sub_category');
		    $query3 = $this->db->get()->result();

		    // Query untuk menghitung jumlah 'sold_out' per 'sub_category' dari sh_m_varian_option
		    $this->db->select('i.varian_category as sub_category, COUNT(i.varian_category) as sold_out');
		    $this->db->from('sh_m_varian_option i');
		    $this->db->join('sh_m_item a', 'a.no = i.item_code', 'inner');
		    $this->db->like('i.parent_code', $item_code);
		    $this->db->where('i.is_active', 1);
		    $this->db->where('a.is_sold_out', 1);
		    $this->db->group_by('i.varian_category');
		    $query4 = $this->db->get()->result();

		    // Menggabungkan hasil dari kedua query yang tersedia
		    $mergedResultsAvailable = array_merge($query1, $query2);

		    // Menggabungkan hasil dari kedua query yang terjual habis
		    $mergedResultsSoldOut = array_merge($query3, $query4);

		    // Menyiapkan array untuk hasil akhir
		    $finalResults = [];

		    // Menyiapkan hasil untuk setiap sub_category yang tersedia
		    foreach ($mergedResultsAvailable as $result) {
		        $sub_category = strtolower($result->sub_category);
		        // Menyimpan jumlah total per sub_category
		        if (!isset($finalResults[$sub_category])) {
		            $finalResults[$sub_category] = [
		                'sub_category' => $result->sub_category,
		                'jml' => 0,
		                'sold_out' => 0 // default
		            ];
		        }
		        // Menambahkan jumlah item ke total sub-kategori
		        $finalResults[$sub_category]['jml'] += $result->jml;
		    }

		    // Menambahkan jumlah sold_out ke hasil akhir
		    foreach ($mergedResultsSoldOut as $result) {
		        $sub_category = strtolower($result->sub_category);
		        // Menambahkan jumlah sold_out ke sub_category yang sesuai
		        if (isset($finalResults[$sub_category])) {
		            $finalResults[$sub_category]['sold_out'] = $result->sold_out;
		        } else {
		            // Jika sub_category hanya ada dalam sold_out, tambahkan ke hasil
		            $finalResults[$sub_category] = [
		                'sub_category' => $result->sub_category,
		                'jml' => 0, // default
		                'sold_out' => $result->sold_out
		            ];
		        }
		    }

		    // Mengembalikan hasil dalam format array
		    return $finalResults;
		}
		public function sub_category_paket()
		{
		    $item_code = $this->input->post('item_code');
		    // var_dump($item_code);exit();
			// First query
			$this->db->select('sub_category');
			$this->db->from('sh_m_item');
			$this->db->like('item_code', $item_code);
			$this->db->group_by('sub_category');
			$this->db->order_by('id', 'asc');
			$query1 = $this->db->get()->result_array();

			// Second query
			$this->db->select('varian_category as sub_category,max_qty');
			$this->db->from('sh_m_varian_option');
			$this->db->like('parent_code', $item_code);
			$this->db->group_by('varian_category');
			$this->db->order_by('id', 'asc');
			$query2 = $this->db->get()->result_array();

			// var_dump($query2);exit();

			// Merge the two arrays
			$merged_result = array_merge($query1, $query2);

			// Remove duplicate values based on 'sub_category'
			$unique_result = [];
			$sub_category_map = [];

			foreach ($query2 as $item) {
			    $sub_category = $item['sub_category'];
			    if (!isset($sub_category_map[$sub_category])) {
			        $sub_category_map[$sub_category] = true; // Mark the sub_category as seen
			        $unique_result[] = $item; // Add the item to the unique result array
			    }
			}
		    return $query2;
		}
		public function get_cart_details($ic,$table,$itemCode,$uoi)
		{
			$this->db->select('*');
	        $this->db->from('sh_cart_details');
	        // $where = "left(entry_date,10) ='".date('Y-m-d H:i:s')."' and id_customer = '".$ic."' and id_table = '".$table."' and item_code = '".$itemCode."' and user_order_id = '".$uoi."'";
	        $this->db->where(['id_customer'=>$ic,'id_table'=>$table,'item_code'=>$itemCode,'user_order_id' => $uoi]);
	        $query = $this->db->get();
	        return $query;
		}
		public function getcartpaket($paket)
		{
			$ic = $this->session->userdata('id');
		    $date = date('Y-m-d');
		    $uoi = $this->session->userdata('user_order_id');
		    
		    $this->db->select('d.item_code, m.sub_category, m.image_path, m.need_stock,m.is_sold_out,m.need_stock,m.stock, d.*');
		    $this->db->from('sh_cart_details d');
		    $this->db->join('sh_m_item m', 'm.no = d.item_code');
		    
		    
		    $where = "LEFT(d.entry_date, 10) = '" . $date . "' AND d.id_customer = '" . $ic . "' AND d.user_order_id = '" . $uoi . "' AND d.id_cart = '".$paket."'";
		    $this->db->where($where);
		    
		    $query = $this->db->get();
		    return $query;
		}
		public function cekitempaket($code)
		{
			$this->db->select('a.need_stock,a.stock,a.description,i.*');
		    $this->db->from('sh_m_varian_option i');
		    $this->db->join('sh_m_item a', 'a.no = i.item_code', 'inner');
		    $this->db->where('i.id', $code);
		    $query2 = $this->db->get()->row();
		    return $query2;
		}
		public function save($table,$data, $where='') {
			if ($where == '') {
				$this->db->insert($table, $data);
				return $this->db->insert_id();
			}
			return $this->db->update($table, $data, $where);			
		}
		public function item_tersedia($pc,$sub)
		{
		    if ($sub != '') {
		        $where = "LOWER(sub_category) = '" . strtolower(urldecode($sub)) . "'";
		        $where1 = "LOWER(varian_category) = '" . strtolower(urldecode($sub)) . "'";
		    }

		    $this->db->select('*');
		    $this->db->from('sh_m_item');
		    $this->db->like('parent_id', $pc);
		    $this->db->where($where);
		    $this->db->where('is_sold_out',0);
		    $this->db->order_by('id', 'asc');
		    $queryitem = $this->db->get()->result();

		    $this->db->select('i.product_info,i.is_sold_out,i.with_option,i.no,i.image_path,i.stock,i.need_stock,i.harga_weekday,i.harga_weekend,i.harga_holiday,i.description,i.sub_category,v.*');
		    $this->db->from('sh_m_varian_option v');
		    $this->db->join('sh_m_item i', 'i.no = v.item_code', 'inner');
		    $this->db->like('v.parent_code', $pc);
		    $this->db->where($where1);
		    $this->db->where('v.is_active',1);
		    $this->db->where('i.is_sold_out',0);
		    $this->db->order_by('v.id', 'asc');
		    $queryvarian = $this->db->get()->result();
		    $result = array_merge($queryitem, $queryvarian);
		    return $queryvarian;
			
		}
		public function get_cart_item($id_cart, $item_code, $id_customer, $table, $id_trans, $uoi) {
		    $this->db->where('id_cart', $id_cart);
		    $this->db->where('item_code', $item_code);
		    $this->db->where('id_customer', $id_customer);
		    $this->db->where('id_table', $table);
		    $this->db->where('id_trans', $id_trans);
		    $this->db->where('user_order_id', $uoi);
		    
		    // Filter berdasarkan tanggal (pastikan format $date adalah 'Y-m-d')
		    $this->db->where('DATE(entry_date)', DATE('Y-m-d'));

		    return $this->db->get('sh_cart_details')->row_array();
		}

		public function update_batch_cart($data) {
		    return $this->db->update_batch('sh_cart_details', $data, 'id_cart');
		}

		public function insert_batch_cart($data) {
		    return $this->db->insert_batch('sh_cart_details', $data);
		}

}