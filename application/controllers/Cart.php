<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;
use Xendit\VirtualAccounts;

class Cart extends CI_Controller {
	private $xendit_key;
function __construct()
		{
			
			parent::__construct();
			$this->xendit_key = 'xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW';
			if($this->session->userdata('username') == ""){
           		$nomeja = $this->session->userdata('nomeja');
  				redirect('index.php/login/logout/'.$nomeja);
        	}
			$this->load->model('Item_model');
			$this->load->model('Admin_model');
			$this->load->model('Paket_model');
			$this->load->model('cekstatus_model');
			$this->load->helper('cookie');
			$this->load->library('encryption');
			$session = $this->cekstatus_model->cek();

	  		// if($session['status'] == 'Cleaning'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/logout/'.$nomeja.'/cleaning');
	  		// }
	  		// if($session['id_table'] != $this->session->userdata('nomeja')){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/log_out/'.$nomeja);
	  		// }
	  		// if($session['status'] == 'Payment'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/logoutPayment/'.$nomeja);
	  		// }
	  		// if($session['status'] == 'Available'){
		  	// 		$nomeja = $this->session->userdata('nomeja');
		  	// 		redirect('index.php/login/log_out/'.$nomeja);
		  	// }

		  	
			
		}

	public function testing()
	{
		$this->load->library('user_agent');

		if ($this->agent->is_mobile()) {
		    $device = $this->agent->mobile();
		   
		} else {
		    $device = "DEKSTOP";
		}
		$browser = $this->agent->browser();
		$version = $this->agent->version();
		$platform = $this->agent->platform();
		$robot = $this->agent->robot();
		$ip_address = $this->input->ip_address();
		echo "IP address pengguna adalah: " . $ip_address . "<br>";
		echo "Browser yang digunakan: " . $browser . "<br>";
		echo "Versi browser yang digunakan: " . $version . "<br>";
		echo "Platform yang digunakan: " . $platform . "<br>";
		echo "Device yang digunakan: " . $device . "<br>";
		echo "Apakah user agent adalah robot: " . ($robot ? 'Ya' : 'Tidak') . "<br>";
	}
	public function index()
	{
		$id_customer = $this->session->userdata('id');
		$data['item'] = $this->Item_model->getDataOrder($id_customer)->result();
		
		$this->load->view('ordersementara',$data);
	}
	public function home($nomeja,$cek=NULL,$sub=NULL,$no=NULL)
	{
		$session = $this->cekstatus_model->cek();
		if($session['status'] == 'Billing'){
		  $nomeja = $this->session->userdata('nomeja');
		  redirect('index.php/login/logoutback/'.$nomeja);
		}
		$uoi = $this->session->userdata('user_order_id');
		$sharp = str_replace("%20","_", $sub);
		$url = $sub.'#'.$sharp;
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$query = $this->db->where('id_table', $nomeja)
         ->where('id_customer', $id_customer)
         ->where('DATE(entry_date)', date('Y-m-d')) // Gunakan DATE() untuk hanya membandingkan tanggal
         // ->where('user_order_id', $uoi)
         ->where('id_trans', $id_trans->id)
         ->get('sh_cart');
		$jumlahData = $query->num_rows();
		// var_dump($jumlahData);exit();
		// $queryadd = $this->db->where('id_table',$nomeja)->where('id_customer',$id_customer)->where('DATE(entry_date)', date('Y-m-d'))->where('user_order_id',$uoi)->where('id_trans',$id_trans->id)->where('addons',1)->get('sh_cart');
		$queryadd = $this->db->where('id_table',$nomeja)->where('id_customer',$id_customer)->where('DATE(entry_date)', date('Y-m-d'))->where('id_trans',$id_trans->id)->where('addons',1)->get('sh_cart');
		$jumlahDataadd = $queryadd->num_rows();
		$data['total'] = $this->Item_model->totalSubOrder($nomeja,$id_customer,$uoi,$id_trans->id);
		$data['hitungbayar'] = $this->Item_model->totalbayar($id_trans->id);
		$data['item'] = $this->Item_model->cart($id_customer)->result();
		$data['nomeja'] = $nomeja;
		$data['jumlah'] = $jumlahData;
		$data['jumlahadd'] = $jumlahDataadd;
		$data['itempaket'] = [];
		$notrans = $this->db->order_by('id',"asc")->where('id_customer',$id_customer)
  			->limit(1)
  			->get('sh_t_transactions')
  			->row('id');
		$data['totalbayar'] = $this->Item_model->totalbayar($notrans);
		// var_dump($this->Item_model->sub_category_awal());exit();
		$data['sca'] = $this->Item_model->sub_category_awal();
		$data['scm'] = $this->Item_model->sub_category_minuman_awal();
		if ($cek == 'Makanan') {
			$log = 'index.php/ordermakanan/menu/Makanan/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
		}elseif ($cek == 'Minuman') {
			$log = 'index.php/orderminuman/menu/Minuman/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
		}else{
			$log = 'index.php/selforder/home/'.$nomeja;
		}
		$cabang = $this->db->order_by('id',"desc")
			  	->limit(1)
			  	->get('sh_m_cabang')
			  	->row('id');
		$ip_address = $this->input->ip_address();
		$item_desc = '';
		foreach ($data['item'] as $item) {
		    $item_desc .= $item->description . ' (Qty: ' . $item->qty . '), ';
		}

		// hapus koma terakhir
		$item_desc = rtrim($item_desc, ', ');
		$dataevent = [
			'event_type' => 'Akses cart SO',
			'cabang' => $cabang,
			'id_trans' => $id_trans->id,
			'id_customer' => $this->session->userdata('id'),
			'event_date' => date('Y-m-d H:i:s'),
			'user_by' => $this->session->userdata('username'),
			'description' => 'Membuka halaman cart dengan IP: '.$ip_address.' | Item: '.$item_desc,
			'created_date' => date('Y-m-d'),
		];
		$result = $this->db->insert('sh_event_log',$dataevent);
		
		$data['log'] = $log;
		$data['cek'] = $cek;
		$data['sub'] = $sub;
		$data['url'] = $url;
		$data['no'] = $no;
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['icon'] = $this->Admin_model->getIcon('add');
		$data['logo'] = $this->Admin_model->getLogo();
		$this->load->view('cart',$data);
	}
	public function get_total() {
	    $uoi = $this->session->userdata('user_order_id');
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();

	    $total = $this->Item_model->totalSubOrder($nomeja,$id_customer,$uoi,$id_trans->id);
	    $hitungbayar = $this->Item_model->totalbayar($id_trans->id);
	    $sc = $hitungbayar->sc;  // Contoh perhitungan SC 5%
	    $ppn = $hitungbayar->ppn; // Contoh perhitungan PPN 10%
	    $grand_total = $total + $sc + $ppn;

	    // Format hasilnya
	    $data = [
	        'success' => true,
	        'total' => $total,
	        'hitungbayar' => $hitungbayar,
	        'total_formatted' => number_format($total),
	        'sc_formatted' => number_format($sc),
	        'ppn_formatted' => number_format($ppn),
	        'grand_total_formatted' => number_format($grand_total),
	    ];

	    echo json_encode($data);
	}

