<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mlogin');

	}

	public function index(){
		$users = $this->mlogin->getList();
		$this->render($_SESSION['dashboard'],['users'=>$users,]);
	}
	public function bumi(){
		$users = $this->mlogin->getList();
		print_r($users);
	}
}
