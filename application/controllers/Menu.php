<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mmenu');
	}
	/**
     * Fungsi list menu
     * @AclName List Menu
     */
	function index(){
		$link = base_url().'menu/grid';
		$this->render('menu/gridmenu',['link'=>$link]);
	}
	/**
     * Fungsi grid menu
     * @AclName Grid Menu
     */
	function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'menuid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';
		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';

		$cond = '';
		if (!empty($filterRules)){
			$cond = ' where 1=1 ';
			$filterRules = json_decode($filterRules);
			foreach($filterRules as $rule){
				$rule = get_object_vars($rule);
				$field = $rule['field'];
				$op = $rule['op'];
				$value = $rule['value'];
				if (!empty($value)){
					if ($op == 'contains'){
						$cond .= " and ($field like '%$value%')";
					}
				}
			}
		}

		$sql = $this->mmenu->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mmenu->get($cond,$sort,$order,$rows,$offset)->result();

		foreach($data as $row){
			$route = count(getTableWhere('tblacos',["acosid"=>$row->acoid]))>0?getTableWhere('tblacos',["acosid"=>$row->acoid])[0]:'-';
			$row->acoid = $route!='-'?$route->class."/".$route->method:$route;
			$view = hasPermission('menu','view')?'<button class="icon-view_detail" onclick="viewData(\''.$row->menuid.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$edit = hasPermission('menu','edit')?'<button class="icon-edit" onclick="editData(\''.$row->menuid.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$del = hasPermission('menu','delete')?'<button class="icon-remove" onclick="deleteData(\''.$row->menuid.'\')" style="width:16px;height:16px;border:0"></button>':'';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|menuid|".$cond;
		echo json_encode($response);
	}
	/**
     * Fungsi view Menu
     * @AclName View Menu
     */
	public function view($id){
		$data = $this->mmenu->getById('tblmenu','menuid',$id);
        if(empty($data)){
            redirect('menu');
        }
		$this->load->view('menu/view',['data'=>$data]);
	}
	/**
     * Fungsi add Menu
     * @AclName Tambah Menu
     */
	public function add(){
		$data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();
			$cek = $this->_save($data);
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}else{
			$data = $this->input->post();
		}
		$this->load->view('menu/form',['data'=>$data]);
	}
	/**
     * Fungsi edit menu
     * @AclName Edit Menu
     */
	public function edit($id){
		$data = $this->mmenu->getById('tblmenu','menuid',$id);
        if(empty($data)){
            redirect('menu');
        }
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();
			$data['menuid'] = $this->input->post('menuid');
			$cek = $this->_save($data);
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('menu/form',['data'=>$data]);
	}
	/**
     * Fungsi delete Menu
     * @AclName Delete Menu
     */
	public function delete($id){
		$data = $this->mmenu->getById('tblmenu','menuid',$id);
		if(empty($data)){
			redirect('menu');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mmenu->delete($this->input->post('menuid'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('menu/delete',['data'=>$data]);
	}
	private function _save($data){
        $this->mmenu->save($data);
    }


    /**
     * Fungsi export excel
     * @AclName Export excel
     */
	function excel(){
	}
	/**
     * Fungsi reseq
     * @AclName Re sequence
     */
	function reseq(){
		return $this->mmenu->reseq();
	}

}