	public function create($nomeja,$cek,$sub)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$ic = $this->session->userdata('id');
		$qty = $this->input->post('qty');
		$ata = $this->input->post('cek');
		$qta = $this->input->post('qta');
		$nama = $this->input->post('nama');
		$pesan = $this->input->post('pesan');
		$harga = $this->input->post('harga');
		$item_code = $this->input->post('no');
		$table = $this->session->userdata('nomeja');
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$id_table = $this->db->get_Where('sh_rel_table', array('id_customer'=> $id_customer))->row();
		$st = $id_table->status;
		if ($st == "Dining" || $st == "Order") {
			$order_stat = 1;
		}elseif ($st == "Billing") {
			$order_stat = 2;
		}
		$today = date('Y-m-d');
		$curTime = explode(':', date('H:i:s'));
		$cekWeekEnd = date('D', strtotime($today));
		$check_promo = $this->Item_model->get_promo($today)->num_rows();
		$get_promo = $this->Item_model->get_promo($today)->row_array();
		$discount = 0;
		// if($check_promo > 0){
		// 	$item_check = $this->Item_model->get_info_item($request['item_code'],$get_promo)->num_rows();
		// 	if($item_check > 0){
		// 		$item_data = $this->Item_model->get_info_item($request['item_code'],$get_promo)->row_array();
		// 		if($get_promo["promo_type"] == 'Discount'){
		// 			if($get_promo["promo_criteria"] == 'Weekday'){ //Weekday
		// 				if($cekWeekEnd !== "Sat" || $cekWeekEnd !== "Sun" || $cekWeekEnd !== "Sab" || $cekWeekEnd !== "Min"){
		// 					if($curTime[0] >= $get_promo["promo_from"] && $curTime[0] <= $get_promo["promo_to"]){
		// 						$discount = $get_promo["promo_value"];		
		// 					}else{
		// 						$discount = 0;
		// 					}
		// 				}else{
		// 					$discount = 0;
		// 				}	
		// 			}else if($get_promo["promo_criteria"] == 'Weekend'){ //Weekend
		// 				if($cekWeekEnd === "Sat" || $cekWeekEnd === "Sun" || $cekWeekEnd === "Sab" || $cekWeekEnd === "Min"){
		// 					if($curTime[0] >= $get_promo["promo_from"] && $curTime[0] <= $get_promo["promo_to"]){
		// 						$discount = $get_promo["promo_value"];		
		// 					}else{
		// 						$discount = 0;
		// 					}
		// 				}else{
		// 					$discount = 0;
		// 				}	
		// 			}else{ //Full Week
		// 				if($curTime[0] >= $get_promo["promo_from"] && $curTime[0] <= $get_promo["promo_to"]){
		// 					$discount = $get_promo["promo_value"];		
		// 				}else{
		// 					$discount = 0;
		// 				}
		// 			}
		// 		}else{
		// 			$discount = 0;	
		// 		}
		// 	}else{
		// 		$discount = 0;
		// 	}
		// }
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
		$nomer = 1;
		for ($i = 0; $i < count($qty); $i++) {
			if ($qty[$i] != 0) {
				$n = $nomer++ . "<br>"; 
				$data[] = [
				'id_trans' => $id_trans->id,
				'id_customer' => $ic,
				'item_code' => $item_code[$i],
				'qty' => $qty[$i],
				'cabang' => $cabang,
				'unit_price' => $harga[$i],
				'description' => $nama[$i],
				'start_time_order' => date('H:i:s'),
				'entry_by' => $this->session->userdata('username'),
				'disc' => $discount,
				'is_cancel' => 0,
				'session_item' => 0,
				'selected_table_no' => $table,
				'seat_id' => 0,
				'sort_id' => $n,
				'as_take_away' => 0,
				'qty_take_away' => 0,
				'extra_notes' => $pesan[$i],
				'checker_printed' => 1,
				'created_date' => date('Y-m-d'),
				'order_type' => $order_stat,
			];
			 }
    
		}
		// var_dump($data);exit();
		$result = $this->db->insert_batch('sh_t_sub_transactions',$data);
			if ($result) {
				
    			$this->session->set_flashdata('success','Order Menu/Paket Berhasil Di Tambahkan');
				redirect('ordermakanan/subcreate/'.$nomeja.'/'.$cek.'/'.$sub);
				// $where = array('qty' => 0);
				// $this->Item_model->hapus_qty($where,'testing');
			}else{
				echo "gagal order";
			}

		
	}
	public function batal($nomeja,$cek,$sub)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$ic = $this->session->userdata('id');
		$this->db->where('id_customer',$ic);
    	$this->db->delete('sh_t_sub_transactions');
    	redirect('index.php/cart/home/'.$nomeja.'/'.$cek.'/'.$sub);
	}
	public function checkout($nomeja, $cek = NULL, $sub = NULL)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    // Ambil data dari session & input
	    $table          = $this->session->userdata('nomeja');
	    $qty            = $this->input->post('qty');
	    $qtyaddon       = $this->input->post('qtyaddon');
	    $qtyip          = $this->input->post('qtyip');
	    $options = (array) $this->input->post('options');
	    $pesan = (array) $this->input->post('pesan');
	    $item_codes     = (array) $this->input->post('no'); // array item utama
	    $item_codeaddon = $this->input->post('noaddon');
	    $item_codeip    = $this->input->post('noip');
	    $id_customer    = $this->session->userdata('id');
	    $uoi = $this->session->userdata('user_order_id');
		$sharp = str_replace("%20","_", $sub);
		$url = $sub.'#'.$sharp;
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		
		$data['total'] = $this->Item_model->totalSubOrder($nomeja,$id_customer,$uoi,$id_trans->id);
		$data['hitungbayar'] = $this->Item_model->totalbayar($id_trans->id);
		$data['item'] = $this->Item_model->cart($id_customer)->result();
		$data['nomeja'] = $nomeja;
		$data['itempaket'] = [];
		$notrans = $this->db->order_by('id',"asc")->where('id_customer',$id_customer)
  			->limit(1)
  			->get('sh_t_transactions')
  			->row('id');
		$data['totalbayar'] = $this->Item_model->totalbayar($notrans);
		
		if ($cek == 'Makanan') {
			$log = 'index.php/cart/home/Makanan/'.$sub.'#'.preg_replace('/%20/', '_', $sub);;
		}elseif ($cek == 'Minuman') {
			$log = 'index.php/cart/home/Minuman/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
		}else{
			$log = 'index.php/cart/home/'.$nomeja;
		}
		$cabang = $this->db->order_by('id',"desc")
			  	->limit(1)
			  	->get('sh_m_cabang')
			  	->row('id');
		$ip_address = $this->input->ip_address();
		$id_table = $this->db->get_where('sh_rel_table', ['id_customer' => $id_customer])->row();
	    $st = $id_table->status;
		$dataevent = [
			'event_type' => 'Akses checkout SO',
			'cabang' => $cabang,
			'id_trans' => $id_trans->id,
			'id_customer' => $this->session->userdata('id'),
			'event_date' => date('Y-m-d H:i:s'),
			'user_by' => $this->session->userdata('username'),
			'description' => 'Membuka halaman checkout dengan IP: '.$ip_address,
			'created_date' => date('Y-m-d'),
		];
		$result = $this->db->insert('sh_event_log',$dataevent);

		$data['log'] = $log;
		$data['cek'] = $cek;
		$data['sub'] = $sub;
		$data['url'] = $url;
		$data['st']	 = $st;
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['icon'] = $this->Admin_model->getIcon('add');
		$data['logo'] = $this->Admin_model->getLogo();
		$this->load->view('checkout',$data);
	}
	public function validasi_order($table, $cek = NULL, $reorder = NULL,$sub = NULL)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $table          = $this->session->userdata('nomeja');
	    $qty            = $this->input->post('qty');
	    $qtyaddon       = $this->input->post('qtyaddon');
	    $qtyip          = $this->input->post('qtyip');
	    $options = (array) $this->input->post('options');
	    $pesan = (array) $this->input->post('pesan');
	    $item_codes     = (array) $this->input->post('no'); // array item utama
	    $item_codeaddon = $this->input->post('noaddon');
	    $item_codeip    = $this->input->post('noip');
	    $id_customer    = $this->session->userdata('id');
	    
	    // 🔹 1. Validasi awal: hitung jumlah item Special Promo yang dikirim
	    $specialPromoCount = 0;
	    foreach ($item_codes as $code) {
	        $item = $this->Item_model->getItemByCode($code);
	        if ($item && $item->sub_category == 'Special Promo') {
	            $specialPromoCount++;
	        }
	    }

	    // Jika ada 2 atau lebih Special Promo dalam satu order → tolak
	    if ($specialPromoCount >= 2) {
	        $this->session->set_flashdata('error', 'You can only order one Special Promo item');
	        redirect('index.php/cart/home/' . $table);
	        return;
	    }

	    // 🔹 2. Validasi per item
	    foreach ($item_codes as $item_code) {
	        $item = $this->Item_model->getItemByCode($item_code);
	        if (!$item) continue; // skip jika item tidak ditemukan

	        // Jika bukan Special Promo → langsung proses
	        if ($item->sub_category != 'Special Promo') {
	            $this->check_stock($item_code, $qty, $id_customer, $table, $cek);
	            continue;
	        }

	        // --- Jika item adalah Special Promo ---
	        $cekSPchart    = $this->Item_model->cekSPchart($item_code);  // item ini sudah di cart?
	        $cekSPtrans    = $this->Item_model->cekSPtrans($item_code);  // item ini sudah di transaksi?
	        $countSPchart  = $this->Item_model->countSPchart();          // total SP di cart
	        $countSPtrans  = $this->Item_model->countSPtrans();          // total SP di transaksi

	        // Jika item sama sudah ada → boleh lanjut
	        if ($cekSPchart || $cekSPtrans) {
	            $this->check_stock($item_code, $qty, $id_customer, $table, $cek);
	            continue;
	        }

	        // Jika belum ada satupun Special Promo di cart & trans → boleh lanjut
	        if ($countSPchart == 0 && $countSPtrans == 0) {
	            $this->check_stock($item_code, $qty, $id_customer, $table, $cek);
	            continue;
	        }

	        // Jika sudah ada Special Promo lain → tolak
	        $this->session->set_flashdata('error', 'You can only order one Special Promo item.');
	        redirect('index.php/cart/home/' . $table);
	        return;
	    }

	    // 🔹 3. Cek item tambahan (addon & IP)
	    if (!empty($item_codeaddon)) {
	        $this->check_stock($item_codeaddon, $qtyaddon, $id_customer, $table, $cek);
	    }

	    if (!empty($item_codeip)) {
	        $this->check_stock($item_codeip, $qtyip, $id_customer, $table, $cek);
	    }

	    // 🔹 4. Jika semua validasi lolos → lanjut order
	    // var_dump($cek);exit();
	    if ($cek) {
	    	if ($cek == 'bayar') {
	    	  // $this->bayar();
	    		$this->bayar_va();
		    }elseif($cek == 'PaymentCashier'){
		      $this->order($table,$cek,'makanan',$sub,$reorder);
		    }else{
		      $this->checkout($table, $cek, $sub);
		    }
	    }else{
	    	$this->checkout($table, $cek, $sub);
	    }
	    
	    
	    // $this->order($table, $cek, $sub);
	}
	private function check_stock($item_codes, $quantities, $id_customer, $table, $cek)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    // Paksa jadi array
	    if (!is_array($item_codes)) {
	        $item_codes = [$item_codes];
	        $quantities = [$quantities];
	    }

	    foreach ($item_codes as $index => $item_code) {

	        // Ambil data item
	        $item = $this->Item_model->getDataC($item_code);
	        if (!$item) {
	            $item = $this->Item_model->getDataCP($item_code);
	        }

	        if (!$item) {
	            continue;
	        }

	        // Skip jika item tidak pakai stock
	        if ((int)$item->need_stock !== 1) {
	            continue;
	        }

	        $qty_input = (int)$quantities[$index];
	        $stock     = (int)$item->stock;

	        // Ambil qty di cart (jika ada)
	        $cart = $this->db->where('id_customer', $id_customer)
	                         ->where('item_code', $item_code)
	                         ->get('sh_cart')
	                         ->row();

	        $qty_cart = $cart ? (int)$cart->qty : 0;

	        /*
	         |--------------------------------------------------------------------------
	         | LOGIKA BENAR
	         | - Saat ADD: qty_input adalah tambahan
	         | - Saat UPDATE: qty_input adalah qty akhir
	         |--------------------------------------------------------------------------
	         */
	        if ($cek === 'update') {
	            $total_qty = $qty_input; // qty final
	        } else {
	            $total_qty = $qty_cart + $qty_input; // qty tambahan
	        }
	        $total_qty = $qty_input;
	        // CEK STOCK
	        if ($total_qty > $stock) {
	            $this->session->set_flashdata(
	                'error',
	                $item->description . ' stock not fulfilled. Available: ' . $stock
	            );
	            redirect('index.php/cart/home/' . $table);
	            exit;
	        }
	    }
	}
	public function order($table,$payment_type, $cek = NULL, $sub = NULL, $reorder=NULL)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $table = $this->session->userdata('nomeja');
	    $qty = $this->input->post('qty');
	    $qtyaddon = $this->input->post('qtyaddon');
	    $qtyip = $this->input->post('qtyip');
	    $item_code = $this->input->post('no');
	    $item_codeaddon = $this->input->post('noaddon');
	    $item_codeip = $this->input->post('noip');
	    $nama = $this->input->post('nama');
	    $options = $this->input->post('options');
	    $namaaddon = $this->input->post('namaaddon');
	    $namaip = $this->input->post('namaip');
	    $harga = $this->input->post('harga');
	    $hargaaddon = $this->input->post('hargaaddon');
	    $pesan = $this->input->post('pesan');
	    $hargaip = $this->input->post('hargaip');
	    $id_customer = $this->session->userdata('id');
	    $uoi = $this->session->userdata('user_order_id');

	    if (!$reorder) {
	    	// Cek apakah item sudah pernah diorder dalam rentang waktu yang ditentukan
	    	$this->check_duplicate_order($table, $item_code, $qty,$sub);
	    }
	    

	    // Jika tidak duplikat, lanjutkan ke proses order posting
	    $this->order_post($table,$payment_type, $cek, $sub);
	}
	private function check_duplicate_order($table, $item_codes, $qty, $sub)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $date = date('Y-m-d');
	    $uoi  = $this->session->userdata('user_order_id');

	    // Ambil semua transaksi hari ini untuk item-item tersebut
	    $query = $this->db->select('item_code, timeout_order_so')
	                      ->from('sh_t_transaction_details')
	                      ->where('LEFT(created_date,10)', $date)
	                      ->where('selected_table_no', $table)
	                      // ->where('user_order_id', $uoi)
	                      ->where('selforder', 1)
	                      ->where_in('item_code', $item_codes)
	                      ->order_by('id', 'DESC')
	                      ->get();

	    $results = $query->result();

	    // Kalau tidak ada satupun item ditemukan → tidak perlu lanjut
	    if (empty($results)) {
	        return;
	    }

	    // Ambil jam dan menit dari setiap timeout_order_so
	    $timeoutHM = [];
	    foreach ($results as $row) {
	        $timeoutHM[] = date('H:i', strtotime($row->timeout_order_so));
	    }

	    // Ambil nilai unik jam-menit
	    $uniqueTimeoutHM = array_unique($timeoutHM);

	    // Kalau semua item punya jam-menit timeout yang sama
	    if (count($uniqueTimeoutHM) === 1) {
	        $timeout = reset($uniqueTimeoutHM) . ':00'; // tambahkan detik agar bisa dibandingkan

	        // Cek apakah waktu sekarang masih di bawah atau sama dengan timeout
	        if (date('H:i') <= date('H:i', strtotime($timeout))) {
	            // ✅ Duplikat dalam rentang waktu (jam dan menit sama)
	            $this->log_duplicate_order($table, $qty, $item_codes);
	            $this->session->set_flashdata('error', 'Duplicate Order, Please Check Bill Preview');
	            // redirect('index.php/selforder/home/' . $table);
	            $subcrash = str_replace('%20', '_', $sub);
				redirect('index.php/ordermakanan/menu/Makanan/'.$sub.'#'.$subcrash);
	        }
	    }

	    // Kalau jam-menit timeout berbeda → tidak dianggap duplikat
	    return;
	}


	private function log_duplicate_order($table, $qty, $item_code)
	{
	    $data = [];
	    $id_customer = $this->session->userdata('id');
	    $id_trans = $this->db->get_where('sh_t_transactions', ['id_customer' => $id_customer])->row()->id;
	    $cabang = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_m_cabang')->row('id');
	    $ip_address = $this->input->ip_address();

	    foreach ($qty as $index => $q) {
	        if ($q != 0) {
	            $data[] = [
	                'event_type' => 'Duplicate Order',
	                'cabang' => $cabang,
	                'id_trans' => $id_trans,
	                'id_customer' => $id_customer,
	                'event_date' => date('Y-m-d H:i:s'),
	                'user_by' => $this->session->userdata('username'),
	                'description' => $item_code[$index] . ' Qty: ' . $q . ' IP: ' . $ip_address,
	                'created_date' => date('Y-m-d'),
	            ];
	        }
	    }

	    $this->db->insert_batch('sh_event_log', $data);
	}
	public function order_postOLD($table,$type, $cek = NULL, $sub = NULL)
	{
	    $table = $this->session->userdata('nomeja');
	    $qty = $this->input->post('qty');
	    $qtyaddon = $this->input->post('qtyaddon');
	    $qtyip = $this->input->post('qtyip');
	    $item_code = $this->input->post('no');
	    $item_codeaddon = $this->input->post('noaddon');
	    $item_codeip = $this->input->post('noip');
	    $nama = $this->input->post('nama');
	    $options = $this->input->post('options');
	    $namaaddon = $this->input->post('namaaddon');
	    $namaip = $this->input->post('namaip');
	    $harga = $this->input->post('harga');
	    $hargaaddon = $this->input->post('hargaaddon');
	    $pesan = $this->input->post('pesan');
	    $hargaip = $this->input->post('hargaip');
	    $id_customer = $this->session->userdata('id');
	    $uoi = $this->session->userdata('user_order_id');
	    $id_trans = $this->db->get_where('sh_t_transactions', ['id_customer' => $id_customer])->row()->id;
	    // var_dump($pesan);exit();
	    // Insert data transaksi untuk item dan addon
	    if ($type == "PN") {
    		$this->bayar_va($table,$cek,$sub);
    	}
	    if (!empty($item_code)) {
	        $id_details = $this->insert_transaction_details($id_trans, $item_code, $qty, $nama, $harga, $type);
	    }
	    if (!empty($item_codeaddon)) {
	        $this->insert_transaction_details($id_trans, $item_codeaddon, $qtyaddon, $namaaddon, $hargaaddon, $type);
	    }
	    if (!empty($item_codeip)) {
	        $this->insert_transaction_details_list($id_details,$id_trans, $item_codeip, $qtyip, $namaip, $hargaip, $type);
	    }

	    // Update stok item dan addon
	    if (!empty($item_code)) {
	        $this->update_stock($item_code, $qty, $this->input->post('need_stock'));
	    }
	    if (!empty($item_codeaddon)) {
	        $this->update_stock($item_codeaddon, $qtyaddon, $this->input->post('need_stockaddon'));
	    }
	    if (!empty($item_codeip)) {
	        $this->update_stock($item_codeip, $qtyip, $this->input->post('need_stockip'));
	    }

	    // Hapus data di sh_cart berdasarkan id_customer dan user_order_id
	    // $this->db->where('id_customer', $id_customer)->where('user_order_id', $uoi)->delete('sh_cart');
	    // $this->db->where('id_customer', $id_customer)->where('user_order_id', $uoi)->delete('sh_cart_details');
		$this->db->where('id_customer', $id_customer)->delete('sh_cart');
	    $this->db->where('id_customer', $id_customer)->delete('sh_cart_details');

	    
	    // Log the event
	    $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');
	    $dataevent = [];
	    
	    // Handle event logging for items (both main items and addons)
	    if (!empty($item_code)) {
	        for ($i = 0; $i < count($item_code); $i++) {
	            $dataevent[] = [
	                'event_type' => 'Order SO',
	                'cabang' => $cabang,
	                'id_trans' => $id_trans,
	                'id_customer' => $id_customer,
	                'event_date' => date('Y-m-d H:i:s'),
	                'user_by' => $this->session->userdata('username'),
	                'description' => 'Melakukan Order item: ' . $this->input->post('nama')[$i] . ' qty: ' . $qty[$i],
	                'created_date' => date('Y-m-d'),
	            ];
	        }
	    }

	    if (!empty($item_codeaddon)) {
	        for ($i = 0; $i < count($item_codeaddon); $i++) {
	            $dataevent[] = [
	                'event_type' => 'Order SO - Addon',
	                'cabang' => $cabang,
	                'id_trans' => $id_trans,
	                'id_customer' => $id_customer,
	                'event_date' => date('Y-m-d H:i:s'),
	                'user_by' => $this->session->userdata('username'),
	                'description' => 'Melakukan Order Addon: ' . $this->input->post('namaaddon')[$i] . ' qty: ' . $qtyaddon[$i],
	                'created_date' => date('Y-m-d'),
	            ];
	        }
	    }

	    // Insert event log data into the database
	    if (!empty($dataevent)) {
	        $result = $this->db->insert_batch('sh_event_log', $dataevent);
	    }
	    
	    // Redirect after successful order
	    if ($result) {
	    	$updatetable = ['status' => 'Dining'];
	    	$this->db->where('id_table',$table);
	    	$this->db->where('id_customer',$id_customer);
	    	$this->db->where('is_close',0);
	    	$this->db->update('sh_rel_table', $updatetable);
	        $this->session->set_flashdata('successcart', 'Menu Sent to Kitchen');
	        redirect('index.php/selforder/home/' . $table);
	    }
	}
	public function order_post($table,$payment_type, $cek = NULL, $sub = NULL)
	{
		$session = $this->cekstatus_model->cek();
		  	// if($session['status'] == 'Billing'){
		  	// 		$nomeja = $this->session->userdata('nomeja');
		  	// 		redirect('index.php/login/logoutback/'.$nomeja);
		  	// }
	    $table = $this->session->userdata('nomeja');
	    $qty = $this->input->post('qty');
	    $qtyaddon = $this->input->post('qtyaddon');
	    $qtyip = $this->input->post('qtyip');
	    $item_code = $this->input->post('no');
	    $item_codeaddon = $this->input->post('noaddon');
	    $item_codeip = $this->input->post('noip');
	    $nama = $this->input->post('nama');
	    $options = $this->input->post('options');
	    $namaaddon = $this->input->post('namaaddon');
	    $namaip = $this->input->post('namaip');
	    $harga = $this->input->post('harga');
	    $hargaaddon = $this->input->post('hargaaddon');
	    $pesan = $this->input->post('pesan');
	    $hargaip = $this->input->post('hargaip');
	    $id_customer = $this->session->userdata('id');
	    $uoi = $this->session->userdata('user_order_id');
	    $id_trans = $this->db->get_where('sh_t_transactions', ['id_customer' => $id_customer])->row()->id;
	    $payment_card_bank      = $this->input->post('payment_card_bank');

	    // var_dump($item_code);exit();
	    // Insert data transaksi untuk item dan addon
	    
	    if (!empty($item_code)) {
	        $id_details = $this->insert_transaction_details($id_trans, $item_code, $qty, $nama, $harga, $payment_type);
	    }
	    if (!empty($item_codeaddon)) {
	        $this->insert_transaction_details($id_trans, $item_codeaddon, $qtyaddon, $namaaddon, $hargaaddon, $payment_type);
	    }
	    if (!empty($item_codeip)) {
	        $this->insert_transaction_details_list($id_details,$id_trans, $item_codeip, $qtyip, $namaip, $hargaip, $payment_type);
	    }

	    // Update stok item dan addon
	    if (!empty($item_code)) {
	        $this->update_stock($item_code, $qty, $this->input->post('need_stock'));
	    }
	    if (!empty($item_codeaddon)) {
	        $this->update_stock($item_codeaddon, $qtyaddon, $this->input->post('need_stockaddon'));
	    }
	    if (!empty($item_codeip)) {
	        $this->update_stock($item_codeip, $qtyip, $this->input->post('need_stockip'));
	    }
	    
	    // Log the event
	    $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');
	    $dataevent = [];
	    
	    // Handle event logging for items (both main items and addons)
	    if (!empty($item_code)) {
	        for ($i = 0; $i < count($item_code); $i++) {
	            $dataevent[] = [
	                'event_type' => 'Order SO',
	                'cabang' => $cabang,
	                'id_trans' => $id_trans,
	                'id_customer' => $id_customer,
	                'event_date' => date('Y-m-d H:i:s'),
	                'user_by' => $this->session->userdata('username'),
	                'description' => 'Melakukan Order item: ' . $this->input->post('nama')[$i] . ' qty: ' . $qty[$i],
	                'created_date' => date('Y-m-d'),
	            ];
	        }
	    }

	    if (!empty($item_codeaddon)) {
	        for ($i = 0; $i < count($item_codeaddon); $i++) {
	            $dataevent[] = [
	                'event_type' => 'Order SO - Addon',
	                'cabang' => $cabang,
	                'id_trans' => $id_trans,
	                'id_customer' => $id_customer,
	                'event_date' => date('Y-m-d H:i:s'),
	                'user_by' => $this->session->userdata('username'),
	                'description' => 'Melakukan Order Addon: ' . $this->input->post('namaaddon')[$i] . ' qty: ' . $qtyaddon[$i],
	                'created_date' => date('Y-m-d'),
	            ];
	        }
	    }

	    // Insert event log data into the database
	    if (!empty($dataevent)) {
	        $result = $this->db->insert_batch('sh_event_log', $dataevent);
	    }
	    
	    // Redirect after successful order
	    if ($result) {
	    	if ($payment_type == 'PaymentNow') {
	    		$updatetable = ['status' => 'Available'];
	    		$this->session->set_flashdata('successcart', 'Menu Sent to Kitchen');
	    	}else{
	    		$updatetable = ['status' => 'Dining'];
	    		// $this->session->set_flashdata('successkasir', 'Please Pay at Cashier to Complete Your Order');
	    		$this->session->set_flashdata('success', 'Menu Sent to Kitchen');
	    	}
	    	
	    	$this->db->where('id_table',$table);
	    	$this->db->where('id_customer',$id_customer);
	    	$this->db->where('is_close',0);
	    	$this->db->update('sh_rel_table', $updatetable);

	    	if ($payment_type == 'PaymentNow') {
	    		$payment = $this->Item_model->totalbayar($id_trans);
				$q = "SELECT * FROM sh_m_setup";
			    $setup = $this->db->query($q)->row();
		    	$this->db->where('id', $id_trans)->update('sh_t_transactions', [
		    		'payment_card_type'	=> 'VA Xendit',
		    		'payment_bank_card' => $payment_card_bank,
			        'payment_amount' => $payment->total,
			        'total_amount'   => $payment->total,
			        'kembalian'      => 0,
			        'payment_date'   => date('Y-m-d H:i:s'),
			        'payment_type'   => 'bank card',
			        'tax_percent'    => $setup->tax_percent,
			        'tax_amount'     => $payment->ppn,
			    ]);
	    	}
	    	
	        // Hapus data di sh_cart berdasarkan id_customer dan user_order_id
		    // $this->db->where('id_customer', $id_customer)->where('user_order_id', $uoi)->delete('sh_cart');
		    // $this->db->where('id_customer', $id_customer)->where('user_order_id', $uoi)->delete('sh_cart_details');
		    // 🔥 GABUNG SEMUA ITEM (MAIN + ADDON + IP)
			$all_item_codes = [];

			// item utama
			if (!empty($item_code)) {
			    $all_item_codes = array_merge($all_item_codes, (array) $item_code);
			}

			// addon
			if (!empty($item_codeaddon)) {
			    $all_item_codes = array_merge($all_item_codes, (array) $item_codeaddon);
			}

			// item IP
			if (!empty($item_codeip)) {
			    $all_item_codes = array_merge($all_item_codes, (array) $item_codeip);
			}

			// 🔥 HAPUS DUPLIKAT (optional tapi bagus)
			$all_item_codes = array_unique($all_item_codes);

			// 🔥 DELETE BERDASARKAN ITEM_CODE
			if (!empty($all_item_codes)) {

			    $this->db->where('id_customer', $id_customer);
			    $this->db->where_in('item_code', $all_item_codes);
			    $this->db->delete('sh_cart');

			    $this->db->where('id_customer', $id_customer);
			    $this->db->where_in('item_code', $all_item_codes);
			    $this->db->delete('sh_cart_details');
			}

			$transRow = $this->db
	        ->where('id_customer', $id_customer)
	        ->where('date_order_menu IS NULL', null, false)
	        ->where('start_time_order IS NULL', null, false)
	        ->where('is_order_menu_active', 0)
	        ->where('checker_printed', 0)
	        ->order_by('id', "DESC")
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();
	        if ($transRow) {
	        	$dateOM = date('Y-m-d H:i:s');
	        	$timeO = date('H:i:s');
	        	$this->db->where('id',$id_trans)->update('sh_t_transactions',[
			        'date_order_menu'=> $dateOM,
			        'start_time_order' => $timeO,
			        'is_order_menu_active'=> 1,
			        'checker_printed'	=>1,
			    ]);
	        }
			
	        // redirect('index.php/selforder/home/' . $table);
	  //       $this->session->unset_userdata(array_keys($this->session->userdata()));
			// $this->session->set_flashdata('successkasir', 'Please Pay at Cashier to Complete Your Order');

			redirect('index.php/login/log/');
			// $subcrash = str_replace('%20', '_', $sub);
			// redirect('index.php/ordermakanan/menu/Makanan/'.$sub.'#'.$subcrash);
	    }
	}
	public function bayar_va()
		{
		    $payment_type = $this->input->post('payment_type');
		    $bank_code    = $this->input->post('bank_code');
		    $amount       = $this->input->post('totalbayar');
		    $id_customer  = $this->session->userdata('id');
		    $nomeja       = $this->session->userdata('nomeja');

		    // Ambil cabang terakhir
		    $cabang = $this->db
		        ->order_by('id', "desc")
		        ->limit(1)
		        ->get('sh_m_cabang')
		        ->row('id');

		    // Ambil transaksi terakhir customer
		    $transRow = $this->db
		        ->order_by('id', "desc")
		        ->where('id_customer', $id_customer)
		        ->limit(1)
		        ->get('sh_t_transactions')
		        ->row();

		    if (!$transRow) {
		        show_error('Transaksi tidak ditemukan');
		    }

		    $notrans = $transRow->id;

		    // Generate external ID
		    $tgl        = date('ymd');
		    $externalId = "SH".$cabang.$notrans.date('ymdHis');
		    Xendit::setApiKey('xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW');

		    $params = [
		        'external_id'      => $externalId,
		        'bank_code'        => $bank_code,
		        'name'             => $this->session->userdata('username'),
		        'amount'  => $amount,
		        'expiration_date'  => date('c', strtotime('+1 day')),

		    ];
		    
		    try {
		        // BUAT VA
		        $va = \Xendit\VirtualAccounts::create($params);
		        // FORMAT EXPIRED DATE
		        $expired_at = (new DateTime($va['expiration_date']))->format('Y-m-d H:i:s');

		        // DATA YANG AKAN DISIMPAN
		        $vaData = [
		            'va_number'   => $va['account_number'],
		            'bank'        => $va['bank_code'],
		            'amount'      => $amount,
		            'status'      => 'PENDING',
		            'id_customer' => $id_customer,
		            'expired_at'  => $expired_at,
		            'id_trans'    => $notrans,
		        ];

		        // 🔍 CEK APAKAH SUDAH ADA
		        $existing = $this->db
		            ->where('external_id', $externalId)
		            ->get('sh_payment_va')
		            ->row();
		        $this->db_dev = $this->load->database('dev', TRUE);
		        $existing_dev = $this->db_dev
		            ->where('external_id', $externalId)
		            ->get('sh_payment_va')
		            ->row();
		        if ($existing) {
		            // 🔄 UPDATE
		            $this->db
		                ->where('external_id', $externalId)
		                ->update('sh_payment_va', $vaData);
		        } else {
		            // ➕ INSERT
		            $vaData['external_id'] = $externalId;
		            $vaData['created_date']  = date('Y-m-d H:i:s');
		            $this->db->insert('sh_payment_va', $vaData);
		        }

		        // if ($existing_dev) {
		        //     $this->db_dev->where('external_id', $externalId)
		        //         ->update('sh_payment_va', $vaData);
		        // } else {
		        //     // ➕ INSERT
		        //     $vaData['external_id'] = $externalId;
		        //     $vaData['created_date']  = date('Y-m-d H:i:s');
		        //     $this->db_dev->insert('sh_payment_va', $vaData);
		        // }

		        // ✅ REDIRECT KE HALAMAN VA
		        redirect('index.php/cart/bayar_va_view/'.$externalId.'/'.$nomeja);

		    } catch (\Exception $e) {
		        show_error($e->getMessage());
		    }
		}

	public function bayar_va_view($external_id,$nomeja)
	{
	    // Ambil data VA
	    $va = $this->db
	        ->where('external_id', $external_id)
	        ->get('sh_payment_va')
	        ->row();

	    if (!$va) {
	        show_error('Virtual Account not found');
	    }

	    // Data untuk view (ARRAY)
	    $data = [];
	    $qty            = $this->input->post('qty');
	    $qtyaddon       = $this->input->post('qtyaddon');
	    $qtyip          = $this->input->post('qtyip');
	    $options = (array) $this->input->post('options');
	    $item_codes     = (array) $this->input->post('no'); // array item utama
	    $item_codeaddon = $this->input->post('noaddon');
	    $item_codeip    = $this->input->post('noip');
	    $id_customer    = $this->session->userdata('id');
	    $uoi = $this->session->userdata('user_order_id');
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		
		$data['total'] = $this->Item_model->totalSubOrder($nomeja,$id_customer,$uoi,$id_trans->id);
		$data['hitungbayar'] = $this->Item_model->totalbayar($id_trans->id);
		$data['item'] = $this->Item_model->cart($id_customer)->result();
		$data['nomeja'] = $nomeja;
		$data['itempaket'] = [];
		$notrans = $this->db->order_by('id',"asc")->where('id_customer',$id_customer)
  			->limit(1)
  			->get('sh_t_transactions')
  			->row('id');
		$data['totalbayar'] = $this->Item_model->totalbayar($notrans);
	    $data['cn'] = $this->Admin_model->getColorCN();
	    $data['va'] = $va;
	    $data['nomeja'] = $nomeja;

	    $this->load->view('checkout/va_view', $data);
	}
	
	public function bayar()
	{
		$session = $this->cekstatus_model->cek();
		  	// if($session['status'] == 'Billing'){
		  	// 		$nomeja = $this->session->userdata('nomeja');
		  	// 		redirect('index.php/login/logoutback/'.$nomeja);
		  	// }
	    $amount      = $this->input->post('totalbayar');
	    $id_customer = $this->session->userdata('id');
	    $uoi         = $this->session->userdata('user_order_id');
	    if (!$id_customer) {
	        show_error('Customer tidak ditemukan');
	    }
	    $table = $this->db->get_where('sh_rel_table', ['id_customer' => $id_customer])->row();
	    if ($table->status == 'Billing') {
	    	$this->session->set_flashdata('error', 'Another user is currently processing the payment for this table');
	    	redirect('index.php/Cart/checkout/'.$table->id_table);
	    }
	    // Ambil transaksi terakhir customer (yang belum dibayar)
	    $transRow = $this->db
	        ->where('id_customer', $id_customer)
	        ->where('payment_date IS NULL', null, false)
	        ->order_by('id', "DESC")
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();

	    if (!$transRow) {
	        show_error('Transaksi tidak ditemukan atau sudah dibayar');
	    }

	    // 🔴 CEK APAKAH DETAIL SUDAH ADA
	    $cekDetail = $this->db
	        ->where('id_trans', $transRow->id)
	        ->count_all_results('sh_t_transaction_details');

	    if ($cekDetail > 0) {
	        // Sudah pernah klik bayar → langsung redirect ke billing existing
	        $billing = $this->db
	            ->where('external_id', $transRow->external_id_so)
	            ->where_in('status', ['PENDING','UNPAID'])
	            ->get('sh_billing_so')
	            ->row();

	        if ($billing) {
	        	$this->db->where('id_customer', $id_customer)->update('sh_rel_table', [
			        'status' => 'Billing'
			    ]);
	            $payload = json_decode($billing->raw_payload);
	            redirect($payload->data->payment_url ?? '/');
	            return;
	        }
	    }
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$sum = $this->Item_model->totalbayar($id_trans->id);
		$amount = $sum->total + $sum->sc + $sum->ppn;
	    // ==============================
	    // GENERATE EXTERNAL ID
	    // ==============================
	    $cabang = $this->db
	        ->order_by('id', "desc")
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    $notrans = $transRow->id;
	    $extId   = $cabang . $notrans . '_' . date('YmdHis');

	    $this->db->where('id',$transRow->id)->update('sh_t_transactions',[
	        'external_id_so'=>$extId,
	    ]);

	    // ==============================
	    // INSERT DETAIL (ANTI DUPLICATE)
	    // ==============================
	    $this->insert_detail_sebelum_payment([
	        'id_trans' => $transRow->id
	    ]);

	    // ==============================
	    // CREATE BILLING
	    // ==============================
	    $invoice = $this->create_billing($amount,$extId);

	    if (!empty($invoice['payment_url'])) {
	    	$this->db->where('id_customer', $id_customer)->update('sh_rel_table', [
		        'status' => 'Billing'
		    ]);
	        redirect($invoice['payment_url']);
	    } else {
	        show_error('Gagal membuat billing');
	    }
	}

	private function create_billing($amount, $external_id)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    // ==============================
	    // 1. CEK APAKAH BILLING SUDAH ADA
	    // ==============================
	    $existing = $this->db
	        ->where('external_id', $external_id)
	        ->where_in('status', ['PENDING', 'UNPAID'])
	        ->get('sh_billing_so')
	        ->row();

	    if ($existing) {
	        // Billing sudah ada → jangan hit API lagi
	        return [
	            'invoice_id'  => $existing->invoice_id,
	            'payment_url' => json_decode($existing->raw_payload)->data->payment_url ?? null
	        ];
	    }

	    // ==============================
	    // 2. AMBIL SETUP
	    // ==============================
	    $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();

		if (!$setup) {
		    return ['status' => false, 'message' => 'Setup tidak ditemukan'];
		}
		// Jika belum login hari ini
		if (date('Y-m-d', strtotime($setup->member_last_login)) != date('Y-m-d')) {
		    // Panggil URL relogin
	        if (empty($setup->member_login_url)) {
	            echo "URL login API kosong";
	            return;
	        }

	        $url = $setup->member_login_url;

	        // Payload login
	        $payload = json_encode([
	            "username" => $setup->member_login_user,
	            "password" => $setup->member_login_password
	        ]);

	        // Init CURL
	        $curl = curl_init();

	        curl_setopt_array($curl, [
	            CURLOPT_URL => $url,
	            CURLOPT_RETURNTRANSFER => true,
	            CURLOPT_POST => true,
	            CURLOPT_POSTFIELDS => $payload,
	            CURLOPT_HTTPHEADER => [
	                'Content-Type: application/json',
	                'Accept: application/json'
	            ],

	            // FIX SSL SELF SIGNED CERTIFICATE
	            CURLOPT_SSL_VERIFYPEER => false,
	            CURLOPT_SSL_VERIFYHOST => false,

	            // Stabilitas koneksi
	            CURLOPT_TIMEOUT => 30,
	            CURLOPT_CONNECTTIMEOUT => 10,
	            CURLOPT_FOLLOWLOCATION => true
	        ]);

	        $result = curl_exec($curl);
	        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	        // Jika CURL error
	        if ($result === false) {
	            echo "CURL Error: " . curl_error($curl);
	            curl_close($curl);
	            return;
	        }

	        curl_close($curl);

	        // Jika response API sukses
	        if ($status == 200) {

	            $response = json_decode($result, true);

	            if (!$response) {
	                echo "Response tidak valid<br>";
	                echo $result;
	                return;
	            }

	            if (!isset($response['data']['access_token'])) {
	                echo "Access token tidak ditemukan<br>";
	                echo $result;
	                return;
	            }

	            $token = $response['data']['access_token'];

	            // Enkripsi token
	            $encrypted_token = $this->encryption->encrypt($token);

	            // Simpan token
	            $this->db->where('id', 1);
	            $this->db->update('sh_m_setup', [
	                'member_access_token' => $token,
	                'member_last_login'   => date('Y-m-d H:i:s')
	            ]);
	        }

		} else {
		    // Sudah login hari ini
		    $token = $setup->member_access_token;
		}
	    if (!$token) {
	        return ['status' => false, 'message' => 'Token kosong'];
	    }
	    
	    // ==============================
	    // 3. HIT API
	    // ==============================
	    $url = "https://api.hachigroup.id/transaction/v1/billing";

	    $payload = [
	        "service"                 => "SELF_ORDER",
	        "external_transaction_id" => $external_id,
	        "email"                   => 'testing@gmail.com',
	        "amount"                  => (int)$amount,
	        "description"             => "Pembayaran " . $external_id,
	        "success_redirect_url"	  => base_url()."index.php/cart/get_payment/".$external_id,
	        "failure_redirect_url"	  => "",
	    ];

	    $ch = curl_init($url);

	    curl_setopt_array($ch, [
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POST           => true,
		    CURLOPT_TIMEOUT        => 30,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_HTTPHEADER     => [
		        "Content-Type: application/json",
		        "Authorization: Bearer " . $token
		    ],
		    CURLOPT_POSTFIELDS     => json_encode($payload),
		]);

	    $response = curl_exec($ch);
	    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    if (curl_errno($ch)) {
	        $error = curl_error($ch);
	        curl_close($ch);
	        return ['status' => false, 'message' => $error];
	    }

	    curl_close($ch);

	    $result = json_decode($response);
	    // $pretty = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	    // var_dump($pretty);exit();

	    if ($httpcode != 200 && $httpcode != 201) {
	        return ['status' => false, 'message' => 'API Error', 'data' => $result];
	    }

	    if (!$result || !$result->status) {
	        return ['status' => false, 'message' => 'Response tidak valid'];
	    }

	    // ==============================
	    // 4. SIMPAN KE DB
	    // ==============================
	    $billing = $result->data;
	    $transaction_id = substr(explode('_', $billing->external_transaction_id)[0], 1);
	    $insert = [
	        'external_id'   => $billing->external_transaction_id,
	        'transaction_id'=> $transaction_id ?? 0,
	        'invoice_id'    => $billing->external_billing_id ?? null,
	        'status'        => $billing->data->status ?? 'PENDING',
	        'amount'        => $billing->amount ?? 0,
	        'paid_amount'   => 0,
	        'payment_method'=> null,
	        'bank_code'     => null,
	        'paid_at'       => $billing->paid_at ?? null,
	        'raw_payload'   => $response
	    ];

	    $this->db->insert('sh_billing_so', $insert);
	    // ==============================
	    // 5. RETURN DATA
	    // ==============================
	    return [
	        'invoice_id'  => $billing->external_billing_id ?? null,
	        'payment_url' => $billing->payment_url ?? null
	    ];
	}
	
	private function insert_detail_sebelum_payment($data)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $id_trans = $data['id_trans'];

	    // 🔴 DOUBLE CEK
	    $cekDetail = $this->db
	        ->where('id_trans', $id_trans)
	        ->count_all_results('sh_t_transaction_details');

	    if ($cekDetail > 0) {
	        return;
	    }

	    $id_customer = $this->session->userdata('id');
	    $table       = $this->session->userdata('nomeja');
	    $username    = $this->session->userdata('username');
	    $uoi         = $this->session->userdata('user_order_id');

	    $cart = $this->db
	    ->select('sh_cart.*, o.description as do')
	    ->from('sh_cart')
	    ->join('sh_m_item_option o', 'sh_cart.options = o.id', 'left')
	    ->where('sh_cart.id_customer', $id_customer)
	    // ->where('sh_cart.user_order_id', $uoi)
	    ->get()
	    ->result();

	    if (!$cart) return;

	    $cabang = $this->db
	        ->order_by('id','DESC')
	        ->get('sh_m_cabang')
	        ->row('id');

	    $isTakeAway = ($this->session->userdata('visit_type') === 'TakeAway');

	    // =============================
	    // TIMEOUT
	    // =============================
	    $timeout_data = $this->db
	        ->order_by('id','DESC')
	        ->limit(1)
	        ->get('sh_set_time_cekdata')
	        ->row('seconds');

	    $seconds = "+" . $timeout_data . " seconds";
	    $ctime   = date('H:i:s');
	    $time    = date('H:i:s', strtotime($ctime . $seconds));


	    $detail = [];

	    foreach ($cart as $row) {
	    	$en = implode(',', array_filter([
			    $row->do,
			    $row->notesdua,
			    $row->extra_notes
			]));

	        $detail[] = [
	            'id_trans'          => $id_trans,
	            'item_code'         => $row->item_code,
	            'qty'               => $row->qty,
	            'unit_price'        => $row->unit_price,
	            'description'       => $row->description,
	            'extra_notes'       => $en ?? '',
	            'is_take_away'      => $isTakeAway ? 1 : 0,
	            'as_take_away'      => $isTakeAway ? 1 : 0,
	            'is_paid'           => 0,
	            'selected_table_no' => $table,
	            'cabang'            => $cabang,
	            'checker_printed'   => 1,
	            'created_date'      => date('Y-m-d H:i:s'),
	            'selforder'         => 1,
	            'start_time_order'  => date('H:i:s'),
	            'submit_time'       => date('Y-m-d H:i:s'),
	            'entry_by'          => $username,
	            'user_order_id'     => $uoi,
	            'timeout_order_so'  => $time,
	            'cekdata'           => 1,
	            'disc'              => 0,
	            'order_type'        => "Order",
	            'is_cancel'         => 0,
	            'session_item'      => 0,
	            'seat_id'           => 0,
	            'sort_id'           => 1,
	            'is_printed_so'     => 0,
	        ];
	    }

	    // ===============================
	    // INSERT DETAIL
	    // ===============================
	    // var_dump($detail);exit();
	    if (!empty($detail)) {
	        $this->db->insert_batch('sh_t_transaction_details', $detail);
	    }
	    // foreach ($cart as $row) {

	    //     $item = $this->db
	    //         ->get_where('sh_m_item', ['no' => $row->item_code])
	    //         ->row();

	    //     if ($item && $item->need_stock == 1) {

	    //         $newStock = $item->stock - $row->qty;

	    //         $this->db->where('no', $row->item_code)->update('sh_m_item', [
	    //             'stock' => $newStock,
	    //             'is_sold_out' => $newStock <= 0 ? 1 : 0
	    //         ]);
	    //     }
	    // }
	    $item_codes = [];
		$quantities = [];
		$need_stock = [];

		foreach ($cart as $row) {
		    $item_codes[] = $row->item_code;
		    $quantities[] = $row->qty;

		    // ambil info need_stock dari item
		    $item = $this->db->get_where('sh_m_item', ['no' => $row->item_code])->row();
		    $need_stock[] = $item ? (int)$item->need_stock : 0;
		}
		$this->update_stock($item_codes, $quantities, $need_stock);
	}
	private function update_stock($item_codes, $quantities, $need_stock)
	{
		$session = $this->cekstatus_model->cek();
		  	if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');

	    foreach ($item_codes as $index => $item_code) {

	        // ✅ Jika tidak butuh stock → skip
	        if ($need_stock[$index] == 0) {
	            continue;
	        }

	        $item = $this->db->where('no', $item_code)->get('sh_m_item')->row();
	        if (!$item) continue;

	        $qty = (int)$quantities[$index];
	        $current_stock = (int)$item->stock;

	        $new_stock = $current_stock - $qty;

	        // Jangan biarkan minus
	        if ($new_stock <= 0) {
	            $new_stock = 0;
	        }

	        $update_data = ['stock' => $new_stock];

	        // Jika stock habis
	        if ($new_stock == 0) {
	            $update_data['is_sold_out'] = 1;
	        }

	        $this->db->where('no', $item_code)->update('sh_m_item', $update_data);

	        $description = 'Stock Used ' . $qty;

	        if ($new_stock == 0 && $current_stock > 0) {
	            $description .= ' and set status from Available to Sold Out';
	        }

	        $datastok[] = [
	            'log_type'     => 'Update Stock',
	            'cabang'       => $cabang,
	            'item_code'    => $item_code,
	            'stock_before' => $current_stock,
	            'stock_after'  => $new_stock,
	            'difference'   => $qty,
	            'stock_entry'  => date('Y-m-d H:i:s'),
	            'username'     => $this->session->userdata('username'),
	            'description'  => $description,
	        ];
	    }

	    if (!empty($datastok)) {
	        $this->db->insert_batch('sh_stok_logs', $datastok);
	    }
	}
	public function get_payment($extId = NULL)
	{
	    // Ambil external_id
	    $external_id = $extId ?? ($this->db->where('is_proses', 0)
	                                        ->get('sh_billing_so')
	                                        ->row()->external_id ?? 0);

	    // Ambil setup
	    $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
	    if (!$setup) {
	        return ['status' => false, 'message' => 'Setup tidak ditemukan'];
	    }

	    // Generate token jika admin belum login hari ini
	    if (date('Y-m-d', strtotime($setup->admin_last_login)) != date('Y-m-d')) {
	        $this->generate_token();
	        $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
	    }

	    $token = $this->encryption->decrypt($setup->admin_access_token);

	    // Panggil API Hachi/Xendit
	    $page   = 1;
	    $limit  = 1000;
	    $status = 1;
	    $url = "https://api.hachigroup.id/transaction/v1/billing?page={$page}&limit={$limit}&start_date=" 
	            . date('Y-m-d') . "&end_date=" . date('Y-m-d') . "&status={$status}&service=SELF_ORDER";

	    $curl = curl_init();
	    curl_setopt_array($curl, [
	        CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_HTTPHEADER => [
	            "Accept: application/json",
	            "Authorization: Bearer {$token}"
	        ],
	        CURLOPT_SSL_VERIFYPEER => false,
	        CURLOPT_SSL_VERIFYHOST => false,
	    ]);
	    $response = curl_exec($curl);
	    curl_close($curl);

	    $result = json_decode($response);

	    // Jika API error atau data kosong
	    if (!$result || !$result->status || empty($result->data)) {
	        $this->load_loading_view($extId);
	        return;
	    }

	    // Cari data sesuai external_id
	    $found = null;
	    foreach ($result->data as $row) {
	        if ($row->external_transaction_id === $external_id) {
	            $found = $row;
	            break;
	        }
	    }

	    if (!$found) {
	        // Data belum muncul di API, tampilkan loading
	        $this->load_loading_view($extId);
	        return;
	    }

	    // Ambil status callback
	    $payment_status = strtoupper($found->status ?? '');
	    
	    if ($payment_status !== '1') {
	        // Status belum PAID, tampilkan loading
	        $this->load_loading_view($extId);
	        return;
	    }
	    // Cek sudah ada di DB atau belum
	    $cek = $this->db->where('external_id', $external_id)->get('sh_payments_so')->row();
	    if (!$cek) {
	    	if ($payment_status == '1') {
	    		$ps = 'PAID';
	    	}else{
	    		$ps = 'PENDING';
	    	}
	    	if ($found->callback->payment_method == 'QR_CODE') {
	    		$bank_code = $found->callback->payment_details->source ?? '';
	    	}else{
	    		$bank_code = $found->callback->bank_code ?? '';
	    	}
	    	
	        // Insert data payment
	        $insert = [
	            'external_id'     => $external_id,
	            'transaction_id'  => (int) substr(explode('_', $external_id)[0],1),
	            'invoice_id'      => $found->external_billing_id ?? '',
	            'status'          => $ps,
	            'amount'          => $found->amount ?? 0,
	            'paid_amount'     => $found->callback->paid_amount ?? 0,
	            'payment_method'  => $found->callback->payment_method ?? '',
	            'bank_code'       => $bank_code,
	            'paid_at'         => $found->callback->paid_at ?? '',
	            'raw_payload'     => json_encode($found),
	            'created_at'      => $found->created_at ?? date('Y-m-d H:i:s')
	        ];
	        $this->db->insert('sh_payments_so', $insert);

	        // ✅ Cek ulang status di DB
	        $cek_db = $this->db->where('external_id', $external_id)
	                           ->where('status', 'PAID')
	                           ->get('sh_payments_so')
	                           ->row();

	        if ($cek_db) {
	            // Update status meja dan redirect ke sukses
	            $id_customer = $this->session->userdata('id');
	            $this->db->where('id_customer', $id_customer)
	                     ->update('sh_rel_table', ['status' => 'Payment']);
	            $nomeja = $this->session->userdata('nomeja');
	            $paymentMethod = $found->callback->payment_method;
	            $this->sukses($nomeja, $external_id,$paymentMethod);
	        } else {
	            // Status belum PAID di DB
	            $this->load_loading_view($extId);
	            return;
	        }

	    } else {
	        // Jika sudah ada di DB, cek status
	        if ($cek->status === 'PAID') {
	            $id_customer = $this->session->userdata('id');
	            $this->db->where('id_customer', $id_customer)
	                     ->update('sh_rel_table', ['status' => 'Payment']);
	            $nomeja = $this->session->userdata('nomeja');
	            $paymentMethod = $found->callback->payment_method;
	            $this->sukses($nomeja, $external_id,$paymentMethod);
	        } else {
	            $this->load_loading_view($extId);
	            return;
	        }
	    }
	}

	// Fungsi helper untuk load view loadingpayment
	private function load_loading_view($extId,$admin=NULL)
	{
	    $nomeja = $this->session->userdata('nomeja');
	    $dataview['logo'] = $this->Admin_model->getLogo();
	    $dataview['cn'] = $this->Admin_model->getColorCN();
	    $dataview['external_id'] = $extId;
	    if ($admin) {
	    	$redirect = base_url().'index.php/Admin/get_payment_online/';
	    }else{
	    	$redirect = base_url().'index.php/cart/sukses/'.$nomeja.'/'.$extId;
	    }
	    $dataview['linkredirect'] = $redirect;
	    $this->load->view('loadingpayment', $dataview);
	}
	public function generate_token()
	{
	    $query = $this->db->where('id', 1)->get('sh_m_setup')->row();
	    if (!$query) return;

	    $url = $query->admin_login_url;
	    $payload = json_encode([
	        "username" => $query->admin_login_user,
	        "password" => $query->admin_login_password
	    ]);

	    $curl = curl_init();
	    curl_setopt_array($curl, [
	        CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_POST => true,
	        CURLOPT_POSTFIELDS => $payload,
	        CURLOPT_HTTPHEADER => [
	            'Content-Type: application/json',
	            'Accept: application/json'
	        ],
	        CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
	        CURLOPT_TIMEOUT => 30
	    ]);

	    $result = curl_exec($curl);
	    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	    curl_close($curl);

	    if ($status == 200) {
	        $response = json_decode($result, true);
	        $token = $response['data']['access_token'] ?? null;
	        if ($token) {
	            $encrypted_token = $this->encryption->encrypt($token);
	            $this->db->where('id', 1)->update('sh_m_setup', [
	                'admin_access_token' => $encrypted_token,
	                'admin_last_login'   => date('Y-m-d H:i:s')
	            ]);
	        }
	    }
	}
	public function sukses($table,$external_id,$paymentMethod)
	{

		$payment = $this->db->where('external_id', $external_id)->get('sh_payments_so')->row();
	    $descriptionpay = $this->input->get('descriptionpay');
	    $amount = $payment->amount;
	    $id_customer = $this->session->userdata('id');
	    
	    if (!$external_id) {
	        show_error('External ID tidak ditemukan');
	    }

	    // ===============================
	    // CEK APAKAH TRANSAKSI SUDAH DIPROSES
	    // ===============================
	    $trans = $this->db
	        ->where('external_id_so', $external_id)
	        ->where('DATE(create_date)', date('Y-m-d'))
	        ->get('sh_t_transactions')
	        ->row();
	     
	    if (!$trans) {
	        show_error('Transaksi tidak ditemukan');
	    }

	    // 🔴 JIKA SUDAH PERNAH DIBAYAR → LANGSUNG KE STRUK
	    if ($trans->payment_date != NULL) {
	        redirect('index.php/selforder/struk');
	        return;
	    }

	    $details = $this->db
        ->where('id_trans', $trans->id)
        ->get('sh_t_transaction_details')
        ->result();

	    // $json_data = json_encode(array(
	    //     'external_id' => $external_id,
	    //     'description' => $descriptionpay,
	    //     'amount' => $amount,
	    //     'Status' => 'Sukses'
	    // ));

	    // $uat = $this->load->database('uat', TRUE);

	    // ambil data terbaru dari UAT
	    // $sps = $uat->where('external_id', $external_id)
	    //            ->order_by('id', 'DESC')
	    //            ->get('sh_payments_so')
	    //            ->row_array();

	    // if ($sps) {
	    //     unset($sps['id']);
	    //     $this->db->insert('sh_payments_so', $sps);
	    // }

	    // $this->get_payment($external_id);
	    
	    $data = [
	        'id_customer' => $trans->id_customer,
	        'details' 	  => $details,
	        'amount'      => $amount,
	        'table'       => $table,
	        'paymentMethod' => $paymentMethod
	    ];
	    $this->logpayment($data,$external_id);
	}
	
	   public function logpayme($data,$external_id)
	{
	    extract($data);

	    if (!$id_customer) {
	        show_error('Missing required parameters');
	    }

	    // ===============================
	    // AMBIL TRANSAKSI TERAKHIR
	    // ===============================
	    $trans = $this->db
	        ->order_by('id','DESC')
	        ->where('DATE(create_date)', date('Y-m-d'))
	        ->get_where('sh_t_transactions', ['external_id_so'=>$external_id], 1)
	        ->row();
	   
	    if (!$trans) {
	        show_error('Transaction not found');
	    }

	    // 🔴 CEK SUDAH DIBAYAR ATAU BELUM (ANTI DOUBLE PROCESS)
	    if ($trans->payment_date != NULL) {
	        return;
	    }
	    // ===============================
	    // MULAI DB TRANSACTION
	    // ===============================
	    $this->db->trans_start();

	    $cabang = $this->db->order_by('id','DESC')->get('sh_m_cabang')->row('id');

	    $row = $this->db->get_where('sh_m_customer', ['id' => $id_customer])->row();
	    $id_member = isset($row->id_member) ? $row->id_member : null;

	    $this->db->where('id_customer', $id_customer)->update('sh_rel_table', [
	        'status' => 'Available'
	    ]);


	    // INSERT LOG
	    $this->db->insert('sh_sopayment_log', [
	        'id_trans'     => $trans->id,
	        'id_invoice'   => $trans->id,
	        'description'  => json_encode($data),
	        'created_date' => date('Y-m-d H:i:s')
	    ]);

	    // AMBIL DATA PAYMENT
	    $paymentso = $this->db
	        ->order_by('id','DESC')
	        ->get_where('sh_payments_so', ['external_id'=>$trans->external_id_so], 1)
	        ->row();
	    $pbc = null;
	    if ($paymentso) {
	        if ($paymentso->payment_method == 'BANK_TRANSFER') {
	            $pbc = $paymentso->bank_code;
	        } else {
	            $pbc = $paymentso->ewallet_type;
	        }
	    }

	    $username   = $this->session->userdata('username');
	    // UPDATE TRANSACTION
	    $this->db->where('id',$trans->id)->update('sh_t_transactions',[
	        'payment_amount'    => $amount,
	        'total_amount'      => $amount,
	        'kembalian'         => 0,
	        'payment_date'      => date('Y-m-d H:i:s'),
	        'payment_type'      => 'xendit',
	        'payment_by'		=> $username,
	        'payment_card_type' => 'BANK_TRANSFER',
	        'payment_bank_card' => $pbc,
	    ]);

	    // UPDATE DETAIL JADI PAID
	    $this->db->where('id_trans', $trans->id)
	             ->update('sh_t_transaction_details', [
	             	'is_paid' => 1,
	             	'payment_reff' => 1,
	             	'submit_time' => date('Y-m-d H:i:s')
	             ]);

	    // PROCESS POINT
	    // if ($id_member){
	    //     $this->process_member_point(
	    //         $cabang,
	    //         $trans->order_no,
	    //         $id_customer,
	    //         $id_member,
	    //         $amount,
	    //         $amount,
	    //         0,
	    //         0,
	    //         $trans->id
	    //     );
	    // }

	    $this->db->where('id_customer',$id_customer)->delete('sh_cart');
	    // ===============================
	    // SELESAI DB TRANSACTION
	    // ===============================
	    $this->db->trans_complete();

	    $this->session->set_flashdata('success','Payment Successful');
	    if ($this->db->trans_status() === FALSE) {
	        show_error('Payment processing failed');
	    }
	    $this->db
	        ->where('external_id', $trans->external_id_so)
	        ->update('sh_billing_so', [
	            'is_proses' => 1,
	        ]);

	    redirect('index.php/selforder/struk');
	}

	public function logpayment($data, $external_id)
	{
	    extract($data);

	    if (!$id_customer) {
	        show_error('Missing required parameters');
	    }

	    // ===============================
	    // AMBIL TRANSAKSI TERAKHIR
	    // ===============================
	    $trans = $this->db
	        ->order_by('id','DESC')
	        ->where('DATE(create_date)', date('Y-m-d'))
	        ->get_where('sh_t_transactions', ['external_id_so'=>$external_id], 1)
	        ->row();
	   
	    if (!$trans) {
	        show_error('Transaction not found');
	    }

	    // 🔴 CEK SUDAH DIBAYAR ATAU BELUM (ANTI DOUBLE PROCESS)
	    if ($trans->payment_date != NULL) {
	        return;
	    }

	    // ===============================
	    // MULAI DB TRANSACTION
	    // ===============================
	    $this->db->trans_start();

	    $cabang = $this->db->order_by('id','DESC')->get('sh_m_cabang')->row('id');

	    $row = $this->db->get_where('sh_m_customer', ['id' => $id_customer])->row();
	    $id_member = isset($row->id_member) ? $row->id_member : null;

	    $this->db->where('id_customer', $id_customer)->update('sh_rel_table', [
	        'status' => 'Available'
	    ]);

	    // ===============================
	    // INSERT LOG
	    // ===============================
	    $this->db->insert('sh_sopayment_log', [
	        'id_trans'     => $trans->id,
	        'id_invoice'   => $trans->id,
	        'description'  => json_encode($data),
	        'created_date' => date('Y-m-d H:i:s')
	    ]);

	    // ===============================
	    // AMBIL DATA PAYMENT
	    // ===============================
	    $paymentso = $this->db
	        ->order_by('id','DESC')
	        ->get_where('sh_payments_so', ['external_id'=>$trans->external_id_so], 1)
	        ->row();
	    $pbc = null;
	    if ($paymentso) {
	        if ($paymentso->payment_method == 'BANK_TRANSFER') {
	            $pbc = $paymentso->bank_code;
	        }elseif ($paymentso->payment_method == 'QR_CODE') {
	        	$pbc = $paymentso->bank_code;
	        } else {
	            $pbc = $paymentso->ewallet_type;
	        }
	    }
	    $username = $this->session->userdata('username');
	    if ($paymentso->payment_method == 'QR_CODE') {
	    	$payment_card_type = 'QRIS';
	    }else{
	    	$payment_card_type = $paymentso->payment_method;
	    }
	    // ===============================
	    // UPDATE TRANSACTION
	    // ===============================
	    $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
	    $cart = $this->db->where('id_customer', $id_customer)->get('sh_cart')->row();
	    $payment = $this->Item_model->totalbayar($trans->id);
	    $this->db->where('id',$trans->id)->update('sh_t_transactions',[
	        'payment_amount'    => $amount,
	        'total_amount'      => $amount,
	        'kembalian'         => 0,
	        'payment_date'      => date('Y-m-d H:i:s'),
	        'payment_type'      => 'xendit',
	        'payment_by'        => $username,
	        'payment_card_type' => $payment_card_type,
	        'payment_bank_card' => $pbc,
	        'tax_percent'       => $setup->tax_percent,
			'tax_amount'        => $payment->ppn,
	    ]);

	    // ===============================
	    // UPDATE DETAIL JADI PAID
	    // ===============================
	    $this->db->where('id_trans', $trans->id)
	             ->update('sh_t_transaction_details', [
	                 'is_paid' => 1,
	                 'payment_reff' => 1,
	                 'submit_time' => date('Y-m-d H:i:s')
	             ]);

	    

	    // ===============================
	    // HAPUS CART
	    // ===============================
	    $uoi = $this->session->userdata('user_order_id');

	    // 🔴 FIX delete cart pakai user_order_id
	    $this->db->where('id_customer', $id_customer)
	             // ->where('user_order_id', $uoi)
	             ->delete('sh_cart');

	    $this->db->where('id_customer', $id_customer)
	             // ->where('user_order_id', $uoi)
	             ->delete('sh_cart_details');

	    // ===============================
	    // SELESAI DB TRANSACTION
	    // ===============================
	    $this->db->trans_complete();

	    $this->session->set_flashdata('success','Payment Successful');
	    if ($this->db->trans_status() === FALSE) {
	        show_error('Payment processing failed');
	    }

	    $this->db
	        ->where('external_id', $trans->external_id_so)
	        ->update('sh_billing_so', [
	            'is_proses' => 1,
	        ]);

	    redirect('index.php/selforder/struk');
	}
 //    public function logpaymentOLDD($data)
	// {
	//     extract($data);

	//     if (!$id_customer || !$item_code) {
	//         show_error('Missing required parameters');
	//     }

	//     // ambil cabang
	//     $cabang = $this->db->order_by('id','DESC')->get('sh_m_cabang')->row('id');

	//     // ambil member (jika ada)
	//     $row = $this->db->get_where('sh_m_customer', ['id' => $id_customer])->row();
	// 	$id_member = isset($row->id_member) ? $row->id_member : null;

	//     $this->db->where('id_customer', $id_customer)->update('sh_rel_table', [
	//         'status' => 'Available'
	//     ]);
	//     // ambil transaksi terakhir
	//     $trans = $this->db
	//         ->order_by('id','DESC')
	//         ->get_where('sh_t_transactions', ['id_customer'=>$id_customer], 1)
	//         ->row();

	//     if (!$trans) show_error('Transaction not found');

	//     // log payment
	//     $this->db->insert('sh_sopayment_log', [
	//         'id_trans' => $trans->id,
	//         'id_invoice' => $trans->id,
	//         'description' => json_encode($data),
	//         'created_date' => date('Y-m-d H:i:s')
	//     ]);

	//     $paymentso = $this->db
	//         ->order_by('id','DESC')
	//         ->get_where('sh_payments_so', ['external_id'=>$trans->external_id_so], 1)
	//         ->row();
	//     if ($paymentso->payment_method == 'BANK_TRANSFER') {
	//     	$pbc = $paymentso->bank_code;
	//     }else{
	//     	$pbc = $paymentso->ewallet_type;
	//     }
	//     // update transaksi
	//     $this->db->where('id',$trans->id)->update('sh_t_transactions',[
	//         'payment_amount'=>$amount,
	//         'total_amount'=>$amount,
	//         'kembalian'=>0,
	//         'payment_date'=>date('Y-m-d H:i:s'),
	//         'payment_type'=>'xendit',
	//         'payment_card_type'=>'credit card',
	//         'payment_bank_card'=> $pbc,
	//     ]);

	//     // insert detail
	//     $isTakeAway = ($this->session->userdata('visit_type') === 'TakeAway');
	// 	$username   = $this->session->userdata('username');
	// 	$cabang = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_m_cabang')->row('id');
	// 	// Ambil waktu timeout dari pengaturan
	//     $timeout_data = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_set_time_cekdata')->row('seconds');
	//     $seconds = "+" . ($timeout_data) . " seconds";

	//     // Cek jika ada duplikasi pesanan hari ini
	//     $date = date('Y-m-d');
	//     $ctime = date('H:i:s');
	//     $this->db->from('sh_t_transaction_details');
	//     $where = "LEFT(created_date, 10) = '$date' AND selected_table_no = '$table' AND selforder = 1 AND user_order_id = '$uoi'";
	//     $this->db->where($where);
	//     $this->db->where_in('item_code', $item_codes);
	//     $this->db->order_by('id', 'DESC');
	//     $this->db->limit(1);
	//     $q = $this->db->get()->row();
	//     $time = date('H:i:s', strtotime($ctime . $seconds));

	// 	$detail = [];
	// 	for ($i = 0; $i < count($item_code); $i++) {

	// 	    $row = [
	// 	        'id_trans'          => $trans->id,
	// 	        'item_code'         => $item_code[$i],
	// 	        'qty'               => $qty[$i],
	// 	        'unit_price'        => $harga[$i],
	// 	        'description'       => $nama[$i],
	// 	        'extra_notes'       => $pesan[$i],
	// 	        'is_take_away'      => $isTakeAway ? 1 : 0,
	// 	        'as_take_away'      => $isTakeAway ? 1 : 0,
	// 	        'is_paid'           => 1,
	// 	        'selected_table_no' => $table,
	// 	        'cabang'			=> $cabang,
	// 	        'checker_printed'   => 1,
	// 	        'created_date'      => date('Y-m-d H:i:s'),
	// 	        'selforder'         => 1,
	// 	        'start_time_order'  => date('H:i:s'),
	// 	        'entry_by'			=> $username,
	// 	        'user_order_id'		=> $this->session->userdata('user_order_id'),
	// 	        'timeout_order_so'	=> $time,
	// 	        'cekdata'			=> 1
	// 	    ];

	// 	    // ===============================
	// 	    // KHUSUS TAKE AWAY
	// 	    // ===============================
	// 	    if ($isTakeAway) {
	// 	        $row['waitress_by'] = $username;
	// 	    }

	// 	    $detail[] = $row;
	// 	}
	//     $this->db->insert_batch('sh_t_transaction_details',$detail);

	//     // update stock
	//     for ($i=0;$i<count($item_code);$i++){
	//         if ($need_stock[$i]==1){
	//             $item=$this->db->get_where('sh_m_item',['no'=>$item_code[$i]])->row();
	//             if ($item){
	//                 $stok=$item->stock-$qty[$i];
	//                 $this->db->where('no',$item_code[$i])->update('sh_m_item',[
	//                     'stock'=>$stok,
	//                     'is_sold_out'=>($stok<=0?1:0)
	//                 ]);
	//             }
	//         }
	//     }
	//     // process point
	//     if ($id_member){
	//         $this->process_member_point(
	//             $cabang,
	//             $trans->order_no,
	//             $id_customer,
	//             $id_member,
	//             $amount,
	//             $amount,
	//             0,
	//             0,
	//             $trans->id
	//         );
	//     }

	//     // clear cart
	//     $this->db->where('id_customer',$id_customer)->delete('sh_cart');

	//     $this->session->set_flashdata('success','Payment Successful');

	//     redirect('index.php/selforder/struk');
	// }
 //    public function logpaymentOLD()
	// {
	//     // AMBIL DATA DARI GET
	//     $id_customer = $this->input->get('id_customer');
	//     $item_code   = $this->input->get('item_code');
	//     $qty         = $this->input->get('qty');
	//     $nama        = $this->input->get('nama');
	//     $pesan       = $this->input->get('pesan');
	//     $harga       = $this->input->get('harga');
	//     $need_stock  = $this->input->get('need_stock');
	//     $amount      = $this->input->get('amount');
	//     $table       = $this->input->get('table');
	//     // VALIDASI
	//     if (!$id_customer || !$item_code) {
	//         echo json_encode([
	//             'status' => false,
	//             'msg'    => 'Missing required parameters'
	//         ]);
	//         return;
	//     }

	//     // AMBIL TRANSAKSI CUSTOMER TERAKHIR
	//     $trans = $this->db
	//         ->order_by('id', 'DESC')
	//         ->get_where('sh_t_transactions', ['id_customer' => $id_customer], 1)
	//         ->row();

	//     if (!$trans) {
	//         echo json_encode(['status' => false, 'msg' => 'Transaction not found']);
	//         return;
	//     }

	//     // LOG PEMBAYARAN (RAW GET)
	//     $this->db->insert('sh_sopayment_log', [
	//         'id_trans'     => $trans->id,
	//         'id_invoice'   => $trans->id,
	//         'description'  => json_encode($_GET),
	//         'created_date' => date('Y-m-d H:i:s')
	//     ]);

	//     // UPDATE TRANSAKSI → LUNAS
	//     $this->db->where('id', $trans->id)->update('sh_t_transactions', [
	//         'payment_amount' => $amount,
	//         'total_amount'   => $amount,
	//         'kembalian'      => 0,
	//         'payment_date'   => date('Y-m-d H:i:s'),
	//         'payment_type'   => 'Xendit Credit Card',
	//     ]);

	//     // INSERT DATA DETAIL TRANSAKSI
	//     $visit_type = $this->session->userdata('visit_type');
	//     if ($visit_type == 'TakeAway') {
	//     	$is_take_away = 1;
	//     }else{
	//     	$is_take_away = 0;
	//     }
	//     $detailInsert = [];
	//     for ($i = 0; $i < count($item_code); $i++) {
	//         $detailInsert[] = [
	//             'id_trans'          => $trans->id,
	//             'item_code'         => $item_code[$i],
	//             'qty'               => $qty[$i],
	//             'unit_price'        => $harga[$i],
	//             'description'       => $nama[$i],
	//             'extra_notes'       => $pesan[$i],
	//             'is_take_away'		=> $is_take_away,
	//             'is_paid'           => 1,
	//             'selected_table_no' => $table,
	//             'checker_printed'	=> 1,
	//             'created_date'      => date('Y-m-d H:i:s'),
	//             'selforder'         => 1,
	//         ];
	//     }
	//     $this->db->insert_batch('sh_t_transaction_details', $detailInsert);

	//     // UPDATE STOCK ITEM
	//     for ($i = 0; $i < count($item_code); $i++) {
	//         if ($need_stock[$i] == 1) {
	//             $item = $this->db->get_where('sh_m_item', ['no' => $item_code[$i]])->row();
	//             if ($item) {
	//                 $newStock = $item->stock - $qty[$i];
	//                 $this->db->where('no', $item_code[$i])->update('sh_m_item', [
	//                     'stock'       => $newStock,
	//                     'is_sold_out' => $newStock <= 0 ? 1 : 0
	//                 ]);
	//             }
	//         }
	//     }

	//     // // UPDATE STATUS MEJA → DINING
	//     $customerId = $this->session->userdata('id');
	//     $this->db->where('id_customer', $customerId)->update('sh_rel_table', [
	//         'status' => 'Available'
	//     ]);

	//     // HAPUS ITEM CART
	//     $userOrderId = $this->session->userdata('user_order_id');

	//     $this->db
	//         ->where('id_customer', $customerId)
	//         ->where('user_order_id', $userOrderId)
	//         ->where_in('item_code', $item_code)
	//         ->delete('sh_cart');

	//     $this->process_member_point(
	// 	    $cabang,
	// 	    $trans->order_no,
	// 	    $id_customer,
	// 	    $id_member,
	// 	    $amount,
	// 	    $amount,
	// 	    0,
	// 	    0,
	// 	    $trans->id
	// 	);

	//     // PESAN BERHASIL
	//     $this->session->set_flashdata('success', 'Payment Successful. Order Sent to Kitchen.');

	//     // REDIRECT
	//     redirect('index.php/selforder/home/' . $table);
	// }
	
	private function process_member_point($cabang,$order_no,$id_cust,$id_member,$pay_amount,$total_amount,$disc_bill,$promo_amount,$id_trans) {
	    $setup = $this->Item_model->get_setup(0);
	    
	    $inquiryCheck = $this->Item_model
	        ->get_member_point('inquiry', $id_member, 0)
	        ->num_rows();
	    if ($inquiryCheck <= 0) return false;
	    $pointCheck = $this->Item_model
	        ->get_member_point('point', $id_trans, $setup->hit_time_delay)
	        ->num_rows();
	    if ($pointCheck > 0) return false;

	    $promoAmount = 0;
	    $model = $this->Item_model->api_service('Transaction', [
	        'member_id' => $id_member,
	        'trans_id'  => null,
	        'order_no'  => str_replace('|', '', urldecode($order_no)),
	        'amount'    => ($total_amount - $disc_bill),
	        'promo_amount' => $promoAmount
	    ]);
	    if (!$model) return false;

	    $header = json_decode($model);

	    if (!isset($header->data) || $header->status != true) return false;

	    $data_trans = [
	        'id_trans' => $id_trans,
	        'order_no' => str_replace('|', '-', urldecode($order_no)),
	        'id_customer' => $id_cust,
	        'payment_amount' => $pay_amount,
	        'amount' => ($total_amount - $disc_bill),
	        'promotion_amount' => $promoAmount,
	        'id_book' => $header->data->transaction_id,
	        'point_amount' => $header->data->point_amount,
	        'point_balance' => $header->data->point_balance,
	        'point_before' => $header->data->point_before,
	        'point_earned' => $header->data->point_earned,
	        'service_status' => 'true',
	        'service_status_message' => $header->message,
	        'void_status' => '',
	        'void_status_message' => '',
	    ];
	    $this->Item_model->saveData('sh_point_member', $data_trans);
	    $this->Item_model->saveData(
	        'sh_t_transactions',
	        ['transaction_point' => $header->data->point_earned],
	        ['id' => $id_trans]
	    );

	    return true;
	}

	private function insert_transaction_details($id_trans, $item_codes, $quantities, $nama, $harga, $payment_type)
	{
	    $table = $this->session->userdata('nomeja');
	    $pesan = $this->input->post('pesan');
	    $is_addon = $this->input->post('is_addon');
	    $options = $this->input->post('options');
	    $pesan = $this->input->post('pesan');
	    $need_stock = $this->input->post('need_stock');
	    $need_stockaddon = $this->input->post('need_stockaddon');
	    $id_customer = $this->session->userdata('id');
	    $qty = $this->input->post('qty');
	    $uoi = $this->session->userdata('user_order_id');
	    // Cek transaksi pelanggan berdasarkan ID
	    $transaction = $this->db->get_where('sh_t_transactions', ['id_customer' => $id_customer])->row();
	    $id_trans = $id_trans;

	    // Cek status meja pelanggan
	    $id_table = $this->db->get_where('sh_rel_table', ['id_customer' => $id_customer])->row();
	    $st = $id_table->status;

	    // Ambil waktu timeout dari pengaturan
	    $timeout_data = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_set_time_cekdata')->row('seconds');
	    $seconds = "+" . ($timeout_data) . " seconds";

	    // Cek jika ada duplikasi pesanan hari ini
	    $date = date('Y-m-d');
	    $ctime = date('H:i:s');
	    $this->db->from('sh_t_transaction_details');
	    // $where = "LEFT(created_date, 10) = '$date' AND selected_table_no = '$table' AND selforder = 1 AND user_order_id = '$uoi'";
	    $where = "LEFT(created_date, 10) = '$date' AND selected_table_no = '$table' AND selforder = 1";
	    $this->db->where($where);
	    $this->db->where_in('item_code', $item_codes);
	    $this->db->order_by('id', 'DESC');
	    $this->db->limit(1);
	    $q = $this->db->get()->row();
	    $time = date('H:i:s', strtotime($ctime . $seconds));

	    // Tetapkan status pesanan berdasarkan status meja
	    $order_stat = ($st === "Dining" || $st === "Order") ? 1 : (($st === "Billing") ? 2 : 0);

	    // Cek promo untuk diskon
	    $today = date('Y-m-d');
	    $curTime = explode(':', date('H:i:s'));
	    $cekWeekEnd = date('D', strtotime($today));
	    $discounts = [];

	    foreach ($item_codes as $index => $item_code) {
	        $check_promo = $this->Item_model->get_promo($item_code, $today)->row_array();
	        $discounts[$index] = 0;

	        if (!empty($check_promo)) {
	            if ($check_promo['promo_type'] === 'Discount') {
	                $is_weekend = $cekWeekEnd === "Sat" || $cekWeekEnd === "Sun";
	                $within_time = $curTime[0] >= $check_promo['promo_from'] && $curTime[0] <= $check_promo['promo_to'];

	                if (($check_promo['promo_criteria'] === 'Weekday' && !$is_weekend && $within_time) ||
	                    ($check_promo['promo_criteria'] === 'Weekend' && $is_weekend && $within_time) ||
	                    ($check_promo['promo_criteria'] === 'Everyday' && $within_time)) {
	                    $discounts[$index] = $check_promo['promo_value'];
	                }
	            }
	        }
	    }
	    // Ambil data cabang
	    $cabang = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_m_cabang')->row('id');

	    // Cek cekdata transaksi
	    $cekdata = $this->Item_model->cekdatatransdetail($id_trans)->row();

		if ($cekdata && $cekdata->max_cekdata) {
		    $cd = $cekdata->max_cekdata + 1;
		} else {
		    $cd = 1;
		}
	    // Siapkan data untuk dimasukkan
	    $data = [];
		foreach ($item_codes as $index => $item_code) {
		    $cekpaket = $this->db->where('no', $item_code)
		                         ->order_by('id', "desc")
		                         ->limit(1)
		                         ->get('sh_m_item')
		                         ->row('is_paket_so');

		    if ($quantities[$index] > 0) {
		        $item_name = $nama[$index];
		        $item_price = $harga[$index];
		        $item_need_stock = $need_stock[$index];
		        if ($payment_type == 'PaymentNow') {
		        	$paid = 1;
		        	$checker_printed = 1;
		        }else{
		        	$paid = 0;
		        	// $checker_printed = 0;
		        	$checker_printed = 1;
		        }
		        $visit_type = $this->session->userdata('visit_type');
			    if ($visit_type == 'TakeAway') {
			    	$is_take_away = 1;
			    }else{
			    	$is_take_away = 0;
			    }
		        // Data awal
		        $itemData = [
		            'id_trans' => $id_trans,
		            'item_code' => $item_code,
		            'qty' => $quantities[$index],
		            'cabang' => $cabang,
		            'unit_price' => $item_price,
		            'description' => $item_name,
		            'start_time_order' => $ctime,
		            'entry_by' => $this->session->userdata('username'),
		            'disc' => $discounts[$index],
		            'is_cancel' => 0,
		            'session_item' => 0,
		            'selected_table_no' => $table,
		            'seat_id' => 0,
		            'sort_id' => $index + 1,
		            'as_take_away' => 0,
		            'is_take_away' => $is_take_away,
		            'qty_take_away' => 0,
		            'extra_notes' => $pesan[$index],
		            'checker_printed' => $checker_printed,
		            'created_date' => date('Y-m-d H:i:s'),
		            'submit_time' => date('Y-m-d H:i:s'),
		            'order_type' => $order_stat,
		            'selforder' => 1,
		            'is_printed_so' => 0,
		            'cekdata' => $cd,
		            'user_order_id' => $uoi,
		            'timeout_order_so' => $time,
		            'is_paid'		=> $paid,
		        ];
		            
			    if ($visit_type == 'TakeAway') {
				    $itemData['waitress_by'] = $this->session->userdata('username');
				    $itemData['as_take_away'] = 1;
				    $itemData['is_take_away'] = 1;
				}

		        // Jika item adalah paket, tambahkan atribut is_package
		        if ($cekpaket == 1) {
		            $itemData['is_package'] = 1;
		            $itemData['parent_id_package'] = 0;
		        }else{
		        	$itemData['is_package'] = 0;
		            $itemData['parent_id_package'] = 0;
		        }

		        // Tambahkan itemData ke dalam array $data
		        $data[] = $itemData;
		    }
		}
	    // Masukkan data ke database jika ada
	    if (!empty($data)) {
	        $this->db->insert_batch('sh_t_transaction_details', $data);
	        return $this->db->insert_id();
	        $this->db->where('id', $id_trans)->update('sh_t_transactions', [
	            'date_order_menu' => date('Y-m-d H:i:s'),
	            'is_order_menu_active' => 1,
	            'start_time_order' => $ctime,
	        ]);
	    }
	}
	private function insert_transaction_details_list($id_details, $id_trans, $item_codes, $quantities, $nama, $harga, $payment_type)
	{
	    $pesan = $this->input->post('pesan');
	    $options = $this->input->post('options');
	    $need_stockip = $this->input->post('need_stockip');
	    $id_customer = $this->session->userdata('id');
	    $qty = $this->input->post('qty');

	    $id_table = $this->session->userdata('nomeja');

	    $idtable = $this->db->get_where('sh_rel_table', ['id_customer' => $id_customer])->row();
	    $st = $idtable ? $idtable->status : null;

	    // Ambil waktu timeout dari pengaturan
	    $timeout_data = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_set_time_cekdata')->row('seconds');
	    $seconds = "+" . ($timeout_data) . " seconds";
	    $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');

	    // Tetapkan status pesanan berdasarkan status meja
	    $order_stat = ($st === "Dining" || $st === "Order") ? 1 : (($st === "Billing") ? 2 : 0);

	    // Cek promo untuk diskon
	    $today = date('Y-m-d');
	    $curTime = explode(':', date('H:i:s'));
	    $cekWeekEnd = date('D', strtotime($today));
	    $discounts = [];

	    foreach ($item_codes as $index => $item_code) {
	        $check_promo = $this->Item_model->get_promo($item_code, $today)->row_array();
	        $discounts[$index] = 0;

	        if (!empty($check_promo)) {
	            if ($check_promo['promo_type'] === 'Discount') {
	                $is_weekend = $cekWeekEnd === "Sat" || $cekWeekEnd === "Sun";
	                $within_time = $curTime[0] >= $check_promo['promo_from'] && $curTime[0] <= $check_promo['promo_to'];

	                if (
	                    ($check_promo['promo_criteria'] === 'Weekday' && !$is_weekend && $within_time) ||
	                    ($check_promo['promo_criteria'] === 'Weekend' && $is_weekend && $within_time) ||
	                    ($check_promo['promo_criteria'] === 'Everyday' && $within_time)
	                ) {
	                    $discounts[$index] = $check_promo['promo_value'];
	                }
	            }
	        }
	    }

	    // Siapkan data untuk dimasukkan
	    $data = [];

	    // Pastikan semua variabel berbentuk array agar tidak error
	    if (!is_array($item_codes)) $item_codes = [$item_codes];
	    if (!is_array($quantities)) $quantities = [$quantities];
	    if (!is_array($nama)) $nama = [$nama];
	    if (!is_array($harga)) $harga = [$harga];

	    $ctime = date('H:i:s');
	    if ($payment_type == 'PaymentNow') {
		 $paid = 1;
		}else{
		 $paid = 0;
		}

	    foreach ($item_codes as $index => $item_code) {
	        $qty = isset($quantities[$index]) ? $quantities[$index] : 0;
	        $item_name = isset($nama[$index]) ? $nama[$index] : '';
	        $item_price = isset($harga[$index]) ? $harga[$index] : 0;

	        if ($qty > 0) {
	            $en = '';
	            $up = $item_price ?: 0;

	            $data[] = [
	                'id_trans' => $id_trans,
	                'parent_id_package' => $id_details,
	                'item_code' => $item_code,
	                'qty' => $qty,

	                'cabang' => $cabang,
	                'start_time_order' => $ctime,
	                'is_cancel' => 0,
	                'session_item' => 0,
	                'seat_id' => 0,
	                'sort_id' => $index + 1,
	                'as_take_away' => 0,
	                'qty_take_away' => 0,
	                'submit_time' => date('Y-m-d H:i:s'),
	                'order_type' => $order_stat,
	                'checker_printed' => 1,

	                'unit_price' => $up,
	                'description' => $item_name,
	                'entry_by' => $this->session->userdata('username'),
	                'selected_table_no' => $id_table,
	                'disc' => isset($discounts[$index]) ? $discounts[$index] : 0,
	                'extra_notes' => $en,
	                'created_date' => date('Y-m-d H:i:s'),
	                'is_paid'	   => $paid,
	            ];
	        }
	    }

	    // Masukkan data ke database jika ada
	    if (!empty($data)) {
	        $this->db->insert_batch('sh_t_transaction_details', $data);
	    } else {
	        log_message('error', 'insert_transaction_details_list(): Tidak ada data yang dimasukkan. Cek item_codes/harga/qty.');
	    }
	}

	

	public function deleteDEV($id,$nomeja,$cekpaket=null,$cek=null,$sub=null)
	{
	    $ic  = $this->session->userdata('id');
	    $it  = $this->session->userdata('id_table');
	    $uoi = $this->session->userdata('user_order_id');
	    $date = date('Y-m-d');

	    $username    = $this->session->userdata('username');
	    $ip_address  = $this->input->ip_address();
	    $id_customer = $ic;

	    /* ================= START TRANSACTION ================= */
	    $this->db->trans_begin();

	    try {

	        /* ================= GET CART ================= */
	        $cart = $this->db
	            ->where('id', $id)
	            ->get('sh_cart')
	            ->row();

	        if (!$cart) {
	            throw new Exception('Cart tidak ditemukan');
	        }

	        /* ================= LOCK ITEM ================= */
	        $item = $this->db
	            ->query("SELECT * FROM sh_m_item WHERE no = ? FOR UPDATE", [$cart->item_code])
	            ->row();

	        if (!$item) {
	            throw new Exception('Item tidak ditemukan');
	        }

	        /* ================= UPDATE STOCK ================= */
	        if ($item->need_stock == 1) {

	            // 🔥 BALIKIN STOCK SESUAI QTY
	            $this->db->set('stock', 'stock + '.$cart->qty, false)
	                     ->where('id', $item->id)
	                     ->update('sh_m_item');
	        }

	        /* ================= DELETE ================= */
	        if ($cekpaket == 'paket') {

	            // $where ="id_customer ='".$ic."' and id_table ='".$nomeja."' and user_order_id ='".$uoi."' and left(entry_date,10) ='".$date."'";
	            $where ="id_customer ='".$ic."' and id_table ='".$nomeja."' and left(entry_date,10) ='".$date."'";

	            $this->db->where($where);
	            $this->db->delete('sh_cart_details');

	            $this->db->where('id',$id);
	            $this->db->delete('sh_cart');

	        } else {

	            $query = $this->db->get_where('sh_cart', array('id' => $id))->row();

	            if ($query) {
	                // $where ="id_customer ='".$ic."' and id_table ='".$nomeja."' and user_order_id ='".$uoi."' and left(entry_date,10) ='".$date."' and item_code_header = '".$query->item_code."'";
	                $where ="id_customer ='".$ic."' and id_table ='".$nomeja."' and left(entry_date,10) ='".$date."' and item_code_header = '".$query->item_code."'";

	                $this->db->where($where);
	                $this->db->delete('sh_cart');
	            }

	            $this->db->where('id',$id);
	            $this->db->delete('sh_cart');
	        }

	        /* ================= GET CABANG ================= */
	        $cabang = $this->db
	            ->order_by('id', 'DESC')
	            ->limit(1)
	            ->get('sh_m_cabang')
	            ->row('id');

	        /* ================= GET TRANSACTION ================= */
	        $id_trans = $this->db
	            ->where('id_customer', $id_customer)
	            ->order_by('id', 'DESC')
	            ->limit(1)
	            ->get('sh_t_transactions')
	            ->row();

	        $id_trans_id = $id_trans ? $id_trans->id : null;

	        /* ================= LOG ================= */
	        $this->db->insert('sh_event_log', [
	            'event_type'  => 'Delete Item di Cart',
	            'cabang'      => $cabang,
	            'id_trans'    => $id_trans_id,
	            'id_customer' => $id_customer,
	            'event_date'  => date('Y-m-d H:i:s'),
	            'user_by'     => $username,
	            'description' => 'Hapus item: '.$item->description.
	                             ' (Qty: '.$cart->qty.') | Meja: '.$nomeja.
	                             ' | IP: '.$ip_address.'dihalaman cart',
	            'created_date'=> date('Y-m-d'),
	        ]);

	        /* ================= COMMIT ================= */
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Gagal transaksi');
	        }

	        $this->db->trans_commit();

	    } catch (Exception $e) {

	        $this->db->trans_rollback();

	        $this->session->set_flashdata('error', $e->getMessage());

	        redirect(base_url('index.php/Cart/home/'.$nomeja));
	        return;
	    }

	    $this->session->set_flashdata('success','Menu Has Been Removed');

	    /* ================= REDIRECT ================= */
	    if ($cek == 'Makanan') {
	        $log = 'index.php/Cart/home/'.$nomeja.'/Makanan/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
	    }elseif ($cek == 'Minuman') {
	        $log = 'index.php/Cart/home/'.$nomeja.'/Minuman/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
	    }elseif ($cek == 'CAKE%20DAN%20BAKERY') {
	        $log = 'index.php/Cart/home/'.$nomeja.'/CAKE%20DAN%20BAKERY/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
	    }else{
	        $log = 'index.php/Cart/home/'.$nomeja;
	    }

	    redirect(base_url().$log);
	}
	
	public function cancel_orderDEV($nomeja, $cek, $sub = NULL, $add = NULL)
	{
	    $ic  = $this->session->userdata('id');
	    $uoi = $this->session->userdata('user_order_id');
	    $date = date('Y-m-d');

	    $username    = $this->session->userdata('username');
	    $ip_address  = $this->input->ip_address();

	    /* ================= GET TRANSACTION ================= */
	    $id_trans = $this->db
	        ->where('id_customer', $ic)
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();

	    if (!$id_trans) {
	        $this->session->set_flashdata('error', 'Transaksi tidak ditemukan.');
	        redirect($_SERVER['HTTP_REFERER']);
	        return;
	    }

	    /* ================= START TRANSACTION ================= */
	    $this->db->trans_begin();

	    try {

	        /* ================= GET ALL CART ================= */
	        $this->db->where([
	            'id_customer' => $ic,
	            'id_table'    => $nomeja,
	            'id_trans'    => $id_trans->id,
	        ]);

	        if ($add) {
	            $this->db->where('addons', 1);
	        }

	        $cart_items = $this->db->get('sh_cart')->result();

	        if (!$cart_items) {
	            throw new Exception('Cart kosong');
	        }

	        /* ================= PREPARE LOG ================= */
	        $log_items = [];

	        /* ================= LOOP ITEM ================= */
	        foreach ($cart_items as $cart) {

	            // 🔥 LOCK ITEM (ANTI RACE CONDITION)
	            $item = $this->db
	                ->query("SELECT * FROM sh_m_item WHERE no = ? FOR UPDATE", [$cart->item_code])
	                ->row();

	            if (!$item) continue;

	            // 🔥 BALIKIN STOCK
	            if ($item->need_stock == 1) {
	                $this->db->set('stock', 'stock + '.$cart->qty, false)
	                         ->where('id', $item->id)
	                         ->update('sh_m_item');
	            }

	            // 🔥 SIMPAN UNTUK LOG
	            $log_items[] = $item->description . ' (Qty: '.$cart->qty.')';
	        }

	        /* ================= HAPUS CART ================= */
	        $this->db->where([
	            'id_customer' => $ic,
	            'id_table'    => $nomeja,
	            'id_trans'    => $id_trans->id,
	        ]);

	        if ($add) {
	            $this->db->where('addons', 1);
	        }

	        $this->db->delete('sh_cart');

	        /* ================= HAPUS CART DETAILS ================= */
	        $this->db->where([
	            'id_customer' => $ic,
	            'id_table'    => $nomeja,
	            'DATE(entry_date)' => $date
	        ]);

	        $this->db->delete('sh_cart_details');

	        /* ================= GET CABANG ================= */
	        $cabang = $this->db
	            ->order_by('id', 'DESC')
	            ->limit(1)
	            ->get('sh_m_cabang')
	            ->row('id');

	        /* ================= INSERT 1 LOG ================= */
	        $this->db->insert('sh_event_log', [
	            'event_type'  => 'Cancel Order',
	            'cabang'      => $cabang,
	            'id_trans'    => $id_trans->id,
	            'id_customer' => $ic,
	            'event_date'  => date('Y-m-d H:i:s'),
	            'user_by'     => $username,
	            'description' => 'Cancel semua item: '.implode(', ', $log_items).
	                             ' | Meja: '.$nomeja.
	                             ' | IP: '.$ip_address,
	            'created_date'=> date('Y-m-d'),
	        ]);

	        /* ================= COMMIT ================= */
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Gagal cancel order');
	        }

	        $this->db->trans_commit();

	    } catch (Exception $e) {

	        $this->db->trans_rollback();

	        $this->session->set_flashdata('error', $e->getMessage());
	        redirect($_SERVER['HTTP_REFERER']);
	        return;
	    }

	    /* ================= REDIRECT ================= */
	    switch ($cek) {
	        case 'Makanan':
	            $log = "index.php/Cart/home/$nomeja/Makanan/$sub#" . str_replace(' ', '_', $sub);
	            break;
	        case 'Minuman':
	            $log = "index.php/Cart/home/$nomeja/Minuman/$sub#" . str_replace(' ', '_', $sub);
	            break;
	        case 'CAKE DAN BAKERY':
	            $log = "index.php/Cart/home/$nomeja/CAKE%20DAN%20BAKERY/$sub#" . str_replace(' ', '_', $sub);
	            break;
	        default:
	            $log = "index.php/Cart/home/$nomeja";
	    }

	    $this->session->set_flashdata('success', 'Successfully Canceled the Order');
	    redirect(base_url($log));
	}

	public function delete($id,$nomeja,$cekpaket=null,$cek=null,$sub=null)
	{
	    $ic  = $this->session->userdata('id');
	    $it  = $this->session->userdata('id_table');
	    $uoi = $this->session->userdata('user_order_id');
	    $date = date('Y-m-d');

	    $username    = $this->session->userdata('username');
	    $ip_address  = $this->input->ip_address();
	    $id_customer = $ic;

	    $this->db->trans_begin();

	    try {

	        /* ================= GET CART ================= */
	        $cart = $this->db
	            ->where('id', $id)
	            ->get('sh_cart')
	            ->row();

	        if (!$cart) {
	            throw new Exception('Cart tidak ditemukan');
	        }

	        /* ================= GET ITEM ================= */
	        $item = $this->db
	            ->where('no', $cart->item_code)
	            ->get('sh_m_item')
	            ->row();

	        if (!$item) {
	            throw new Exception('Item tidak ditemukan');
	        }

	        /* ================= DELETE ================= */
	        if ($cekpaket == 'paket') {

	            $where ="id_customer ='".$ic."' and id_table ='".$nomeja."' and left(entry_date,10) ='".$date."'";
	            $this->db->where($where);
	            $this->db->delete('sh_cart_details');

	            $this->db->where('id',$id);
	            $this->db->delete('sh_cart');

	        } else {

	            $query = $this->db->get_where('sh_cart', array('id' => $id))->row();

	            if ($query) {
	                $where ="id_customer ='".$ic."' and id_table ='".$nomeja."' and left(entry_date,10) ='".$date."' and item_code_header = '".$query->item_code."'";
	                $this->db->where($where);
	                $this->db->delete('sh_cart');
	            }

	            $this->db->where('id',$id);
	            $this->db->delete('sh_cart');
	        }

	        /* ================= GET CABANG ================= */
	        $cabang = $this->db
	            ->order_by('id', 'DESC')
	            ->limit(1)
	            ->get('sh_m_cabang')
	            ->row('id');

	        /* ================= GET TRANSACTION ================= */
	        $id_trans = $this->db
	            ->where('id_customer', $id_customer)
	            ->order_by('id', 'DESC')
	            ->limit(1)
	            ->get('sh_t_transactions')
	            ->row();

	        $id_trans_id = $id_trans ? $id_trans->id : null;

	        /* ================= LOG ================= */
	        $this->db->insert('sh_event_log', [
	            'event_type'  => 'Delete Item di Cart',
	            'cabang'      => $cabang,
	            'id_trans'    => $id_trans_id,
	            'id_customer' => $id_customer,
	            'event_date'  => date('Y-m-d H:i:s'),
	            'user_by'     => $username,
	            'description' => 'Hapus item: '.$item->description.
	                             ' (Qty: '.$cart->qty.') | Meja: '.$nomeja.
	                             ' | IP: '.$ip_address.' dihalaman cart',
	            'created_date'=> date('Y-m-d'),
	        ]);

	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Gagal transaksi');
	        }

	        $this->db->trans_commit();

	    } catch (Exception $e) {

	        $this->db->trans_rollback();
	        $this->session->set_flashdata('error', $e->getMessage());
	        redirect(base_url('index.php/Cart/home/'.$nomeja));
	        return;
	    }

	    $this->session->set_flashdata('success','Menu Has Been Removed');

	    if ($cek == 'Makanan') {
	        $log = 'index.php/Cart/home/'.$nomeja.'/Makanan/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
	    }elseif ($cek == 'Minuman') {
	        $log = 'index.php/Cart/home/'.$nomeja.'/Minuman/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
	    }elseif ($cek == 'CAKE%20DAN%20BAKERY') {
	        $log = 'index.php/Cart/home/'.$nomeja.'/CAKE%20DAN%20BAKERY/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
	    }else{
	        $log = 'index.php/Cart/home/'.$nomeja;
	    }

	    redirect(base_url().$log);
	}
	public function cancel_order($nomeja, $cek='Makanan', $sub = NULL, $add = NULL)
	{
	    $ic  = $this->session->userdata('id');
	    $date = date('Y-m-d');

	    $username    = $this->session->userdata('username');
	    $ip_address  = $this->input->ip_address();

	    $id_trans = $this->db
	        ->where('id_customer', $ic)
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();

	    if (!$id_trans) {
	        $this->session->set_flashdata('error', 'Transaksi tidak ditemukan.');
	        redirect($_SERVER['HTTP_REFERER']);
	        return;
	    }

	    $this->db->trans_begin();

	    try {

	        /* ================= GET ALL CART ================= */
	        $this->db->where([
	            'id_customer' => $ic,
	            'id_table'    => $nomeja,
	            'id_trans'    => $id_trans->id,
	        ]);

	        if ($add) {
	            $this->db->where('addons', 1);
	        }

	        $cart_items = $this->db->get('sh_cart')->result();

	        if (!$cart_items) {
	            throw new Exception('Cart kosong');
	        }

	        /* ================= PREPARE LOG ================= */
	        $log_items = [];

	        foreach ($cart_items as $cart) {

	            $item = $this->db
	                ->where('no', $cart->item_code)
	                ->get('sh_m_item')
	                ->row();

	            if (!$item) continue;

	            $log_items[] = $item->description . ' (Qty: '.$cart->qty.')';
	        }

	        /* ================= DELETE CART ================= */
	        $this->db->where([
	            'id_customer' => $ic,
	            'id_table'    => $nomeja,
	            'id_trans'    => $id_trans->id,
	        ]);

	        if ($add) {
	            $this->db->where('addons', 1);
	        }

	        $this->db->delete('sh_cart');

	        /* ================= DELETE CART DETAILS ================= */
	        $this->db->where([
	            'id_customer' => $ic,
	            'id_table'    => $nomeja,
	            'DATE(entry_date)' => $date
	        ]);

	        $this->db->delete('sh_cart_details');

	        /* ================= GET CABANG ================= */
	        $cabang = $this->db
	            ->order_by('id', 'DESC')
	            ->limit(1)
	            ->get('sh_m_cabang')
	            ->row('id');

	        /* ================= LOG ================= */
	        $this->db->insert('sh_event_log', [
	            'event_type'  => 'Cancel Order',
	            'cabang'      => $cabang,
	            'id_trans'    => $id_trans->id,
	            'id_customer' => $ic,
	            'event_date'  => date('Y-m-d H:i:s'),
	            'user_by'     => $username,
	            'description' => 'Cancel semua item: '.implode(', ', $log_items).
	                             ' | Meja: '.$nomeja.
	                             ' | IP: '.$ip_address,
	            'created_date'=> date('Y-m-d'),
	        ]);

	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Gagal cancel order');
	        }

	        $this->db->trans_commit();

	    } catch (Exception $e) {

	        $this->db->trans_rollback();
	        $this->session->set_flashdata('error', $e->getMessage());
	        redirect($_SERVER['HTTP_REFERER']);
	        return;
	    }

	    switch ($cek) {
	        case 'Makanan':
	            $log = "index.php/Cart/home/$nomeja/Makanan/$sub#" . str_replace(' ', '_', $sub);
	            break;
	        case 'Minuman':
	            $log = "index.php/Cart/home/$nomeja/Minuman/$sub#" . str_replace(' ', '_', $sub);
	            break;
	        case 'CAKE DAN BAKERY':
	            $log = "index.php/Cart/home/$nomeja/CAKE%20DAN%20BAKERY/$sub#" . str_replace(' ', '_', $sub);
	            break;
	        default:
	            $log = "index.php/Cart/home/$nomeja";
	    }

	    $this->session->set_flashdata('success', 'Successfully Canceled the Order');
	    redirect(base_url($log));
	}

	public function ubah($id,$nomeja,$cek,$sub)
	{
		// echo $id;exit();
		$qty = $this->input->post('qty');
		$extra_notes = $this->input->post('extra_notes');
		$data = [
			'qty' => $qty,
			'extra_notes' => $extra_notes,
		];
		$this->db->where('id',$id);
		if ($qty != 0) {
			$this->db->update('sh_cart',$data);
			$this->session->set_flashdata('success','Menu Has Been Updated');
		}else{
			$this->db->delete('sh_cart');
			$this->session->set_flashdata('error','Menu Has Been Removed');
		}
    	
    	if ($cek == 'Makanan') {
			$log = 'index.php/Cart/home/'.$nomeja.'/Makanan/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
		}elseif ($cek == 'Minuman') {
			$log = 'index.php/Cart/home/'.$nomeja.'/Minuman/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
		}elseif ($cek == 'CAKE%20DAN%20BAKERY') {
			$log = 'index.php/Cart/home/'.$nomeja.'/CAKE%20DAN%20BAKERY/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
		}else{
			$log = 'index.php/Cart/home/'.$nomeja;
		}
		redirect(base_url().$log);
		
	}
	public function update_qtyOLD() {
	    // Mendapatkan data dari request (JSON input)
	    $data = json_decode(file_get_contents('php://input'), true);

	    // Mendapatkan id item dan qty
	    $id = $data['id'];
	    $qty = $data['qty'];
	    $nomeja = $this->session->userdata('nomeja');
	    $uoi = $this->session->userdata('user_order_id');

	    // Memperbarui qty item di keranjang
	    $this->db->where('id', $id);
	    $this->db->where('id_table', $nomeja);
	    // $this->db->where('user_order_id', $uoi);
	    $this->db->update('sh_cart', array('qty' => $qty));

	    // Cek apakah ada baris yang diupdate
	    if ($this->db->affected_rows() > 0) {
	        echo json_encode(array('success' => true));
	    } else {
	        echo json_encode(array('success' => false, 'message' => 'Item not found or no change in qty'));
	    }
	}
	public function update_qtydev() {

	    $data = json_decode(file_get_contents('php://input'), true);

	    $id   = $data['id'];
	    $qty  = (int)$data['qty'];

	    $nomeja      = $this->session->userdata('nomeja');
	    $id_customer = $this->session->userdata('id');
	    $username    = $this->session->userdata('username');
	    $ip_address  = $this->input->ip_address();

	    /* ================= GET CART ================= */
	    $cart = $this->db
	        ->where('id', $id)
	        ->where('id_table', $nomeja)
	        ->get('sh_cart')
	        ->row();

	    if (!$cart) {
	        echo json_encode([
	            'success' => false,
	            'message' => 'Cart tidak ditemukan'
	        ]);
	        return;
	    }

	    /* ================= GET ITEM ================= */
	    $item = $this->db
	        ->where('no', $cart->item_code)
	        ->get('sh_m_item')
	        ->row();

	    if (!$item) {
	        echo json_encode([
	            'success' => false,
	            'message' => 'Item tidak ditemukan'
	        ]);
	        return;
	    }

	    /* ================= HITUNG SELISIH ================= */
	    $old_qty = $cart->qty;
	    $selisih = $qty - $old_qty;

	    /* ================= START TRANSACTION ================= */
	    $this->db->trans_start();

	    /* ================= UPDATE STOCK ================= */
	    if ($item->need_stock == 1 && $selisih != 0) {

	        // jika qty naik → kurangi stock
	        if ($selisih > 0) {

	            if ($item->stock < $selisih) {
	                echo json_encode([
	                    'success' => false,
	                    'message' => 'Stock tidak cukup',
	                    'stock' => $item->stock
	                ]);
	                return;
	            }

	            $this->db->set('stock', 'stock - '.$selisih, false)
	                     ->where('id', $item->id)
	                     ->update('sh_m_item');
	        }

	        // jika qty turun → balikin stock
	        if ($selisih < 0) {
	            $this->db->set('stock', 'stock + '.abs($selisih), false)
	                     ->where('id', $item->id)
	                     ->update('sh_m_item');
	        }
	    }

	    /* ================= UPDATE CART ================= */
	    $this->db->where('id', $id)
	             ->where('id_table', $nomeja)
	             ->update('sh_cart', ['qty' => $qty]);

	    /* ================= GET CABANG ================= */
	    $cabang = $this->db
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    /* ================= GET TRANSACTION ================= */
	    $id_trans = $this->db
	        ->where('id_customer', $id_customer)
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();

	    $id_trans_id = $id_trans ? $id_trans->id : null;

	    /* ================= INSERT LOG ================= */
	    $this->db->insert('sh_event_log', [
	        'event_type'  => 'Update Qty Cart',
	        'cabang'      => $cabang,
	        'id_trans'    => $id_trans_id,
	        'id_customer' => $id_customer,
	        'event_date'  => date('Y-m-d H:i:s'),
	        'user_by'     => $username,
	        'description' => 'Update qty item: '.$item->description.
	                         ' ('.$old_qty.' → '.$qty.') | Meja: '.$nomeja.
	                         ' | IP: '.$ip_address.' dihalaman Cart',
	        'created_date'=> date('Y-m-d'),
	    ]);

	    /* ================= COMPLETE ================= */
	    $this->db->trans_complete();

	    if ($this->db->trans_status() === FALSE) {
	        echo json_encode([
	            'success' => false,
	            'message' => 'Gagal update'
	        ]);
	    } else {
	        echo json_encode([
	            'success' => true,
	            'qty' => $qty
	        ]);
	    }
	}
	public function update_qty() {

	    $data = json_decode(file_get_contents('php://input'), true);

	    $id   = $data['id'];
	    $qty  = (int)$data['qty'];

	    $nomeja      = $this->session->userdata('nomeja');
	    $id_customer = $this->session->userdata('id');
	    $username    = $this->session->userdata('username');
	    $ip_address  = $this->input->ip_address();

	    /* ================= GET CART ================= */
	    $cart = $this->db
	        ->where('id', $id)
	        ->where('id_table', $nomeja)
	        ->get('sh_cart')
	        ->row();

	    if (!$cart) {
	        echo json_encode([
	            'success' => false,
	            'message' => 'Cart tidak ditemukan'
	        ]);
	        return;
	    }

	    /* ================= GET ITEM ================= */
	    $item = $this->db
	        ->where('no', $cart->item_code)
	        ->get('sh_m_item')
	        ->row();

	    if (!$item) {
	        echo json_encode([
	            'success' => false,
	            'message' => 'Item tidak ditemukan'
	        ]);
	        return;
	    }

	    /* ================= HITUNG SELISIH ================= */
	    $old_qty = $cart->qty;

	    /* ================= START TRANSACTION ================= */
	    $this->db->trans_start();

	    /* ================= UPDATE CART ================= */
	    $this->db->where('id', $id)
	             ->where('id_table', $nomeja)
	             ->update('sh_cart', ['qty' => $qty]);

	    /* ================= GET CABANG ================= */
	    $cabang = $this->db
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    /* ================= GET TRANSACTION ================= */
	    $id_trans = $this->db
	        ->where('id_customer', $id_customer)
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();

	    $id_trans_id = $id_trans ? $id_trans->id : null;

	    /* ================= INSERT LOG ================= */
	    $this->db->insert('sh_event_log', [
	        'event_type'  => 'Update Qty Cart',
	        'cabang'      => $cabang,
	        'id_trans'    => $id_trans_id,
	        'id_customer' => $id_customer,
	        'event_date'  => date('Y-m-d H:i:s'),
	        'user_by'     => $username,
	        'description' => 'Update qty item: '.$item->description.
	                         ' ('.$old_qty.' → '.$qty.') | Meja: '.$nomeja.
	                         ' | IP: '.$ip_address.' dihalaman Cart',
	        'created_date'=> date('Y-m-d'),
	    ]);

	    /* ================= COMPLETE ================= */
	    $this->db->trans_complete();

	    if ($this->db->trans_status() === FALSE) {
	        echo json_encode([
	            'success' => false,
	            'message' => 'Gagal update'
	        ]);
	    } else {
	        echo json_encode([
	            'success' => true,
	            'qty' => $qty
	        ]);
	    }
	}
	public function changeitem($ic,$no,$qty,$id_cart)
	{
		$id_customer = $this->session->userdata('id');
		$uoi = $this->session->userdata('user_order_id');
		$table = $this->session->userdata('nomeja');
		$id_trans = $this->db->order_by('id',"desc")->where('id_customer',$id_customer)
                    ->limit(1)
                    ->get('sh_t_transactions')
                    ->row('id');
                $this->db->select('i.*');
				$this->db->from('sh_cart_details i');
				$this->db->where('i.item_code',$ic);
				$this->db->where('i.id_cart',$id_cart);
				// $this->db->where('i.user_order_id',$uoi);
		$itemdetails = $this->db->get()->row();
		        $this->db->select('i.*');
				$this->db->from('sh_m_item i');
				$this->db->where('i.no',$no);
		$item = $this->db->get()->row();

				$this->db->select('i.*');
				$this->db->from('sh_cart_details i');
				$this->db->where('i.item_code', $no);
				$this->db->where('i.id_cart', $id_cart);
				$ci = $this->db->get()->row();

				if ($ci) {
				    // Jika item sudah ada, update qty
				    $new_qty = $ci->qty + $qty; // Tambahkan qty baru ke qty lama

				    $this->db->where('id_cart', $id_cart);
				    $this->db->where('item_code', $no);
				    $update = $this->db->update('sh_cart_details', ['qty' => $new_qty]);

				    if ($update) {
				        $this->session->set_flashdata('success', 'Item quantity has been updated successfully.');
				    } else {
				        $this->session->set_flashdata('error', 'Failed to update item quantity.');
				    }
				} else {
				    // Jika item tidak ada, insert data baru
				    $data = [
				        'id_cart' => $id_cart,
				        'id_customer' => $id_customer,
				        'id_table' => $table,
				        'id_trans' => $id_trans,
				        'user_order_id' => $uoi,
				        'paket_code' => $itemdetails->paket_code,
				        'sub_category' => $itemdetails->sub_category,
				        'item_code' => $no,
				        'description' => $item->description,
				        'qty' => $qty,
				        'extra_notes' => $itemdetails->extra_notes,
				        'entry_date' => date('Y-m-d H:i:s'),
				    ];

				    $insert = $this->db->insert('sh_cart_details', $data);

				    if ($insert) {

				        $this->session->set_flashdata('success', 'Item has been successfully added.');
				    } else {
				        $this->session->set_flashdata('error', 'Failed to add item.');
				    }
				}

				$this->db->where('item_code',$ic);
				$this->db->where('id_cart',$id_cart);
				// $this->db->where('user_order_id',$uoi);
				$this->db->where('id_trans',$id_trans);
				$this->db->where('id_customer',$id_customer);
	    		$delete = $this->db->delete('sh_cart_details');


				// Redirect kembali ke halaman sebelumnya
				redirect($_SERVER['HTTP_REFERER']);        
}

	public function changeitemmodal() 
{
    $itemcodeOLD = $this->input->post('itemcodeOLD');
    $item_ids = $this->input->post('id');
    $item_codes = $this->input->post('no');
    $paket_codes = $this->input->post('paket_code');
    $id_cart = $this->input->post('id_cart');
    $quantities = $this->input->post('qty');
    $names = $this->input->post('nama');
    $subcategory = $this->input->post('subcategory');
    
    $id_customer = $this->session->userdata('id');
    $uoi = $this->session->userdata('user_order_id');
    $table = $this->session->userdata('nomeja');
    
    // Ambil transaksi terakhir pelanggan
    $id_trans = $this->db->order_by('id', "desc")
        ->where('id_customer', $id_customer)
        ->limit(1)
        ->get('sh_t_transactions')
        ->row('id');

    if (!empty($item_ids) && !empty($quantities)) {
        // Hapus item lama (itemcodeOLD) hanya jika ada perubahan
        $this->db->where('item_code', $itemcodeOLD);
        $this->db->where('id_cart', $id_cart);
        // $this->db->where('user_order_id', $uoi);
        $this->db->where('id_trans', $id_trans);
        $this->db->where('id_customer', $id_customer);
        $this->db->delete('sh_cart_details');

        $batch_update = [];
        $batch_insert = [];

        foreach ($item_ids as $key => $id) {
            $qty = (int) $quantities[$key];

            // Abaikan jika qty = 0
            if ($qty <= 0) {
                continue;
            }

            $itemC = $item_codes[$key];

            // Cek apakah item sudah ada di sh_cart_details
            $existing_item = $this->Paket_model->get_cart_item($id_cart, $itemC, $id_customer, $table, $id_trans, $uoi, date('Y-m-d'));

            if ($existing_item) {
                // Jika item sudah ada, update qty sebelumnya + qty baru
                $new_qty = (int) $existing_item['qty'] + $qty;
                $batch_update[] = [
                    'id_cart' => $id_cart,
                    'item_code' => $itemC,
                    'qty' => $new_qty
                ];
            } else {
                // Jika belum ada, insert data baru
                $batch_insert[] = [
                    'id_cart' => $id_cart,
                    'item_code' => $itemC,
                    'paket_code' => $paket_codes[$key], 
                    'qty' => $qty,
                    'description' => $names[$key],
                    'id_customer' => $id_customer,
                    'id_table' => $table,
                    'id_trans' => $id_trans,
                    'user_order_id' => $uoi,
                    'sub_category' => $subcategory[$key],
                    'extra_notes' => '',
                    'entry_date' => date('Y-m-d H:i:s'),
                ];
            }
        }

        // Update data yang sudah ada
        if (!empty($batch_update)) {
            $this->Paket_model->update_batch_cart($batch_update);
        }

        // Insert data yang belum ada
        if (!empty($batch_insert)) {
            $this->Paket_model->insert_batch_cart($batch_insert);
        }

        $this->session->set_flashdata('success', 'Items updated successfully');
    } else {
        $this->session->set_flashdata('error', 'No items selected.');
    }

    redirect($_SERVER['HTTP_REFERER']);
}





	public function simulate_va_payment($external_id, $amount)
	{
		$url = "https://api.xendit.co/callback_virtual_accounts/external_id={$external_id}/simulate_payment";

		$payload = [
			'amount' => (int) $amount
		];

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => json_encode($payload),
			CURLOPT_HTTPHEADER     => [
				'Content-Type: application/json'
			],
			CURLOPT_USERPWD        => $this->xendit_key . ':',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		]);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {

			$this->session->set_flashdata('error', 'Unable to process the virtual account payment simulation. Please try again.');

			curl_close($ch);

			redirect('payment');
		}

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		$data = json_decode($response, true);

		if ($httpcode == 200 && isset($data['status']) && $data['status'] == 'COMPLETED') {

			$this->db->where('external_id', $external_id)
					->update('sh_payment_va', [
						'status'      => 'PAID',
						'paid_amount' => $amount,
						'paid_at'     => date('Y-m-d H:i:s')
					]);

			$this->session->set_flashdata(
				'success',
				'Virtual Account payment has been successfully completed. The transaction has been verified and your order is now being processed.'
			);

		} else {

			$message = !empty($data['message'])
				? $data['message']
				: 'The Virtual Account payment could not be processed. Please try again later.';

			$this->session->set_flashdata('error', $message);
		}

		redirect('index.php/payment');
	}
	public function get_va_status($external_id)
	{
	    $url = "https://api.xendit.co/callback_virtual_accounts?external_id={$external_id}";

	    $ch = curl_init($url);
	    curl_setopt_array($ch, [
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_USERPWD        => $this->xendit_key . ':'
	    ]);

	    $response = curl_exec($ch);
	    curl_close($ch);

	    echo $response;
	}
	public function check_existing_order()
	{
	    header('Content-Type: application/json'); // WAJIB

	    $id_customer = $this->session->userdata('id');

	    if (!$id_customer) {
	        echo json_encode([
	            'exists' => false,
	            'items' => [],
	            'error' => 'Session expired'
	        ]);
	        return;
	    }

	    $trans = $this->db->get_where('sh_t_transactions', [
	        'id_customer' => $id_customer
	    ])->row();

	    if (!$trans) {
	        echo json_encode([
	            'exists' => false,
	            'items' => []
	        ]);
	        return;
	    }

	    $id_trans = $trans->id;

	    $data = json_decode(file_get_contents("php://input"), true);
	    $items = isset($data['items']) ? $data['items'] : [];

	    $result = [];

	    foreach ($items as $item) {
	        $this->db->where([
	            'item_code' => $item['item_code'],
	            'qty' => $item['qty'],
	            'id_trans' => $id_trans,
	            'is_paid' => 0,
	            'qty_finish' => 0
	        ]);

	        $cek = $this->db->get('sh_t_transaction_details')->row();

	        if ($cek) {
	            $result[] = $item['name'];
	        }
	    }

	    echo json_encode([
	        'exists' => count($result) > 0,
	        'items'  => $result
	    ]);
	}
	public function cek_status_va_xendit($external_id)
	{
		$url = "https://api.xendit.co/callback_virtual_accounts?external_id={$external_id}";

	    $ch = curl_init($url);
	    curl_setopt_array($ch, [
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_USERPWD        => $this->xendit_key . ':'
	    ]);

	    $response = curl_exec($ch);
	    curl_close($ch);

	    $result = json_decode($response, true);
	    var_dump($result);exit();
	    // ❌ BELUM DIBAYAR → TIDAK ADA CALLBACK
	    if (empty($result['data'])) {
	        echo json_encode([
	            'status' => true,
	            'payment_status' => 'PENDING',
	            'message' => 'VA belum dibayar'
	        ]);
	        return;
	    }

	    // ✅ SUDAH DIBAYAR
	    $va = $result['data'][0];

	    // UPDATE DB
	    $this->db->where('external_id', $external_id)
	             ->update('sh_payment_va', [
	                 // 'status'       => 'PAID',
	                 'paid_amount'  => $va['amount'],
	                 'paid_at'      => date('Y-m-d H:i:s')
	             ]);

	    echo json_encode([
	        'status' => true,
	        // 'payment_status' => 'PAID',
	        'bank' => $va['bank_code'],
	        'amount' => $va['amount']
	    ]);
	}

		public function cek_status_va_local($external_id)
	{
	    // DB DEV
	    // $this->db_dev = $this->load->database('dev', TRUE);

	    // $va_dev = $this->db_dev
	    //     ->where('external_id', $external_id)
	    //     ->get('sh_payment_va')
	    //     ->row();

	    // DB LOCAL
	    $va = $this->db
	        ->where('external_id', $external_id)
	        ->get('sh_payment_va')
	        ->row();

	    if (!$va) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'VA not found'
	        ]);
	        return;
	    }

	    /* ===============================
	       JIKA DEV SUDAH PAID → UPDATE LOCAL
	       =============================== */
	    // if ($va_dev && $va_dev->status === 'PAID') {

	    //     $this->db->where('external_id', $external_id)
	    //         ->update('sh_payment_va', [
	    //             'status'       => 'PAID',
	    //             'paid_amount'  => $va_dev->paid_amount,
	    //             'va_number'    => $va_dev->va_number,
	    //             'bank'         => $va_dev->bank,
	    //             'paid_at'      => $va_dev->paid_at,
	    //         ]);

	    //     // refresh data local setelah update
	    //     $va = $this->db
	    //         ->where('external_id', $external_id)
	    //         ->get('sh_payment_va')
	    //         ->row();
	    // }

	    /* ===============================
	       RESPONSE
	       =============================== */
	    echo json_encode([
	        'status'          => true,
	        'payment_status' => $va->status, // PENDING | PAID
	        'bank'            => $va->bank,
	        'amount'          => $va->amount
	    ]);
	}

}


