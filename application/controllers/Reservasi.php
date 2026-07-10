<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservasi extends CI_Controller {
function __construct()
	{
		parent::__construct();
		$this->load->model('Item_model');
		$this->load->model('Admin_model');
			
	}
	public function index()
	{
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
		$data['iconfooter'] = $this->Admin_model->getIcon('footer');
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['icon'] = $this->Admin_model->getIcon('add');
		$data['logo'] = $this->Admin_model->getLogo();
		$this->load->view('reservasi',$data);
	}
	public function send_reservation()
	{
		$name = $this->input->post('name');
		$pax = $this->input->post('pax');
		$nohp = $this->input->post('nohp');
		$tgl  = $this->input->post('date');  // contoh: 2025-05-24
		$hour = $this->input->post('hour');  // contoh: 14:30

		// Gabung tanggal dan jam
		$datetime_str = $tgl . ' ' . $hour;

		// Buat objek DateTime dari gabungan
		$dateTime = DateTime::createFromFormat('Y-m-d H:i', $datetime_str, new DateTimeZone('Asia/Jakarta'));

		if (!$dateTime) {
			$this->session->set_flashdata('error', 'Waktu tidak valid');
			return redirect('/reservasi');
		}

		// Salin untuk manipulasi waktu
		$end_time_obj = clone $dateTime;
		$pt_obj       = clone $dateTime;

		$end_time_obj->modify('+2 hours');
		$pt_obj->modify('-15 minutes');

		// Format ke string
		$end_time = $end_time_obj->format('Y-m-d H:i');
		$pt       = $pt_obj->format('Y-m-d H:i');
		$start_time = $dateTime->format('Y-m-d H:i'); // Optional simpan waktu mulai penuh

		// Format tanggal tampil ke: 24 May 2025
		$date_only_obj = DateTime::createFromFormat('Y-m-d', $tgl);
		$tgl_formatted = $date_only_obj->format('d F Y');

		// Ambil id cabang terakhir
		$cabang = $this->db->order_by('id', 'desc')
			->limit(1)
			->get('sh_m_cabang')
			->row('id');

		$last_queue = $this->db
		    ->select_max('antrian')
		    ->where('cabang', $cabang)
		    ->get('sh_m_walkin')
		    ->row()
		    ->antrian;

		$next_queue = ($last_queue ?? 0) + 1;


		// Simpan ke tabel customer
		$walkin_data = [
		    'customer_name'   => $name,
		    'no_telp'         => $nohp,
		    'email'           => '',
		    'create_date'     => date('Y-m-d H:i:s'),
		    'booking_date'    => $dateTime->format('Y-m-d H:i:s'),
		    'is_waiting'      => 1,
		    'is_checkin'      => 0,
		    'total_pax'       => $pax,
		    'total_real_pax'  => $pax,
		    'cabang'          => $cabang,
		    'antrian'         => $next_queue,               // bisa di-generate nanti
		    'antrian_prefix'  => 'W',
		    'id_member'       => 0,
		    'is_cancel'       => 0,
		    'cancel_reason'   => '',
		    'checkin_type'    => 'reservation'
		];

		$this->db->insert('sh_m_walkin', $walkin_data);
		$id_walkin = $this->db->insert_id();
		
		// Notifikasi
		$this->session->set_flashdata('success', "You successfully booked a reservation for " . $tgl_formatted . " at " . $hour);

		return redirect('index.php/reservasi');
	}

	
}
