<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mprofil extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	
	function get($userid){
		$sql = $this->db->query("SELECT * FROM tbluser WHERE userid='$userid'");
        return $sql;
	}
}