<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gender extends MY_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model([
			'mgender'
		]);
	}
	/**
     * tampilan awal dari gender
     * @AclName List Gender
     */
	public function index(){
		$link = base_url()."gender/grid";
		$this->render('gender/gridgender',['link'=>$link]);
	}
	/**
     * Fungsi view Gender
     * @AclName View Gender
     */
	public function view($parameter_key=0){
		$data['data'] = $this->mgender->getById('tblparameter','parameter_key',$parameter_key);
		$this->load->view('gender/view',$data);
	}
	/**
     * Fungsi add Gender
     * @AclName Tambah Gender
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
		$this->load->view('gender/add',['data'=>$data]);
	}
	/**
     * grid
     * @AclName Grid Gender
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

		$sql = $this->mgender->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mgender->get($cond,$sort,$order,$rows,$offset)->result();
		foreach($data as $row){
			$view = hasPermission('gender','view')?'<button class="icon-view_detail" onclick="viewData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$edit = hasPermission('gender','edit')?'<button class="icon-edit" onclick="editData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$del = hasPermission('gender','delete')?'<button class="icon-remove" onclick="deleteData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button>':'';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|parameter_key|".$cond;
		echo json_encode($response);
	}

	/**
     * Fungsi edit Gender
     * @AclName Edit Gender
     */
	public function edit($id){
		$data = $this->mgender->getById('tblparameter','parameter_key',$id);
        if(empty($data)){
            redirect('gender');
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
		$this->load->view('gender/edit',['data'=>$data]);
	}
	/**
     * Fungsi delete Gender
     * @AclName Delete Gender
     */
	public function delete($id){
		$data = $this->mgender->getById('tblparameter','parameter_key',$id);
		if(empty($data)){
			redirect('gender');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mgender->delete($this->input->post('parameter_key'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('gender/delete',['data'=>$data]);
	}
	private function _save($data){
		@$form = array(
			'parametergrpid' =>'GENDER',
			'parameter_key' => @$data['parameter_key'],
			'parameterid' =>  strtoupper(@$data['parameterid']),
			'parametertext' => strtoupper(@$data['parametertext']),
			'parametermemo' => strtoupper(@$data['parametermemo']),
			'modifiedby' => $this->session->userdata('username'),
			'modifiedon' => date("Y-m-d H:i:s")
		);
		return $this->mgender->save($form);
	}
	/**
     * Fungsi untuk export excel
     * @AclName Export Excel Gender
     */
	public function excel(){
		echo "excel";
	}

}