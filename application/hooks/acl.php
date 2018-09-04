<?php
class ACL{
    private $CI;
    private $user;
    private static $instance;

    public function __construct(){
        $this->CI =& get_instance();
        self::$instance = $this;
    }
    public static function get_instance(){
        return self::$instance;
    }
    public function auth(){
        $CI = $this->CI;
        if($this->_checkQuestAccess()){
            return true;
        }
        $user = $this->getLoggedInUser();
        if(!empty($user)){

        }else{
            redirect('/login');
        }
        if(!$this->_validateActionPermission($user['acl']) && $CI->session->userdata('username')!='admin'){
            if(empty($user)){
                redirect('/login');
            }else{
                exit('unauthorized');
            }
        }

    }
    public function getLoggedInUser(){
        $CI = $this->CI;
        $user = $CI->session->userdata('user');

        if(!empty($this->user)){
            return $this->user;
        }
        if(is_numeric($user)){
            $CI->load->model('Mlogin');
            $user = $CI->Mlogin->getDetailWithAclById($user);
        }
        $this->user = $user;
        return $user;
    }
    public function _checkQuestAccess($class = null,$method=null){
        $group = $this->CI->session->userdata('groups.guest');
        if(empty($group)){
            $this->CI->load->model('Mroles');
            $group = $this->CI->Mroles->getGuestGroup();
            $this->CI->session->set_userdata('groups.guest',$group);
        }
        return $this->_validateActionPermission($group['acos'],$class,$method);
    }
    private function _validateActionPermission($acos,$class = null,$method = null){
        if(empty($acos)){
            return false;
        }
        if(empty($class)){
            $class = $this->CI->router->fetch_class();
        }
        if(empty($method)){
            $method = $this->CI->router->fetch_method();
        }
        foreach($acos as $aco){
            if(strtolower(trim($aco['class'])) == strtolower(trim($class)) && strtolower(trim($aco['method']))==strtolower(trim($method))){
                return true;
            }
        }
        return false;
    }
    public function hasPermission($class,$method){
        if($this->_checkQuestAccess($class,$method)){
            return true;
        }
        $user = $this->user;
        if(!empty($user) && isset($user['acl'])){
            return $this->_validateActionPermission($user['acl'],$class,$method);
        }
        return false;
    }
}