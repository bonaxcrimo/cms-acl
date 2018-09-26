<?php

class Login extends MY_Controller {

	public function __construct() {
		parent::__construct();
        session_start();
		$this->load->model('mlogin');
	}
    /**
     * Fungsi awal login
     * @AclName Login
     */
	public function index(){

		$adminId = $this->session->userdata('logged_in');

        if(!empty($adminId)){
    		redirect('home');
        }
        $error = '';
        if($this->input->server('REQUEST_METHOD') == 'POST'){
        	$data = $this->input->post();
        	$user = $this->mlogin->verifyLogin($data['userid'],$data['password']);
        	if(!empty($user)){
        		$this->session->set_userdata('user',$user['userpk']);
        		$this->session->set_userdata('userpk',$user['userpk']);
                $this->session->set_userdata('username',$user['username']);
        		$this->session->set_userdata('dashboard',$user['dashboard']);
                $this->session->set_userdata('versi','acl');
        		$this->session->set_userdata('logged_in',true);
        		redirect("home");
        	}else{
        		$error = 'Invalid Credentials';
        	}
        }
        $this->render('login',['error'=>$error]);
	}
    /**
     * Fungsi logout
     * @AclName Logout
     */
	public function logout(){
		$this->session->unset_userdata('user');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('excel');
		$this->session->unset_userdata('userpk');
		$this->session->unset_userdata('dashboard');
		$this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('groups.guest');
		redirect('login');
	}
}

