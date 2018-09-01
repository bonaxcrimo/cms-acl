<?php

class Login extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('mlogin');
	}

	public function index(){
		$adminId = $this->session->userdata('userpk');
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
        		$this->session->set_userdata('dashboard',$user['dashboard']);
        		$this->session->set_userdata('logged_in',true);
        		redirect("home");
        	}else{
        		$error = 'Invalid Credentials';
        	}
        }
        $this->render('login',['error'=>$error]);
	}
	public function logout(){
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('userpk');
		$this->session->unset_userdata('dashboard');
		$this->session->unset_userdata('logged_in');
		redirect('login');
	}
	// public function proses(){
	// 	$userid = $this->input->post('userid');
	// 	$password = md5($this->input->post('password'));
	// 	$cek = $this->mlogin->login($userid,$password);

	// 	if($cek!=""){
	// 		foreach ($cek->result() as $row){
	// 			$_SESSION['userpk']=$row->userpk;
	// 			$_SESSION['userid']=$row->userid;
	// 			$_SESSION['username']=$row->username;
	// 			$_SESSION['userlevel']=$row->userlevel;
	// 			$_SESSION['password']=$row->password;
	// 			$_SESSION['dashboard']=$row->dashboard;
	// 		}
	// 		redirect("home");
	// 	}
	// 	else{
	// 		$data["error"]="Kombinasi userid Atau Password Salah";
	// 		$this->load->view('login',$data);
	// 		session_destroy();
	// 	}
	// }
}

