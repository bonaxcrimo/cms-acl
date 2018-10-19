<?php
Class Mprofile extends MY_Model{
    protected $table = 'tblprofile';
    public function save($data) {
        $this->db->trans_start();
        $activitydate=$data['activitydate'];
        @$exp1 = explode('/',$activitydate);
        @$activitydate = $exp1[2]."-".$exp1[0]."-".$exp1[1]." ".date("H:i:s");
        $data['activitydate']=$activitydate;
        $data['modifiedon'] =  date("Y-m-d H:i:s");
        $data['modifiedby'] = $_SESSION['username'];
        if (isset($data['profile_key']) && !empty($data['profile_key'])) {
            $id = $data['profile_key'];
            unset($data['profile_key']);
            $save = $this->_preFormat($data); //format the fields
            $result = $this->update($save, $id,'profile_key');
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
        $this->db->where(['profile_key'=>$id]);
        return $this->db->delete($this->table);
    }
    private function _preFormat($data){
        $fields = ['member_key','activityid','activitydate','remark','modifiedon','modifiedby'];
        $save = [];
        foreach($fields as $val){
            if(isset($data[$val])){
                $save[$val] = $data[$val];
            }
        }
        return $save;
    }
    function count($where){
        $sql = "SELECT profile_key FROM tblprofile " . $where;
        return $this->db->query($sql);
    }
    function get($where, $sidx, $sord, $limit, $start){
        $sql ="SELECT *,
        DATE_FORMAT(besukdate,'%d-%m-%Y') besukdateview,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
        FROM tblbesuk " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
        return  $this->db->query($sql);
    }
    function getM($where, $sidx, $sord, $limit, $start){
        $query = "select tblprofile.*,tblmember.membername,tblmember.chinesename,tblmember.address,
        DATE_FORMAT(tblprofile.activitydate,'%d-%m-%Y') activitydate,
        DATE_FORMAT(tblprofile.modifiedon,'%d-%m-%Y %T') modifiedon from tblprofile inner join tblmember on tblprofile.member_key = tblmember.member_key inner join tblparameter on tblparameter.parameter_key = tblprofile.activityid  " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
        return $this->db->query($query);
    }
    function add($tabel,$data){
        $sql = $this->db->insert($tabel,$data);
    }
    function edit($tabel,$data,$id){
        $query = $this->db->where("profile_key",$id);
        $query = $this->db->update($tabel,$data);
    }
    function getwhere($member_key){
        $sql = "SELECT *,
        DATE_FORMAT(dob,'%d-%m-%Y') dob,
        DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
        DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
        FROM tblmember WHERE member_key ='$member_key' LIMIT 0,1";
        return $this->db->query($sql);
    }
    function del($tabel,$id){
        $query = $this->db->where("profile_key",$id);
        $sql = $this->db->delete($tabel);
        return $sql;
    }
}
?>
