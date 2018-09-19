<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Mbesuk extends MY_Model{
	protected $table = 'tblbesuk';
	public function save($data) {
        $this->db->trans_start();
        $data['modifiedon'] =  date("Y-m-d H:i:s");
        $data['modifiedby'] = $this->session->userdata('username');
        if (isset($data['besukid']) && !empty($data['besukid'])) {
            $id = $data['besukid'];
            unset($data['besukid']);
            $save = $this->_preFormat($data); //format the fields

            $result = $this->update($save, $id,'besukid');
            if($result === true ){
            } else {
                $this->db->trans_rollback();
            }
        } else {
        	$save = $this->_preFormat($data);//format untuk field
            $result = $this->insert($save);
            if($result === true){

            } else {
                $this->db->trans_rollback();
            }
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    private function _preFormat($data){
    	$fields = ['member_key','besukdate','pembesuk','pembesukdari','remark','besuklanjutan','modifiedon','modifiedby'];
    	$save = [];
    	foreach($fields as $val){
    		if(isset($data[$val])){
    			$save[$val] = $data[$val];
    		}
    	}
    	return $save;
    }
	function count($where){
		$sql = $this->db->query("SELECT besukid FROM tblbesuk " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(besukdate,'%d-%m-%Y') besukdateview,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblbesuk " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	function getM($where, $sidx, $sord, $limit, $start){
		$query = "select *,
		DATE_FORMAT(besukdate,'%d-%m-%Y') besukdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon from tblbesuk  " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
		// echo $query;
		return $this->db->query($query);
	}
	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("besukid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function getwhere($member_key){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		FROM tblmember WHERE member_key ='$member_key' LIMIT 0,1");
		return $sql;
	}
	function del($tabel,$id){
		$query = $this->db->where("besukid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}
}
?>
