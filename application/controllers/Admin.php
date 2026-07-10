<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
function __construct()
		{
			parent::__construct();
			if ($this->session->userdata('usernameadmin') == "" || 
			    !in_array($this->session->userdata('role'), ['admin', 'marketing', 'operation','waitress','cashier','kitchen'])) {
			    redirect('index.php/login/admin');
			}

			$this->load->model('Admin_model');
			$this->load->model('Item_model');
			$this->load->library('pagination');
			$this->load->library('encryption');
			
		}
	public function index()
	{
		$config1['base_url'] = base_url('index.php/Admin');
	    $config1['total_rows'] = $this->Admin_model->countEvent(); 
	    $config1['per_page'] = 10; 
	    $config1['uri_segment'] = 3;
	    $config1['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config1['full_tag_close'] = '</ul></nav>';
	    $config1['first_link'] = 'First';
	    $config1['last_link'] = 'Last';
	    $config1['first_tag_open'] = '<li class="page-item">';
	    $config1['first_tag_close'] = '</li>';
	    $config1['prev_link'] = '&laquo';
	    $config1['prev_tag_open'] = '<li class="page-item">';
	    $config1['prev_tag_close'] = '</li>';
	    $config1['next_link'] = '&raquo';
	    $config1['next_tag_open'] = '<li class="page-item">';
	    $config1['next_tag_close'] = '</li>';
	    $config1['last_tag_open'] = '<li class="page-item">';
	    $config1['last_tag_close'] = '</li>';
	    $config1['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config1['cur_tag_close'] = '</a></li>';
	    $config1['num_tag_open'] = '<li class="page-item">';
	    $config1['num_tag_close'] = '</li>';
	    $config1['attributes'] = array('class' => 'page-link');

	    $this->pagination->initialize($config1);

	    $page1 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    	
		$data = [
			'cn' => $this->Admin_model->getColorCN(),
			'ch' => $this->Admin_model->getColorHD(),
			'cb' => $this->Admin_model->getColorBTN(),
			'optionA' => $this->db->where('is_active',1)->where('type','option')->count_all_results('sh_m_item_option'),
			'optionI' => $this->db->where('is_active',0)->where('type','option')->count_all_results('sh_m_item_option'),
			'addonA' => $this->db->where('is_active',1)->where('type','addon')->count_all_results('sh_m_item_option'),
			'addonI' => $this->db->where('is_active',0)->where('type','addon')->count_all_results('sh_m_item_option'),
			'usersA' => $this->db->where('is_active',1)->count_all_results('sh_user_so'),
			'usersI' => $this->db->where('is_active',0)->count_all_results('sh_user_so'),
			'ichA' => $this->db->where('is_active',1)->where('type','home')->count_all_results('sh_m_setup_so'),
			'ichI' => $this->db->where('is_active',0)->where('type','home')->count_all_results('sh_m_setup_so'),
			'icfA' => $this->db->where('is_active',1)->where('type','footer')->count_all_results('sh_m_setup_so'),
			'icfI' => $this->db->where('is_active',0)->where('type','footer')->count_all_results('sh_m_setup_so'),
			'event' => $this->Admin_model->getEvent($config1['per_page'], $page1),
			'links' => $this->pagination->create_links(),
			'count' => $this->Admin_model->countEvent(),
			'logo' => $this->Admin_model->getLogo(),
		];
		
		$this->load->view('admin/index',$data);
	}
	public function dashboard($offset = 0)
	{
	    // =========================
	    // CONFIG PAGINATION
	    // =========================
	    $config1['base_url'] = base_url('index.php/Admin/dashboard/');
	    $config1['total_rows'] = $this->Admin_model->countEvent(); 
	    $config1['per_page'] = 10; 
	    $config1['uri_segment'] = 3; // karena pakai Admin/{offset}
	    $config1['use_page_numbers'] = FALSE;

	    // =========================
	    // STYLING PAGINATION
	    // =========================
	    $config1['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config1['full_tag_close'] = '</ul></nav>';
	    $config1['first_link'] = 'First';
	    $config1['last_link'] = 'Last';
	    $config1['first_tag_open'] = '<li class="page-item">';
	    $config1['first_tag_close'] = '</li>';
	    $config1['prev_link'] = '&laquo';
	    $config1['prev_tag_open'] = '<li class="page-item">';
	    $config1['prev_tag_close'] = '</li>';
	    $config1['next_link'] = '&raquo';
	    $config1['next_tag_open'] = '<li class="page-item">';
	    $config1['next_tag_close'] = '</li>';
	    $config1['last_tag_open'] = '<li class="page-item">';
	    $config1['last_tag_close'] = '</li>';
	    $config1['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config1['cur_tag_close'] = '</a></li>';
	    $config1['num_tag_open'] = '<li class="page-item">';
	    $config1['num_tag_close'] = '</li>';
	    $config1['attributes'] = array('class' => 'page-link');

	    // =========================
	    // INIT PAGINATION
	    // =========================
	    $this->pagination->initialize($config1);

	    // =========================
	    // OFFSET
	    // =========================
	    $page1 = $offset;

	    // =========================
	    // DATA
	    // =========================
	    $data = [
	        'cn' => $this->Admin_model->getColorCN(),
	        'ch' => $this->Admin_model->getColorHD(),
	        'cb' => $this->Admin_model->getColorBTN(),

	        'optionA' => $this->db->where('is_active',1)->where('type','option')->count_all_results('sh_m_item_option'),
	        'optionI' => $this->db->where('is_active',0)->where('type','option')->count_all_results('sh_m_item_option'),

	        'addonA' => $this->db->where('is_active',1)->where('type','addon')->count_all_results('sh_m_item_option'),
	        'addonI' => $this->db->where('is_active',0)->where('type','addon')->count_all_results('sh_m_item_option'),

	        'usersA' => $this->db->where('is_active',1)->count_all_results('sh_user_so'),
	        'usersI' => $this->db->where('is_active',0)->count_all_results('sh_user_so'),

	        'ichA' => $this->db->where('is_active',1)->where('type','home')->count_all_results('sh_m_setup_so'),
	        'ichI' => $this->db->where('is_active',0)->where('type','home')->count_all_results('sh_m_setup_so'),

	        'icfA' => $this->db->where('is_active',1)->where('type','footer')->count_all_results('sh_m_setup_so'),
	        'icfI' => $this->db->where('is_active',0)->where('type','footer')->count_all_results('sh_m_setup_so'),

	        'event' => $this->Admin_model->getEvent($config1['per_page'], $page1),
	        'links' => $this->pagination->create_links(),
	        'count' => $this->Admin_model->countEvent(),
	        'logo' => $this->Admin_model->getLogo(),
	    ];

	    $this->load->view('admin/index', $data);
	}
	public function option()
	{
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
	        redirect('index.php/login/admin');
	    }

	    // Ambil input search
	    $search = [
	        'item_name' => $this->input->get('item_name'),
	        'type'      => $this->input->get('type'),
	        'option'    => $this->input->get('option'),
	        'status'    => $this->input->get('status')
	    ];

	    // Pagination config
	    $config1['base_url'] = base_url('index.php/Admin/option');
	    $config1['total_rows'] = $this->Admin_model->countOption($search); // ubah supaya bisa filter search
	    $config1['per_page'] = 10; 
	    $config1['uri_segment'] = 3;
	    $config1['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config1['full_tag_close'] = '</ul></nav>';
	    $config1['first_link'] = 'First';
	    $config1['last_link'] = 'Last';
	    $config1['first_tag_open'] = '<li class="page-item">';
	    $config1['first_tag_close'] = '</li>';
	    $config1['prev_link'] = '&laquo';
	    $config1['prev_tag_open'] = '<li class="page-item">';
	    $config1['prev_tag_close'] = '</li>';
	    $config1['next_link'] = '&raquo';
	    $config1['next_tag_open'] = '<li class="page-item">';
	    $config1['next_tag_close'] = '</li>';
	    $config1['last_tag_open'] = '<li class="page-item">';
	    $config1['last_tag_close'] = '</li>';
	    $config1['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config1['cur_tag_close'] = '</a></li>';
	    $config1['num_tag_open'] = '<li class="page-item">';
	    $config1['num_tag_close'] = '</li>';
	    $config1['attributes'] = array('class' => 'page-link');

	    $this->pagination->initialize($config1);

	    $page1 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

	    $data['cekOPTION'] = $this->Admin_model->cekOPTION();
	    $data['option'] = $this->Admin_model->get_option($config1['per_page'], $page1, $search);
	    $data['links1'] = $this->pagination->create_links();
	    $data['cn'] = $this->Admin_model->getColorCN();
	    $data['ch'] = $this->Admin_model->getColorHD();
	    $data['cb'] = $this->Admin_model->getColorBTN();
	    $data['item'] = $this->Admin_model->get_item();
	    $data['logo'] = $this->Admin_model->getLogo();
	    $this->load->view('admin/option', $data);
	}
	public function addon()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			   redirect('index.php/login/admin');
		}
		$config2['base_url'] = base_url('index.php/Admin/addon');
	    $config2['total_rows'] = $this->Admin_model->countAddon(); 
	    $config2['per_page'] = 10; 
	    $config2['uri_segment'] = 3;
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_link'] = 'First';
	    $config2['last_link'] = 'Last';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['prev_link'] = '&laquo';
	    $config2['prev_tag_open'] = '<li class="page-item">';
	    $config2['prev_tag_close'] = '</li>';
	    $config2['next_link'] = '&raquo';
	    $config2['next_tag_open'] = '<li class="page-item">';
	    $config2['next_tag_close'] = '</li>';
	    $config2['last_tag_open'] = '<li class="page-item">';
	    $config2['last_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['num_tag_open'] = '<li class="page-item">';
	    $config2['num_tag_close'] = '</li>';
	    $config2['attributes'] = array('class' => 'page-link');

	    $this->pagination->initialize($config2);

	    $page2 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

	    $data['cekADDON'] = $this->Admin_model->cekADDON();
	    $data['cekADDONDESC'] = $this->Admin_model->cekADDONDESC();
	    $data['addon'] = $this->Admin_model->get_addon($config2['per_page'], $page2);
    	$data['links2'] = $this->pagination->create_links();
    	$data['item'] = $this->Admin_model->get_item();
    	$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
    	$data['logo'] = $this->Admin_model->getLogo();
	    $this->load->view('admin/addon', $data);
	}

	public function create_option()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			   redirect('index.php/login/admin');
		}
		$data = [
			'item_code' => $this->input->post('no'),
			'description' => $this->input->post('option'),
			'is_active' => $this->input->post('is_active'),
			'option' => $this->input->post('typeoption'),
			'type' => 'option',
		];
		$this->db->insert('sh_m_item_option',$data);
		$this->session->set_flashdata('success','Data option has been successfully saved');
		redirect('index.php/Admin/option/');
	}

	public function create_addon()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			   redirect('index.php/login/admin');
		}
		$data = [
			'item_code' => $this->input->post('no'),
			'description' => $this->input->post('addon'),
			'is_active' => $this->input->post('is_active'),
			'option' => 1,
			'type' => 'addon',
		];
		$this->db->insert('sh_m_item_option',$data);
		$this->session->set_flashdata('success','Data add on has been successfully saved');
		redirect('index.php/Admin/option/');
	}
	public function update($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			   redirect('index.php/login/admin');
		}
		$id = $this->input->post('id'); // Pastikan id dikirim dari form

		$data = [
			'item_code' => $this->input->post('no'),
			'description' => $this->input->post('option'),
			'is_active' => $this->input->post('is_active'),
		];

		$this->db->where('id', $id);
		$this->db->update('sh_m_item_option', $data);

		// Set flashdata untuk menampilkan pesan sukses
		$this->session->set_flashdata('success', 'Data option has been successfully updated');

		// Redirect ke halaman option
		redirect('index.php/Admin/option/');

	}
	public function signout()
	{
		$customer_name = $this->input->get('customer_name');
	    $table_number = $this->input->get('table_number');

	    // Validasi akses user
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation','waitress'])) {
	        redirect('index.php/login/admin');
	    }

	    // Data Pagination
	    $page2 = $this->input->get('start') ? intval($this->input->get('start')) : 0;

	    // Konfigurasi pagination
	    $query_string = http_build_query(array_filter([
	        'customer_name' => $customer_name,
	        'table_number' => $table_number
	    ]));

	    $config2['base_url'] = base_url('index.php/admin/asigncategory') . ($query_string ? '?' . $query_string : '');
	    $config2['total_rows'] = $this->Admin_model->countSignout($table_number,$customer_name);
	    $config2['per_page'] = 10;
	    $config2['page_query_string'] = true;
	    $config2['query_string_segment'] = 'start';
	    $config2['suffix'] = $query_string ? '&' . $query_string : '';
	    $config2['first_url'] = $config2['base_url'] . $config2['suffix'];
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['attributes'] = ['class' => 'page-link'];

	    $this->pagination->initialize($config2);

	    // Ambil data berdasarkan filter
	    $data['event'] = $this->Admin_model->getSignout($config2['per_page'], $page2,$table_number,$customer_name);
	    $data['customer_name'] = $customer_name;
    	$data['table_number'] = $table_number;
    	$data['links2'] = $this->pagination->create_links();
    	$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
    	$data['logo'] = $this->Admin_model->getLogo();
	    $this->load->view('admin/signout', $data);
	}
	public function get_payment_online()
	{
		$customer_name = $this->input->get('customer_name');
	    $table_number = $this->input->get('table_number');

	    // Validasi akses user
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
	        redirect('index.php/login/admin');
	    }

	    // Data Pagination
	    $page2 = $this->input->get('start') ? intval($this->input->get('start')) : 0;

	    // Konfigurasi pagination
	    $query_string = http_build_query(array_filter([
	        'customer_name' => $customer_name,
	        'table_number' => $table_number
	    ]));
	    $config2['base_url'] = base_url('index.php/admin/get_payment_online') . ($query_string ? '?' . $query_string : '');
	    $config2['total_rows'] = $this->Admin_model->countPayment($table_number,$customer_name);
	    $config2['per_page'] = 10;
	    $config2['page_query_string'] = true;
	    $config2['query_string_segment'] = 'start';
	    $config2['suffix'] = $query_string ? '&' . $query_string : '';
	    $config2['first_url'] = $config2['base_url'] . $config2['suffix'];
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['attributes'] = ['class' => 'page-link'];

	    $this->pagination->initialize($config2);

	    // Ambil data berdasarkan filter
	    $data['pay'] = $this->Admin_model->getPayment($config2['per_page'], $page2,$table_number,$customer_name);
	    $data['customer_name'] = $customer_name;
    	$data['table_number'] = $table_number;
    	$data['links2'] = $this->pagination->create_links();
    	$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
    	$data['logo'] = $this->Admin_model->getLogo();
	    $this->load->view('admin/getpaymentonline', $data);
	}
	public function get_payment_api($external_id,$id_customer)
	{
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
	    	$this->load_loading_view($external_id,'admin');
	    	// redirect('index.php/cart/load_loading_view/'.$external_id.'/admin');
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
	        $this->load_loading_view($external_id,'admin');
	        // redirect('index.php/cart/load_loading_view/'.$external_id.'/admin');
	        return;
	    }

	    // Ambil status callback
	    $payment_status = strtoupper($found->status ?? '');
	    
	    if ($payment_status !== '1') {
	        // Status belum PAID, tampilkan loading
	        $this->load_loading_view($external_id,'admin');
	        // redirect('index.php/cart/load_loading_view/'.$external_id.'/admin');
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
	            'invoice_id'      => $found->external_billing_id ?? null,
	            'status'          => $ps,
	            'amount'          => $found->amount ?? 0,
	            'paid_amount'     => $found->callback->paid_amount ?? 0,
	            'payment_method'  => $found->callback->payment_method ?? null,
	            'bank_code'       => $bank_code,
	            'paid_at'         => $found->callback->paid_at ?? null,
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
	            $this->sukses($external_id,$id_customer);
	        } else {
	            // Status belum PAID di DB
	            $this->load_loading_view($external_id,'admin');
	            // redirect('index.php/cart/load_loading_view/'.$external_id.'/admin');
	            return;
	        }

	    } else {
	        // Jika sudah ada di DB, cek status
	        if ($cek->status === 'PAID') {
	            $id_customer = $this->session->userdata('id');
	            $this->db->where('id_customer', $id_customer)
	                     ->update('sh_rel_table', ['status' => 'Payment']);
	            $nomeja = $this->session->userdata('nomeja');
	            $this->sukses($external_id,$id_customer);
	        } else {
	        	$this->load_loading_view($external_id,'admin');
	            // redirect('index.php/cart/load_loading_view/'.$external_id.'/admin');
	            return;
	        }
	    }
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
	public function sukses($external_id,$id_customer)
	{
		$payment = $this->db->where('external_id', $external_id)->get('sh_payments_so')->row();
	    $descriptionpay = $this->input->get('descriptionpay');
	    $amount = $payment->amount;
	    
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
	    
	    $data = [
	        'id_customer' => $trans->id_customer,
	        'details' 	  => $details,
	        'amount'      => $amount,
	    ];
	    $this->logpayment($data,$external_id);
	}
	public function logpayment($data, $external_id)
	{
	    extract($data);

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
	    $payment = $this->Item_model->totalbayaradmin($trans->id);
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
	             ->where('user_order_id', $uoi)
	             ->delete('sh_cart');

	    $this->db->where('id_customer', $id_customer)
	             ->where('user_order_id', $uoi)
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
	    $this->session->set_flashdata('success', 'Berhasil get payment online ');
	    redirect('index.php/Admin/get_payment_online');
	}
	public function signoutOLD()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			   redirect('index.php/login/admin');
		}
	    $config1['base_url'] = base_url('index.php/Admin/signout');
	    $config1['total_rows'] = $this->Admin_model->countEvent(); 
	    $config1['per_page'] = 10; 
	    $config1['uri_segment'] = 3;
	    $config1['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config1['full_tag_close'] = '</ul></nav>';
	    $config1['first_link'] = 'First';
	    $config1['last_link'] = 'Last';
	    $config1['first_tag_open'] = '<li class="page-item">';
	    $config1['first_tag_close'] = '</li>';
	    $config1['prev_link'] = '&laquo';
	    $config1['prev_tag_open'] = '<li class="page-item">';
	    $config1['prev_tag_close'] = '</li>';
	    $config1['next_link'] = '&raquo';
	    $config1['next_tag_open'] = '<li class="page-item">';
	    $config1['next_tag_close'] = '</li>';
	    $config1['last_tag_open'] = '<li class="page-item">';
	    $config1['last_tag_close'] = '</li>';
	    $config1['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config1['cur_tag_close'] = '</a></li>';
	    $config1['num_tag_open'] = '<li class="page-item">';
	    $config1['num_tag_close'] = '</li>';
	    $config1['attributes'] = array('class' => 'page-link');

	    $this->pagination->initialize($config1);

	    $page1 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

	    $data['cekOPTION'] = $this->Admin_model->cekOPTION();
	    $data['event'] = $this->Admin_model->getEvent($config1['per_page'], $page1);
    	$data['links1'] = $this->pagination->create_links();
    	$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
    	$data['item'] = $this->Admin_model->get_item();
    	$data['logo'] = $this->Admin_model->getLogo();
    	$data['count'] = $this->Admin_model->countEvent();
	    $this->load->view('admin/signout', $data);
	}
	public function SignoutTable($table,$id_cust)
	{
		$date = date('Y-m-d');
		$data = ['status' => 'Available'];
		$this->db->where('id_table', $table);
		$this->db->where('id_customer', $id_cust);
		$this->db->where('created_date', $date);
		$this->db->update('sh_rel_table', $data);
		$this->session->set_flashdata('success', 'Sign-out successful. Table is now available');
		redirect('index.php/Admin/signout/');
	}
	public function delete($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			   redirect('index.php/login/admin');
		}
		$this->db->where('id', $id);
		$this->db->delete('sh_m_item_option');
		$this->session->set_flashdata('success','Data add on has been successfully deleted');
		redirect('index.php/Admin/option/');
	}

	public function icon()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'marketing'])) {
			   redirect('index.php/login/admin');
		}
		$data = [
			'home' => $this->Admin_model->getIconSet('home'),
			'footer' => $this->Admin_model->getIconSet('footer'),
			'cn' => $this->Admin_model->getColorCN(),
			'ch' => $this->Admin_model->getColorHD(),
			'cb' => $this->Admin_model->getColorBTN(),
			'logo' => $this->Admin_model->getLogo(),
		];
		$this->load->view('admin/icon',$data);
	}
	public function create_icon($type)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'marketing'])) {
			   redirect('index.php/login/admin');
		}
		$upload_path = 'D:/wamp/www/SO/assets/icon/menu/';

		$max_file_size = 1 * 1024 * 1024;
		$allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];

		$data = [
		    'type' => $type,
		    'title' => $this->input->post('title'),
		    // 'link' => $this->input->post('link'),
		    'is_active' => $this->input->post('is_active'),
		];

		// Check if a file is uploaded
		if (isset($_FILES['icon']) && $_FILES['icon']['error'] == UPLOAD_ERR_OK) {
		    $file_name = $_FILES['icon']['name'];
		    $file_tmp = $_FILES['icon']['tmp_name'];
		    $file_type = $_FILES['icon']['type'];
		    $file_size = $_FILES['icon']['size'];
		    
		    // Generate a new file name
		    $file_name = date('Ymd') . '_' . basename($file_name);

		    // Validate file type
		    if (!in_array($file_type, $allowed_types)) {
		        $this->session->set_flashdata('error', 'File type not allowed. Please upload a PNG or JPG image.');
		        redirect('index.php/Admin/icon/');
		        return; // Stop execution if the file type is not allowed
		    }

		    // Validate file size
		    if ($file_size > $max_file_size) {
		        $this->session->set_flashdata('error', 'File size exceeds the maximum limit of 1 MB.');
		        redirect('index.php/Admin/icon/');
		        return; // Stop execution if the file size is too large
		    }

		    // Attempt to move the uploaded file
		    if (move_uploaded_file($file_tmp, $upload_path . $file_name)) {
		        // If successful, update the image_path in the data array
		        $data['image_path'] = base_url('assets/icon/menu/' . $file_name); // Save the relative URL
		    } else {
		        $this->session->set_flashdata('error', 'Failed to move uploaded file.');
		        redirect('index.php/Admin/icon/');
		        return; // Stop execution if the file move failedp
		    }
		}

		$this->db->insert('sh_m_setup_so', $data);
		$this->session->set_flashdata('success', 'Data icon home has been successfully saved');
		redirect('index.php/Admin/icon/');
	}
	public function update_icon($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'marketing'])) {
			   redirect('index.php/login/admin');
		}
	    $upload_path = 'D:/wamp/www/SO/assets/icon/menu/';
		$max_file_size = 1 * 1024 * 1024; // 1 MB
		$allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];

		// Prepare the data array
		$data = [
		    'title' => $this->input->post('title'),
		    // 'link' => $this->input->post('link'),
		    'is_active' => $this->input->post('is_active'),
		];

		if (isset($_FILES['icon']) && $_FILES['icon']['error'] == UPLOAD_ERR_OK) {
		    $file_name = $_FILES['icon']['name'];
		    $file_tmp = $_FILES['icon']['tmp_name'];
		    $file_type = $_FILES['icon']['type'];
		    $file_size = $_FILES['icon']['size'];
		    
		    $file_name = date('Ymd') . '_' . basename($file_name);

		    if (!in_array($file_type, $allowed_types)) {
		        $this->session->set_flashdata('error', 'File type not allowed. Please upload a PNG or JPG image.');
		        redirect('index.php/Admin/icon/');
		        return; 
		    }

		    if ($file_size > $max_file_size) {
		        $this->session->set_flashdata('error', 'File size exceeds the maximum limit of 1 MB.');
		        redirect('index.php/Admin/icon/');
		        return; 
		    }

		    if (move_uploaded_file($file_tmp, $upload_path . $file_name)) {
		        $data['image_path'] = base_url('assets/icon/menu/' . $file_name); 
		        
		    } else {
		        $this->session->set_flashdata('error', 'Failed to move uploaded file.');
		        redirect('index.php/Admin/icon/');
		        return; 
		    }
		}
		$this->db->where('id', $id);
		$this->db->update('sh_m_setup_so', $data);
		$this->session->set_flashdata('success', 'Icon has been successfully updated.');
		redirect('index.php/Admin/icon/');

	}
	public function color()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'marketing'])) {
			   redirect('index.php/login/admin');
		}
		$data = [
			'cn' => $this->Admin_model->getColorCN(),
			'ch' => $this->Admin_model->getColorHD(),
			'cb' => $this->Admin_model->getColorBTN(),
			'color' => $this->Admin_model->getColor(),
			'logo' => $this->Admin_model->getLogo(),
		];
		$this->load->view('admin/color',$data);
	}
	public function savecolor($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'marketing'])) {
			   redirect('index.php/login/admin');
		}
		$this->load->helper('color_helper');
		$color = $this->input->post('color');
		$rgb = hex_to_rgb($color);
		$rgb_value = $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'];
		function lighten_hex($hex, $percent = 20) {
	        $hex = str_replace('#', '', $hex); // Menghapus simbol # jika ada

	        // Mengonversi HEX ke RGB
	        $r = hexdec(substr($hex, 0, 2));
	        $g = hexdec(substr($hex, 2, 2));
	        $b = hexdec(substr($hex, 4, 2));

	        // Meningkatkan kecerahan RGB
	        $r = min(255, $r + ($r * $percent / 100));
	        $g = min(255, $g + ($g * $percent / 100));
	        $b = min(255, $b + ($b * $percent / 100));

	        // Mengonversi kembali ke HEX
	        $new_hex = sprintf("#%02x%02x%02x", $r, $g, $b);

	        return $new_hex;
	    }
	    function dark_hex($hex, $percent = 20) {
		    // Menghapus simbol # jika ada
		    $hex = str_replace('#', '', $hex);

		    // Mengonversi HEX ke RGB
		    $r = hexdec(substr($hex, 0, 2));
		    $g = hexdec(substr($hex, 2, 2));
		    $b = hexdec(substr($hex, 4, 2));

		    // Menghitung nilai yang lebih gelap
		    $r = max(0, $r - ($r * $percent / 100));
		    $g = max(0, $g - ($g * $percent / 100));
		    $b = max(0, $b - ($b * $percent / 100));

		    // Mengonversi kembali ke HEX
		    $new_hex = sprintf("#%02x%02x%02x", $r, $g, $b);

		    return $new_hex;
		}
		$lighterColor = lighten_hex($color, 30);
		$darkColor = dark_hex($color, 30);
		$data = [
			'color' => $color,
			'lightcolor' => $lighterColor,
			'darkcolor' => $darkColor,
			'rgb' => $rgb_value,
		];
		$this->db->where('id',$id);
		$this->db->update('sh_m_setup_color_so',$data);
		$this->session->set_flashdata('success','Successfully Changed the Self-Order Display Color');
		redirect('index.php/Admin/color/');
	}
	
	public function users()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin'])) {
			   redirect('index.php/login/admin');
		}
		$config2['base_url'] = base_url('index.php/Admin/users');
	    $config2['total_rows'] = $this->Admin_model->countUsers(); 
	    $config2['per_page'] = 10; 
	    $config2['uri_segment'] = 3;
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_link'] = 'First';
	    $config2['last_link'] = 'Last';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['prev_link'] = '&laquo';
	    $config2['prev_tag_open'] = '<li class="page-item">';
	    $config2['prev_tag_close'] = '</li>';
	    $config2['next_link'] = '&raquo';
	    $config2['next_tag_open'] = '<li class="page-item">';
	    $config2['next_tag_close'] = '</li>';
	    $config2['last_tag_open'] = '<li class="page-item">';
	    $config2['last_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['num_tag_open'] = '<li class="page-item">';
	    $config2['num_tag_close'] = '</li>';
	    $config2['attributes'] = array('class' => 'page-link');

	    $this->pagination->initialize($config2);

	    $page2 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

	    $data['users'] = $this->Admin_model->get_users($config2['per_page'], $page2);
    	$data['links2'] = $this->pagination->create_links();
    	$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
    	$data['logo'] = $this->Admin_model->getLogo();
	    $this->load->view('admin/users', $data);
	}
	public function deleteuser($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin'])) {
			   redirect('index.php/login/admin');
		}
		$this->db->where('id', $id);
		$this->db->delete('sh_user_so');
		$this->session->set_flashdata('success','Data user has been successfully deleted');
		redirect('index.php/Admin/users/');
	}
	public function deleteitem($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin'])) {
			   redirect('index.php/login/admin');
		}
		$this->db->where('id', $id);
		$this->db->delete('sh_m_item');
		$this->session->set_flashdata('success','Data item has been successfully deleted');
		redirect('index.php/Admin/item/');
	}
	public function create_users()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin'])) {
			   redirect('index.php/login/admin');
		}
		$data = [
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'role' => $this->input->post('role'),
			'is_active' => 1,
		];
		$this->db->insert('sh_user_so',$data);
		$this->session->set_flashdata('success','Data user has been successfully saved');
		redirect('index.php/Admin/users/');
	}
	public function update_user($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin'])) {
			   redirect('index.php/login/admin');
		}
		$id = $this->input->post('id'); // Pastikan id dikirim dari form

		$data = [
	        'username' => $this->input->post('username'),
	        'role' => $this->input->post('role'),
	        'is_active' => $this->input->post('status'),
	    ];

	    $pw = $this->input->post('password');
	    if (!empty($pw)) {
	        $data['password'] = md5($pw);
	    }

		$this->db->where('id', $id);
		$this->db->update('sh_user_so', $data);

		// Set flashdata untuk menampilkan pesan sukses
		$this->session->set_flashdata('success', 'Data user has been successfully updated');

		// Redirect ke halaman option
		redirect('index.php/Admin/users/');

	}
	
	public function item()
	{
	    $sub = $this->input->get('sub');
	    $div = $this->input->get('divisi');
	    $item_name = $this->input->get('item_name');
	    $sub_category = $sub ? explode('/', $sub)[0] : null;

	    // Validasi akses user
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
	        redirect('index.php/login/admin');
	    }

	    // Data Pagination
	    $page2 = $this->input->get('start') ? intval($this->input->get('start')) : 0;

	    // Konfigurasi pagination
	    $query_string = http_build_query(array_filter([
	        'sub' => $sub,
	        'item_name' => $item_name,
	        'divisi' => $div
	    ]));

	    $config2['base_url'] = base_url('index.php/admin/item') . ($query_string ? '?' . $query_string : '');
	    $config2['total_rows'] = $this->Admin_model->countItem($sub_category, $item_name,$div);
	    $config2['per_page'] = 10;
	    $config2['page_query_string'] = true;
	    $config2['query_string_segment'] = 'start';
	    $config2['suffix'] = $query_string ? '&' . $query_string : '';
	    $config2['first_url'] = $config2['base_url'] . $config2['suffix'];
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['attributes'] = ['class' => 'page-link'];

	    $this->pagination->initialize($config2);
	    // Ambil data berdasarkan filter
	    $data['item'] = $this->Admin_model->get_itemData($config2['per_page'], $page2, $sub_category, $item_name, $div);

	    // Data tambahan untuk view
	    $data['sub'] = $this->Admin_model->get_Category();
	    $data['divisi'] = $this->Admin_model->get_Divisi();
	    $data['divc'] = $div;
	    $data['subc'] = $sub_category;
	    $data['links2'] = $this->pagination->create_links();
	    $data['cn'] = $this->Admin_model->getColorCN();
	    $data['ch'] = $this->Admin_model->getColorHD();
	    $data['cb'] = $this->Admin_model->getColorBTN();
	    $data['logo'] = $this->Admin_model->getLogo();
	    $data['item_name'] = $item_name;
	    $data['allitem'] = $this->Admin_model->get_item();

	    // Load view
	    $this->load->view('admin/item', $data);
	}
	public function item_online()
	{
	    $item_name = $this->input->get('item_name');

	    // Validasi akses user
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
	        redirect('index.php/login/admin');
	    }

	    // Data Pagination
	    $page2 = $this->input->get('start') ? intval($this->input->get('start')) : 0;

	    // Konfigurasi pagination
	    $query_string = http_build_query(array_filter([
	        'item_name' => $item_name,
	    ]));

	    $config2['base_url'] = base_url('index.php/admin/item_online') . ($query_string ? '?' . $query_string : '');
	    $config2['total_rows'] = $this->Admin_model->countItemOnline($item_name);
	    $config2['per_page'] = 10;
	    $config2['page_query_string'] = true;
	    $config2['query_string_segment'] = 'start';
	    $config2['suffix'] = $query_string ? '&' . $query_string : '';
	    $config2['first_url'] = $config2['base_url'] . $config2['suffix'];
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['attributes'] = ['class' => 'page-link'];

	    $this->pagination->initialize($config2);
	    // Ambil data berdasarkan filter
	    $data['item'] = $this->Admin_model->get_itemDataOnline($config2['per_page'], $page2,$item_name);

	    // Data tambahan untuk view
	    $data['sub'] = $this->Admin_model->get_Category();
	    $data['divisi'] = $this->Admin_model->get_Divisi();
	    $data['links2'] = $this->pagination->create_links();
	    $data['cn'] = $this->Admin_model->getColorCN();
	    $data['ch'] = $this->Admin_model->getColorHD();
	    $data['cb'] = $this->Admin_model->getColorBTN();
	    $data['logo'] = $this->Admin_model->getLogo();
	    $data['item_name'] = $item_name;
	    $data['dataitem'] = $this->Admin_model->get_itemDine();
	    $data['dataitemonline'] = $this->Admin_model->get_itemOnline();

	    // Load view
	    $this->load->view('admin/item_online', $data);
	}
	public function insert_item_online()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			   redirect('index.php/login/admin');
		}
		$nodine = $this->input->post('item_dine');
		$noonline = $this->input->post('item_online');
		$itemDine = $this->db->where('no',$nodine)->get('sh_m_item')->row('description');
		$itemOnline = $this->db->where('no',$noonline)->get('sh_m_item')->row('description');
		$data = [
			'item_code_dinein' => $nodine,
			'item_name_dinein' => $itemDine,
			'item_code_online' => $noonline,
			'item_name_online' => $itemOnline,
		];
		$this->db->insert('sh_m_item_online',$data);
		$this->session->set_flashdata('success','Item Online has been successfully saved');
		redirect('index.php/Admin/item_online/');
	}
	public function update_item_online($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
		$nodine = $this->input->post('item_dine');
		$noonline = $this->input->post('item_online');
		$itemDine = $this->db->where('no',$nodine)->get('sh_m_item')->row('description');
		$itemOnline = $this->db->where('no',$noonline)->get('sh_m_item')->row('description');
	    $data = [
		'item_code_dinein' => $nodine,
		'item_name_dinein' => $itemDine,
		'item_code_online' => $noonline,
		'item_name_online' => $itemOnline,
		];
		    $this->db->where('id', $id);
			$this->db->update('sh_m_item_online', $data);

            $this->session->set_flashdata('success', 'Menu item online has been successfully updated');

		redirect('index.php/Admin/item_online/');

	}
	public function deleteitemonline($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin'])) {
			   redirect('index.php/login/admin');
		}
		$this->db->where('id', $id);
		$this->db->delete('sh_m_item_online');
		$this->session->set_flashdata('success','Item online has been successfully deleted');
		redirect('index.php/Admin/item_online/');
	}
	public function update_item($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
            // Baca file yang diunggah langsung dari $_FILES
            $imageType = $_FILES['image_path']['type'];
            $imageData = file_get_contents($_FILES['image_path']['tmp_name']);
            if (!empty($_FILES['image_path']['tmp_name'])) {
            	$image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);
            }else{
            	$image = '';
            }
            
	        $data = [
		      'image_path' => $image,
		      'description' => $this->input->post('description'),
		      'need_stock' => $this->input->post('need_stock'),
		      'stock' => $this->input->post('stock'),
		      'is_active' => $this->input->post('status'),
		      'is_sold_out' => $this->input->post('stock_status'),
		    ];
		    $this->db->where('id', $id);
			$this->db->update('sh_m_item', $data);

            $this->session->set_flashdata('success', 'Menu item has been successfully updated');

		redirect('index.php/Admin/item/');

	}
	public function remove_image($item_code)
	{
	    // 🔒 auth
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin','operation'])) {
	        redirect('index.php/login/admin');
	    }

	    // =========================
	    // 🔥 AMBIL DATA GAMBAR
	    // =========================
	    $dataImage = $this->db
	        ->get_where('sh_m_item_image', ['item_code' => $item_code])
	        ->row();

	    // =========================
	    // 🔥 HAPUS FILE DI DIRECTORY
	    // =========================
	    if ($dataImage && !empty($dataImage->image_path)) {
	        $filePath = FCPATH . $dataImage->image_path;

	        if (file_exists($filePath)) {
	            unlink($filePath); // 🗑️ hapus file
	        }
	    }

	    // =========================
	    // 🔥 UPDATE DB (KOSONGKAN PATH)
	    // =========================
	    $this->db->where('item_code', $item_code);
	    $this->db->update('sh_m_item_image', [
	        'image_path' => ''
	    ]);

	    $this->session->set_flashdata('success', 'Image removed successfully.');

	    redirect('index.php/Admin/item/');
	}

	public function itemevent()
	{
	    $sub = $this->input->get('sub');
	    // $div = $this->input->get('divisi');
	    $item_name = $this->input->get('item_name');
	    $sub_category = $sub ? explode('/', $sub)[0] : null;

	    // Validasi akses user
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
	        redirect('index.php/login/admin');
	    }

	    // Data Pagination
	    $page2 = $this->input->get('start') ? intval($this->input->get('start')) : 0;

	    // Konfigurasi pagination
	    $query_string = http_build_query(array_filter([
	        'sub' => $sub,
	        'item_name' => $item_name,
	        // 'divisi' => $div
	    ]));

	    $config2['base_url'] = base_url('index.php/admin/itemevent') . ($query_string ? '?' . $query_string : '');
	    // $config2['total_rows'] = $this->Admin_model->countItem($sub_category, $item_name,$div);
	    $config2['total_rows'] = $this->Admin_model->countItemEvent($sub_category, $item_name);
	    $config2['per_page'] = 10;
	    $config2['page_query_string'] = true;
	    $config2['query_string_segment'] = 'start';
	    $config2['suffix'] = $query_string ? '&' . $query_string : '';
	    $config2['first_url'] = $config2['base_url'] . $config2['suffix'];
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['attributes'] = ['class' => 'page-link'];

	    $this->pagination->initialize($config2);

	    // Ambil data berdasarkan filter
	    // $data['item'] = $this->Admin_model->get_itemData($config2['per_page'], $page2, $sub_category, $item_name, $div);
	    $data['item'] = $this->Admin_model->get_itemEventData($config2['per_page'], $page2, $sub_category, $item_name);
	    $data['dataitem'] = $this->Admin_model->getitem();
	    // Data tambahan untuk view
	    $data['sub'] = $this->Admin_model->get_Category();
	    $data['divisi'] = $this->Admin_model->get_Divisi();
	    // $data['divc'] = $div;
	    $data['subc'] = $sub_category;
	    $data['links2'] = $this->pagination->create_links();
	    $data['cn'] = $this->Admin_model->getColorCN();
	    $data['ch'] = $this->Admin_model->getColorHD();
	    $data['cb'] = $this->Admin_model->getColorBTN();
	    $data['logo'] = $this->Admin_model->getLogo();
	    $data['item_name'] = $item_name;

	    // Load view
	    $this->load->view('admin/itemevent', $data);
	}
	public function tambahitemevent($value='')
	{
		$data = [
			'item_code' => $this->input->post('item_code'),
			'date_from' => $this->input->post('date_from'),
			'date_to' => $this->input->post('date_to'),
			'time_from' => $this->input->post('time_from'),
			'time_to' => $this->input->post('time_to'),
		];

		$this->db->insert('sh_m_item_event',$data);
		$this->session->set_flashdata('success', 'Menu item event has been successfully saved');

		redirect('index.php/Admin/itemevent/');
	}
	public function update_itemevent($id)
	{
	    $data = [
			'item_code' => $this->input->post('item_code'),
			'date_from' => $this->input->post('date_from'),
			'date_to' => $this->input->post('date_to'),
			'time_from' => $this->input->post('time_from'),
			'time_to' => $this->input->post('time_to'),
		];

		$this->db->where('id', $id);
		$this->db->update('sh_m_item_event', $data);
        $this->session->set_flashdata('success', 'Menu item event has been successfully updated');

		redirect('index.php/Admin/itemevent/');

	}
	public function deleteitemevent($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('sh_m_item_event');
		$this->session->set_flashdata('success','Data item event has been successfully deleted');
		redirect('index.php/Admin/itemevent/');
	}
	
	public function package()
	{
	    $sub = $this->input->get('sub');
	    $item_name = $this->input->get('item_name');
	   
	    // Validasi akses user
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
	        redirect('index.php/login/admin');
	    }

	    $page2 = $this->input->get('start') ? intval($this->input->get('start')) : 0;

	    $query_string = http_build_query(array_filter([
	        'item_name' => $item_name,
	        // 'divisi' => $div
	    ]));

	    $config2['base_url'] = base_url('index.php/admin/package') . ($query_string ? '?' . $query_string : '');
	    // $config2['total_rows'] = $this->Admin_model->countItem($sub_category, $item_name,$div);
	    $config2['total_rows'] = $this->Admin_model->countPackage($item_name);
	    $config2['per_page'] = 10;
	    $config2['page_query_string'] = true;
	    $config2['query_string_segment'] = 'start';
	    $config2['suffix'] = $query_string ? '&' . $query_string : '';
	    $config2['first_url'] = $config2['base_url'] . $config2['suffix'];
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['attributes'] = ['class' => 'page-link'];

	    $this->pagination->initialize($config2);

	    // Ambil data berdasarkan filter
	    // $data['item'] = $this->Admin_model->get_itemData($config2['per_page'], $page2, $sub_category, $item_name, $div);
	    $data['item'] = $this->Admin_model->get_Package($config2['per_page'], $page2, $item_name);
	    $data['dataitem'] = $this->Admin_model->getitem('package');
	    // Data tambahan untuk view
	    $data['sub'] = $this->Admin_model->get_Category();
	    $data['divisi'] = $this->Admin_model->get_Divisi();
	    $data['links2'] = $this->pagination->create_links();
	    $data['cn'] = $this->Admin_model->getColorCN();
	    $data['ch'] = $this->Admin_model->getColorHD();
	    $data['cb'] = $this->Admin_model->getColorBTN();
	    $data['logo'] = $this->Admin_model->getLogo();
	    $data['item_name'] = $item_name;

	    // Load view
	    $this->load->view('admin/package', $data);
	}
	public function load_item_package($parent_code) {
	    $data['itempackage'] = $this->Admin_model->getitempackage($parent_code);
	    
	    $this->load->view('admin/table/table_item_package', $data); // Buat view baru untuk list item
	}

	public function tambahpackage($value='')
	{
		$this->db->select('*');
		$this->db->from('sh_m_cabang');
		$this->db->limit(1);
		$c = $this->db->get();
		$cabang = $c->row();

		$this->db->select('no');
		$this->db->from('sh_m_item');
		$this->db->limit(1);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();

		$last_no = $query->row('no');

		if ($last_no) {
		    // Pisahkan prefix "MG-" dan angkanya
		    preg_match('/^([A-Z]+)-(\d+)$/', $last_no, $matches);
		    
		    if (!empty($matches)) {
		        $prefix = $matches[1]; // "MG"
		        $number = (int)$matches[2] + 1; // Tambah 1 ke angka
		        
		        // Format ulang nomor dengan leading zeros (padding 7 digit)
		        $new_no = sprintf('%s-%07d', $prefix, $number);
		    } else {
		        // Jika format tidak sesuai, gunakan default
		        $new_no = 'MG-0000001';
		    }
		} else {
		    // Jika tidak ada data, mulai dari awal
		    $new_no = 'MG-0000001';
		}

		
        if (!empty($_FILES['image_path']['tmp_name'])) {
        	$imageType = $_FILES['image_path']['type'];
        	$imageData = file_get_contents($_FILES['image_path']['tmp_name']);
         	$image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);
        }else{
         $image = '';
        }
		
		$data = [
			'no' => $new_no,
			'description' => $this->input->post('description'),
			'image_path' => $image,
			'show_list' => 1,
			'dine_in_menu' => 1,
			'take_away_menu' => 1,
			'monitor1' => 1,
			'harga_weekday' => $this->input->post('price'),
			'harga_weekend' => $this->input->post('price'),
			'harga_holiday' => $this->input->post('price'),
			'cabang' => $cabang->id,
			'need_stock' => $this->input->post('need_stock'),
			'stock' => $this->input->post('stock'),
			'is_active' => 1,
			'is_active_so' => 1,
			'is_paket_so' => 1,
		];

		$this->db->insert('sh_m_item',$data);
		$this->session->set_flashdata('success', 'Package has been successfully saved');

		redirect('index.php/Admin/package/');
	}
	public function update_package($id)
	{
		$data = [
		    'description' => $this->input->post('description'),
		    'show_list' => 1,
		    'dine_in_menu' => 1,
		    'take_away_menu' => 1,
		    'monitor1' => 1,
		    'harga_weekday' => $this->input->post('price'),
		    'harga_weekend' => $this->input->post('price'),
		    'harga_holiday' => $this->input->post('price'),
		    'is_active' => $this->input->post('is_active'),
		    'is_active_so' => $this->input->post('is_active'),
		];

		// Jika ada gambar yang diunggah, tambahkan ke `$data`
		if (!empty($_FILES['image_path']['tmp_name'])) {
		    $imageType = $_FILES['image_path']['type'];
		    $imageData = file_get_contents($_FILES['image_path']['tmp_name']);
		    $image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);

		    $data['image_path'] = $image; // Tambahkan hanya jika ada gambar
		}

		// Lanjutkan dengan proses update atau insert
		$this->db->where('id', $id);
		$this->db->update('sh_m_item', $data);
        $this->session->set_flashdata('success', 'Package has been successfully updated');

		redirect('index.php/Admin/package/');

	}
	public function deletepackage($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('sh_m_item');
		$this->session->set_flashdata('success','Package has been successfully deleted');
		redirect('index.php/Admin/package/');
	}

	public function tambahitempackage()
	{
	    $data = [
	        'parent_code' => $this->input->post('parent_code'),
	        'item_code' => $this->input->post('item_code'),
	        'varian_category' => $this->input->post('varian_category'),
	        'max_qty' => $this->input->post('max_qty'),
	        'is_active' => 1,
	    ];
	    $cekedit = $this->input->post('edit_id');
	    if ($cekedit) {
	    	$this->db->where('id', $cekedit);
			$this->db->update('sh_m_varian_option', $data);
	    }else{
	    	$this->db->insert('sh_m_varian_option', $data);	
	    }

	    
	    $this->session->set_flashdata('success','Package has been successfully added');
		redirect('index.php/Admin/package/');
	}


	public function update_itempackage($id)
	{
		$data = [
		    'description' => $this->input->post('description'),
		    'show_list' => 1,
		    'dine_in_menu' => 1,
		    'take_away_menu' => 1,
		    'monitor1' => 1,
		    'harga_weekday' => $this->input->post('price'),
		    'harga_weekend' => $this->input->post('price'),
		    'harga_holiday' => $this->input->post('price'),
		    'is_active' => $this->input->post('is_active'),
		    'is_active_so' => $this->input->post('is_active'),
		];

		// Jika ada gambar yang diunggah, tambahkan ke `$data`
		if (!empty($_FILES['image_path']['tmp_name'])) {
		    $imageType = $_FILES['image_path']['type'];
		    $imageData = file_get_contents($_FILES['image_path']['tmp_name']);
		    $image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);

		    $data['image_path'] = $image; // Tambahkan hanya jika ada gambar
		}

		// Lanjutkan dengan proses update atau insert
		$this->db->where('id', $id);
		$this->db->update('sh_m_item', $data);
        $this->session->set_flashdata('success', 'Package has been successfully updated');

		redirect('index.php/Admin/package/');

	}
	public function deleteitempackage($id)
	{
	    // Pastikan ID tidak kosong atau null
	    if (!$id) {
	        echo json_encode(['status' => 'error', 'message' => 'Invalid item ID']);
	        return;
	    }

	    // Cek apakah data dengan ID tersebut ada
	    $this->db->where('id', $id);
	    $query = $this->db->get('sh_m_varian_option');

	    if ($query->num_rows() > 0) {
	        // Hapus data dari database
	        $this->db->where('id', $id);
	        $this->db->delete('sh_m_varian_option');

	        echo json_encode(['status' => 'success', 'message' => 'Item Package has been successfully deleted']);
	    } else {
	        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
	    }
	}


	public function logo()
	{
		$logo = $this->Admin_model->getLogo();
		$data = [
			'logo' => $logo,
			'cn' => $this->Admin_model->getColorCN(),
			'ch' => $this->Admin_model->getColorHD(),
			'cb' => $this->Admin_model->getColorBTN(),
		];
		$this->load->view('admin/logo',$data);
	}
	public function simpan_logo()
	{
		$data = [
			'type' => 'logo',
			'is_active' => 1,
		];

		$this->db->insert('sh_m_setup_so',$data);
	}
	public function update_logo($id)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','marketing'])) {
			   redirect('index.php/login/admin');
		}
            // Baca file yang diunggah langsung dari $_FILES
            $imageType = $_FILES['image_path']['type'];
            $imageData = file_get_contents($_FILES['image_path']['tmp_name']);
            if (!empty($_FILES['image_path']['tmp_name'])) {
            	$image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);
            }else{
            	$logo = $this->Admin_model->getLogo();
            	$image = $logo->image_path;
            }
            
	        $data = [
		      'image_path' => $image,
		      'title' => $this->input->post('title'),
		      'open' => $this->input->post('open'),
		      'close'	=> $this->input->post('close'),
		    ];
		    // var_dump($data);exit();
		    $this->db->where('id', $id);
			$this->db->update('sh_m_setup_so', $data);

            $this->session->set_flashdata('success', 'has been successfully updated');

		redirect('index.php/Admin/logo/');

	}
	public function UpdateStatusStockOLD($id,$type)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
		$item = $this->db->where('id', $id)->get('sh_m_item')->row();
            $imageType = $_FILES['image_path']['type'];
            $imageData = file_get_contents($_FILES['image_path']['tmp_name']);
            if (!empty($_FILES['image_path']['tmp_name'])) {
            	$image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);
            }else{
            	$image = '';
            }
         	if ($type == 'sold') {
         	   	$status = 1;
         	   	$s = 'sold out';
         	   	$stock = 0;
         	   	$description = "Stock updated from ".intval($item->stock)." to ".intval($stock)." and set item status to Sold Out";
         	   	$total_stock = intval($item->stock) - intval($stock);
         	}else{
         		$status = 0;
         		$s = 'available';
         		$stock= 1;
         		$description = "Stock updated from ".intval($item->stock)." to ".intval($stock)." and set item status to Available";
         		$total_stock = intval($stock) - intval($item->stock);
         	}
         	
        	$stokOLD = $item->stock;   
	        $data = [
		      'is_sold_out' => $status,
		      'stock' => $stock,
		    ];
		    $this->db->where('id', $id);
			$result = $this->db->update('sh_m_item', $data);
			if ($result) {
				$cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');
				$datastok = [
		            'log_type' => 'Update Stock',
		            'cabang' => $cabang,
		            'item_code' => $item->no,
		            'stock_before' => $stokOLD,
		            'stock_after' => $stock,
		            'difference' => $total_stock,
		            'stock_entry' => date('Y-m-d H:i:s'),
		            'username' => $this->session->userdata('usernameadmin'),
		            'description' => $description,
		        ];
		        $this->db->insert('sh_stok_logs', $datastok);
			}

            $this->session->set_flashdata('success', 'Successfully changed the item status to '.$s);

		redirect('index.php/Admin/item/');
	}
	public function UpdateStatusStock()
{
    $id     = $this->input->post('id');
    $status = $this->input->post('status');  // 0 = Available, 1 = Sold Out
    $flagns = $this->input->post('flagns');  // 0 unlimited, 1 limited
    $stock  = $this->input->post('stock');

    // Ambil item utama
    $item = $this->db->where('id', $id)->get('sh_m_item')->row();
    if (!$item) {
        echo json_encode(['success' => false, 'error' => 'Item not found']);
        return;
    }

    // Cek di tabel mapping online
    $itemOnline = $this->db
        ->group_start()
            ->where('item_code_dinein', $item->no)
            ->or_where('item_code_online', $item->no)
        ->group_end()
        ->get('sh_m_item_online')
        ->row();

    $ns      = ($item->need_stock == 1) ? 1 : 0;
    $stokOLD = (int)$item->stock;

    // Jika status = sold out → paksa stock API = 0
    $stock_api = ($status == 1) ? 0 : (int)$stock;

    // =====================================================
    // ❗ PERBAIKAN: DETEKSI ITEM ONLINE TANPA MAPPING
    // =====================================================
    $isOnline       = false;
    $itemCodeOnline = null;
    $itemNameOnline = null;
    $itemCodeDinein = null;

    if ($itemOnline) {
        // CASE: ada mapping
        $isOnline       = true;
        $itemCodeOnline = $itemOnline->item_code_online;
        $itemNameOnline = $itemOnline->item_name_online;
        $itemCodeDinein = $itemOnline->item_code_dinein;

    } elseif ($item->sub_category == "Online") {
        // CASE: tidak ada mapping, tapi ini item online
        $isOnline       = true;
        $itemCodeOnline = $item->no;
        $itemNameOnline = $item->description;
        $itemCodeDinein = $item->no;
    }

    // =====================================================
    // PROSES POSTING API JIKA ITEM ONLINE
    // =====================================================
    if ($isOnline) {

        $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
        if (!$setup) {
            echo json_encode(['success' => false, 'error' => 'Setup API tidak ditemukan']);
            return;
        }

        $token = $this->get_token();
        if (!$token) {
            echo json_encode(['success' => false, 'error' => 'Gagal mengambil token API']);
            return;
        }

        $outletId = (int)$setup->id_user_outlet;

        // Ambil stock_before dari API
        $stock_before = 0;
        $url = "http://202.10.37.16:9001/api/delivery/menu/stock?outlet_id=" . $outletId;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$token}",
                "Accept: application/json"
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $result = json_decode($response, true);
            if (!empty($result['data'])) {
                foreach ($result['data'] as $row) {
                    if (!empty($row['item_code']) && $row['item_code'] == $itemCodeOnline) {
                        $stock_before = isset($row['stock']) ? (int)$row['stock'] : 0;
                        break;
                    }
                }
            }
        }

        // Payload API
        $payload = [
            "items" => [
                [
                    "outlet_id" => $outletId,
                    "item_code" => $itemCodeOnline,
                    "item_name" => $itemNameOnline,
                    "stock"     => $stock_api
                ]
            ]
        ];

        // POSTING → sudah diperbaiki: 200/201/404 dianggap sukses
        $postingSuccess = $this->posting_menu($payload);

        // Log online
        $this->insert_online_log(
            $postingSuccess,
            $itemCodeOnline,
            $stock_before,
            $stock_api,
            $ns
        );

        if (!$postingSuccess) {
            echo json_encode(['success' => false, 'error' => 'Posting API gagal, update dibatalkan']);
            return;
        }

        // Update item dine-in
        $this->db->where('no', $itemCodeDinein);

    } else {
        // Offline → update berdasarkan id
        $this->db->where('id', $id);
    }

    // =====================================================
    // UPDATE DATABASE
    // =====================================================
    $newStock = ($status == 1) ? 0 : $stock;

    $data = [
        'is_sold_out' => $status,
        'stock'       => $newStock
    ];

    $this->db->update('sh_m_item', $data);

    // =====================================================
    // LOG LOKAL
    // =====================================================
    $description = ($status == 1)
        ? "Stock updated from $stokOLD to 0 and set item status to Sold Out"
        : "Stock updated from $stokOLD to $stock and set item status to Available";

    $difference = $newStock - $stokOLD;

    $cabang = $this->db->order_by('id', 'desc')->limit(1)->get('sh_m_cabang')->row('id');

    $datastok = [
        'log_type'     => 'Update Stock',
        'cabang'       => $cabang,
        'item_code'    => $item->no,
        'stock_before' => $stokOLD,
        'stock_after'  => $newStock,
        'difference'   => $difference,
        'stock_entry'  => date('Y-m-d H:i:s'),
        'username'     => $this->session->userdata('usernameadmin'),
        'description'  => $description,
    ];

    $this->db->insert('sh_stok_logs', $datastok);

    // SUCCESS RESPONSE
    echo json_encode([
        'success'   => true,
        'newStatus' => $status,
        'newStock'  => $newStock
    ]);
}

	public function UpdateStockStatusOLD($id,$type)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
			$item = $this->db->where('id', $id)->get('sh_m_item')->row();
			$stokOLD = intval($item->stock);
         	if ($type == 'limited') {
         	   	$status = 1;
         	   	$ns = 1;
         	   	$s = 'limited';
         	   	$stock = 0;
         	   	$description = "Stock updated from ".intval($item->stock)." to ".intval($stock)." and set item status to Limited";
         	   	$total_stock = intval($item->stock) - intval($stock);
         	}else{
         		$status = 0;
         		$ns = 0;
         		$s = 'unlimited';
         		$stock=0;
         		$description = "Stock updated from ".intval($item->stock)." to ".intval($stock)." and set item status to Unlimited";
         	   	$total_stock = intval($item->stock) - intval($stock);
         	}   
	        $data = [
		      'is_sold_out' => $status,
		      'need_stock' => $ns,
		      'stock' => $stock,
		    ];
		    $this->db->where('id', $id);
			$result = $this->db->update('sh_m_item', $data);
			if ($result) {
				$cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');
				$datastok = [
		            'log_type' => 'Update Stock',
		            'cabang' => $cabang,
		            'item_code' => $item->no,
		            'stock_before' => $stokOLD,
		            'stock_after' => $stock,
		            'difference' => $total_stock,
		            'stock_entry' => date('Y-m-d H:i:s'),
		            'username' => $this->session->userdata('usernameadmin'),
		            'description' => $description,
		        ];
		        $this->db->insert('sh_stok_logs', $datastok);
			}

            $this->session->set_flashdata('success', 'Successfully changed the stock status to '.$s);

		redirect('index.php/Admin/item/');
	}
	public function UpdateStockStatus()
	{
	    // Retrieve the POST data
	    $id = $this->input->post('id');
	    $status = $this->input->post('status'); // Expected values: 'limited' or 'unlimited'

	    // Fetch the item data from the database
	    $item = $this->db->where('id', $id)->get('sh_m_item')->row();

	    if (!$item) {
	        // If the item doesn't exist, return an error response
	        echo json_encode(['success' => false, 'error' => 'Item not found']);
	        return;
	    }

	    // Determine the new stock status based on the input
	    $newNeedStock = ($status === 'limited') ? 1 : 0;
	    $isSoldOut = ($status === 'limited') ? 1 : 0;
	    $newStock = ($status === 'limited') ? 0 : $item->stock;

	    // Prepare description for logging
	    $description = "Stock updated from " . intval($item->stock) . " to " . $newStock . " and set item status to " . ucfirst($status);

	    // Calculate the stock difference
	    $total_stock = $newStock - intval($item->stock);

	    // Update the item in the database
	    $data = [
	        'need_stock' => $newNeedStock,
	        'is_sold_out' => $isSoldOut,
	        'stock' => $newStock
	    ];

	    $this->db->where('id', $id);
	    $result = $this->db->update('sh_m_item', $data);

	    if ($result) {
	        // Log the stock update if the update was successful
	        $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');

	        $datastok = [
	            'log_type' => 'Update Stock',
	            'cabang' => $cabang,
	            'item_code' => $item->no,
	            'stock_before' => intval($item->stock),
	            'stock_after' => $newStock,
	            'difference' => $total_stock,
	            'stock_entry' => date('Y-m-d H:i:s'),
	            'username' => $this->session->userdata('usernameadmin'),
	            'description' => $description,
	        ];

	        $this->db->insert('sh_stok_logs', $datastok);

	        // Send a JSON success response
	        echo json_encode([
	            'success' => true,
	            'newStatus' => $status,
	            'isSoldOut' => $isSoldOut,
	            'newNeedStock' => $newNeedStock
	        ]);
	    } else {
	        // If the update failed, return an error response
	        echo json_encode([
	            'success' => false,
	            'error' => 'Database update failed'
	        ]);
	    }
	}

	public function UpdateStatus() 
	{
	    $id = $this->input->post('id');
	    $status = $this->input->post('status');

	    // Update status di database
	    $this->db->set('is_active', $status);
	    $this->db->where('id', $id);
	    $update = $this->db->update('sh_m_item'); // Sesuaikan dengan nama tabel

	    if ($update) {
	        echo json_encode(['success' => true]);
	    } else {
	        echo json_encode(['success' => false, 'error' => 'Database update failed']);
	    }
	}

	public function insertRemark()
	{
		$id = $this->input->post('id');
        $remark = $this->input->post('remark');
        $data = array(
            'remarks' => $remark,
        );

        // Query update
        $this->db->where('id', $id);
        return $this->db->update('sh_m_item', $data);
	}
	/**
 * Ambil token OAuth dan simpan ke tabel sh_m_setup.access_token_stock
 * Return: access_token string | false
 */
