<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mparameter extends MY_Model {
	protected $table = 'tblparameter';
	public function save($data) {
        $this->db->trans_start();
        $data['modifiedon'] =  date("Y-m-d H:i:s");
        $data['modifiedby'] = $_SESSION['username'];
        if (isset($data['parameter_key']) && !empty($data['parameter_key'])) {
            $id = $data['parameter_key'];
            unset($data['parameter_key']);
            $save = $this->_preFormat($data); //format the fields

            $result = $this->update($save, $id,'parameter_key');
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
    public function delete($id){
        $this->db->where(['parameter_key'=>$id]);
        return $this->db->delete($this->table);
    }
	private function _preFormat($data){
    	$fields = ['parametergrpid','parameterid','parametertext','parametermemo','modifiedon','modifiedby'];
    	$save = [];
    	foreach($fields as $val){
    		if(isset($data[$val])){
    			$save[$val] = $data[$val];
    		}
    	}
    	return $save;
    }
	function count($where){
		$sql = "SELECT parameter_key FROM tblparameter " . $where;
        return $this->db->query($sql);
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = "SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		FROM tblparameter " . $where . " ORDER BY $sidx $sord  LIMIT $start , $limit";
		return $this->db->query($sql);
	}

}