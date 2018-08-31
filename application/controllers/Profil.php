<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profil extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session'); // session_start()
		$this->load->model('mlogin');
		$cek = $this->mlogin->cek();
		if($cek==""){
			redirect("");
			session_destroy();
		}
		date_default_timezone_set("Asia/Jakarta");
		ini_set('memory_limit', '-1');
		$this->load->model('mmenutop');
		$this->load->model('muser');
		$this->load->model('mprofil');
		$this->load->helper('my_helper');
	}
	
	function index(){
		$data['sqlmenu'] = $this->mmenutop->get_data();
		$data['sqlprofil'] = $this->mprofil->get($_SESSION['userid']);
		$this->load->view('header');
		$this->load->view('navbar',$data);
		$this->load->view('profil/view',$data);
		$this->load->view('footer');
	}

	function editprofil(){
		$userpk = $_SESSION['userpk'];
		$userid = $_GET['userid'];
		$username = $_GET['username'];
		$data = array('userid' => $userid,'username' => $username);
		$data = $this->muser->edit("tbluser",$data,$userpk);
		$_SESSION['userid']=$userid;
		$_SESSION['username']=$username;
		echo "1";
	}

	function editpassword(){
		$userpk = $_SESSION['userpk'];
		$password = $_SESSION['password'];
		$password1 = md5($_GET['password1']);
		$password2 = md5($_GET['password2']);
		$password3 = md5($_GET['password3']);
		if($password!=$password1){
			echo"1";
		}
		else if($password2!=$password3){
			echo"2";
		}
		else{
			$data = array('password' => $password2 );
			$data = $this->muser->edit("tbluser",$data,$userpk);
			$_SESSION['password']=$password2;
			echo"3";
		}
	}
}
