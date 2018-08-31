<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function __construct() { 
		parent::__construct(); 
	}

	function index(){
		$this->load->library('session'); // session_start()
		session_destroy();
		redirect("");
	}
}