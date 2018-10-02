<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mprofil extends MY_Model {
	function __construct() {
        parent::__construct();
    }

	function get($userpk){
		$sql = $this->db->query("SELECT * FROM tbluser WHERE userpk='$userpk'");
        return $sql;
	}
}