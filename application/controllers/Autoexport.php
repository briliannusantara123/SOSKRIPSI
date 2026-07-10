<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Autoexport extends CI_Controller {
	public function __construct() {
			parent::__construct();
			
			$this->load->model('Item_model');
			$this->load->model('Admin_model');
			
		}

	public function download_struk_pdf_auto()
	{
	    $this->load->library('pdf');

	    $trans = $this->db
	    ->select('b.id_table, a.*')
	    ->from('sh_t_transactions a')
	    ->join('sh_rel_table b', 'b.id_customer = a.id_customer')
	    ->where('a.is_pdf', 0)
	    ->where('a.total_amount >', 0)
	    ->order_by('a.id', 'ASC')
	    ->limit(1)
	    ->get()
	    ->row();


	    if (!$trans) {
	        echo '<script>
			    window.onload = function() {
			        window.close();
			    }
			</script>';
			exit;
	    }

	    $cabang = $this->db->order_by('id', 'DESC')->limit(1)->get('sh_m_cabang')->row();
	    $logo   = $this->Admin_model->getLogo();


	        $nomeja = $trans->id_table;
	        $ic     = $trans->id_customer;

	        $user = $this->db
	            ->select('a.id,b.customer_name,b.visit_type,b.total_pax')
	            ->from('sh_rel_table a')
	            ->join('sh_m_customer b', 'b.id = a.id_customer')
	            ->where('a.id_table', $nomeja)
	            ->where('a.created_date', date('Y-m-d'))
	            ->limit(1)
	            ->get()
	            ->row();

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

	        $html = $this->load->view('struk_pdf', $data, true);

	        $this->pdf->setPaper([0, 0, 350, 1500], 'portrait');
	        $this->pdf->loadHtml($html);
	        $this->pdf->render();

	        // 📁 SIMPAN FILE
	        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $trans->order_no) . '.pdf';
	        $path = FCPATH . '/export/struk/' . $filename;

	        file_put_contents($path, $this->pdf->output());

	        // update flag
	        $this->db->where('id', $trans->id)->update('sh_t_transactions', [
	            'is_pdf' => 1
	        ]);

	        unset($this->pdf);
			$this->load->library('pdf');
			echo '<script>
			    window.onload = function() {
			        window.close();
			    }
			</script>';
			exit;
	}
	
}
