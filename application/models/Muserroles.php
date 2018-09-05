<?php
class Muserroles extends MY_Model{
    protected $table = 'tbluserroles';
    protected $alias = 'ur';
    public function getList($conditions =[],$count = false,$limit=0,$offset=0){
        $table= $this->table;
        $alias = $this->alias;
        $this->db->from($table.' as '.$alias);
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }
        if($count===true){
            return $this->db->get()->num_rows();
        }else{
            return $this->db->get()->result_array();
        }
    }
    public function getByUserID($user_id){
        return $this->getList(['userpk'=>$user_id]);
    }
    protected function getListByGroup($conditions){
        $this->db->select('GROUP_CONCAT('.$this->alias.'.roleid SEPARATOR ",") as roles')
            ->where($conditions)
            ->from($this->table.' as '.$this->alias);
        return $this->db->get()->result_array();
    }
    public function deleteByRole($role_id){
        $this->db->where(['roleid'=>$role_id])->delete($this->table);
        return true;
    }
     public function saveBatch($data) {
        $insert = [];
        if (isset($data['roles'])) {

            $records = $this->getList(['userpk' => $data['userpk']], true);

            if (empty($records)) {
                foreach ($data['roles'] as $role) {
                    $insert[] = [
                        'userpk' => $data['userpk'],
                        'roleid' => $role
                    ];
                }
                return $this->insertBatch($insert);
            } else {
                $records = $this->getListByGroup(['userpk' => $data['userpk']]);

                $roles = strpos($records[0]['roles'], ',') === false ? [$records[0]['roles']] : explode(', ', $records[0]['roles']);

                $inserts = array_diff($data['roles'], $roles);
                $removes = array_diff($roles, $data['roles']);

                if (!empty($inserts)) {
                    $insert = [];
                    foreach ($inserts as $val) {
                        $insert[] = [
                            'roleid' => $val,
                            'userpk' => $data['userpk']
                        ];
                    }
                    $this->insertBatch($insert);
                }

                if (!empty($removes)) {
                    $remove = [];
                    foreach ($removes as $val) {
                        $remove[] = [
                            'roleid' => $val,
                            'userpk' => $data['userpk']
                        ];
                    }
                    $this->removeBatch($remove);
                }
                return true;
            }
        }
        return false;
    }
}