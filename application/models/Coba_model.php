<?php
class Coba_model extends CI_Model{
	public function __construct(){
        $this->load->database();
    }
    public function get_table($table){
        $query=$this->db->get($table);
        return $query->result_object();
    }
}
?>