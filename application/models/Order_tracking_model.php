<?php 
    class Order_tracking_model extends CI_Model
    {
        public function tableListFood($serverId=1,$param=1) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  inner join sh_m_item mt on dt.item_code = mt.no 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  ".$where."
						  and dt.is_cancel = 0
						  AND dt.end_time_order IS NULL 
						  AND t.parent_id = 0 
						   
						  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
						  and (dt.is_complete < dt.qty) 
						  and dt.selected_table_no not like 'TA%' 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }

        public function cek($serverId=1,$param=1,$nomeja) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  inner join sh_m_item mt on dt.item_code = mt.no 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  
						  and dt.selected_table_no = ".$nomeja."
						  and dt.is_cancel = 0 
						   
						  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
						  and (dt.is_complete < dt.qty) 
						  and dt.selected_table_no not like 'TA%' 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }
        public function cekdessert($serverId=1,$param=1,$nomeja,$cek=NULL) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
			if ($cek) {
				$query = "SELECT t.id, dt.selected_table_no AS table_no,mt.sub_category 
		          FROM sh_t_transactions t 
		          INNER JOIN sh_t_transaction_details dt ON dt.id_trans = t.id 
		          INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
		          WHERE DATE(t.create_date) = DATE(SYSDATE()) 
		          AND mt.sub_category = 'dessert' 
		          AND dt.selected_table_no = '".$nomeja."' 
		          AND dt.is_cancel = 0 
		          AND (dt.is_complete = dt.qty) 
		          AND dt.selected_table_no NOT LIKE 'TA%' 
		          GROUP BY dt.id_trans, dt.selected_table_no 
		          ORDER BY dt.start_time_order ASC";
			}else{
				$query = "SELECT t.id, dt.selected_table_no AS table_no,mt.sub_category 
		          FROM sh_t_transactions t 
		          INNER JOIN sh_t_transaction_details dt ON dt.id_trans = t.id 
		          INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
		          WHERE DATE(t.create_date) = DATE(SYSDATE()) 
		          AND mt.sub_category = 'dessert' 
		          AND dt.selected_table_no = '".$nomeja."' 
		          AND dt.is_cancel = 0 
		          AND (dt.is_complete < dt.qty) 
		          AND dt.selected_table_no NOT LIKE 'TA%' 
		          GROUP BY dt.id_trans, dt.selected_table_no 
		          ORDER BY dt.start_time_order ASC";
			}

				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }
        public function cekRP($serverId=1,$param=1,$nomeja,$cekdata) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  inner join sh_m_item mt on dt.item_code = mt.no 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  and dt.cekdata = ".$cekdata."
						  and dt.selected_table_no = ".$nomeja."
						  and dt.is_cancel = 0 
						   
						  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
						  and (dt.is_complete = dt.qty) 
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }
        public function cekitem($nomeja,$cekdata)
        {
        	$db1 = $this->load->database("default", TRUE);
				$query = "select dt.* from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  
						  and dt.selected_table_no = ".$nomeja."
						  and dt.cekdata = ".$cekdata."
						  and dt.is_cancel = 0
						  and dt.is_complete < dt.qty 
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        }
        public function cekitempickup($nomeja,$cekdata)
        {
        	$nomejaList = implode("','", $nomeja);
        	$cekdataList = implode("','", $cekdata);
        	// var_dump($cekdataList);exit();
        	$db1 = $this->load->database("default", TRUE);
				$query = "SELECT dt.* 
		          FROM sh_t_transactions t 
		          INNER JOIN sh_t_transaction_details dt ON dt.id_trans = t.id 
		          WHERE DATE(t.create_date) = DATE(SYSDATE()) 
		          AND dt.end_time_order IS NULL
		          AND dt.selected_table_no IN ('" . $nomejaList . "') 
		          AND dt.cekdata IN ('" . $cekdataList . "') 
		          AND dt.is_cancel = 0
		          AND dt.is_complete = dt.qty 
		          AND dt.selected_table_no NOT LIKE 'TA%'
		          group by dt.id_trans 
		          ORDER BY dt.start_time_order ASC";

				return $db1->query($query);
        }
        public function cekitemcomplete($nomeja,$cekdata)
        {
        	$db1 = $this->load->database("default", TRUE);
				$query = "select dt.* from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  
						  and dt.selected_table_no = ".$nomeja."
						  and dt.cekdata = ".$cekdata."
						  and dt.is_cancel = 0
						  and dt.is_complete = dt.qty 
						  and dt.end_time_order is null
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        }
        public function tableListFoodReady($serverId=1,$param=1) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no,dt.cekdata from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  inner join sh_m_item mt on dt.item_code = mt.no 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  ".$where."
						  and dt.is_cancel = 0
						  AND dt.end_time_order IS NULL
						  
						  AND dt.is_paid = 1
						   
						  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
						   
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }
        public function table_process($serverId=1,$param=1) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no,dt.cekdata from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  inner join sh_m_item mt on dt.item_code = mt.no 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  ".$where."
						  and dt.is_cancel = 0
						  AND dt.end_time_order IS NULL
						  
						  AND dt.is_paid = 1
						  AND dt.is_complete < dt.qty
						   
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }
        public function table_pickup($serverId=1,$param=1) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no,dt.cekdata,dt.qty,dt.is_complete from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  ".$where."
						  and dt.is_cancel = 0
						  AND dt.end_time_order IS NULL
						 
						  AND dt.is_paid = 1
						  AND dt.is_complete = dt.qty
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }
         public function table_complete($serverId=1,$param=1) 
        {
        	// if($param == 1){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($param == 2){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else{
        	// 	$where = " and mt.category in ('NONE') ";
        	// }
        	$where = "";
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no,dt.cekdata from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  inner join sh_m_item mt on dt.item_code = mt.no 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  ".$where."
						  and dt.is_cancel = 0
						 
						  AND dt.is_paid = 1
						   
						  AND dt.is_complete = dt.qty
						  AND dt.end_time_order is not null
						   
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  ".$where."
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/
				// 		  and (dt.is_complete < dt.qty)  
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }
        public function tableListDrink($serverId=1) 
        {
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
						  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
						  inner join sh_m_item mt on dt.item_code = mt.no 
						  where left(t.create_date,10) = left(sysdate(),10) 
						  and mt.category in ('MINUMAN') 
						  and dt.is_cancel = 0 
						   
						  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/ 
						  and (dt.is_complete < dt.qty) 
						  /*and dt.selected_table_no not like 'TA%'*/ 
						  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select t.id, dt.selected_table_no as table_no from sh_t_transactions t 
				// 		  inner join sh_t_transaction_details dt on dt.id_trans = t.id 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where left(t.create_date,10) = left(sysdate(),10) 
				// 		  and mt.category in ('MINUMAN') 
				// 		  and dt.is_cancel = 0 
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/ 
				// 		  and (dt.is_complete < dt.qty) 
				// 		  and dt.selected_table_no not like 'TA%' 
				// 		  group by dt.id_trans,dt.selected_table_no order by dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }

        public function detailList($serverId=1,$tipe,$idTrans,$tableNo) 
        {
        	// if($tipe == "Food1"){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($tipe == "Food2"){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else if($tipe == "Drink"){
        	// 	$where = " and mt.category in ('MINUMAN') ";
        	// }else{
        		$where = "";
        	// }
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
						$query = "(SELECT dt.id, dt.description AS menu, dt.qty, mt.sub_category AS cat 
		          FROM sh_t_transaction_details dt 
		          INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
		          INNER JOIN sh_m_item_sub_category sc ON sc.description = mt.sub_category 
		          WHERE dt.is_cancel = 0 
		          ".$where."
		          
		          AND dt.runner_scan_date IS NULL
		          AND dt.end_time_order IS NULL
		          /*AND (dt.runner_scan_date IS NULL OR (dt.is_complete < dt.qty))*/ 
		          AND (dt.is_complete < dt.qty) 
		          AND dt.id_trans = '".$idTrans."' 
		          AND dt.selected_table_no = '".$tableNo."' 
		          GROUP BY dt.selected_table_no, dt.item_code)

		          UNION 

		          (SELECT dt.id, dt.description AS menu, dt.qty, mt.sub_category AS cat 
		          FROM sh_t_transaction_details dt 
		          INNER JOIN sh_t_transactions t ON dt.id_trans = t.id 
		          INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
		          INNER JOIN sh_m_item_sub_category sc ON sc.description = mt.sub_category 
		          WHERE dt.is_cancel = 0 
		          ".$where."
		          
		          AND dt.runner_scan_date IS NULL
		          AND dt.end_time_order IS NULL
		          /*AND (dt.runner_scan_date IS NULL OR (dt.is_complete < dt.qty))*/ 
		          AND (dt.is_complete < dt.qty) 
		          AND t.parent_id = '".$idTrans."' 
		          AND dt.selected_table_no = '".$tableNo."' 
		          GROUP BY dt.selected_table_no, dt.item_code)
		          
		          ORDER BY cat, menu ASC";

		return $db1->query($query);

        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select dt.id, dt.description as menu, dt.qty, mt.sub_category as cat from sh_t_transaction_details dt 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  inner join sh_m_item_sub_category sc on sc.description = mt.sub_category 
				// 		  where dt.is_cancel = 0 
				// 		  ".$where."
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/ 
				// 		  and (dt.is_complete < dt.qty) 
				// 		  and dt.id_trans = '".$idTrans."' 
				// 		  and dt.selected_table_no = '".$tableNo."' group by dt.selected_table_no,dt.item_code order by sc.id,dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }

        public function detailListReady($serverId=1,$tipe,$idTrans,$tableNo) 
        {
        	// if($tipe == "Food1"){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($tipe == "Food2"){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else if($tipe == "Drink"){
        	// 	$where = " and mt.category in ('MINUMAN') ";
        	// }else{
        		$where = "";
        	// }
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "(SELECT dt.id, dt.description AS menu, dt.qty, mt.sub_category AS cat 
			          FROM sh_t_transaction_details dt 
			          INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
			          INNER JOIN sh_m_item_sub_category sc ON sc.description = mt.sub_category 
			          WHERE dt.is_cancel = 0 
			          ".$where."
			          
			          AND dt.runner_scan_date IS NOT NULL
			          AND dt.end_time_order IS NULL  
			          /*AND (dt.runner_scan_date IS NULL OR (dt.is_complete < dt.qty))*/ 
			          AND (dt.is_complete = dt.qty) 
			          AND dt.id_trans = '".$idTrans."' 
			          AND dt.selected_table_no = '".$tableNo."' 
			          GROUP BY dt.selected_table_no, dt.item_code)

			          UNION 

			          (SELECT dt.id, dt.description AS menu, dt.qty, mt.sub_category AS cat 
			          FROM sh_t_transaction_details dt
			          INNER JOIN sh_t_transactions t ON dt.id_trans = t.id 
			          INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
			          INNER JOIN sh_m_item_sub_category sc ON sc.description = mt.sub_category 
			          WHERE dt.is_cancel = 0 
			          ".$where."
			          
			          AND dt.runner_scan_date IS NOT NULL
			          AND dt.end_time_order IS NULL  
			          /*AND (dt.runner_scan_date IS NULL OR (dt.is_complete < dt.qty))*/ 
			          AND (dt.is_complete = dt.qty) 
			          AND t.parent_id = '".$idTrans."' 
			          AND dt.selected_table_no = '".$tableNo."' 
			          GROUP BY dt.selected_table_no, dt.item_code)
			          
			          ORDER BY cat, menu ASC";

			return $db1->query($query);

        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select dt.id, dt.description as menu, dt.qty, mt.sub_category as cat from sh_t_transaction_details dt 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  inner join sh_m_item_sub_category sc on sc.description = mt.sub_category 
				// 		  where dt.is_cancel = 0 
				// 		  ".$where."
				// 		   
				// 		  /*and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/ 
				// 		  and (dt.is_complete < dt.qty) 
				// 		  and dt.id_trans = '".$idTrans."' 
				// 		  and dt.selected_table_no = '".$tableNo."' group by dt.selected_table_no,dt.item_code order by sc.id,dt.start_time_order asc ";
				// return $db2->query($query);
        	}
        }

        public function remainingList($serverId=1,$tipe,$idTrans,$tableNo) 
        {
        	// if($tipe == "Food1"){
        	// 	$where = " and mt.category in ('SIAP SAJI') ";
        	// }else if($tipe == "Food2"){
        	// 	$where = " and mt.category in ('PROSES') ";
        	// }else if($tipe == "Drink"){
        	// 	$where = " and mt.category in ('MINUMAN') ";
        	// }else{
        		$where = "";
        	// }
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "SELECT SUM(items) AS total_items FROM (
				    (SELECT COUNT(x.item) AS items FROM (
				        SELECT dt.item_code AS item 
				        FROM sh_t_transaction_details dt 
				        INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
				        WHERE dt.id_trans = '".$idTrans."' 
				        ".$where."
				        AND dt.selected_table_no = '".$tableNo."'
				        /*AND dt.is_cancel = 0 AND (dt.runner_scan_date IS NULL OR (dt.is_complete < dt.qty))*/ 
				        AND dt.is_cancel = 0 AND (dt.is_complete < dt.qty) 
				        GROUP BY dt.item_code) AS x)
				    UNION ALL
				    (SELECT COUNT(x.item) AS items FROM (
				        SELECT dt.item_code AS item 
				        FROM sh_t_transaction_details dt
				        INNER JOIN sh_t_transactions t ON dt.id_trans = t.id 
				        INNER JOIN sh_m_item mt ON dt.item_code = mt.no 
				        WHERE t.parent_id = '".$idTrans."' 
				        ".$where."
				        AND dt.selected_table_no = '".$tableNo."'
				        /*AND dt.is_cancel = 0 AND (dt.runner_scan_date IS NULL OR (dt.is_complete < dt.qty))*/ 
				        AND dt.is_cancel = 0 AND (dt.is_complete < dt.qty) 
				        GROUP BY dt.item_code) AS x)
				) AS total_items_query";

				return $db1->query($query);

        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select count(x.item) as items from (select dt.item_code as item 
				// 		  from sh_t_transaction_details dt 
				// 		  inner join sh_m_item mt on dt.item_code = mt.no 
				// 		  where dt.id_trans = '".$idTrans."' 
				// 		  ".$where."
				// 		  and dt.selected_table_no = '".$tableNo."'
				// 		  /*and dt.is_cancel = 0 and (dt.runner_scan_date is null or (dt.is_complete < dt.qty))*/ 
				// 		  and dt.is_cancel = 0 and (dt.is_complete < dt.qty) group by dt.item_code) as x ";
				// return $db2->query($query);
        	}
        }

        public function categoryList($serverId=1,$desc) 
        {
        	if($serverId == 1){
        		//Load database
				$db1 = $this->load->database("default", TRUE);
				$query = "select description, color_set from sh_m_item_sub_category
						  where description = '".$desc."'limit 1 ";
				return $db1->query($query);
        	}else{
        		//Load database
				// $db2 = $this->load->database("default", TRUE);
				// $query = "select description, color_set from sh_m_item_sub_category
				// 		  where description = '".$desc."'limit 1 ";
				// return $db2->query($query);
        	}
        }
    }
?>
