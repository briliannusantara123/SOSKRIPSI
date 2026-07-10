<?php 
	class Home_model extends CI_model {
		public function cek($type)
		{
			$date = date('Y-m-d');
			$this->db->select('*');
			$this->db->from('sh_no_series');
			$this->db->where('id',$type);
			$query = $this->db->get()->row_array();

			return $query;
		}
	}