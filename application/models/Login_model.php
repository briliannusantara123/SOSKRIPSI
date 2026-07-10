<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_model {
		
	public function get_walkin($username, $no_hp, $date)
	{
	    $this->db->select('*');
	    $this->db->from('sh_m_walkin');
	    $this->db->where('customer_name', $username);
	    $this->db->where('no_telp', $no_hp);
	    $this->db->where('create_date >=', $date . ' 00:00:00');
	    $this->db->where('create_date <=', $date . ' 23:59:59');

	    return $this->db->get()->row();
	}

	public function insert_customer($data, $visit_type)
	{	
	    // cek customer hari ini
	    // $this->db->where('customer_name', $data->customer_name);
	    // $this->db->where('no_telp', $data->no_telp);
	    // $this->db->where('DATE(create_date)', date('Y-m-d'));
	    // $cust = $this->db->get('sh_m_customer')->row();

	    // if ($cust) {
	    //     return $cust->id;
	    // }
		if ($visit_type == 'Walk In') {
			$visit_type = 'WalkIn';
		}
	    $insert = [
	        'customer_name' => $data->customer_name,
	        'member_name'   => $data->customer_name,
	        'no_telp'       => $data->no_telp,
	        'total_pax'     => $data->total_pax,
	        'visit_type'    => $visit_type,
	        'create_date'   => date('Y-m-d H:i:s')
	    ];
	    // var_dump($insert);exit();
	    $this->db->insert('sh_m_customer', $insert);
	    return $this->db->insert_id();
	}
	public function insert_reservation_cust($data, $customer_id)
	{
	    $this->db->where('id_customer', $customer_id);
	    $this->db->where('optdate', date('Y-m-d'));
	    $res = $this->db->get('sh_t_reservation_cust')->row();

	    if ($res) {
	        return $res->id;
	    }
	   

	    $insert = [
	        'id_customer' => $customer_id,
	        'total_pax'   => $data->total_pax,
	        'is_waiting'  => 0,
	        'cabang'      => $data->cabang,
	        'down_payment'=> 0,
	        'create_date' => date('Y-m-d H:i:s'),
	        'optdate'     => date('Y-m-d')
	    ];
	    
	    $this->db->insert('sh_t_reservation_cust', $insert);
	    return $this->db->insert_id();
	}
	public function new_datetime($datetime,$interval,$type, $part)
	{
		$mode = explode('|', $part);
		if($mode[0] == 'time'){
			$result = $this->db->query("select RIGHT(ADDDATE('".$datetime."', INTERVAL ".$interval." ".$type."),8) as result")->row();
		}else if($mode[0] == 'datetime'){
			$result = $this->db->query("select ADDDATE('".$datetime."', INTERVAL ".$interval." ".$type.") as result")->row();
		}else if($mode[0] == 'sorttime'){
			$result = $this->db->query("select LEFT(RIGHT(ADDDATE('".$datetime."', INTERVAL ".$interval." ".$type."),8),2) as result")->row();
		}else if($mode[0] == 'newtime'){
			$today = date('Y-m-d');
			$qH = "select * from sh_m_holiday where tipe = 0 and holiday_date = '".$today."'";
			$holiday = $this->db->query($qH)->num_rows();
			$session = 'WeekDay';
			$cekWeekEnd = date('D', strtotime($today));
			if($holiday > 0 || $cekWeekEnd === "Sat" || $cekWeekEnd === "Sun" || $cekWeekEnd === "Sab" || $cekWeekEnd === "Min"){
				$session = 'WeekEnd';
			}
			$q = "select * from sh_m_setup ";
			$setup = $this->db->query($q)->row();
			if($session == 'WeekEnd'){
				$interval = $setup->weekend_normal_time;
			}else{
				$interval = $setup->weekday_normal_time;
			}
			$result = $this->db->query("SELECT id,customer_name,booking_date, adddate(booking_date, interval ".$interval." MINUTE) as end_time,adddate('".$datetime."', interval ".$interval." MINUTE) as cur_time, case when CAST(TIMEDIFF(right(adddate(booking_date, interval ".$interval." MINUTE),8), right(adddate('".$datetime."', interval 120 MINUTE),8)) as SIGNED) > ".$interval." then right(adddate('".$datetime."', interval ".$interval." MINUTE),8) else right(adddate(booking_date, interval ".$interval." MINUTE),8) end as used_end_time FROM sh_m_booking where id=$mode[1]")->row();
		}
		return $result;

	}
	
	public function insert_rel_tableOLD($data, $customer_id, $res_id, $visit_type)
	{
	    $tables = explode('&', $data->table_no);
	    $rel_ids = [];

	    foreach ($tables as $tbl) {

	        $this->db->where('id_treservation', $res_id);
	        $this->db->where('id_table', $tbl);
	        $rel = $this->db->get('sh_rel_table')->row();

	        if ($rel) {
	            $rel_ids[] = $rel->id;
	            continue;
	        }

	        $insert = [
	            'id_treservation' => $res_id,
	            'id_table'        => $tbl,
	            'id_customer'     => $customer_id,
	            'cabang'          => $data->cabang,
	            'status'          => 'Order',
	            'visit_type'      => $visit_type,
	            'tipe_paket'      => $data->tipe_customer,
	            'created_date'    => date('Y-m-d')
	        ];

	        $this->db->insert('sh_rel_table', $insert);
	        $rel_ids[] = $this->db->insert_id();
	    }

	    return $rel_ids;
	}
	public function insert_rel_table($data, $customer_id, $res_id, $visit_type, $nomeja, $type)
	{
	    // cek duplicate
	    // $this->db->where('id_treservation', $res_id);
	    // $this->db->where('id_table', $nomeja);
	    // $exist = $this->db->get('sh_rel_table')->row();
	    // if ($exist) {
	    //     return $exist->id;
	    // }

	    // insert baru
	    $insert = [
	        'id_treservation' => $res_id,
	        'id_table'        => $nomeja,
	        'reff_table'	  => $nomeja,
	        'id_customer'     => $customer_id,
	        'cabang'          => $data->cabang,
	        'status'          => 'Order',
	        'visit_type'      => $type,
	        'tipe_paket'      => 'Ala Carte',
	        'created_date'    => date('Y-m-d')
	    ];
	    // var_dump($insert);exit();
	    $this->db->insert('sh_rel_table', $insert);
	    return $this->db->insert_id();
	}

	public function insert_transaction($data, $customer_id, $res_id, $rel_ids, $type, $username)
	{
	    $this->db->trans_begin(); // 🔒 START TRANSACTION

	    // ===============================
	    // Ambil No Series sesuai type
	    // ===============================
	    $desc = ($type == 'TakeAway') ? 'TakeAway' : 'Transaksi';
	    $ns = $this->getNoSeries($data->cabang, $desc);

	    if (!$ns) {
	        $this->db->trans_rollback();
	        return false;
	    }

	    $currentMonth = (int) date('n'); // 1–12
	    $currentYear  = (int) date('Y');

	    $running_no = (int) $ns->inc_by_no;

	    // ===============================
	    // CEK BULAN
	    // ===============================
	    if ((int) $ns->month !== $currentMonth) {
	        $running_no = 1; // reset nomor
	        $ns->month  = $currentMonth;
	    }

	    // ===============================
	    // CEK TAHUN
	    // ===============================
	    if ((int) $ns->year !== $currentYear) {
	        $ns->year = $currentYear;
	    }

	    // ===============================
	    // FORMAT ORDER NO
	    // ===============================
	    $no_pad = str_pad($running_no, $ns->length_no, '0', STR_PAD_LEFT);

		// format bulan 2 digit (01–12)
		$monthFormatted = str_pad($ns->month, 2, '0', STR_PAD_LEFT);

		// order no: PREFIX + MONTH + RUNNING NO
		$order_no = $ns->prefix . '-' . $monthFormatted . '-' . $no_pad;
		$isTakeAway = ($type === 'TakeAway');
	    // ===============================
	    // INSERT TRANSAKSI
	    // ===============================
	    $this->db->insert('sh_t_transactions', [
	        'id_customer'      => (int) $customer_id,
	        'id_treservation'  => (int) $res_id,
	        'id_rel_reservasi' => (int) $rel_ids,
	        'order_no'         => $order_no,
	        'create_date'      => date('Y-m-d H:i:s'),
	        'is_take_away'      => $isTakeAway ? 1 : 0,
	        'cabang'           => (int) $data->cabang,
	        'optdate'          => date('Y-m-d'),
	        'entry_by'		   => $username,
	    ]);

	    $trans_id = $this->db->insert_id();
	    if ($trans_id <= 0) {
	        $this->db->trans_rollback();
	        return false;
	    }

	    // ===============================
	    // UPDATE NO SERIES
	    // ===============================
	    $this->db->where('id', $ns->id)
	        ->update('sh_no_series', [
	            'last_no_used'   => $order_no,
	            'inc_by_no'      => $running_no + 1,
	            'month'          => $ns->month,
	            'year'           => $ns->year,
	            'last_date_used' => date('Y-m-d H:i:s')
	        ]);

	    // ===============================
	    // COMMIT / ROLLBACK
	    // ===============================
	    if ($this->db->trans_status() === FALSE) {
	        $this->db->trans_rollback();
	        return false;
	    }

	    $this->db->trans_commit();
	    return $trans_id;
	}





	public function getNoSeries($cabang, $description)
	{
	    return $this->db
	        ->where([
	            'cabang'      => $cabang,
	            'description' => $description,
	            'is_active'   => 1
	        ])
	        ->limit(1)
	        ->get('sh_no_series')
	        ->row();
	}


	public function insert_trans_reltable($trans_id, $rel_ids, $cabang)
	{
	        // $this->db->where('id_trans', $trans_id);
	        // $this->db->where('id_rel_table', $rel_ids);
	        // $cek = $this->db->get('sh_trans_reltable')->row();
	       
	        // if ($cek) {
	        //     return $cek->id;
	        // }

	        $this->db->insert('sh_trans_reltable', [
	            'id_trans'     => $trans_id,
	            'id_rel_table' => $rel_ids,
	            'cabang'       => $cabang,
	            'created_date' => date('Y-m-d')
	        ]);
	}
	public function get_holiday($cabang, $tipe='all', $tanggal='all')
	{
		if($cabang !='0'){
			$this->db->where(["h.cabang"=> $cabang]);
		}

		if($tipe != 'all'){
			$this->db->where(["h.tipe"=> $tipe]);
		}

		if($tanggal != 'all'){
			$this->db->where(["h.holiday_date"=> $tanggal]);
		}
		$this->db->where(["h.blocked"=> 0]);
		$this->db->select("h.*")
			->from("sh_m_holiday h")
			->order_by("h.id", "asc");
		return $this->db->get();
	}
	public function get_setup($cabang)
	{
		if($cabang !='0'){
			$this->db->where(["s.cabang"=> $cabang]);
		}
		$this->db->select("s.*")
			->from("sh_m_setup s")
			->order_by("s.id", "asc");
		return $this->db->get()->row();
	}
	public function get_booking_rcp($id,$mode=0)
	{
		if($mode == 0){
			$query = "select z.id,z.id_booking, z.booking_date, z.table_no, z.real_no, z.total_pax, (select y.id from sh_m_customer y where y.no_telp = w.no_telp and left(y.create_date,10) = left(w.booking_date,10) order by y.id desc limit 1) as customer_id, w.customer_name, z.cabang, z.name_booking as book_name, w.no_telp, right(w.no_telp,4) as passcode, w.email, w.down_payment, LEFT(w.booking_date,10) as book_date, w.checkin_type as tipe_order from sh_temp_booking_engine z inner join sh_m_booking w on w.id = z.id_booking where LEFT(w.booking_date,10) >= LEFT(SYSDATE(), 10) and w.is_waiting = 1 and w.is_open = 1 and z.table_no != '' and z.id = ".$id." limit 1";
		}else{
			$query = "select z.id,z.id_booking, z.booking_date, z.table_no, z.real_no, z.total_pax, (select y.id from sh_m_customer y where y.no_telp = w.no_telp and left(y.create_date,10) = left(w.booking_date,10) order by y.id desc limit 1) as customer_id, w.customer_name, z.cabang, z.name_booking as book_name, w.no_telp, SUBSTRING_INDEX(w.customer_name, ' ', 1) as passcode, w.email, w.down_payment, LEFT(w.booking_date,10) as book_date, w.checkin_type as tipe_order from sh_temp_booking_engine z inner join sh_m_booking w on w.id = z.id_booking where LEFT(w.booking_date,10) >= LEFT(SYSDATE(), 10) and w.is_waiting = 1 and w.is_open = 1 and z.table_no != '' and z.id = ".$id." limit 1";	
		}
		return $this->db->query($query);
	}

}