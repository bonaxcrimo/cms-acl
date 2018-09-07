<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function print_recursive_list($data)
{
    $str = "";
    foreach($data as $list)
    {
        $str .= "<div a href='".base_url().$list['menuexe']."' iconCls='".$list['menuicon']."'>".$list['menuname'];
        $subchild = print_recursive_list($list['child']);
        if($subchild != ''){
            $str .= "<span>".$subchild."</span>";
            $str .= "<div>".$subchild."</div>";
        }
        $str .= "</div>";
    }
    return $str;
}
function getTableWhere($table,$where){
    $db = get_instance()->db;
    $db->where($where);
    $sql =$db->get($table);
    return $sql->result();
}
function hakakses($x){
    $CI = get_instance();
    $CI->load->model('mmenutop');
    $x = $CI->mmenutop->get_menuid($x);
    return $x;
}
function excel($nama_session,$tabel,$route){
    $CI = get_instance();
    $excel = $_SESSION[$nama_session];
    $splitexcel = explode("|",$excel);
    $sord = $splitexcel[0];
    $sidx= $splitexcel[1];
    $where = $splitexcel[2];
    $data['sql']=$CI->db->query("SELECT *,
    DATE_FORMAT(modifiedon,'%d-%m-%Y') modifiedon
    FROM $tabel " . $where . " ORDER BY $sidx $sord");
    $CI->load->view($route,$data);
}
function getDataPeriodly($period='DAILY',$table,$field,$sfield,$sort){
    $db = get_instance()->db;
    $where = ' where ';
    if($period=='DAILY'){
        $where .=' DATE_FORMAT(NOW(), "%Y-%m-%d") = DATE_FORMAT('.$field.',"%Y-%m-%d")';
    }else if($period=='MONTHLY'){
        $where .=' DATE_FORMAT(NOW(), "%Y-%m") = DATE_FORMAT('.$field.',"%Y-%m")';
    }else if($period=='YEARLY'){
        $where .=' DATE_FORMAT(NOW(), "%Y") = DATE_FORMAT('.$field.',"%Y")';
    }
    $sql= $db->query("select * from ".$table.$where." order by $sfield $sort ")->result();
    return $sql;
}
function getOne($field,$value,$table){
    $db = get_instance()->db;
    return $db->query("select * from ".$table." where ".$field." = '".$value."'")->result();
}
function escapeString($val){
    $db = get_instance()->db->conn_id;
    $val = mysqli_real_escape_string($db, $val);
    return $val;
}
function format_rupiah($angka){
  $rupiah=number_format($angka,0,',','.');
  return $rupiah;
}

function Terbilang($satuan){
        $huruf = array("","satu","dua","tiga","empat","lima","enam","tujuh",
        "delapan","sembilan","sepuluh","sebelas");
        if($satuan<12)
        return " ".$huruf[$satuan];
        elseif($satuan<20)
        return Terbilang($satuan-10)." belas";
        elseif($satuan<100)
        return Terbilang($satuan/10)." puluh".
        Terbilang($satuan%10);
        elseif($satuan<200)
        return "seratus".Terbilang($satuan-100);
        elseif($satuan<1000)
        return Terbilang($satuan/100)." ratus".
        Terbilang($satuan%100);
        elseif($satuan<2000)
        return "seribu".Terbilang($satuan-1000);
        elseif($satuan<1000000)
        return Terbilang($satuan/1000)." ribu".
        Terbilang($satuan%1000);
        elseif($satuan<1000000000)
        return Terbilang($satuan/1000000)." juta".
        Terbilang($satuan%1000000);
        elseif($satuan>=1000000000)
        echo"hasil terbilang tidak dapat di proses, nilai terlalu besar";
}
function getParameterKey($key){
    $db = get_instance()->db;
    $db->where('parameter_key',$key);
    $sql =$db->get('tblparameter');
    return $sql->row();
}
function getParameter($tipe){
    $db = get_instance()->db;
    $db->where('parametergrpid',$tipe);
    $sql =$db->get('tblparameter');
    return $sql->result();
}
function getComboParameter($tipe){
        $data="{value:'',text:'All'},";
        $sql = getParameter($tipe);
        foreach ($sql as $key) {
            $data .="{value:'".$key->parameter_key."',text:'".$key->parametertext."'},";
        }
        $data=strrev($data);
        $data=substr($data,1);
        $data=strrev($data);
        return $data;
    }
function queryCustom($sql){
    @$CI =& get_instance();
    @$data = $CI->db->query($sql)->result()[0];
    return @$data;
}
function queryCustom2($sql){
    @$CI =& get_instance();
    @$data = $CI->db->query($sql)->result_array()[0];
    return @$data;
}
function getDataCurrentMonth($table){
    $db = get_instance()->db;
    $sql =$db->query("select * from ".$table." where MONTH(modifiedon) = MONTH(CURRENT_DATE()) AND YEAR(modifiedon) = YEAR(CURRENT_DATE())");
    return $sql->result();
}
function bacaFormat($format,$mulai){
    $hasil="";
    // $mulai =2;
    $mati=[];
    $temp="";
    $pagarke=0;
    //untuk ambil nilai statik
    for($i=0;$i<strlen($format);$i++){
        if($pagarke==1){
            if($format[$i]!="#"){
                $temp .= $format[$i];
            }
        }else{
            $hasil .= $format[$i];
        }
        if($format[$i]=="#"){
            $pagarke+=1;
        }
        if($pagarke==2){
            $mati[] = $temp;
            $temp = "";
            $pagarke=0;
        }
    }
    $index=0;
    $listAngka=[];
    $start=-1;
    $end=-1;
    //ambil index karakter angka
    for($i=0;$i<strlen($hasil);$i++){
        if(is_numeric($hasil[$i])){
            if($start==-1){
                $start = $i;
            }
        }
        if($start!=-1){
            if(!is_numeric($hasil[$i])){
                if($end==-1){
                    $end = $i;
                    $listAngka[]=[$start,$end];
                    $start=-1;
                    $end=-1;
                }
            }
        }
    }
    //replace angka dengan data
    for($i=0;$i<count($listAngka);$i++){
        for($j=$listAngka[$i][0];$j<$listAngka[$i][1];$j++){
            $hasil[$j]=$i."";
            if($j<($listAngka[$i][1]-1)  and ($listAngka[$i][1]-$listAngka[$i][0])>1){
                $hasil = str_replace($i.""," ",$hasil);
            }
        }
        $mulai = sprintf("%0".($listAngka[$i][1]-$listAngka[$i][0])."d",$mulai);
        $hasil = str_replace($i."",$mulai,$hasil);
    }
    $hasilAkhir = "";
    //mengabungkan data statik dengan data
    for($i=0;$i<strlen($hasil);$i++){
        if($hasil[$i]=="#"){
            $hasilAkhir .= $mati[$index];
            $index++;
        }else{
            $hasilAkhir .= $hasil[$i];
        }
    }
    $hasilAkhir=explode(" ",$hasilAkhir);
    $hasilAkhir= implode("",$hasilAkhir);

    $hasilAkhir=explode("/",$hasilAkhir);
    $hasilAkhir[count($hasilAkhir)-1]=Date($hasilAkhir[count($hasilAkhir)-1]);
    $hasilAkhir= implode("/",$hasilAkhir);
    return $hasilAkhir;
}
if(!function_exists('hasPermission')){
    function hasPermission($class,$method){
        $acl = ACL::get_instance();
        return $acl->hasPermission($class,$method);
    }
}