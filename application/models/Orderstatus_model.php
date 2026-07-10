<?php 
    class Orderstatus_model extends CI_model {
    public function cekstatusorder($cabang, $notrans, $status) 
    {
        $this->db->select("
            a.item_code, 
            i.image_path,
            a.extra_notes,
            SUM(a.qty) AS qty, 
            a.disc, 
            a.description, 
            CASE WHEN a.unit_price > 0 THEN a.unit_price ELSE 'FREE' END AS unit_price, 
            CASE WHEN (SUM(a.qty * a.unit_price) - SUM(a.qty * a.unit_price * (a.disc / 100))) > 0 
                 THEN (SUM(a.qty * a.unit_price) - SUM(a.qty * a.unit_price * (a.disc / 100))) 
                 ELSE 'FREE' 
            END AS sub_total", false);
        
        $this->db->from("sh_t_transaction_details a");
        $this->db->join("sh_t_transactions b", "a.id_trans = b.id", "inner");
        $this->db->join("sh_m_item i", "a.item_code = i.no", "inner");
        $this->db->join("sh_m_customer c", "c.id = b.id_customer", "inner");

        $this->db->where("a.is_cancel", 0);
        $this->db->where("a.parent_id_package", 0);
        $this->db->where("b.cabang", $cabang);
        $this->db->where("b.id", $notrans);

        // Filter berdasarkan status
        if ($status == 'complete') {
            $this->db->where("a.end_time_order IS NOT NULL", null, false);
        } else if ($status == 'proses') {
            $this->db->where("a.is_complete < a.qty", null, false);
            $this->db->where("a.is_paid", 1);
        } else if ($status == 'deliver') {
            $this->db->where("a.is_complete = a.qty", null, false);
            $this->db->where("a.runner_by IS NOT NULL", null, false);
            $this->db->where("a.end_time_order IS NULL", null, false);
            $this->db->where("a.is_paid", 0);
        } else if($status == 'awaiting'){
            $this->db->where("a.is_paid", 0);
        }

        // Group by item_code, id_trans, dan extra_notes agar item dengan notes berbeda tidak digabung
        $this->db->group_by("a.item_code, a.id_trans, a.extra_notes");
        $this->db->order_by("a.id", "asc");

        return $this->db->get()->result();
    }
}
