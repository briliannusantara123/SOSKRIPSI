<?php 
class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Item_model');
		$this->load->model('Admin_model');
		$this->load->model('Login_model');
	}
	public function index()
	{
		$this->form_validation->set_rules('username','username','trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('login');	
		}else{
			$this->_login($nomeja);
		}
		
	}
	
	public function logOLD($nomeja = NULL)
	{
	    // VALIDASI
	    $this->form_validation->set_rules('username', 'Username', 'trim|required');
	    $username = $this->input->post('username');
		$no_hp = $this->input->post('no_hp');
	    // DATA VIEW
	    $data['cn']   = $this->Admin_model->getColorCN();
	    $data['ch']   = $this->Admin_model->getColorHD();
	    $data['cb']   = $this->Admin_model->getColorBTN();
	    $data['logo'] = $this->Admin_model->getLogo();

	    // MEJA
	    if ($nomeja != NULL) {
	        $data['nomeja'] = $nomeja;
	        $this->session->set_userdata('nomeja', $nomeja);
	    } else {
	        $data['nomeja'] = $this->session->userdata('nomeja');
	        $nomeja = $data['nomeja'];
	    }

	    // TANGGAL (HARUS SAMA FORMAT)
	    $date = date('Y-m-d');

	    // CEK STATUS MEJA TERAKHIR
	    $session = $this->db
	        ->select('status')
	        ->from('sh_rel_table')
	        ->where('id_table', $nomeja)
	        ->where('LEFT(created_date,10) =', $date, FALSE)
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get()
	        ->row();

	    if ($session) {
	    	// JIKA MEJA CLEANING
		    if ($session && $session->status == 'Cleaning') {
		        $this->session->set_flashdata(
		            'error',
		            'Table status is Cleaning, cannot access menu page.'
		        );
		        $this->load->view('login', $data);
		        return;
		    }
	    }
	    

	    // CEK LOG
	    $log = $this->Item_model->log($nomeja)->result();

	    if (!$log) {
	    	redirect('index.php/login/log/'.$nomeja);
	        // $this->load->view('login', $data);
	        return;
	    }

	    // FORM
	    if ($this->form_validation->run() == FALSE) {
	        $this->load->view('login', $data);
	    } else {
	    	$this->checkin_walkin($username,$no_hp);
	        // $this->_login($nomeja);
	    }
	}

	public function log($nomeja = NULL)
	{
		$data['cn']   = $this->Admin_model->getColorCN();
	    $data['ch']   = $this->Admin_model->getColorHD();
	    $data['cb']   = $this->Admin_model->getColorBTN();
	    $data['logo'] = $this->Admin_model->getLogo();
	    if ($nomeja) {
	    	$data['nomeja'] = $nomeja;
	    }else{
	    	$data['nomeja'] = 0;
	    }
	    // MEJA
	    if ($nomeja != NULL) {
	        $data['nomeja'] = $nomeja;
	        $this->session->set_userdata('nomeja', $nomeja);
	    } else {
	        $data['nomeja'] = $this->session->userdata('nomeja');
	        $nomeja = $data['nomeja'];
	    }

	    // TANGGAL (HARUS SAMA FORMAT)
	    $date = date('Y-m-d');
		$used = $this->db
		    ->select('a.id,b.customer_name,b.no_telp,a.id_customer,b.visit_type,b.total_pax')
		    ->from('sh_rel_table a')
		    ->join('sh_m_customer b', 'b.id = a.id_customer')
		    ->where('a.id_table', $nomeja)
		    ->where("a.created_date", $date)
		    ->where_in('a.status', ['Order','Dining'])
		    ->limit(1)
		    ->get()
		    ->row();
		$data['used'] = 0;
		// if ($used) {
		// 	$data['used'] = 1;
		//     $this->load->view('login', $data);
		//     return;
		// }
	    // VALIDASI
	    $this->form_validation->set_rules('username', 'Username', 'trim|required');
	    $username = $this->input->post('username');
		$no_hp = $this->input->post('no_hp');
		$type = $this->input->post('type');
		

	    // CEK STATUS MEJA TERAKHIR
	    $session = $this->db
		    ->select('r.status, r.id_table, t.is_closed,r.id_customer,t.start_time_order')
		    ->from('sh_rel_table r')
		    ->join('sh_t_transactions t', 't.id_customer = r.id_customer', 'left') // left join supaya tetap muncul meski t.id_table null
		    ->where('r.id_table', $nomeja)
		    ->where('r.created_date', $date)
		    ->order_by('r.id', 'DESC')
		    ->limit(1)
		    ->get()
		    ->row();

	    if ($session) {
	    	if ($session && in_array($session->status, ['Payment', 'Cleaning'])) {

			    // ✅ update ke Available
			    $this->db->where('id_customer', $session->id_customer)->update('sh_rel_table', [
			        'status' => 'Available'
			    ]);

			    // ✅ langsung cek ke API setelah update
			    // $cekreservasi = $this->get_table();

			    // if (!$cekreservasi || !isset($cekreservasi['data'])) {
			    //     $this->session->set_flashdata('error', 'Failed to validate table from server');
			    //     $this->load->view('login', $data);
			    //     return;
			    // }

			    // $table_no_list = array_column($cekreservasi['data'], 'table_no');

			    // if (!in_array((string)$nomeja, $table_no_list)) {
			    //     $this->session->set_flashdata(
			    //         'error',
			    //         'Table not available please contact our staff'
			    //     );
			    //     $this->load->view('login', $data);
			    //     return;
			    // }

			    // ✅ kalau lolos → lanjut ke login page
			    $this->load->view('login', $data);
			    return;
			}
		    if ($session && $session->status == 'Billing' && $session->is_closed == '0') {
		        $this->session->set_flashdata(
		            'error',
		            'Your table is currently in Billing status. Please complete the payment at the cashier first'
		        );
		        $this->load->view('login', $data);
		        return;
		    }
		    if ($session && $session->status == 'Billing' && $session->is_closed == '1') {
		        $this->db->where('id_customer', $session->id_customer)->update('sh_rel_table', [
			        'status' => 'Available'
			    ]);
		    }
		    if ($session->status == 'Available') {
		  //   	$cekreservasi = $this->get_table();

				// if (!$cekreservasi || !isset($cekreservasi['data']) || !is_array($cekreservasi['data'])) {
				//     $this->session->set_flashdata(
				//         'error',
				//         'Failed to get table data from server'
				//     );
				//     $this->load->view('login', $data);
				//     return;
				// }

				// $table_no_list = array_column($cekreservasi['data'], 'table_no');
				// // cek apakah meja ada di API
				// if (!in_array($nomeja, $table_no_list)) {
				//     $this->session->set_flashdata(
				//         'error',
				//         'Table not available please contact our staff'
				//     );
				//     $this->load->view('login', $data);
				//     return;
				// }
		    }
		    // if ($session && $session->status == 'Payment') {
		    //     $this->db->where('id_customer', $used->id_customer)->update('sh_rel_table', [
			   //      'status' => 'Available'
			   //  ]);
		    // }
		    // CEK LOG
		    // $log = $this->Item_model->log($nomeja)->result();

		    // if (!$log) {
		    // 	redirect('index.php/login/log/'.$nomeja);
		    //     return;
		    // }
	    }
	    if ($used) {
	    	$this->_login($nomeja,$type,$used);
	    }else{
	    	if ($this->form_validation->run() == FALSE) {
	        $this->load->view('login', $data);
		    } else {
		    	if (empty($nomeja)) {
			        $this->session->set_flashdata(
			            'error',
			            "We couldn't detect your table. Please scan the QR code again to continue."
			        );
			        redirect('index.php/Login/log'); // halaman login / scan QR
			        return;
			    }else{
			    	$this->send_reservation($username,$no_hp,$type);
		    		$this->checkin_walkin($username,$no_hp,$nomeja,$type);
			    }
		    	
		        // $this->_login($nomeja);
		    }
	    }
	    
	}
	public function get_table()
	{
		$base = base_url();

		$parsed = parse_url($base);

		$root_url = $parsed['scheme'] . '://' . $parsed['host'];

		if (isset($parsed['port'])) {
		    $root_url .= ':' . $parsed['port'];
		}

		$root_url .= '/'; // sudah ada slash di akhir

		// parameter
		$date = date('Y-m-d');
		$hour = (int) date('H');
		$pax  = $this->input->post('pax') ?? 1;
		$cabang = $this->db->order_by('id', 'desc')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

		// final URL (TANPA double slash)
		$url = "{$root_url}hachi/alacarte.php/panel/tableavailable/walkin/{$cabang}/{$date}/{$hour}/{$pax}";

	    $ch = curl_init();

	    curl_setopt_array($ch, [
	        CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_TIMEOUT => 30,
	    ]);

	    $response = curl_exec($ch);

	    if (curl_errno($ch)) {
	        return [
	            'status' => false,
	            'message' => curl_error($ch)
	        ];
	    }

	    curl_close($ch);

	    return json_decode($response, true); // 🔥 WAJIB RETURN
	}
	public function send_reservation($name, $nohp,$type)
	{
	    $today = date('Y-m-d');

	    /* =========================
	       VALIDASI DUPLICATE WALKIN
	       ========================= */
	    // $exist = $this->db
	    //     ->where('customer_name', $name)
	    //     ->where('no_telp', $nohp)
	    //     ->where('DATE(create_date)', $today)
	    //     ->where('is_cancel', 0)
	    //     ->get('sh_m_walkin')
	    //     ->row();

	    // if ($exist) {
	    //     return $exist->id;
	    // }

	    /* =========================
	       DATA BARU
	       ========================= */
	    $pax = 0;
	    $tgl  = date('Y-m-d');
	    $hour = date('H:i');

	    $datetime_str = $tgl . ' ' . $hour;
	    $dateTime = DateTime::createFromFormat(
	        'Y-m-d H:i',
	        $datetime_str,
	        new DateTimeZone('Asia/Jakarta')
	    );

	    // Ambil cabang terakhir
	    $cabang = $this->db->order_by('id', 'desc')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    // Generate antrian
	    $last_queue = $this->db
	        ->select_max('antrian')
	        ->where('cabang', $cabang)
	        ->where('DATE(create_date)', $today)
	        ->get('sh_m_walkin')
	        ->row()
	        ->antrian;

	    $next_queue = ($last_queue ?? 0) + 1;

	    $walkin_data = [
	        'customer_name'   => $name,
	        'no_telp'         => $nohp,
	        'email'           => '',
	        'create_date'     => date('Y-m-d H:i:s'),
	        'booking_date'    => $dateTime->format('Y-m-d H:i:s'),
	        'is_waiting'      => 0,
	        'is_checkin'      => 0,
	        'total_pax'       => $pax,
	        'total_real_pax'  => $pax,
	        'cabang'          => $cabang,
	        'antrian'         => $next_queue,
	        'antrian_prefix'  => 'W',
	        'id_member'       => 0,
	        'is_cancel'       => 0,
	        'cancel_reason'   => '',
	        'checkin_type'    => 'Alacarte'
	    ];

	    $this->db->insert('sh_m_walkin', $walkin_data);

	    return $this->db->insert_id();
	}

	public function checkin_walkin($username,$no_hp,$nomeja,$type)
	{
	    $this->db->trans_start();
	    
	    $date = date('Y-m-d');
	    // ambil data walkin
	    $data = $this->Login_model->get_walkin($username,$no_hp,$date);

	    if (!$data) {
	        show_error('Data walkin tidak ditemukan');
	    }
	    // if ((int)$data->is_waiting === 0) {
	    //     // sudah check-in → langsung login
	    //     return $this->_login($nomeja);
	    // }
	    // $visit_type = 'Walkin';
	    $visit_type = $type;

	    // 1. insert customer
	    $customer_id = $this->Login_model->insert_customer($data, $visit_type);

	    // 2. insert reservation
	    $this->Login_model->insert_reservation_cust($data, $customer_id);
	    $today = date('Y-m-d');
		$session_item = "WeekDay";

		$cekHoliday = $this->Login_model->get_holiday($data->cabang, "0", $today)->num_rows();
		$cekPuasa   = $this->Login_model->get_holiday($data->cabang, "1", $today)->num_rows();
		$cekWeekEnd = date('D', strtotime($today));

		if ($cekHoliday > 0 || $cekPuasa > 0 || in_array($cekWeekEnd, ['Sat','Sun','Sab','Min'])) {
		    $session_item = "WeekEnd";
		}

		$setup = $this->Login_model->get_setup($data->cabang);

		$start   = date('H:i:s');
		$prepare = date('Y-m-d H') . ':00:00';
		$create  = date('Y-m-d H:i:s');

		if ($session_item == "WeekEnd") {
		    $menit_prepare = $setup->weekend_normal_time - 15;
		    $menit_end     = $setup->weekend_normal_time;
		} else {
		    $menit_prepare = $setup->weekday_normal_time - 15;
		    $menit_end     = $setup->weekday_normal_time;
		}

		$end_time = $this->Login_model
		    ->new_datetime($create, $menit_end, 'MINUTE', 'time')
		    ->result;

		$prep_time = $this->Login_model
		    ->new_datetime($prepare, $menit_prepare, 'MINUTE', 'time')
		    ->result;

		/* =======================
		   VALIDASI DUPLICATE
		   ======================= */
		// $this->db->where('cabang', $data->cabang);
		// $this->db->where('optdate', $today);
		// $this->db->where('is_close', 0);
		// $this->db->where('is_canceled', 0);

		// $exist = $this->db->get('sh_t_reservation')->row();
		
		// if ($exist) {
		//     // pakai reservation yang sudah ada
		//     $res_id = $exist->id;
		// } else {
		    // insert baru
		    $insertreservation = [
		        'start_time'            => $start,
		        'preparation_time'      => $prep_time,
		        'end_time'              => $end_time,
		        'create_date'           => $create,
		        'is_canceled'           => 0,
		        'is_canceled_date'      => '0000-00-00 00:00:00',
		        'is_extend'             => 0,
		        'is_close'              => 0,
		        'is_use_reserved_table' => 0,
		        'cabang'                => $data->cabang,
		        'optdate'               => $today
		    ];

		    $this->db->insert('sh_t_reservation', $insertreservation);
		    $res_id = $this->db->insert_id();
		// }
	    // 3. insert rel table
	    $rel_ids = $this->Login_model->insert_rel_table(
	        $data,
	        $customer_id,
	        $res_id,
	        $visit_type,$nomeja,$type
	    );

	    // 4. insert transaction
	    $trans_id = $this->Login_model->insert_transaction(
	        $data,
	        $customer_id,
	        $res_id,
	        $rel_ids,
	        $type,
	        $username
	    );

	    // 5. insert trans rel table
	    $this->Login_model->insert_trans_reltable(
	        $trans_id,
	        $rel_ids,
	        $data->cabang
	    );

	    $data_log = [
			'event_type' => 'Self Order Checkin',
			'cabang' => $data->cabang,
			'id_trans' => $trans_id,
			'id_customer' => $customer_id,
			'event_date' => date('Y-m-d H:i:s'),
			'user_by' => $username,
			'table_before' => '',
			'table_after' => $nomeja,
			'created_date' => date('Y-m-d')
		];
		$this->db->insert('sh_event_log', $data_log);
	    $this->db->trans_complete();

	    if ($this->db->trans_status() === FALSE) {
	        show_error('Gagal check-in walkin');
	    }
	    // UPDATE STATUS WALKIN (berhasil check-in)
		$this->db->where('id', $data->id);
		$this->db->update('sh_m_walkin', [
		    'is_waiting'   => 0,
		]);

	    return $this->_login($nomeja,$visit_type);
	}


	public function _login($nomeja,$type,$used =NULL)
	{
		$user_order_id = $this->input->ip_address();
		if ($used) {
			$username = $used->customer_name;
			$no_hp = $used->no_telp;
			if ($used->visit_type == 'Walkin') {
				$type = 'Dine in';
			}else{
				$type = $used->visit_type;
			}
			
		}else{
			$username = $this->input->post('username');
			$no_hp = $this->input->post('no_hp');
			$type = $this->input->post('type');
			if ($type == 'Walkin') {
				$type = 'Dine in';
			}else{
				$type = $type;
			}
		}
		
		$date = date('Y-m-d');
		$where = "sh_rel_table.id_table = '".$nomeja."' and left(created_date,10) ='".$date."' and status in('Order','Dining') and sh_m_customer.customer_name = '".$username."' and sh_m_customer.no_telp = '".$no_hp."'";
		$this->db->select('*');
		$this->db->from('sh_rel_table');
		$this->db->join('sh_m_customer', 'sh_m_customer.id = sh_rel_table.id_customer');
		$this->db->where($where);
		$log = $this->db->get()->row_array();
		// $user = $this->db->get_where('sh_m_customer',['passcode' => $passcode,'left(create_date,10) = ' => $date])->row_array();
		// // var_dump($user);exit();
		
		// $meja = $this->db->get_where('sh_rel_table',$where)->row_array();
		// var_dump($meja);die();
			if ($log) {
				$data = [
					'username' => $log['customer_name'],
					'no_telp' => $log['no_telp'],
					'id' => $log['id'],
					'nomeja' => $nomeja,
					'user_order_id' => $user_order_id,
					'visit_type'	=> $type,
				];
				$a = $nomeja;
				

				$d = [
						'created_date' => date('Y-m-d'),
						'id_table' => $nomeja,
						'user_order_id' =>$user_order_id
					 ];
				$this->db->insert('sh_log_user',$d);
				$this->session->set_userdata($data);
				$id_customer = $this->session->userdata('id');
				$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
				$cabang = $this->db->order_by('id',"desc")
			  			->limit(1)
			  			->get('sh_m_cabang')
			  			->row('id');
			  	$ip_address = $this->input->ip_address();
			  	$cust = $this->session->userdata('username');
				$dataevent = [
					'event_type' => 'Login SO',
					'cabang' => $cabang,
					'id_trans' => $id_trans->id,
					'id_customer' => $this->session->userdata('id'),
					'event_date' => date('Y-m-d H:i:s'),
					'user_by' => $this->session->userdata('username'),
					'description' => 'Melakukan Login dengan IP: '.$ip_address,
					'created_date' => date('Y-m-d'),
				];
				$result = $this->db->insert('sh_event_log',$dataevent);

				$this->session->set_flashdata('success','Success Checkin to Table No '.$nomeja);

				$subcategory = $this->Item_model->sub_category_awal_raw();
				$nowDate = date('Y-m-d');
				$nowHour = date('H');

				$valid = true;

				if ($subcategory) {
				    if (!empty($subcategory['date_from']) && !empty($subcategory['date_to'])) {
				        if ($nowDate < $subcategory['date_from'] || $nowDate > $subcategory['date_to']) {
				            $valid = false;
				        }
				    }

				    if (!empty($subcategory['time_from']) && !empty($subcategory['time_to'])) {
				        if ($nowHour < $subcategory['time_from'] || $nowHour > $subcategory['time_to']) {
				            $valid = false;
				        }
				    }
				} else {
				    $valid = false;
				}

				// ============================
				// SUB CATEGORY AKHIR
				// ============================
				$sca = $valid ? $subcategory['sub_category'] : '';

				// ============================
				// CEK SIGNATURE
				// ============================
				$cekSignature = $this->Item_model->cekSignature();

				// ============================
				// TENTUKAN CATEGORY LINK
				// ============================
				$category = $sca;


				if ($cekSignature && (int)$cekSignature->sort === 0) {
				    $category = 'Signature';
				}
				// ============================
				// BUILD LINK
				// ============================
				$dataview['categoryUrl']    = rawurlencode($category);
				$dataview['categoryAnchor'] = str_replace(' ', '_', $category);
				$dataview['logo'] = $this->Admin_model->getLogo();

				// LOAD VIEW (BUKAN redirect)
				$this->load->view('landing', $dataview);

			}else{
				redirect('index.php/login/log/'.$nomeja);
			}
	}
	public function logout($nm=null,$cek=null)
	{
		
		$cs = $this->session->userdata('id');
		$nomeja = $this->Item_model->nomeja($cs);
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$ip_address = $this->input->ip_address();
		if ($id_trans) {
			$it = $id_trans->id;
			$ic = $id_customer;
			$usr = $this->session->userdata('username');
			$des = 'Melakukan Logout dengan IP: '.$ip_address;
		}else{
			$it = 0;
			$ic = 0;
			$usr = "System";
			$des = 'Logout oleh system timeout';
		}
		$this->db->where('id_customer', $id_customer);
	    $this->db->update('sh_rel_table', [
	        'status' => 'Available'
	    ]);
		$cabang = $this->db->order_by('id',"desc")
			  	->limit(1)
			  	->get('sh_m_cabang')
			  	->row('id');
			 $cust = $this->session->userdata('username');
		$dataevent = [
			'event_type' => 'Logout SO',
			'cabang' => $cabang,
			'id_trans' => $it,
			'id_customer' => $ic,
			'event_date' => date('Y-m-d H:i:s'),
			'user_by' => $usr,
			'description' => $des,
			'created_date' => date('Y-m-d'),
		];
		$result = $this->db->insert('sh_event_log',$dataevent);
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('no_telp');

		if ($cek == 'billing') {
			$this->session->set_flashdata('error','Your table is currently in Billing status. You cannot log in to Self Order');
		}
		// elseif($cek == 'payment'){
		// 	$this->session->set_flashdata('error','Your table is currently in Payment status. You cannot log in to Self Order');
		// }elseif($cek == 'payment'){
		// 	$this->session->set_flashdata('error','Your table is currently in Cleaning status. You cannot log in to Self Order');
		// }
		else{
			$this->session->set_flashdata('success','Logout Successfully, Please Scan the QR Code to Login Again');
		}
		
		redirect('index.php/login/log/');
	}
	public function log_out($nm=null)
	{
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$cabang = $this->db->order_by('id',"desc")
			  	->limit(1)
			  	->get('sh_m_cabang')
			  	->row('id');
			 $ip_address = $this->input->ip_address();
			 $cust = $this->session->userdata('username');
		// $dataevent = [
		// 	'event_type' => 'Logout SO',
		// 	'cabang' => $cabang,
		// 	'id_trans' => $id_trans->id,
		// 	'id_customer' => $this->session->userdata('id'),
		// 	'event_date' => date('Y-m-d H:i:s'),
		// 	'user_by' => $this->session->userdata('username'),
		// 	'description' => 'Melakukan Logout dengan IP: '.$ip_address,
		// 	'created_date' => date('Y-m-d'),
		// ];
		// $result = $this->db->insert('sh_event_log',$dataevent);
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('no_telp');
		$this->session->set_flashdata('error','You have logged out because you changed tables');
		
		redirect('index.php/login/log/'.$nm);
	}
	public function logoutPayment($nm=null)
	{
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$cabang = $this->db->order_by('id',"desc")
			  	->limit(1)
			  	->get('sh_m_cabang')
			  	->row('id');
			 $ip_address = $this->input->ip_address();
			 $cust = $this->session->userdata('username');
		$dataevent = [
			'event_type' => 'Logout SO',
			'cabang' => $cabang,
			'id_trans' => $id_trans->id,
			'id_customer' => $this->session->userdata('id'),
			'event_date' => date('Y-m-d H:i:s'),
			'user_by' => $this->session->userdata('username'),
			'description' => 'Melakukan Logout dengan IP: '.$ip_address,
			'created_date' => date('Y-m-d'),
		];
		$result = $this->db->insert('sh_event_log',$dataevent);
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('no_telp');
		$this->session->set_flashdata('error','Please scan again to place another order');
		
		redirect('index.php/login/log/'.$nm);
	}
	public function logoutback($nm=null,$cek=null)
	{
		
		$cs = $this->session->userdata('id');
		$nomeja = $this->Item_model->nomeja($cs);
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$ip_address = $this->input->ip_address();
		if ($id_trans) {
			$it = $id_trans->id;
			$ic = $id_customer;
			$usr = $this->session->userdata('username');
			$des = 'Melakukan Logout dengan IP: '.$ip_address;
		}else{
			$it = 0;
			$ic = 0;
			$usr = "System";
			$des = 'Logout oleh system timeout';
		}
		
		$cabang = $this->db->order_by('id',"desc")
			  	->limit(1)
			  	->get('sh_m_cabang')
			  	->row('id');
			 $cust = $this->session->userdata('username');
		$dataevent = [
			'event_type' => 'Logout SO',
			'cabang' => $cabang,
			'id_trans' => $it,
			'id_customer' => $ic,
			'event_date' => date('Y-m-d H:i:s'),
			'user_by' => $usr,
			'description' => $des,
			'created_date' => date('Y-m-d'),
		];
		$result = $this->db->insert('sh_event_log',$dataevent);
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('no_telp');
		if ($cek == 'billing') {
			$this->session->set_flashdata('error','Your table is currently in Billing status. Please complete the payment at the cashier first');
		}
		// elseif($cek == 'payment'){
		// 	$this->session->set_flashdata('error','Your table is currently in Payment status. You cannot log in to Self Order');
		// }elseif($cek == 'payment'){
		// 	$this->session->set_flashdata('error','Your table is currently in Cleaning status. You cannot log in to Self Order');
		// }
		else{
			$this->session->set_flashdata('success','Logout Successfully, Please Scan the QR Code to Login Again');
		}
		
		redirect('index.php/login/log/');
	}
	//ADMIN
	public function admin()
	{
		$this->form_validation->set_rules('passcode','passcode','trim|required');

		if ($this->form_validation->run() == FALSE) {
			$data['logo'] = $this->Admin_model->getLogo();
			$data['cn'] = $this->Admin_model->getColorCN();
			$this->load->view('admin/loginAdmin',$data);	
		}else{
			$this->loginadmin();
		}
		
	}
	public function loginadmin() {
	    $username = $this->input->post('username');
	    $password = $this->input->post('password');
	    $this->db->where('username', $username);
	    $user = $this->db->get('sh_user_so')->row();
	    // var_dump($user);exit();
	    if ($user) {
	        if (md5($password) == $user->password) {
	            $data = [
	                'usernameadmin' => $user->username,
	                'role' => $user->role,
	                'id' => $user->id,
	            ];
	            $this->session->set_userdata($data);
	            $this->session->set_flashdata('success', 'Login Successfully');
				if ($user->role == 'cashier') {
					redirect('index.php/Admin/cashier');
				}else if($user->role == 'kitchen') {
					redirect('index.php/Admin/kitchen');
				}else{
					redirect('index.php/Admin/dashboard');
				}
	            
	        } else {
	        	
	            $this->session->set_flashdata('error', 'Username atau password salah');
	            $data['logo'] = $this->Admin_model->getLogo();
	            $data['cn'] = $this->Admin_model->getColorCN();
	            $this->load->view('admin/loginAdmin',$data);    
	        }
	    } else {
	        echo "Username tidak ditemukan!";
	    }
	}

	public function logoutAdmin($nm=null,$pm=NULL)
	{
		$cs = $this->session->userdata('id');
		$id_customer = $this->session->userdata('id');
		$ip_address = $this->input->ip_address();
		$this->session->unset_userdata('usernameadmin');
		$this->session->unset_userdata('role');
		$this->session->unset_userdata('id');
		$this->session->set_flashdata('success','Successfully Logged Out');
		redirect('index.php/login/admin');
	}
	public function changepw()
	{
		$po = $this->input->post('passwordOLD');
		$usr = $this->input->post('username');
		$cekpw = $this->Admin_model->cekpw($po,$usr);
		if ($cekpw) {
			$data = [
				'password' => md5($this->input->post('password')),
			];
			$this->db->where('username',$usr);
			$this->db->where('password',md5($po));
			$this->db->update('sh_user_so',$data);
			$this->session->set_flashdata('success','Password has been successfully changed');
		}else{
			$this->session->set_flashdata('error','The current password you entered is incorrect.');
		}
		redirect('index.php/admin');
	}
	public function loginremote($nomeja,$ip) {
	    $date = date('Y-m-d');
		$where = "sh_rel_table.id_table = '".$nomeja."' and left(created_date,10) ='".$date."' and status in('Order','Dining')";
		$this->db->select('*');
		$this->db->from('sh_rel_table');
		$this->db->join('sh_m_customer', 'sh_m_customer.id = sh_rel_table.id_customer');
		$this->db->where($where);
		$log = $this->db->get()->row_array();
			if ($log) {
				$data = [
					'username' => $log['customer_name'],
					'no_telp' => $log['no_telp'],
					'id' => $log['id'],
					'nomeja' => $nomeja,
					'user_order_id' => $ip
				];
				$a = $nomeja;
				$this->session->set_userdata($data);
				// $id_customer = $this->session->userdata('id');
				// $id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
				// $cabang = $this->db->order_by('id',"desc")
			 //  			->limit(1)
			 //  			->get('sh_m_cabang')
			 //  			->row('id');
			 //  	$ip_address = $ip;
			 //  	$cust = $this->session->userdata('username');
				// $dataevent = [
				// 	'event_type' => 'Login SO',
				// 	'cabang' => $cabang,
				// 	'id_trans' => $id_trans->id,
				// 	'id_customer' => $this->session->userdata('id'),
				// 	'event_date' => date('Y-m-d H:i:s'),
				// 	'user_by' => $this->session->userdata('username'),
				// 	'description' => 'Melakukan Login dengan IP: '.$ip_address,
				// 	'created_date' => date('Y-m-d'),
				// ];
				// $result = $this->db->insert('sh_event_log',$dataevent);
				// $this->session->set_flashdata('success','Login Successfully, Please Order !');
				redirect('index.php/selforder/landing/'.$a);
			}else{
				$a = $nomeja;
				// $this->session->set_flashdata('error','Wrong Passcode !');
				redirect('index.php/login/log/'.$a);
			}
	}
}
?>
