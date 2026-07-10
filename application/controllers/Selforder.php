<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Selforder extends CI_Controller {
public function __construct() {
			parent::__construct();
			if($this->session->userdata('username') == ""){
           		$nomeja = $this->session->userdata('nomeja');
  				redirect('index.php/login/logout/'.$nomeja);
        	}
			$this->load->model('Item_model');
			$this->load->model('Admin_model');
			$this->load->model('cekstatus_model');
			$this->load->helper('cookie');
			$session = $this->cekstatus_model->cek();

	  		// if($session['status'] == 'Cleaning'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/logout/'.$nomeja.'/cleaning');
	  		// }
	  		if($session['id_table'] != $this->session->userdata('nomeja')){
	  			$nomeja = $this->session->userdata('nomeja');
	  			redirect('index.php/login/log_out/'.$nomeja);
	  		}
	  		
		}
	public function index()
	{
		$cs = $this->session->userdata('id');
		$data['no_meja'] = $this->Item_model->nomeja($cs);
		$this->load->view('self_index',$data);
	}
	public function homeOLD()
	{
		$nomeja = $this->session->userdata('nomeja');
		// $cek = $this->Item_model->sub_category_awal();
		// var_dump($cek);exit();
		$id_customer = $this->session->userdata('id');
		$cs = $this->session->userdata('id');
		$data['no_meja'] = $nomeja;
		$data['cart_count'] = $this->Item_model->hitungcart($nomeja);
		$data['sca'] = $this->Item_model->sub_category_awal();
		$data['scm'] = $this->Item_model->sub_category_minuman_awal();
		$data['sub_category'] = "ayam";
		$data['sub_category_minuman'] = "Cold Drink";
		$data['nomeja'] = $this->session->userdata('nomeja');
		$cart_count = $this->Item_model->cart_count($id_customer,$nomeja)->num_rows();
		if($cart_count > 0){
			$cart = $this->Item_model->cart_count($id_customer,$nomeja)->row();//tambahan	
			$cart_total = $cart->total_qty;
		}else{
			$cart_total = 0;
		}
		$data['total_qty'] = $cart_total;
		$data['icon'] = $this->Admin_model->getIcon('home');
		$data['iconfooter'] = $this->Admin_model->getIcon('footer');
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['logo'] = $this->Admin_model->getLogo();
		$data['cekSignature'] = $this->Item_model->cekSignature();
		$this->load->view('self_index',$data);
	}

	public function home()
	{
	    $nomeja = $this->session->userdata('nomeja');
	    $id_customer = $this->session->userdata('id');

	    $data['nomeja'] = $nomeja;

	    // Hitung total qty di cart
	    $cart_count_query = $this->Item_model->cart_count($id_customer, $nomeja);
	    $cart_count = $cart_count_query->num_rows();
	    $data['total_qty'] = $cart_count > 0 ? $cart_count_query->row()->total_qty : 0;

	    // Ambil satu data sub_category dari model
	    $subcategory = $this->Item_model->sub_category_awal_raw();

	    // Jika data ada, lakukan filter tanggal dan jam (karena model hanya 1 data)
	    $nowDate = date('Y-m-d');
	    $nowHour = date('H');

	    $valid = true;
	    if ($subcategory) {
	        // Filter tanggal
	        if (!empty($subcategory['date_from']) && !empty($subcategory['date_to'])) {
	            if ($nowDate < $subcategory['date_from'] || $nowDate > $subcategory['date_to']) {
	                $valid = false;
	            }
	        }
	        // Filter jam
	        if (!empty($subcategory['time_from']) && !empty($subcategory['time_to'])) {
	            if ($nowHour < $subcategory['time_from'] || $nowHour > $subcategory['time_to']) {
	                $valid = false;
	            }
	        }
	    } else {
	        $valid = false; // jika data kosong otomatis invalid
	    }

	    $data['sca'] = $valid ? $subcategory['sub_category'] : '';

	    // Data lain
	    $data['scm'] = $this->Item_model->sub_category_minuman_awal();
	    $data['sub_category'] = "ayam";
	    $data['sub_category_minuman'] = "Cold Drink";

	    // Icon, warna, logo, dan cek signature
	    $data['icon'] = $this->Admin_model->getIcon('home');
	    $data['iconfooter'] = $this->Admin_model->getIcon('footer');
	    $data['cn'] = $this->Admin_model->getColorCN();
	    $data['ch'] = $this->Admin_model->getColorHD();
	    $data['cb'] = $this->Admin_model->getColorBTN();
	    $data['logo'] = $this->Admin_model->getLogo();
	    $data['cekSignature'] = $this->Item_model->cekSignature();
	    $trans = $this->db->order_by('create_date', 'DESC')
                  ->get_where('sh_t_transactions', array('id_customer' => $id_customer))
                  ->row();
        if ($trans->parent_id != 0) {
        	$data['cekpay'] = $this->Item_model->getitem($trans->parent_id,'parent');
        }else{
        	$data['cekpay'] = $this->Item_model->getitem($trans->id,'notparent');
        }
        
	    $this->load->view('self_index', $data);
	}

	public function struk()
	{
	    $cabang = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_m_cabang')->row();
	    $logo   = $this->Admin_model->getLogo();
	    $nomeja = $this->session->userdata('nomeja');
	    $ic     = $this->session->userdata('id');

	    if (!$ic) {
	        show_error("Session customer tidak ditemukan");
	        return;
	    }

	    /* ===============================
	       AMBIL DATA CUSTOMER DI MEJA
	    ================================ */
	    $user = $this->db
	        ->select('a.id,b.customer_name,b.no_telp,a.id_customer,a.visit_type,b.total_pax')
	        ->from('sh_rel_table a')
	        ->join('sh_m_customer b', 'b.id = a.id_customer')
	        ->where('b.id', $ic)
	        ->where('a.id_table', $nomeja)
	        ->where("a.created_date", date('Y-m-d'))
	        ->limit(1)
	        ->get()
	        ->row();

	    if (!$user) {
	        show_error("Customer tidak ditemukan");
	        return;
	    }

	    $todayStart = date('Y-m-d 00:00:00');
	    $todayEnd   = date('Y-m-d 23:59:59');

	    /* ===============================
	       AMBIL TRANSAKSI PENDING HARI INI
	    ================================ */
	    $trans = $this->db
	        ->where('id_customer', $ic)
	        ->where('create_date >=', $todayStart)
	        ->where('create_date <=', $todayEnd)
	        ->get('sh_t_transactions')
	        ->row();

	    if (!$trans) {
	        show_error("Transaksi tidak ditemukan");
	        return;
	    }

	    $paymentso = $this->db
	        ->order_by('id','DESC')
	        ->get_where('sh_payments_so', ['external_id'=>$trans->external_id_so], 1)
	        ->row();

	    /* ===============================
	       AMBIL DETAIL
	    ================================ */
	    $details = $this->db
	        ->where('id_trans', $trans->id)
	        ->get('sh_t_transaction_details')
	        ->result();
	    if (empty($details)) {
	        show_error("Detail transaksi kosong");
	        return;
	    }

	    /* ===============================
	       CEK JAM BATAS
	    ================================ */
	    $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
	    $jam   = date('H:i:s');


	    if ($jam <= $setup->end_time_trnons) {

	        /* ===============================
	           CEK SUDAH PERNAH INSERT?
	        ================================ */
	        $existing = $this->db
	            ->where('order_no', $trans->order_no)
	            ->get('sh_t_transaction')
	            ->row();

	        if (!$existing) {

	            $this->db->trans_start();

	            /* INSERT HEADER */
	            $datatrans = [
	            	'id'				=> $trans->id,
	                'payment_amount'    => $trans->payment_amount,
	                'total_amount'      => $trans->total_amount,
	                'kembalian'         => 0,
	                'payment_date'      => date('Y-m-d H:i:s'),
	                'payment_type'      => 'xendit',
	                'payment_card_type' => 'credit card',
	                'payment_bank_card' => $trans->payment_bank_card,
	                'payment_by'        => $trans->payment_by,
	                'order_no'          => $trans->order_no,
	                'cabang'            => $trans->cabang,
	                'is_take_away'      => $trans->is_take_away,
	                'sc_percent'        => $trans->sc_percent,
	                'sc_amount'         => $trans->sc_amount,
	                'tax_percent'       => $trans->tax_percent,
	                'tax_amount'        => $trans->tax_amount,
	                'bill_discount'     => $trans->bill_discount,
	                'down_payment'      => $trans->down_payment,
	            ];

	            $this->db->insert('sh_t_transaction', $datatrans);

	            /* INSERT DETAIL */
	            foreach ($details as $d) {

	                $datadetail = [
	                    'id_trans'          => $d->id_trans,
	                    'item_code'         => $d->item_code,
	                    'qty'               => $d->qty,
	                    'unit_price'        => $d->unit_price,
	                    'description'       => $d->description,
	                    'entry_by'          => $d->entry_by,
	                    'disc'              => $d->disc,
	                    'is_cancel'         => $d->is_cancel,
	                    'cabang'            => $d->cabang,
	                    'selected_table_no' => $d->selected_table_no,
	                    'sort_id'           => $d->sort_id,
	                    'is_take_away'      => $d->is_take_away,
	                    'is_paid'           => 1,
	                    'created_date'      => date('Y-m-d H:i:s'),
	                ];

	                $this->db->insert('sh_t_transaction_detail', $datadetail);
	            }

	            $this->db->trans_complete();

	            if ($this->db->trans_status() === FALSE) {
	                show_error("Gagal memproses transaksi");
	                return;
	            }
	        }
	    }

	    /* ===============================
	       AMBIL POINT MEMBER
	    ================================ */
	    $point = $this->db
	        ->where('id_customer', $ic)
	        ->where('id_trans', $trans->id)
	        ->where('transaction_date >=', $todayStart)
	        ->where('transaction_date <=', $todayEnd)
	        ->get('sh_point_member')
	        ->row();

	    /* ===============================
	       AMBIL DATA STRUK
	    ================================ */
	    $order_bill = $this->Item_model->order_bill_struk($cabang->id, $trans->id);
	    $order_bill_line = $this->Item_model->order_bill_line($cabang->id, $trans->id);
	    $visit_type = $this->session->userdata('visit_type');
	    $vt = ($user->visit_type == 'Walk In') ? 'Dine in' : 'TakeAway';

	    $data = [
	        'cabang'          => $cabang,
	        'logo'            => $logo,
	        'visit_type'      => $vt,
	        'nomeja'          => $nomeja,
	        'customer_name'   => $user->customer_name,
	        'total_pax'       => $user->total_pax,
	        'trans'           => $trans,
	        'order_bill'      => $order_bill,
	        'order_bill_line' => $order_bill_line,
	        'point'           => $point,
	        'print'           => 0,
	        'paymentMethod'	  => $paymentso->payment_method,
	    ];

	    $this->load->view('struk', $data);
	}
		public function struk_done()
	{
		$this->session->unset_userdata(array_keys($this->session->userdata()));
		$this->session->set_flashdata('success', 'Payment Successful. Order Sent to Kitchen.');

		redirect('index.php/login/log/');

	}
	public function download_struk_pdf()
	{
	    $this->load->library('pdf');

	    // ================= DATA =================
	    $cabang = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_m_cabang')->row();
	    $logo   = $this->Admin_model->getLogo();
	    $nomeja = $this->session->userdata('nomeja');

	    $user = $this->db
	        ->select('a.id,b.customer_name,b.no_telp,a.id_customer,b.visit_type,b.total_pax')
	        ->from('sh_rel_table a')
	        ->join('sh_m_customer b', 'b.id = a.id_customer')
	        ->where('a.id_table', $nomeja)
	        ->where('a.created_date', date('Y-m-d'))
	        ->limit(1)
	        ->get()
	        ->row();

	    $ic = $this->session->userdata('id');

	    $trans = $this->db
	        ->where('id_customer', $ic)
	        ->where('create_date >=', date('Y-m-d 00:00:00'))
	        ->where('create_date <=', date('Y-m-d 23:59:59'))
	        ->get('sh_t_transactions')
	        ->row();

	    if (!$trans) {
	        show_error('Transaction not found');
	    }

	    $point = $this->db
	        ->where('id_customer', $ic)
	        ->where('id_trans', $trans->id)
	        ->get('sh_point_member')
	        ->row();

	    $order_bill = $this->Item_model->order_bill_struk($cabang->id, $trans->id);
	    $order_bill_line = $this->Item_model->order_bill_line($cabang->id, $trans->id);

	    $vt = ($user && $user->visit_type == 'Walkin') ? 'Dine in' : 'TakeAway';

	    $data = [
	        'cabang'          => $cabang,
	        'logo'            => $logo,
	        'visit_type'      => $vt,
	        'nomeja'          => $nomeja,
	        'customer_name'   => $user->customer_name ?? '-',
	        'total_pax'       => $user->total_pax ?? 0,
	        'trans'           => $trans,
	        'order_bill'      => $order_bill,
	        'order_bill_line' => $order_bill_line,
	        'point'           => $point,
	        'print'           => 1,
	    ];

	    // ================= RENDER PDF =================
	    $html = $this->load->view('struk_pdf', $data, true);

	    // 👉 LEBAR 80mm, TINGGI BESAR BIAR GA KEPOTONG
	    $this->pdf->setPaper([0, 0, 350, 1500], 'portrait');

		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream(
		    $trans->order_no . '.pdf',
		    ['Attachment' => true]
		);

	}



	public function landing()
	{
		$data = [
			'username' => $this->session->userdata('username'),
			'nomeja' => $this->session->userdata('nomeja'),
			'logo' => $this->Admin_model->getLogo(),
			'cn' => $this->Admin_model->getColorCN(),
			'ch' => $this->Admin_model->getColorHD(),
			'cb' => $this->Admin_model->getColorBTN(),
		];
		$this->load->view('landing',$data);
	}
		function cekinternet()
	{
		$this->load->view('cekinternet');
	}
}
