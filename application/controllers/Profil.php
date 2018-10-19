<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profil extends MY_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model(
			['muser','mprofil']
		);
	}
	/**
     * Fungsi awal profil user
     * @AclName profil
     */
	public function index(){
		$data['row'] = $this->mprofil->get($_SESSION['userpk'])->row();
        $this->render('profil/view',$data);
	}
	/**
     * Fungsi edit password user
     * @AclName editpassword
     */
	public function editpassword(){
		$userpk = $_SESSION['userpk'];
		$password = $_SESSION['password'];
		$password1 = md5($_POST['password']);
		$password2 = md5($_POST['passwordbaru']);
		$password3 = md5($_POST['ulangpassword']);
		$error="sukses";
		if($password!=$password1){
			$error="gagal";
			$msg = "Password Lama Salah";
		}
		else if($password2!=$password3){
			$error="gagal";
			$msg = "Password Baru Tidak Sama";
		}
		else{
			$data = array('password' => $password2 );
			$data = $this->muser->edit("tbluser",$data,$userpk);
			$_SESSION['password']=$password2;
			$msg = "Password Anda Berhasil terganti";
		}
		$hasil = array(
	        'status' => $error,
	        'msg'=>$msg
	    );
	    echo json_encode($hasil);
	}
}
