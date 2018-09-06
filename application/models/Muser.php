<?php
Class Muser extends MY_Model{
	protected $table = 'tbluser';
	protected $alias = 'u';
	protected $insert_id;
	public function getList($conditions=[],$count=false,$limit=0,$offset=0){
		$table = $this->table;
		$alias = $this->alias;
		$this->db->from($table.' as '.$alias);
		$select = "$alias.userpk, $alias.username, $alias.userid, $alias.password,$alias.dashboard, (select group_concat(ur.roleid separator ',') from tbluserroles ur where ur.userpk =  $alias.userpk) as roles,(select group_concat(r.rolename separator ',') from tbluserroles ur inner join tblroles r ON r.roleid = ur.roleid where ur.userpk = $alias.userpk)as roles_name";
		if(!empty($conditions)){
			$this->db->where($conditions);
		}
		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}
		if($count===true){
			return $this->db->get()->num_rows();
		}else{
			$this->db->select($select);
			return $this->db->get()->result();
		}
	}
	public function getByIdUser($id){
		$conditions = ['userpk'=>$id];
		$user = $this->getList($conditions);
		if(!empty($user)){
			$user = $user[0];
			return $user;
		}
		return [];
	}
	public function save($data) {
        $this->db->trans_start();
        $data['modifiedon'] =  date("Y-m-d H:i:s");
        $data['modifiedby'] = $this->session->userdata('username');
        //encypt the password
        if(isset($data['password']) && !empty($data['password'])){
            $data['password'] = $this->_hashPassword($data['password']);
        }

        if (isset($data['userpk']) && !empty($data['userpk'])) {
            $id = $data['userpk'];
            unset($data['userpk']);
            $save = $this->_preFormat($data); //format the fields

            $result = $this->update($save, $id,'userpk');
            if($result === true ){

                if(isset($data['user_roles'])){
                    $this->saveUserRoles($id, $data['user_roles']);
                }
            } else {
                $this->db->trans_rollback();
            }
        } else {
        	$save = $this->_preFormat($data);//format untuk field
            $result = $this->insert($save);
            if($result === true){
                $id = $this->insert_id;
            	if(isset($data['user_roles'])){
                    $this->saveUserRoles($id, $data['user_roles']);
                }

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
    public function saveUserRoles($userpk,$roles){
        $this->load->model('Muserroles');
        $result = $this->Muserroles->saveBatch(['userpk'=>$userpk,'roles'=>$roles]);
        return $result;
    }
    private function _preFormat($data){
    	$fields = ['userid','username','password','dashboard','modifiedon','modifiedby'];
    	$save = [];
    	foreach($fields as $val){
    		if(isset($data[$val])){
    			$save[$val] = $data[$val];
    		}
    	}
    	return $save;
    }
    public function _hashPassword($password){
    	return md5($password);
    }
	function count($where){
		$sql = $this->db->query("SELECT userpk FROM tbluser " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tbluser " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	function add($tabel,$data){
		$this->db->insert($tabel,$data);
		$userpk="";
		$sql = $this->db->query("SELECT userpk FROM tbluser ORDER BY userpk DESC LIMIT 0,1");
		foreach ($sql->result() as $key) {
			$userpk .= $key->userpk;
		}
		return $userpk;
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("userpk",$id);
		$query = $this->db->update($tabel,$data);
	}
	function getwhere($userpk){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		FROM tbluser WHERE userpk ='$userpk' LIMIT 0,1");
		return $sql;
	}
	function del($tabel,$id){
		$query = $this->db->where("userpk",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}
}
?>