public function get_token()
{
    $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
    if (!$setup) return false;

    $url = trim($setup->stock_login_url);
    $postData = [
        'grant_type'    => 'client_credentials',
        'client_id'     => $setup->stock_login_user,
        'client_secret' => $setup->stock_login_password
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        log_message('error', 'get_token curl error: ' . $curlErr);
        return false;
    }

    $result = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        log_message('error', 'get_token json decode error: ' . json_last_error_msg());
        return false;
    }

    if (!empty($result['access_token'])) {
        $this->db->limit(1)->update('sh_m_setup', [
            'access_token_stock' => $result['access_token']
        ]);
        return $result['access_token'];
    }

    return false;
}

	/**
	 * Posting menu ke API POS.
	 * $payload = ['items' => [ ... ]]
	 * Return: boolean
	 */
	public function posting_menu($payload)
{
    $setup = $this->db->limit(1)->get('sh_m_setup')->row();
    if (!$setup || empty($setup->access_token_stock)) return false;

    $token = $setup->access_token_stock;
    $url = "http://202.10.37.16:9001/api/pos/menu/posting";

    $jsonData = json_encode($payload);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json",
            "Accept: application/json"
        ],
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        log_message('error', 'posting_menu curl error: ' . $curlErr);
        return false;
    }

    // ================
    // PERBAIKAN UTAMA
    // ================
    // Anggap sukses jika API mengembalikan:
    // 200 / 201  = sukses normal
    // 404        = item tidak ditemukan, tapi tetap tidak perlu menggagalkan update stok
    if (in_array((int)$httpCode, [200, 201, 404], true)) {
        return true;
    }

    return false;
}


