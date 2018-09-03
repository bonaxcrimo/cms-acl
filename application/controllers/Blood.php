<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class blood extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model([
			'mblood'
		]);
	}
	/**
     * tampilan awal dari blood
     * @AclName List Blood
     */
	function index(){
		$link = base_url()."blood/grid";
		$this->render('blood/gridblood',['link'=>$link]);
	}
	/**
     * Merupakan Grid dari Blood
     * @AclName Grid Blood
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
					} else if ($op == 'greater'){
						$cond .= " and $field>$value";
					}
				}
			}
		}
		$where='';
		$sql = $this->mblood->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mblood->getJ($cond,$sort,$order,$rows,$offset)->result();
		// $total = count($data);
		foreach($data as $row){
				$view = '<button class="icon-view_detail" onclick="viewBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ';
				$edit = '<button class="icon-edit" onclick="editBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ';
				$del = '<button class="icon-remove" onclick="deleteBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button>';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|parameter_key|".$cond;
		echo json_encode($response);
	}

	public function view($parameter_key=0){
		$data["data"] = $this->mblood->getListAll('tblparameter',['parameter_key'=>$parameter_key]);
		$this->load->view('blood/view',$data);
	}
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
	public function edit($id){
		$data = $this->mblood->getById($id);
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
	public function delete($id){
		$data = $this->mblood->getById($id);
		if(empty($data)){
			redirect('blood');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$data['parameter_key'] = $this->input->post('parameter_key');
			$cek = $this->mblood->delete($data['parameter_key']);
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
			'parameterid' =>  strtoupper(@$data['parametertext']),
			'parametertext' => strtoupper(@$data['parametertext']),
			'modifiedby' =>$this->session->userdata('username'),
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