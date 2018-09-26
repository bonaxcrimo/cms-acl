<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parameter extends MY_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model('mparameter');
	}
	/**
     * Fungsi list menu
     * @AclName List Menu
     */
	public function index(){
		$link = base_url().'parameter/grid';
		$this->render('parameter/gridparameter',['link'=>$link]);
	}
	/**
     * Fungsi grid parameter
     * @AclName Grid parameter
     */
	public function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'parameter_key';
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

		$sql = $this->mparameter->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mparameter->get($cond,$sort,$order,$rows,$offset)->result();


		foreach($data as $row){
			$view = hasPermission('menu','view')?'<button class="icon-view_detail" onclick="viewData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$edit = hasPermission('menu','edit')?'<button class="icon-edit" onclick="editData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$del = hasPermission('menu','delete')?'<button class="icon-remove" onclick="deleteData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button>':'';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|parameter_key|".$cond;
		echo json_encode($response);
	}

	/**
     * Fungsi view parameter
     * @AclName View parameter
     */
	public function view($parameter_key=0){
		$data['data'] = $this->mparameter->getById('tblparameter','parameter_key',$parameter_key);
		$this->load->view('parameter/view',$data);
	}
	/**
     * Fungsi add parameter
     * @AclName Tambah parameter
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
		$this->load->view('parameter/add',['data'=>$data]);
	}
	/**
     * Fungsi edit parameter
     * @AclName Edit parameter
     */
	public function edit($id){
		$data = $this->mparameter->getById('tblparameter','parameter_key',$id);
        if(empty($data)){
            redirect('parameter');
        }
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();
			$data['parameter_key'] = $this->input->post('parameter_key');
			$cek = $this->_save($data);
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('parameter/edit',['data'=>$data]);
	}
	/**
     * Fungsi delete parameter
     * @AclName Delete parameter
     */
	public function delete($id){
		$data = $this->mparameter->getById('tblparameter','parameter_key',$id);
		if(empty($data)){
			redirect('parameter');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mparameter->delete($this->input->post('parameter_key'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('parameter/delete',['data'=>$data]);
	}
	private function _save($data){
		$data = array_map("strtoupper", $data);
		return $this->mparameter->save($data);
	}
	/**
     * Fungsi untuk export excel
     * @AclName Export Excel rayon
     */
	public function excel(){
		echo "excel";
	}

}