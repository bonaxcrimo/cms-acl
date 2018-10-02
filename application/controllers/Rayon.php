<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rayon extends MY_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model([
			'mrayon'
		]);
	}
	/**
     * tampilan awal dari rayon
     * @AclName List rayon
     */
	public function index(){
		$link = base_url()."rayon/grid";
		$this->render('rayon/gridrayon',['link'=>$link]);
	}
	/**
     * Fungsi view rayon
     * @AclName View rayon
     */
	public function view($parameter_key=0){
		$data['data'] = $this->mrayon->getById('tblparameter','parameter_key',$parameter_key);
		$this->load->view('rayon/view',$data);
	}
	/**
     * Fungsi add rayon
     * @AclName Tambah rayon
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
		$this->load->view('rayon/add',['data'=>$data]);
	}
	/**
     * grid
     * @AclName Grid rayon
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

		$sql = $this->mrayon->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mrayon->get($cond,$sort,$order,$rows,$offset)->result();
		foreach($data as $row){
			$view = hasPermission('rayon','view')?'<button class="icon-view_detail" onclick="viewData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$edit = hasPermission('rayon','edit')?'<button class="icon-edit" onclick="editData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$del = hasPermission('rayon','delete')?'<button class="icon-remove" onclick="deleteData(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button>':'';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|parameter_key|".$cond;
		echo json_encode($response);
	}

	/**
     * Fungsi edit rayon
     * @AclName Edit rayon
     */
	public function edit($id){
		$data = $this->mrayon->getById('tblparameter','parameter_key',$id);
        if(empty($data)){
            redirect('rayon');
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
		$this->load->view('rayon/edit',['data'=>$data]);
	}
	/**
     * Fungsi delete rayon
     * @AclName Delete rayon
     */
	public function delete($id){
		$data = $this->mrayon->getById('tblparameter','parameter_key',$id);
		if(empty($data)){
			redirect('rayon');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mrayon->delete($this->input->post('parameter_key'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('rayon/delete',['data'=>$data]);
	}
	private function _save($data){
		@$form = array(
			'parametergrpid' =>'RAYON',
			'parameter_key' => @$data['parameter_key'],
			'parameterid' =>  strtoupper(@$data['parameterid']),
			'parametertext' => strtoupper(@$data['parametertext']),
			'parametermemo' => strtoupper(@$data['parametermemo']),
			'modifiedby' => $_SESSION['username'],
			'modifiedon' => date("Y-m-d H:i:s")
		);
		return $this->mrayon->save($form);
	}
	/**
     * Fungsi untuk export excel
     * @AclName Export Excel rayon
     */
	public function excel(){
		echo "excel";
	}

}