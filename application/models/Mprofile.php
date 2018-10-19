<?php
Class Mprofile extends MY_Model{

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
