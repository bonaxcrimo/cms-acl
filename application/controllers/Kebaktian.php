<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kebaktian extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model([
			'mkebaktian'
		]);
	}
	/**
     * tampilan awal dari kebaktian
     * @AclName List kebaktian
     */
	public function index(){
		$link = base_url()."kebaktian/grid";
		$this->render('kebaktian/gridkebaktian',['link'=>$link]);
	}
	/**
     * Fungsi view kebaktian
     * @AclName View kebaktian
     */
	public function view($parameter_key=0){
		$data['data'] = $this->mkebaktian->getById('tblparameter','parameter_key',$parameter_key);
		$this->load->view('kebaktian/view',$data);
	}
	/**
     * Fungsi add kebaktian
     * @AclName Tambah kebaktian
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
		$this->load->view('kebaktian/add',['data'=>$data]);
	}
	/**
     * grid
     * @AclName Grid kebaktian
     */
	function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'parameterid';
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

		$sql = $this->mkebaktian->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mkebaktian->get($cond,$sort,$order,$rows,$offset)->result();
		foreach($data as $row){
			$view = hasPermission('kebaktian','view')?'<button class="icon-view_detail" onclick="viewData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$edit = hasPermission('kebaktian','edit')?'<button class="icon-edit" onclick="editData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$del = hasPermission('kebaktian','delete')?'<button class="icon-remove" onclick="deleteData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button>':'';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelkebaktian']= "asc|parameter_key|".$cond;
		echo json_encode($response);
	}

	/**
     * Fungsi edit kebaktian
     * @AclName Edit kebaktian
     */
	public function edit($id){
		$data = $this->mkebaktian->getById('tblparameter','parameter_key',$id);
        if(empty($data)){
            redirect('kebaktian');
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
		$this->load->view('kebaktian/edit',['data'=>$data]);
	}
	/**
     * Fungsi delete kebaktian
     * @AclName Delete kebaktian
     */
	public function delete($id){
		$data = $this->mkebaktian->getById('tblparameter','parameter_key',$id);
		if(empty($data)){
			redirect('kebaktian');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mkebaktian->delete($this->input->post('parameter_key'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('kebaktian/delete',['data'=>$data]);
	}
	private function _save($data){
		@$form = array(
			'parametergrpid' =>'KEBAKTIAN',
			'parameter_key' => @$data['parameter_key'],
			'parameterid' =>  strtoupper(@$data['parametertext']),
			'parametertext' => strtoupper(@$data['parametertext']),
			'modifiedby' => $this->session->userdata('username'),
			'modifiedon' => date("Y-m-d H:i:s")
		);
		return $this->mkebaktian->save($form);
	}
	/**
     * Fungsi untuk export excel
     * @AclName Export Excel kebaktian
     */
	public function excel(){
		excel('excelkebaktian','tblparameter','kebaktian/excel');
	}

}