<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('username') == ""){
            redirect('login/logout');
        }
        $this->load->model('Review_model');
        $this->load->model('Item_model');
        $this->load->model('Admin_model');
        
  		$id_customer = $this->session->userdata('id');
  		$nomeja = $this->session->userdata('nomeja');
		$count = $this->Review_model->verify($id_customer,$nomeja)->num_rows();
		$status = $this->Review_model->verify($id_customer,$nomeja)->row();
  		if ($count > 0) {
  			$this->load->model('cekstatus_model');
			$this->load->helper('cookie');
			$session = $this->cekstatus_model->cek();

  		if($session['status'] == 'Cleaning'){
  			$nomeja = $this->session->userdata('nomeja');
  			redirect('index.php/login/logout/'.$nomeja.'/cleaning');
			}
			}
		}

		public function form($nomeja,$sub)
		{
		$id_customer = $this->session->userdata('id');
		$count = $this->Review_model->verify($id_customer,$nomeja)->num_rows();
		$status = $this->Review_model->verify($id_customer,$nomeja)->row();
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['logo'] = $this->Admin_model->getLogo();
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
  		$notrans = $this->db->order_by('id',"desc")->where('id_customer',$id_customer)
  			->limit(1)
  			->get('sh_t_transactions')
  			->row('id');
		$data['order_bill_line'] = $this->Item_model->order_bill_line($cabang,$notrans);
		$subid = str_replace('%20', '_', $sub);
		$link = base_url().'index.php/ordermakanan/menu/Makanan/'.$sub.'#'.$subid;
  		if ($count > 0) {
		    $data['category'] = $this->Review_model->get_category()->result();
		    $data['nomeja'] = $nomeja;
		    $data['linkback'] = $link;
		    $data['sub']	= $sub;
			$this->load->view('review',$data);
		}else{
			redirect('index.php/ordermakanan/menu/Makanan/'.$sub.'#'.$subid);
		}
	}

	public function save($nomeja, $sub)
	{
		$username = $this->session->userdata('username') ?: 'Guest';

		$post = $this->input->post();

		$linkback = $this->input->post('linkback');

		$cabang = $this->db
			->select('id')
			->order_by('id', 'DESC')
			->limit(1)
			->get('sh_m_cabang')
			->row('id');

		$tanggal = date('Y-m-d H:i:s');

		$saved = 0;

		$categoryMap = [
			'rasa' => ['id' => 1, 'desc' => 'Rasa makanan & minuman'],
			'pelayanan' => ['id' => 2, 'desc' => 'Pelayanan'],
			'fasilitas' => ['id' => 3, 'desc' => 'Fasilitas'],
			'kebersihan' => ['id' => 4, 'desc' => 'Kebersihan'],
			'harga' => ['id' => 5, 'desc' => 'Harga'],
		];

		// 1. Food & Beverage Taste
		$rasaCount = max(
			count($post['rasa_item'] ?? []),
			count($post['rasa_pujian'] ?? []),
			count($post['rasa_kritik'] ?? [])
		);

		for ($i = 0; $i < $rasaCount; $i++) {
			$rasaItem = trim($post['rasa_item'][$i] ?? '');
			$pujian = trim($post['rasa_pujian'][$i] ?? '');
			$kritik = trim($post['rasa_kritik'][$i] ?? '');

			if ($pujian === '' && $kritik === '') {
				continue;
			}
			$data = [
				'category_id' => $categoryMap['rasa']['id'],
				'category_desc' => $categoryMap['rasa']['desc'],
				'description' => $rasaItem,
				'table_no' => $nomeja,
				'customer_name' => $username,
				'kritik_saran' => $kritik,
				'pujian' => $pujian,
				'cabang' => $cabang,
				'tanggal' => $tanggal,
				'entry_by' => $username,
			];

			$this->Review_model->save($data);
			$saved++;
		}

		// 2. Facilities
		$fasilitasCount = max(
			count($post['fasilitas_name'] ?? []),
			count($post['fasilitas_pujian'] ?? []),
			count($post['fasilitas_kritik'] ?? [])
		);

		for ($i = 0; $i < $fasilitasCount; $i++) {
			$nama = trim($post['fasilitas_name'][$i] ?? '');
			$pujian = trim($post['fasilitas_pujian'][$i] ?? '');
			$kritik = trim($post['fasilitas_kritik'][$i] ?? '');

			if ($pujian === '' && $kritik === '') {
				continue;
			}


			$data = [
				'category_id' => $categoryMap['fasilitas']['id'],
				'category_desc' => $categoryMap['fasilitas']['desc'],
				'description' => $nama,
				'table_no' => $nomeja,
				'customer_name' => $username,
				'kritik_saran' => $kritik,
				'pujian' => $pujian,
				'cabang' => $cabang,
				'tanggal' => $tanggal,
				'entry_by' => $username,
			];

			$this->Review_model->save($data);
			$saved++;
		}

		// 3. Pricing
		$hargaCount = max(
			count($post['harga_cat'] ?? []),
			count($post['harga_pujian'] ?? []),
			count($post['harga_kritik'] ?? [])
		);

		for ($i = 0; $i < $hargaCount; $i++) {
			$nama = trim($post['harga_cat'][$i] ?? '');
			$pujian = trim($post['harga_pujian'][$i] ?? '');
			$kritik = trim($post['harga_kritik'][$i] ?? '');

			if ($pujian === '' && $kritik === '') {
				continue;
			}

			$data = [
				'category_id' => $categoryMap['harga']['id'],
				'category_desc' => $categoryMap['harga']['desc'],
				'description' => $nama,
				'table_no' => $nomeja,
				'customer_name' => $username,
				'kritik_saran' => $kritik,
				'pujian' => $pujian,
				'cabang' => $cabang,
				'tanggal' => $tanggal,
				'entry_by' => $username,
			];

			$this->Review_model->save($data);
			$saved++;
		}

		// 4. Service
		$pelayananCount = max(
			count($post['pelayanan_name'] ?? []),
			count($post['pelayanan_pujian'] ?? []),
			count($post['pelayanan_kritik'] ?? [])
		);

		for ($i = 0; $i < $pelayananCount; $i++) {
			$nama = trim($post['pelayanan_name'][$i] ?? '');
			$pujian = trim($post['pelayanan_pujian'][$i] ?? '');
			$kritik = trim($post['pelayanan_kritik'][$i] ?? '');

			if ($pujian === '' && $kritik === '') {
				continue;
			}

			$data = [
				'category_id' => $categoryMap['pelayanan']['id'],
				'category_desc' => $categoryMap['pelayanan']['desc'],
				'description' => $nama,
				'table_no' => $nomeja,
				'customer_name' => $username,
				'kritik_saran' => $kritik,
				'pujian' => $pujian,
				'cabang' => $cabang,
				'tanggal' => $tanggal,
				'entry_by' => $username,
			];

			$this->Review_model->save($data);
			$saved++;
		}

		// 5. Cleanliness
		$kebersihanCount = max(
			count($post['kebersihan_area'] ?? []),
			count($post['kebersihan_pujian'] ?? []),
			count($post['kebersihan_kritik'] ?? [])
		);

		for ($i = 0; $i < $kebersihanCount; $i++) {
			$nama = trim($post['kebersihan_area'][$i] ?? '');
			$pujian = trim($post['kebersihan_pujian'][$i] ?? '');
			$kritik = trim($post['kebersihan_kritik'][$i] ?? '');

			if ($pujian === '' && $kritik === '') {
				continue;
			}

			$data = [
				'category_id' => $categoryMap['kebersihan']['id'],
				'category_desc' => $categoryMap['kebersihan']['desc'],
				'description' => $nama,
				'table_no' => $nomeja,
				'customer_name' => $username,
				'kritik_saran' => $kritik,
				'pujian' => $pujian,
				'cabang' => $cabang,
				'tanggal' => $tanggal,
				'entry_by' => $username,
			];

			$this->Review_model->save($data);
			$saved++;
		}

		if ($saved > 0) {
			$this->session->set_flashdata(
				'success',
				'Thank you for your valuable feedback. Your review has been submitted successfully.'
			);

			redirect($linkback);
		}

		$this->session->set_flashdata(
			'error',
			'Please enter at least one review before submitting.'
		);

		redirect('index.php/review/form/' . $nomeja . '/' . $sub);
	}
}
?>
