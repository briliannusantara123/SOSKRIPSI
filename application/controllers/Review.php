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
  		}else if($session['status'] == 'Billing'){
  			$nomeja = $this->session->userdata('nomeja');
  			redirect('index.php/login/logout/'.$nomeja.'/billing');
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
		$subid = str_replace('%20', '_', $sub);
		$link = base_url().'index.php/ordermakanan/menu/Makanan/'.$sub.'#'.$subid;
  		if ($count > 0) {
  			if ($status->status == 'Payment') {
  				redirect('index.php/login/logout');
  			} 
		    $data['category'] = $this->Review_model->get_category()->result();
		    $data['nomeja'] = $nomeja;
		    $data['linkback'] = $link;
		    $data['sub']	= $sub;
			$this->load->view('review',$data);
		}else{
			redirect('index.php/ordermakanan/menu/Makanan/'.$sub.'#'.$subid);
		}
	}

	public function save($nomeja,$sub) 
	{
		$username = $this->session->userdata('username') ? $this->session->userdata('username') : 'none';
		$post = $this->input->post(); 
		$linkback = $this->input->post('linkback'); 

		$saved = 0;
		$tanggal = date('Y-m-d H:i:s');
		for($num = 0; $num < 5; $num++){
			if($post['kritik'][$num] != '' || $post['pujian'][$num] != ''){
				$data = [
					'category_id' => $post['cat_id'][$num],
					'category_desc' => $post['desc'][$num],
					'table_no' => $nomeja,
					'customer_name' => $username,
					'kritik_saran' => $post['kritik'][$num],
					'pujian' => $post['pujian'][$num],
					'cabang' => 8,
					'tanggal' => $tanggal,
					'entry_by' => $username,
				]; 
				$this->Review_model->Save($data);
				$saved++;	
			} 
		}
		if($saved > 0){
			$this->session->set_flashdata('success','Thank you for your review');
			redirect($linkback);
		}else{
			$this->session->set_flashdata('error',"You haven't submitted any reviews yet");
			redirect('index.php/review/form/'.$nomeja.'/'.$sub);
		}
	}
}
?>