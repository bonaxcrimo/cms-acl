<?php
Class Moffering extends MY_Model{

    function count($where){
        $sql = $this->db->query("SELECT offering_key FROM tbloffering " . $where);
        return $sql;
    }
    function get($where, $sidx, $sord, $limit, $start,$status){
        $row_status = $where==''?' where row_status="'.$status.'"':' and row_status="'.$status.'"';
        $query = "select *,
        DATE_FORMAT(transdate,'%d-%m-%Y') transdate,
        DATE_FORMAT(inputdate,'%d-%m-%Y') inputdate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon from tbloffering  " . $where .$row_status. "  ORDER BY $sidx $sord LIMIT $start , $limit";
        return $this->db->query($query);
    }
    function add($tabel,$data){
        $sql = $this->db->insert($tabel,$data);
    }
    function edit($tabel,$data,$id){
        $query = $this->db->where("offering_key",$id);
        $query = $this->db->update($tabel,$data);
    }
    function editAll($tabel,$data,$status){
        $query = $this->db->where("row_status",$status);
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
        $query = $this->db->where("offering_key",$id);
        $data = array(
            'row_status' => 'D'
        );
        $sql = $this->db->update($tabel,$data);
        return $sql;
    }
}
?>
