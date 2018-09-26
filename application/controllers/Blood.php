<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class blood extends MY_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model([
			'mblood'
		]);
	}
	/**
     * tampilan awal dari blood
     * @AclName List Blood
     */
	public function index(){
		$link = base_url()."blood/grid";
		$this->render('blood/gridblood',['link'=>$link]);
	}
	/**
     * Merupakan Grid dari Blood
     * @AclName Grid Blood
     */
	public function grid(){
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

		$sql = $this->mblood->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mblood->get($cond,$sort,$order,$rows,$offset)->result();

		foreach($data as $row){
			$view = hasPermission('blood','view')?'<button class="icon-view_detail" onclick="viewBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$edit = hasPermission('blood','edit')?'<button class="icon-edit" onclick="editBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$del = hasPermission('blood','delete')?'<button class="icon-remove" onclick="deleteBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button>':'';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelblood']= "asc|parameter_key|".$cond;
		echo json_encode($response);
	}
	/**
     * Fungsi view blood
     * @AclName View Blood
     */
	public function view($parameter_key=0){
		// $data["data"] = $this->mblood->getListAll('tblparameter',['parameter_key'=>$parameter_key]);
		$data['data'] = $this->mblood->getById('tblparameter','parameter_key',$parameter_key);
		$this->load->view('blood/view',$data);
	}
	/**
     * Fungsi add blood
     * @AclName Tambah Blood
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
		$this->load->view('blood/add',['data'=>$data]);
	}
	/**
     * Fungsi edit blood
     * @AclName Edit Blood
     */
	public function edit($id){
		$data = $this->mblood->getById('tblparameter','parameter_key',$id);
        if(empty($data)){
            redirect('blood');
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
		$this->load->view('blood/edit',['data'=>$data]);
	}
	/**
     * Fungsi delete blood
     * @AclName Delete Blood
     */
	public function delete($id){
		$data = $this->mblood->getById('tblparameter','parameter_key',$id);
		if(empty($data)){
			redirect('blood');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mblood->delete($this->input->post('parameter_key'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('blood/delete',['data'=>$data]);
	}
	private function _save($data){
		@$form = array(
			'parametergrpid' =>'BLOOD',
			'parameter_key' => @$data['parameter_key'],
			'parameterid' =>  strtoupper(@$data['parameterid']),
			'parametertext' => strtoupper(@$data['parametertext']),
			'parametermemo' => strtoupper(@$data['parametermemo']),
			'modifiedby' => $this->session->userdata('username'),
			'modifiedon' => date("Y-m-d H:i:s")
		);
		return $this->mblood->save($form);
	}
	/**
     * Fungsi untuk export excel
     * @AclName Export Excel Blood
     */
	public function excel(){
		echo "excel";
	}

}