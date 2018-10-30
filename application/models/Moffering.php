<?php
Class Moffering extends MY_Model{
    protected $table = 'tbloffering';
    public function save($data) {
        $this->db->trans_start();
        $data['modifiedon'] =  date("Y-m-d H:i:s A");
        $data['modifiedby'] = $_SESSION['username'];
        $offering_value = str_replace(".","",$data['offeringvalue']);
        $data['offeringvalue']=$offering_value;
        $transdate= $data['transdate'];
        @$transdate =date("Y-m-d H:i:s A",strtotime($transdate));
        $inputdate =  $data['inputdate'];
        @$inputdate = date("Y-m-d H:i:s A",strtotime($inputdate));
        $data['transdate'] = $transdate;
        $data['inputdate'] = $inputdate;
        if (isset($data['offering_key']) && !empty($data['offering_key'])) {
            $id = $data['offering_key'];
            unset($data['offering_key']);
            $save = $this->_preFormat($data); //format the fields
            $result = $this->update($save, $id,'offering_key');
            if($result === true ){
            } else {
                $this->db->trans_rollback();
            }
        } else {
            $data['row_status'] = '';
            $noOffering = getTableWhere('tblparameter',array('parametergrpid'=>'FORMAT_NO','parameterid'=>'OFFERING'))[0];
            // $period = getTableWhere('tblparameter',array('parameter_key'=>$noOffering->parametermemo))[0];
            $offerData = getDataPeriodly($noOffering->parametermemo,'tbloffering','inputdate','offeringno','desc');
            if(count($offerData)==0){
                $offeringno = bacaFormat($noOffering->parametertext,1);
            }else{
                $offerData = $offerData[0]->offeringno;
                $pecah = explode("/",$offerData)[0];
                $offeringno = bacaFormat($noOffering->parametertext,$pecah+1);
            }
            $data['offeringno']=$offeringno;
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
        $query = $this->db->where("offering_key",$id);
        $data = array(
            'row_status' => 'D'
        );
        $sql = $this->db->update($this->table,$data);
        return $sql;
    }
    private function _preFormat($data){
        $fields = ['member_key','offeringid','membername','chinesename','address','handphone','offeringno','transdate','inputdate','aliasname2','remark','offeringvalue','row_status','modifiedon','modifiedby'];
        $save = [];
        foreach($fields as $val){
            if(isset($data[$val])){
                $save[$val] = $data[$val];
            }
        }
        return $save;
    }
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
