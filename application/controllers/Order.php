<?php 
    class Order extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
			$this->load->model(array(
				'Order_tracking_model' => 'model'
			));
        }

        public function index()
        {
            redirect('index.php/order/food1');
        }

        public function food1()
        {
            // $cek = $this->model->cekitemcomplete(2, 1)->result();
            // var_dump($cek);exit;
			date_default_timezone_set('Asia/Jakarta');

			$table_order1 = $this->model->tableListFood(1,1);
            $table_orderReady = $this->model->tableListFoodReady(1,1);
            $table_process = $this->model->table_process(1,1);
            $table_pickup = $this->model->table_pickup(1,1);
            $table_complete = $this->model->table_complete(1,1);
            // $cek = $this->model->cekitemcomplete(12, 0)->result(); 
            // var_dump($cek);exit();
            // $cekorder = substr('TA000123', 0, 2);
            // var_dump($cekorder);exit();
            // $cek = $this->model->cekdessert(1,1,3,'pickup')->num_rows();
            // var_dump($cek);exit();
            // $cekitem = $this->model->cekitem(1,1);
            // var_dump($cekitem->result());exit();
           
			// $table_order2 = $this->model->tableListFood(2,1);
            $table_no_array = array();
            $cekdata_array = array();
            foreach ($table_pickup->result() as $tp) {
                $table_no_array[] = $tp->table_no;
                $cekdata_array[] = $tp->cekdata;
            }
            $count_pickup = $this->model->cekitempickup($table_no_array, $cekdata_array)->num_rows();
            // var_dump($count_pickup);exit();
			$data['outlet'] = 'DAISUKI VETERAN';
            $data['tipe'] = 'Food1';
			$data['table_order1'] = $table_order1;
            $data['table_orderReady'] = $table_orderReady;
            $data['table_process'] = $table_process;
            $data['table_pickup'] = $table_pickup;
            $data['table_complete'] = $table_complete;
            $data['process'] = $table_process->num_rows();
            $data['pickup'] = $count_pickup;
            $data['complete'] = $table_complete->num_rows();
			// $data['table_order2'] = $table_order2;

            $this->load->view('order/index', $data);
        }

        public function food2()
        {
            date_default_timezone_set('Asia/Jakarta');

            $table_order1 = $this->model->tableListFood(1,2);
            // $table_order2 = $this->model->tableListFood(2,2);
            $data['outlet'] = 'DEAR CLIO';
            $data['tipe'] = 'Food2';
            $data['table_order1'] = $table_order1;
            // $data['table_order2'] = $table_order2;

            $this->load->view('order/index', $data);
        }

        public function drink()
        {
            date_default_timezone_set('Asia/Jakarta');

            $table_order1 = $this->model->tableListDrink(1);
            // $table_order2 = $this->model->tableListDrink(2);
            $data['outlet'] = 'DEAR CLIO';
            $data['tipe'] = 'Drink';
            $data['table_order1'] = $table_order1;
            // $data['table_order2'] = $table_order2;

            $this->load->view('order/index', $data);
        } 
    }
    
?>
