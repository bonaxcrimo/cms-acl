<?php

class MY_Controller extends CI_Controller{
    private $layout = 'home';
    public function __construct(){
        parent::__construct();
        $this->load->model('mmenutop');
    }
    protected function render($page,$data=[]){
        $acl = ACL::get_instance();
        $data['loggedUser'] = $acl->getLoggedInUser();
        if(!$data['loggedUser']==""){
            $data['sqlmenu'] = $this->mmenutop->get_data();
            $this->load->view('partials/header');
            $this->load->view('navbar',$data);
        }
        @$format_tgl = queryCustom("select * from tblparameter where parametergrpid='FORMAT_TGL' and parameterid='UMUM' ")->parametertext;
        $this->load->view($this->getLayout(),['template'=>$page,'data'=>$data,'format_tgl'=>@$format_tgl]);
        $this->load->view('partials/footer',['format_tgl'=>@$format_tgl]);
    }
    public function set(){
        $_SESSION['userpk'] = $_GET['userpk'];
    }
    protected function setLayout($layout){
        $this->layout = $layout;
        return $this;
    }
    protected function getLayout(){
        return 'layouts/'.$this->layout;
    }
}