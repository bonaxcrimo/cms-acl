<?php
Class Mlogin extends MY_Model{
	protected $table = 'tbluser';
	protected $alias = 'u';
	protected $insert_id;
	function login($userid, $password){
		$this->db->from('tbluser');
		$this->db->where('userid', $userid);
		$this->db->where('password', $password);
		$this->db->limit(1);
		$sql = $this->db->get();
		if($sql->num_rows()==1){
			return $sql;
		}
		else{
			return false;
		}
	}
	public function getList($conditions=[],$count=false,$limit=0,$offset=0){
		$table= $this->table;
		$alias = $this->alias;
		$this->db->from($this->table.' as '.$alias);
		$select = "$alias.userpk, $alias.userid, $alias.password, $alias.username,$alias.dashboard , (select GROUP_CONCAT(ur.roleid SEPARATOR ',') from tbluserroles ur where ur.userpk = $alias.userpk) as roles, (select group_concat(r.rolename separator ',' ) from tbluserroles ur inner join tblroles r ON r.roleid = ur.roleid where ur.userpk = $alias.userpk) as rolesname ";
		if(!empty($conditions)){
			$this->db->where($conditions);
		}
		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}
		if($count===true){
			return $this->db->get()->num_rows;
		}else{
			$this->db->select($select);
			return $this->db->get()->result_array();
		}
	}
	public function verifyLogin($userid,$password){
		$user = $this->getList(['userid'=>$userid]);
		if(!empty($user)){
			if($user[0]['password']===md5($password)){
				$user = $user[0];
				return $user;
			}
		}
		return [];
	}
	public function getDetailWithAclById($id){
		$user  = $this->getList(['userpk'=>$id]);
		if(!empty($user)){
			$user = $user[0];
			$roles = strpos($user['roles'],',') === false ? $user['roles']:explode(', ',$user['roles']);
			$this->load->model('Macl');

			$user['acl'] = $this->Macl->getByRoles($roles);

		}
		return $user;
	}
	// function cek(){
	// 	@$userid = $_SESSION['userid'];
	// 	@$userlevel = $_SESSION['userlevel'];
	// 	@$username = $_SESSION['username'];
	// 	@$password = $_SESSION['password'];

	// 	$this->db->from('tbluser');
	// 	$this->db->where('userid', $userid);
	// 	$this->db->where('userlevel', $userlevel);
	// 	$this->db->where('username', $username);
	// 	$this->db->where('password', $password);

	// 	$this->db->limit(1);
	// 	$sql = $this->db->get();
	// 	if($sql->num_rows()==1){
	// 		return $sql;
	// 	}
	// 	else{
	// 		return false;
	// 	}
	// }
}
?>