/**
 * Insert log ke tabel sh_online_item_log
 */
public function insert_online_log($postingSuccess, $item_code_online, $stock_before, $stock_after, $ns = 0)
{
    $status = $postingSuccess ? 'Success' : 'Failed';

    $setup   = $this->db->where('id', 1)->get('sh_m_setup')->row();
    $outletId = $setup ? (int)$setup->id_user_outlet : 0;

    $stock_before = (int)$stock_before;
    $stock_after  = (int)$stock_after;
    $difference   = $stock_after - $stock_before;

    if ($ns == 1) {
        $description = "Stock updated from $stock_before to $stock_after";
    } else {
        $description = "Stock updated from $stock_before to $stock_after";
    }

    $logData = [
        'log_type'      => 'Update Stock',
        'status'        => $status,
        'cabang'        => $outletId,
        'item_code'     => $item_code_online,
        'stock_before' => $stock_before,
        'stock_after'  => $stock_after,
        'difference'   => $difference,
        'stock_entry'  => date('Y-m-d H:i:s'),
        'username'     => $this->session->userdata('usernameadmin'),
        'description'  => $description
    ];

    return (bool)$this->db->insert('sh_online_item_log', $logData);
}



/**
 * Update Stock
 */
public function UpdateStock()
{
    $id    = $this->input->post('id');
    $stock = (int)$this->input->post('stock');
    $flag  = (int)$this->input->post('flag');

    $item = $this->db->where('id', $id)->get('sh_m_item')->row();
    if (!$item) {
        return ['status' => false, 'message' => 'Item tidak ditemukan'];
    }

    $itemOnline = $this->db
        ->group_start()
            ->where('item_code_dinein', $item->no)
            ->or_where('item_code_online', $item->no)
        ->group_end()
        ->get('sh_m_item_online')
        ->row();

    $ns       = ($item->need_stock == 1) ? 1 : 0;
    $stokOLD  = (int)$item->stock;
    $cekstok  = $stokOLD - $stock;

    $isOnline       = false;
    $itemCodeOnline = null;
    $itemNameOnline = null;

    if ($itemOnline) {
        $isOnline       = true;
        $itemCodeOnline = $itemOnline->item_code_online;
        $itemNameOnline = $itemOnline->item_name_online;

    } else if ($item->sub_category == 'Online') {
        // CASE Perbaikan:
        // Jika tidak ada mapping tapi sub_category = Online → tetap jadi online
        $isOnline       = true;
        $itemCodeOnline = $item->no;
        $itemNameOnline = $item->description;
    }

    // ================
    // PROSES ITEM ONLINE
    // ================
    if ($isOnline) {

        $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
        if (!$setup) {
            return ['status' => false, 'message' => 'Setup API tidak ditemukan'];
        }

        $token = $this->get_token();
        if (!$token) {
            return ['status' => false, 'message' => 'Gagal mengambil token API'];
        }

        $outletId = (int)$setup->id_user_outlet;

        // AMBIL STOCK BEFORE API
        $stock_before = 0;
        $url = "http://202.10.37.16:9001/api/delivery/menu/stock?outlet_id=" . $outletId;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$token}",
                "Accept: application/json"
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $result = json_decode($response, true);
            if (!empty($result['data'])) {
                foreach ($result['data'] as $row) {
                    if (!empty($row['item_code']) && $row['item_code'] == $itemCodeOnline) {
                        $stock_before = isset($row['stock']) ? (int)$row['stock'] : 0;
                        break;
                    }
                }
            }
        }

        // PAYLOAD POSTING API
        $payload = [
            "items" => [
                [
                    "outlet_id" => $outletId,
                    "item_code" => $itemCodeOnline,
                    "item_name" => $itemNameOnline,
                    "stock"     => $stock
                ]
            ]
        ];

        // POSTING
        $postingSuccess = $this->posting_menu($payload);

        // LOG API
        $this->insert_online_log(
            $postingSuccess,
            $itemCodeOnline,
            $stock_before,
            $stock,
            $ns
        );

        // PERBAIKAN: jika gagal tapi HTTP 404, postingSuccess tetap TRUE
        if (!$postingSuccess) {
            return ['status' => false, 'message' => 'Posting ke API gagal, update dibatalkan'];
        }

        $this->db->where('no', $item->no);

    } else {
        // OFFLINE UPDATE
        $this->db->where('id', $id);
    }

    // HITUNG STATUS ITEM
    if ($flag == 0) {
        $status      = ($stock >= 0) ? 0 : 1;
        $stockstatus = ($stock >= 1) ? 1 : 0;
    } else {
        $status      = ($stock > 0) ? 0 : 1;
        $stockstatus = 1;
    }

    // UPDATE DB
    $data = [
        'stock'       => $stock,
        'is_sold_out' => $status,
        'need_stock'  => $stockstatus,
    ];

    $updateResult = $this->db->update('sh_m_item', $data);

    if (!$updateResult) {
        return ['status' => false, 'message' => 'Gagal update database'];
    }

    // LOG STOK
    if ($ns == 1) {
        if ($stock == 0) {
            $description = "Stock updated from $stokOLD to $stock and set item status to Sold Out";
        } else {
            if ($item->is_sold_out == 0) {
                if ($cekstok == 0) {
                    $description = "Stock updated from $stokOLD to $stock and set item status to Sold Out";
                } else {
                    $description = "Stock updated from $stokOLD to $stock";
                }
            } else {
                if ($cekstok == 0) {
                    $description = "Stock updated from $stokOLD to $stock and set item status to Sold Out";
                } else {
                    $description = "Stock updated from $stokOLD to $stock and set item status to Available";
                }
            }
        }
    } else {
        $description = "Stock updated from $stokOLD to $stock and set status to Limited";
    }

    $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');

    $datastok = [
        'log_type'     => 'Update Stock',
        'cabang'       => $cabang,
        'item_code'    => $item->no,
        'stock_before' => $stokOLD,
        'stock_after'  => $stock,
        'difference'   => abs($stock - $stokOLD),
        'stock_entry'  => date('Y-m-d H:i:s'),
        'username'     => $this->session->userdata('usernameadmin'),
        'description'  => $description,
    ];

    $this->db->insert('sh_stok_logs', $datastok);

    $updateitem = [
        'stock_update_date' => date('Y-m-d H:i:s'),
        'stock_update_by'   => $this->session->userdata('usernameadmin'),
    ];

    $this->db->where('no', $item->no);
    $this->db->update('sh_m_item', $updateitem);

    return ['status' => true, 'message' => 'Update stok berhasil'];
}




	// public function UpdateStock()
	// {
	//     $id    = $this->input->post('id');
	//     $stock = $this->input->post('stock');
	//     $flag  = $this->input->post('flag');

	//     $item = $this->db->where('id', $id)->get('sh_m_item')->row();

	//     if (!$item) {
	//         return false;
	//     }

	//     // ==========================
	//     // ✅ VALIDASI KHUSUS ONLINE
	//     // ==========================
	//     if (strtolower($item->sub_category) == 'online') {

	//         // ✅ Ambil token dulu
	//         $token = $this->get_token();
	//         if (!$token) {
	//             return [
	//                 'status'  => false,
	//                 'message' => 'Gagal mengambil token API'
	//             ];
	//         }
	//         $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
	//         // ✅ Buat payload dari item ini
	//         $payload = [
	//             "items" => [
	//                 [
	//                     "outlet_id" => $setup->id_user_outlet,
	//                     "item_code" => $item->no,
	//                     "item_name" => $item->description,
	//                     "stock"     => (int)$stock
	//                 ]
	//             ]
	//         ];

	//         // ✅ Posting ke API
	//         $posting = $this->posting_menu($payload);

	//         if (!$posting) {
	//             return [
	//                 'status'  => false,
	//                 'message' => 'Posting ke API gagal, update dibatalkan'
	//             ];
	//         }
	//     }

	//     // ==========================
	//     // ✅ PROSES UPDATE DB (SETELAH API BERHASIL)
	//     // ==========================

	//     if ($flag == 0) {
	//         $status       = ($stock >= 0) ? 0 : 1;
	//         $stockstatus  = ($stock >= 1) ? 1 : 0;
	//     } else {
	//         $status       = ($stock > 0) ? 0 : 1;
	//         $stockstatus  = 1;
	//     }

	//     $stokOLD = $item->stock;

	//     $data = [
	//         'stock'       => $stock,
	//         'is_sold_out' => $status,
	//         'need_stock'  => $stockstatus,
	//     ];

	//     $this->db->where('id', $id);
	//     $result = $this->db->update('sh_m_item', $data);

	//     return $result;
	// }




	public function UpdateStockOLDD($value = '')
	{
	    $id = $this->input->post('id');
        $stock = $this->input->post('stock');
        $flag = $this->input->post('flag');

        if ($flag == 0) {
        	if ($stock >= 0) {
	        	$status = 0;
	        }else{
	        	$status = 1;
	        }
	        if ($stock >= 1) {
	        	$stockstatus = 1;
	        }else{
	        	$stockstatus = 0;
	        }
        }else{
        	if ($stock > 0) {
	        	$status = 0;
	        }else{
	        	$status = 1;
	        }
	        if ($stock >= 1) {
	        	$stockstatus = 1;
	        }else{
	        	$stockstatus = 1;
	        }
        }
        $item = $this->db->where('id', $id)->get('sh_m_item')->row();
			if (!$item) {
			    return false; // Jika item tidak ditemukan, hentikan fungsi
			}

			$ns = ($item->need_stock == 1) ? 1 : 0; // Tentukan apakah item membutuhkan stok
			$stokOLD = $item->stock;

			$data = [
			    'stock' => $stock,
			    'is_sold_out' => $status,
			    'need_stock' => $stockstatus,
			];

			$this->db->where('id', $id);
			$result = $this->db->update('sh_m_item', $data);

			if ($result) {
			    // Hitung perbedaan stok
			    $cekstok = $stokOLD - $stock;

			    // Tentukan deskripsi log berdasarkan kondisi
			    if ($ns == 1) { 
			    	if ($stock == 0) {
			    		$description = "Stock updated from $stokOLD to $stock and set item status to Sold Out";
			    	}else{
			    		if ($item->is_sold_out == 0) { 
				            if ($cekstok == 0) {
				                $description = "Stock updated from $stokOLD to $stock and set item status to Sold Out";
				            } else {
				                $description = "Stock updated from $stokOLD to $stock";
				            }
				        } else { // Jika awalnya habis
				            if ($cekstok == 0) {
				                $description = "Stock updated from $stokOLD to $stock and set item status to Sold Out";
				            } else {
				                $description = "Stock updated from $stokOLD to $stock and set item status to Available";
				            }
				        }
			    	}
			    } else { // Jika tidak membutuhkan stok
			        $description = "Stock updated from $stokOLD to $stock and set status to Limited";
			    }

	        $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');
	        // Membuat log perubahan stok
	        $datastok = [
	            'log_type' => 'Update Stock',
	            'cabang' => $cabang,
	            'item_code' => $item->no,
	            'stock_before' => $stokOLD,
	            'stock_after' => $stock,
	            'difference' => abs($stock - $stokOLD),
	            'stock_entry' => date('Y-m-d H:i:s'),
	            'username' => $this->session->userdata('usernameadmin'),
	            'description' => $description,
	        ];
	        $log = $this->db->insert('sh_stok_logs', $datastok);
	        if ($log) {
	        	$updateitem = [
	        		'stock_update_date' => date('Y-m-d H:i:s'),
	        		'stock_update_by' => $this->session->userdata('usernameadmin'),
	        	];
	        	$this->db->where('no',$item->no);
	        	$this->db->update('sh_m_item',$updateitem);
	        }
	    }

	    return $result;
	}
	public function UpdateStockOLD($value='')
	{
		$id = $this->input->post('id');
        $stock = $this->input->post('stock');
        $flag = $this->input->post('flag');

        if ($flag == 0) {
        	if ($stock >= 0) {
	        	$status = 0;
	        }else{
	        	$status = 1;
	        }
	        if ($stock >= 1) {
	        	$stockstatus = 1;
	        }else{
	        	$stockstatus = 0;
	        }
        }else{
        	if ($stock > 0) {
	        	$status = 0;
	        }else{
	        	$status = 1;
	        }
	        if ($stock >= 1) {
	        	$stockstatus = 1;
	        }else{
	        	$stockstatus = 1;
	        }
        }
        
        $data = array(
            'stock' => $stock,
            'is_sold_out' => $status,
            'need_stock' => $stockstatus,
        );

        $this->db->where('id', $id);
        return $this->db->update('sh_m_item', $data);
	}
	public function asigncategory()
	{
		$sub = $this->input->get('sub');
		$subso = $this->input->get('subso');
		$signature = $this->input->get('signature');
	    $item_name = $this->input->get('item_name');
	    $sub_category = $sub ? explode('/', $sub)[0] : null;
	    $sub_categoryso = $subso ? explode('/', $subso)[0] : null;

	    // Validasi akses user
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin', 'operation'])) {
	        redirect('index.php/login/admin');
	    }

	    // Data Pagination
	    $page2 = $this->input->get('start') ? intval($this->input->get('start')) : 0;

	    // Konfigurasi pagination
	    $query_string = http_build_query(array_filter([
	        'sub' => $sub,
	        'subso' => $subso,
	        'signature' => $signature,
	        'item_name' => $item_name
	    ]));

	    $config2['base_url'] = base_url('index.php/admin/asigncategory') . ($query_string ? '?' . $query_string : '');
	    $config2['total_rows'] = $this->Admin_model->countAsignCategory($sub_category,$sub_categoryso, $item_name,$signature);
	    $config2['per_page'] = 10;
	    $config2['page_query_string'] = true;
	    $config2['query_string_segment'] = 'start';
	    $config2['suffix'] = $query_string ? '&' . $query_string : '';
	    $config2['first_url'] = $config2['base_url'] . $config2['suffix'];
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['attributes'] = ['class' => 'page-link'];

	    $this->pagination->initialize($config2);

	    // Ambil data berdasarkan filter
	    $data['item'] = $this->Admin_model->get_AsignCategory($config2['per_page'], $page2, $sub_category,$sub_categoryso, $item_name,$signature);
	    $data['sub'] = $this->Admin_model->get_Category();
	    $data['subso'] = $this->Admin_model->get_Category_so();
	    $data['signature'] = $signature;
	    $data['dataitem'] = $this->Admin_model->get_itemData();
    	$data['subc'] = $sub_category;
    	$data['subcso'] = $sub_categoryso;
    	$data['item_name'] = $item_name;
    	$data['links2'] = $this->pagination->create_links();
    	$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
    	$data['logo'] = $this->Admin_model->getLogo();
	    $this->load->view('admin/asigncategory', $data);
	}
	public function asignpost($cek=null)
	{
		$item_code = $this->input->post('item_code');
		$sub = $this->input->post('sub');
		$sso = $this->input->post('subso');
		if ($sso == 'custom') {
			$subso = $this->input->post('custom_subso');
		}else{
			$subso = $sso;
		}
		$signature = $this->input->post('signature');

		$data = [
			'sub_category' => $sub,
			'sub_category_so' => $subso,
			'is_active_so' => 1,
			'chef_recommended' => $signature,
		];

		$this->db->where('no',$item_code);
		$this->db->update('sh_m_item',$data);
		if ($cek) {
			$this->session->set_flashdata('success', 'Sub category assignment has been successfully updated');
		}else{
			$this->session->set_flashdata('success', 'Sub category has been successfully assigned');
		}
		
		redirect('index.php/Admin/asigncategory');	
	}
	public function asigndelete($id)
	{
		$data = [
			'sub_category_so' => NULL,
			'is_active_so' => 0,
			'chef_recommended' => 0,
		];

		$this->db->where('id',$id);
		$this->db->update('sh_m_item',$data);
		$this->session->set_flashdata('success', 'Sub category assignment has been successfully deleted');
		
		redirect('index.php/Admin/asigncategory');	
	}
	public function category()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
		$config2['base_url'] = base_url('index.php/Admin/category');
	    $config2['total_rows'] = $this->Admin_model->countCategory(); 
	    $config2['per_page'] = 10; 
	    $config2['uri_segment'] = 3;
	    $config2['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
	    $config2['full_tag_close'] = '</ul></nav>';
	    $config2['first_link'] = 'First';
	    $config2['last_link'] = 'Last';
	    $config2['first_tag_open'] = '<li class="page-item">';
	    $config2['first_tag_close'] = '</li>';
	    $config2['prev_link'] = '&laquo';
	    $config2['prev_tag_open'] = '<li class="page-item">';
	    $config2['prev_tag_close'] = '</li>';
	    $config2['next_link'] = '&raquo';
	    $config2['next_tag_open'] = '<li class="page-item">';
	    $config2['next_tag_close'] = '</li>';
	    $config2['last_tag_open'] = '<li class="page-item">';
	    $config2['last_tag_close'] = '</li>';
	    $config2['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
	    $config2['cur_tag_close'] = '</a></li>';
	    $config2['num_tag_open'] = '<li class="page-item">';
	    $config2['num_tag_close'] = '</li>';
	    $config2['attributes'] = array('class' => 'page-link');

	    $this->pagination->initialize($config2);

	    $page2 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	    $data['item'] = $this->Admin_model->get_Category($config2['per_page'], $page2);
	    $data['signature'] = $this->Admin_model->get_Category_Signature();
    	$data['links2'] = $this->pagination->create_links();
    	$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
    	$data['logo'] = $this->Admin_model->getLogo();
	    $this->load->view('admin/category', $data);
	}
	public function UpdateStatusCategory($sub,$type)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
            // $imageType = $_FILES['image_path']['type'];
            // $imageData = file_get_contents($_FILES['image_path']['tmp_name']);
            // if (!empty($_FILES['image_path']['tmp_name'])) {
            // 	$image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);
            // }else{
            // 	$image = '';
            // }
         	if ($type == 'active') {
         	   	$status = 1;
         	   	$s = 'active';
         	}else{
         		$status = 0;
         		$s= 'inactive';
         	}   
	        $data = [
		      'is_active_so' => $status,
		    ];
		    $this->db->where('sub_category', urldecode($sub));
			$this->db->update('sh_m_item', $data);

            $this->session->set_flashdata('success', 'Successfully changed the status to '.$s);

		redirect('index.php/Admin/category/');

	}
	public function Signature($id,$type)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
         	if ($type == 'yes') {
         	   	$signature = 1;
         	   	$s = 'Yes';
         	}else{
         		$signature = 0;
         		$s= 'No';
         	}   
	        $data = [
		      'chef_recommended' => $signature,
		    ];
		    $this->db->where('id', $id);
			$this->db->update('sh_m_item', $data);

            $this->session->set_flashdata('success', 'Successfully changed the signature to '.$s);

		redirect('index.php/Admin/asigncategory/');

	}
	public function UpdateSignature($sub,$type)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			   !in_array($this->session->userdata('role'), ['admin','operation'])) {
			   redirect('index.php/login/admin');
		}
         	if ($type == 'last') {
         	   	$status = 1;
         	   	$s = 'last';
         	}else{
         		$status = 0;
         		$s= 'first';
         	}   
	        $data = [
		      'sort' => $status,
		    ];
		    $this->db->where('description', urldecode($sub));
			$this->db->update('sh_m_item_sub_category', $data);

            $this->session->set_flashdata('success', 'Successfully changed the sort to '.$s);

		redirect('index.php/Admin/category/');

	}

    public function import_csv()
	{
	    if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
	        $file = $_FILES['file']['tmp_name'];

	        // Baca 1 baris pertama untuk deteksi delimiter
	        $first_line = fgets(fopen($file, 'r'));
	        $delimiter = ',';
	        if (substr_count($first_line, ';') > substr_count($first_line, ',')) {
	            $delimiter = ';';
	        } elseif (substr_count($first_line, "\t") > substr_count($first_line, ',') && substr_count($first_line, "\t") > substr_count($first_line, ';')) {
	            $delimiter = "\t"; // tab
	        }

	        $handle = fopen($file, "r");

	        // Lewati header jika memang ada
	        // Kalau file kamu tidak punya header, hapus baris ini
	        // fgetcsv($handle, 1000, $delimiter);

	        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
	            // Pastikan jumlah kolom sesuai
	            if (count($row) < 6) {
	                continue; // skip baris yang tidak lengkap
	            }

	            $data = [
	                'item_code'  => trim($row[0]),
	                'time_from'  => trim($row[1]),
	                'time_to'    => trim($row[2]),
	                'date_from'  => trim($row[3]),
	                'date_to'    => trim($row[4]),
	                'type'       => trim($row[5])
	            ];

	            $this->db->insert('sh_m_item_event', $data);
	        }

	        fclose($handle);
	        $this->session->set_flashdata('success', 'Data CSV berhasil diimport!');
	    } else {
	        $this->session->set_flashdata('error', 'Gagal mengupload file CSV.');
	    }

	    redirect($_SERVER['HTTP_REFERER']);
	}
	public function save_image()
	{
	    // 🔒 auth (optional kalau mau dipakai juga di sini)
	    if ($this->session->userdata('usernameadmin') == "" || 
	        !in_array($this->session->userdata('role'), ['admin','operation'])) {
	        redirect('index.php/login/admin');
	    }

	    $item_code = $this->input->post('item_code');

	    // 🔥 ambil item
	    $item = $this->db->get_where('sh_m_item', ['no' => $item_code])->row();

	    if (!$item) {
	        $this->session->set_flashdata('error', 'Item tidak ditemukan');
	        redirect('index.php/admin/item');
	    }

	    // 🔥 validasi size manual (100KB)
	    if ($_FILES['image_path']['size'] > 102400) {
	        $this->session->set_flashdata('error', 'Ukuran gambar maksimal 100KB');
	        redirect('index.php/admin/item');
	    }

	    // 🔥 sanitize nama file
	    $filename = strtolower($item->description);
	    $filename = preg_replace('/[^a-z0-9]/', '_', $filename);
	    $filename = trim($filename, '_');

	    // config upload
	    $config['upload_path']   = FCPATH . 'assets/ItemImage/';
	    $config['allowed_types'] = 'jpg|jpeg|png';
	    $config['max_size']      = 100;
	    $config['file_name']     = $filename;
	    $config['overwrite']     = TRUE;

	    $this->load->library('upload', $config);

	    if (!$this->upload->do_upload('image_path')) {

	        $error = $this->upload->display_errors('', '');

	        if (strpos($error, 'larger than the permitted size') !== false) {
	            $this->session->set_flashdata('error', 'Ukuran gambar maksimal 100KB');
	        } else {
	            $this->session->set_flashdata('error', $error);
	        }

	        redirect('index.php/admin/item');
	    }

	    $uploadData = $this->upload->data();
	    $image_path = 'assets/ItemImage/' . $uploadData['file_name'];

	    // 🔥 insert ke DB
	    $this->db->insert('sh_m_item_image', [
	        'item_code'  => $item_code,
	        'image_path' => $image_path
	    ]);

	    $this->session->set_flashdata('success', 'Image berhasil ditambahkan');
	    redirect('index.php/admin/item');
	}
	public function update_image($item_code)
{
    // 🔒 auth
    if ($this->session->userdata('usernameadmin') == "" || 
        !in_array($this->session->userdata('role'), ['admin','operation'])) {
        redirect('index.php/login/admin');
    }

    // 🔥 ambil item
    $item = $this->db->get_where('sh_m_item', ['no' => $item_code])->row();

    if (!$item) {
        $this->session->set_flashdata('error', 'Item tidak ditemukan');
        redirect('index.php/admin/item');
    }

    // 🔥 validasi size manual (100KB)
    if (!empty($_FILES['image_path']['name']) && $_FILES['image_path']['size'] > 102400) {
        $this->session->set_flashdata('error', 'Ukuran gambar maksimal 100KB');
        redirect('index.php/admin/item');
    }

    // 🔥 sanitize nama file
    $filename = strtolower($item->description);
    $filename = preg_replace('/[^a-z0-9]/', '_', $filename);
    $filename = trim($filename, '_');

    if (!empty($_FILES['image_path']['name'])) {

        // 🔥 ambil gambar lama
        $old = $this->db
            ->get_where('sh_m_item_image', ['item_code' => $item_code])
            ->row();

        // 🔥 hapus file lama
        if ($old && !empty($old->image_path)) {
            $old_path = FCPATH . $old->image_path;
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }

        // config upload
        $config['upload_path']   = FCPATH . 'assets/ItemImage/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 100;
        $config['file_name']     = $filename . '_' . time();

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image_path')) {

            $error = $this->upload->display_errors('', '');

            if (strpos($error, 'larger than the permitted size') !== false) {
                $this->session->set_flashdata('error', 'Ukuran gambar maksimal 100KB');
            } else {
                $this->session->set_flashdata('error', $error);
            }

            redirect('index.php/admin/item');
        }

        $uploadData = $this->upload->data();
        $image_path = 'assets/ItemImage/' . $uploadData['file_name'];

        // 🔥 update / insert
        $check = $this->db
            ->get_where('sh_m_item_image', ['item_code' => $item_code])
            ->row();

        if ($check) {
            $this->db->where('item_code', $item_code);
            $this->db->update('sh_m_item_image', [
                'image_path' => $image_path
            ]);
        } else {
            $this->db->insert('sh_m_item_image', [
                'item_code'  => $item_code,
                'image_path' => $image_path
            ]);
        }
    }

    $this->session->set_flashdata('success', 'Image berhasil diupdate');
    redirect('index.php/Admin/item/');
}
	public function generate_kode_item()
	{
	    $row = $this->db->get_where('sh_no_series', [
	        'description' => 'Item',
	        'is_active' => 1
	    ])->row();


	    // ambil last number
	    $last_no = (int)$row->inc_by_no;
	    
	    // padding sesuai length
	    $no_urut = str_pad($last_no, $row->length_no, '0', STR_PAD_LEFT);

	    // format kode
	    $kode = $row->prefix . '-' . $no_urut;
	    return $kode;
	}
	public function tambahitem()
    {
        $data = [
            'category' => $this->Admin_model->getCategory(),
            'sub' => $this->Admin_model->getSub(),
            'item' => $this->Admin_model->getItem(),
            'logo' => $this->Admin_model->getLogo(),
            'paket' => $this->Admin_model->getitempaket(),
            'no'	=> $this->generate_kode_item(),
            'monitor'	=> $this->Admin_model->getmonitor(),
        ];
        $this->load->view('admin/tambahitem',$data);
    }
    public function simpanitem()
	{
	    $this->db->trans_start();

	    // ✅ ambil cabang (lebih aman)
	    $cabangRow = $this->db->select('id')
	        ->from('sh_m_cabang')
	        ->order_by('id', 'ASC')
	        ->limit(1)
	        ->get()
	        ->row();

	    if (!$cabangRow) {
	        show_error('Data cabang tidak ditemukan');
	    }

	    $cabang = $cabangRow->id;

	    // ✅ ambil no series
	    $series = $this->db->get_where('sh_no_series', [
	        'description' => 'Item',
	        'is_active' => 1
	    ])->row();

	    if (!$series) {
	        show_error('No series Item tidak ditemukan');
	    }

	    // ✅ ambil angka terakhir
	    $last_kode = $series->last_no_used;

	    preg_match('/(\d+)$/', $last_kode, $match);
	    $last_no = isset($match[1]) ? (int)$match[1] : 0;

	    // increment
	    $new_no = $last_no + 1;

	    // padding
	    $no_urut = str_pad($new_no, $series->length_no, '0', STR_PAD_LEFT);

	    // format kode
	    $kode = $series->prefix . '-' . $no_urut;

	    // ✅ ambil monitor
	    $monitor = $this->input->post('monitor');

	    $data_monitor = [];
	    for ($i = 1; $i <= 26; $i++) {
	        $data_monitor['monitor' . $i] = 0;
	    }

	    if (!empty($monitor)) {
	        foreach ($monitor as $id => $val) {
	            if ($val == 1 && $id <= 26) {
	                $data_monitor['monitor' . $id] = 1;
	            }
	        }
	    }

	    // ✅ ambil harga (hindari null)
	    $harga_weekday = (float)$this->input->post('harga_weekday');
	    $harga_weekend = (float)$this->input->post('harga_weekend');
	    $harga_holiday = (float)$this->input->post('harga_holiday');

	    // ✅ data insert
	    $data = [
	        'parent_id' => 0,
	        'no' => $kode,
	        'description' => $this->input->post('description'),
	        'category' => $this->input->post('item_category'),
	        'sub_category' => $this->input->post('item_sub_category'),
	        'item_package' => $this->input->post('item_package'),

	        'consignment' => $this->input->post('consignment'),
	        'additional' => $this->input->post('additional'),
	        'group_item' => $this->input->post('group_item'),
	        'additional_package' => $this->input->post('additional_package'),

	        'dine_in_menu' => $this->input->post('dine_in'),
	        'take_away_menu' => $this->input->post('take_away'),

	        'product_info' => $this->input->post('product_info'),
	        'show_list' => 1,
	        'cabang' => $cabang,

	        'harga_weekday' => $harga_weekday,
	        'harga_weekend' => $harga_weekend,
	        'harga_holiday' => $harga_holiday,

	        'harga_no_sc_weekday' => $harga_weekday / 1.05,
	        'harga_no_sc_weekend' => $harga_weekend / 1.05,
	        'harga_no_sc_holiday' => $harga_holiday / 1.05,
	        'entry_by'		=> $this->session->userdata('usernameadmin'),
	    ];

	    // ✅ gabung monitor
	    $data = array_merge($data, $data_monitor);
	    
	    // insert
	    $simpan = $this->db->insert('sh_m_item', $data);

	    if ($simpan) {
	        $dataupdate = [
	            'last_no_used' => $kode,
	            'last_date_used' => date('Y-m-d H:i:s'),
	            'inc_by_no' => $series->inc_by_no + 1,
	        ];

	        $this->db->where('id', $series->id);
	        $this->db->update('sh_no_series', $dataupdate);
	    }

	    $this->db->trans_complete();

	    if ($this->db->trans_status() === FALSE) {
	        $this->session->set_flashdata('error', 'Gagal simpan data');
	    } else {
	        $this->session->set_flashdata('success', 'Item berhasil disimpan');
	    }

	    redirect('index.php/Admin/item/');
	}
	public function edititem($id)
	{
	    $data['item'] = $this->db->get_where('sh_m_item', ['id' => $id])->row();
	    $data['category'] = $this->Admin_model->getCategory();
	    $data['sub'] = $this->Admin_model->getSub();
	    $data['paket'] = $this->Admin_model->getitempaket();
	    $data['monitor'] = $this->Admin_model->getmonitor();
	    $data['logo'] = $this->Admin_model->getLogo();

	    $this->load->view('admin/edit_item', $data);
	}
	public function updateitem($id)
	{
	    $this->db->trans_start();

	    // ambil data item lama (optional, buat validasi)
	    $item = $this->db->get_where('sh_m_item', ['id' => $id])->row();
	    if (!$item) {
	        show_error('Item tidak ditemukan');
	    }

	    // ✅ ambil monitor
	    $monitor = $this->input->post('monitor');

	    $data_monitor = [];
	    for ($i = 1; $i <= 26; $i++) {
	        $data_monitor['monitor' . $i] = 0;
	    }

	    if (!empty($monitor)) {
	        foreach ($monitor as $mid => $val) {
	            if ($val == 1 && $mid <= 26) {
	                $data_monitor['monitor' . $mid] = 1;
	            }
	        }
	    }

	    // ✅ harga
	    $harga_weekday = (float)$this->input->post('harga_weekday');
	    $harga_weekend = (float)$this->input->post('harga_weekend');
	    $harga_holiday = (float)$this->input->post('harga_holiday');

	    // ✅ data update
	    $data = [
	        'description' => $this->input->post('description'),
	        'category' => $this->input->post('item_category'),
	        'sub_category' => $this->input->post('item_sub_category'),
	        'item_package' => $this->input->post('item_package'),

	        'consignment' => $this->input->post('consignment'),
	        'additional' => $this->input->post('additional'),
	        'group_item' => $this->input->post('group_item'),
	        'additional_package' => $this->input->post('additional_package'),

	        'dine_in_menu' => $this->input->post('dine_in'),
	        'take_away_menu' => $this->input->post('take_away'),

	        'product_info' => $this->input->post('product_info'),

	        'harga_weekday' => $harga_weekday,
	        'harga_weekend' => $harga_weekend,
	        'harga_holiday' => $harga_holiday,

	        'harga_no_sc_weekday' => $harga_weekday / 1.05,
	        'harga_no_sc_weekend' => $harga_weekend / 1.05,
	        'harga_no_sc_holiday' => $harga_holiday / 1.05,

	        'is_active' => $this->input->post('aktif'),

	        'entry_by' => $this->session->userdata('usernameadmin'),
	    ];
	    
	    // gabung monitor
	    $data = array_merge($data, $data_monitor);

	    // update
	    $this->db->where('id', $id);
	    $this->db->update('sh_m_item', $data);

	    $this->db->trans_complete();

	    if ($this->db->trans_status() === FALSE) {
	        $this->session->set_flashdata('error', 'Gagal update data');
	    } else {
	        $this->session->set_flashdata('success', 'Item berhasil diupdate');
	    }

	    redirect('index.php/Admin/item/');
	}
	public function pesanan()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			!in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			redirect('index.php/login/admin');
		}

		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['logo'] = $this->Admin_model->getLogo();

		// Mengambil data pesanan dari table sh_t_transaction_details per hari ini
		$data['pesanan_list'] = $this->Admin_model->getPesananHariIni();

		$this->load->view('admin/pesanan', $data);
	}

	public function update_status_pesanan()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			!in_array($this->session->userdata('role'), ['admin', 'operation'])) {
			redirect('index.php/login/admin');
		}

		$id_pesanan = $this->input->post('id_pesanan');
		$status = $this->input->post('status');

		// TODO: Lakukan proses update status ke database sesuai tabel yang digunakan
		// $this->db->where('id', $id_pesanan)->update('nama_tabel_pesanan', ['status' => $status]);

		$this->session->set_flashdata('success', 'Status pesanan berhasil diperbarui menjadi ' . $status);
		redirect('index.php/Admin/pesanan');
	}

	public function get_pesanan_detail()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			!in_array($this->session->userdata('role'), ['admin', 'operation', 'cashier'])) {
			echo json_encode([]); return;
		}
		$id_trans = $this->input->post('id_trans');
		$details = $this->Admin_model->getPesananDetail($id_trans);
		echo json_encode($details);
	}
	public function payment_pesanan($id_trans)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			!in_array($this->session->userdata('role'), ['admin', 'operation', 'cashier'])) {
			redirect('index.php/login/admin');
		}

		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['logo'] = $this->Admin_model->getLogo();
        
        $data['transaction'] = $this->db->select('t.*, c.customer_name')
                                        ->from('sh_t_transactions t')
                                        ->join('sh_m_customer c', 'c.id = t.id_customer', 'left')
                                        ->where('t.id', $id_trans)
                                        ->get()->row();
        
        if (!$data['transaction']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan');
            redirect('index.php/Admin/pesanan');
        }

        $data['details'] = $this->Admin_model->getPesananDetail($id_trans);
        $data['payment'] = $this->Item_model->totalbayaradmin($id_trans);
        
		$this->load->view('admin/payment_pesanan', $data);
	}

    public function proses_payment_pesanan()
    {
		if ($this->session->userdata('usernameadmin') == "" || 
			!in_array($this->session->userdata('role'), ['admin', 'operation', 'cashier'])) {
			redirect('index.php/login/admin');
		}

        $id_trans = $this->input->post('id_trans');
        $payment_method = $this->input->post('payment_method');
        
        $payment = $this->Item_model->totalbayaradmin($id_trans);
        $amount = $payment->total + $payment->sc + $payment->ppn;

        $kembalian = $this->input->post('kembalian');
        if (empty($kembalian)) $kembalian = 0;

        $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();

        $this->db->trans_start();

        // Update transaction
        $this->db->where('id', $id_trans)->update('sh_t_transactions', [
            'payment_amount'    => $amount,
            'total_amount'      => $amount,
            'kembalian'         => $kembalian,
            'payment_date'      => date('Y-m-d H:i:s'),
            'payment_type'      => 'kasir',
            'payment_card_type' => $payment_method,
            'payment_by'        => $this->session->userdata('usernameadmin'),
            'tax_percent'       => $setup->tax_percent,
			'tax_amount'        => $payment->ppn,
        ]);

        // Update details
        $this->db->where('id_trans', $id_trans)->update('sh_t_transaction_details', [
            'is_paid' => 1
        ]);

        // Bebaskan meja
        $trans = $this->db->where('id', $id_trans)->get('sh_t_transactions')->row();
        if ($trans) {
            $this->db->where('id_customer', $trans->id_customer)->update('sh_rel_table', [
                'status' => 'Available'
            ]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $msg = 'Gagal memproses pembayaran';
            if ($this->input->is_ajax_request()) { echo json_encode(['status' => 'error', 'message' => $msg]); exit; }
            $this->session->set_flashdata('error', $msg);
        } else {
            $msg = 'Pembayaran berhasil diproses';
            if ($this->input->is_ajax_request()) { echo json_encode(['status' => 'success', 'message' => $msg, 'id_trans' => $id_trans]); exit; }
            $this->session->set_flashdata('success', $msg);
        }

		redirect('index.php/Admin/pesanan');
    }

	public function print_receipt($id_trans)
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			!in_array($this->session->userdata('role'), ['admin', 'operation', 'cashier'])) {
			redirect('index.php/login/admin');
		}

		$data['transaction'] = $this->db->select('t.*, c.customer_name')
				->from('sh_t_transactions t')
				->join('sh_m_customer c', 'c.id = t.id_customer', 'left')
				->where('t.id', $id_trans)
				->get()->row();

		if (!$data['transaction']) {
			echo 'Transaksi tidak ditemukan'; exit;
		}

		$data['details'] = $this->Admin_model->getPesananDetail($id_trans);
		$data['payment'] = $this->Item_model->totalbayaradmin($id_trans);
		$data['logo'] = $this->Admin_model->getLogo();

		$this->load->view('admin/receipt_print', $data);
	}

	public function cashier()
	{
		if ($this->session->userdata('usernameadmin') == "" || 
			!in_array($this->session->userdata('role'), ['admin', 'operation', 'cashier'])) {
			redirect('index.php/login/admin');
		}

		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['logo'] = $this->Admin_model->getLogo();
		$data['pesanan_list'] = $this->Admin_model->getPesananHariIni();

		$this->load->view('admin/cashier', $data);
	}

}
