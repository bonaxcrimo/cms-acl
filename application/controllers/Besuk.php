<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Besuk extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model([
			'mbesuk',
			'mgender',
			'mpstatus',
			'mblood',
			'mkebaktian',
			'mpersekutuan',
			'mrayon',
			'mjemaat',
			'mparameter',
			'mserving',
			'mmenu'
		]);
	}
	public function set(){
		$_SESSION['member_key'] = $_GET['member_key'];
	}
	/**
     * Fungsi awal besuk
     * @AclName Awal besuk
     */
	public function index(){
		$link = base_url()."besuk/gridbesuk";
		$this->render('besuk/gridbesuk',['link'=>$link]);
	}
	public function jemaat(){
		if(empty($_SESSION['member_key'])){
			echo" Empty";
		}
		else{
			$data['member_key'] = $_SESSION['member_key'];
			$data['sql'] = $this->mbesuk->getwhere($_SESSION['member_key']);


			$data['sqlgender'] = getParameter('GENDER');
			$data['sqlpstatus'] =getParameter('PSTATUS');

			$data['sqlstatusidv'] = getParameter('STATUS');
			$data['sqlblood'] =getParameter('BLOOD');
			$data['sqlkebaktian'] =getParameter('KEBAKTIAN');
			$data['sqlpersekutuan'] =getParameter('PERSEKUTUAN');
			$data['sqlrayon'] =getParameter('RAYON');

			$data['statusidv'] = getComboParameter('STATUS');
			$data['blood'] = getComboParameter('BLOOD');
			$data['gender'] = getComboParameter('GENDER');
			$data['pstatus'] = getComboParameter('PSTATUS');
			$data['kebaktian'] = getComboParameter('KEBAKTIAN');
			$data['persekutuan'] =getComboParameter('PERSEKUTUAN');
			$data['rayon'] = getComboParameter('RAYON');
			$this->load->view('jemaat/gridbesuk',$data);
		}
	}

	/**
     * Fungsi grid besuk di jemaat
     * @AclName grid besuk di jemaat
     */
	public function gridBesukJemaat($member_key){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		if (!empty($filterRules)){
			$cond = ' where member_key = "'.$member_key.'" and  1=1 ';
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
		}else{
			$cond = ' where member_key = "'.$member_key.'" ';
		}
		$where='';
		$sql = $this->mbesuk->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mbesuk->get($cond,$sort,$order,$rows,$offset)->result();
		foreach($data as $row){
			$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewData(\''.$row->besukid.'\')" style="width:16px;height:16px;border:0"></button> ';
			$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="editData(\''.$row->besukid.'\');" style="width:16px;height:16px;border:0"></button> ';
			$del = '<button id='.$row->member_key.' class="icon-remove" onclick="deleteData(\''.$row->besukid.'\');" style="width:16px;height:16px;border:0"></button>';
			$row->aksi =$view.$edit.$del;
			$row->besukdate=$row->besukdate=="00-00-0000"?"-":$row->besukdate;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelbesuk']= "asc|member_key|".$cond;
		echo json_encode($response);
	}
	/**
     * Fungsi grid besuk
     * @AclName grid besuk
     */
	public function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		if (!empty($filterRules)){
			$cond = ' where  1=1 ';
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
		$sql = $this->mbesuk->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mbesuk->get($cond,$sort,$order,$rows,$offset)->result();
		foreach($data as $row){
			$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewData(\''.$row->besukid.'\')" style="width:16px;height:16px;border:0"></button> ';
			$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="editData(\''.$row->besukid.'\');" style="width:16px;height:16px;border:0"></button> ';
			$del = '<button id='.$row->member_key.' class="icon-remove" onclick="deleteData(\''.$row->besukid.'\');" style="width:16px;height:16px;border:0"></button>';
			$row->aksi =$view.$edit.$del;
			$row->besukdate=$row->besukdate=="00-00-0000"?"-":$row->besukdate;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelbesuk']= "asc|member_key|".$cond;
		echo json_encode($response);
	}
	/**
     * Fungsi add besuk
     * @AclName Tambah besuk
     */
	public function add($member_key=null){
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
		$check=$member_key==null?0:$member_key;
		$this->load->view('besuk/add',['data'=>$data,'check'=>$check]);
	}
	/**
     * Fungsi edit besuk
     * @AclName Edit besuk
     */
	public function edit($id,$member_key=null){
		$data = $this->mbesuk->getById('tblbesuk','besukid',$id);
        if(empty($data)){
            redirect('besuk');
        }
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();
			$data['besukid'] = $this->input->post('besukid');
			$cek = $this->_save($data);
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$check=$member_key==null?0:$member_key;
		$this->load->view('besuk/edit',['data'=>$data,'check'=>$check]);
	}
	/**
     * Fungsi delete besuk
     * @AclName Delete besuk
     */
	public function delete($id,$member_key=null){
		$data = $this->mbesuk->getById('tblbesuk','besukid',$id);
		if(empty($data)){
			redirect('besuk');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mbesuk->delete($this->input->post('besukid'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$check=$member_key==null?0:$member_key;
		$this->load->view('besuk/delete',['data'=>$data,'check'=>$check]);
	}
	private function _save($data){
		$data = array_map("strtoupper", $data);
        $this->mbesuk->save($data);
    }
	/**
     * Fungsi view besuk
     * @AclName View besuk
     */
	public function view($id,$member_key=null){
		$data = $this->mbesuk->getById('tblbesuk','besukid',$id);
        if(empty($data)){
            redirect('besuk');
        }
        $check=$member_key==null?0:$member_key;
		$this->load->view('besuk/view',['data'=>$data,'check'=>$check]);
	}

	/**
     * Fungsi export excel
     * @AclName Export excel
     */
	public function excel(){
		excel('excelbesuk','tblbesuk','besuk/excel');
	}
}