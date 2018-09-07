<?php
class Mroles extends MY_Model{
    protected $table = 'tblroles';
    protected $alias = 'r';
    function count($where){
        $sql = $this->db->query("SELECT * FROM tblroles " . $where);
        return $sql;
    }
    function get($where, $sidx, $sord, $limit, $start){
        $query = "select * from tblroles " . $where." ORDER BY $sidx $sord LIMIT $start , $limit";
        return $this->db->query($query);
    }
    public function getList($conditions=[],$count=false,$limit=0,$offset=0){
        $table= $this->table;
        $alias = $this->alias;
        $this->db->from($table.' as '.$alias);
        $select = "$alias.roleid, $alias.rolename , (select GROUP_CONCAT(tblacl.acoid SEPARATOR ',') from tblacl where tblacl.roleid= $alias.roleid) as acos";
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
    public function getByIdRoles($id){
        $conditions = [
            'roleid' =>$id
        ];
        $roles = $this->getList($conditions);
        if(!empty($roles)){
            $roles = $roles[0];
        }
        return $roles;
    }

    public function getGuestGroup(){
        $conditions= [
            'rolename' =>'Guest'
        ];
        $group = $this->getList($conditions);

        if(!empty($group)){
            $group = $group[0];
            $this->load->model('Macos');
            $ids = strpos($group->acos,',')===false?$group->acos:explode(', ',$group->acos);
            $group->acos = $this->Macos->getByMultiId($ids);

            // print_r($group);
            return $group;
        }
        return [];
    }
    public function isNameExists($name,$id=null){
        $conditions = [
            $this->alias.'.rolename'=>$name
        ];
        if(!empty($id) && is_numeric($id)){
            $conditions[$this->alias.'.roleid !='] = $id;
        }
        $count = $this->getList($conditions,true);
        if($count>0)
            return true;
        return false;
    }
    public function save($data){
        $this->db->trans_start();
        $save=[
            'rolename'=>$data['rolename'],
            'modifiedby'=>$this->session->userdata('username')
        ];
        if(isset($data['roleid']) && !empty($data['roleid'])){
            $id = $data['roleid'];
            $result = $this->update($save,$id,'roleid');
            if($result===true){
                $this->saveRolePermission($id,$data['role_permission']);
            }else{
                $this->db->trans_rollback();
            }
        }else{
            $result = $this->insert($save);
            if($result===true){
                $this->saveRolePermission($this->db->insert_id(),$data['role_permission']);
            }else{
                $this->db->trans_rollback();
            }
        }
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }
    public function saveRolePermission($roleid,$acos){
        $this->load->model('Macl');
        $result = $this->Macl->saveBatch(['roleid'=>$roleid,'acos'=>$acos]);
        return $result;
    }
    public function delete($id){
        $this->load->model('Muserroles');
        $this->Muserroles->deleteByRole($id);
        $this->load->model('Macl');
        $this->Macl->deleteByRole($id);
        $this->db->where(['roleid'=>$id]);
        $this->db->delete($this->table);
    }
}
