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
        $adminId = @$_SESSION['logged_in'];
        if(!empty($adminId)){
    		redirect('home');
        }
        $error = '';
        if($this->input->server('REQUEST_METHOD') == 'POST'){
        	$data = $this->input->post();
        	$user = $this->mlogin->verifyLogin($data['userid'],$data['password']);
        	if(!empty($user)){
                $_SESSION['user'] = $user['userpk'];
                $_SESSION['userpk'] = $user['userpk'];
                $_SESSION['password'] = $user['password'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['dashboard'] = $user['dashboard'];
                $_SESSION['versi'] ='acl';
                $_SESSION['logged_in'] = true;
                $status="sukses";
                $msg="Anda Berhasil Login";
        	}else{
        		$msg = 'Username atau Password Salah';
                $status="gagal";
        	}
            $hasil = array(
                'status' =>$status,
                'msg'=>$msg
            );
            // echo json_encode($hasil);
            redirect("home");
        }else{
            $this->render('login',['error'=>$error]);
        }

	}
    // public function index2(){
    //      $this->render('loginnew');
    // }
    /**
     * Fungsi logout
     * @AclName Logout
     */
	public function logout(){
        session_unset();
        session_destroy();
		redirect('login');
	}
}

