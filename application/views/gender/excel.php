<?php
class Excel{
    function setHeader($filename){
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=$filename");
        header("Content-Transfer-Encoding: binary ");
    }
    function BOF(){
        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        return;
    }
    function EOF(){
        echo pack("ss", 0x0A, 0x00);
        return;
    }
    function writeNumber($Row, $Col, $Value){
        echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
        echo pack("d", $Value);
        return;
    }
    function writeLabel($Row, $Col, $Value){
        $L = strlen($Value);
        echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
        echo $Value;
        return;
    }

    function cleanData(&$str){
        $search  = array("<br />","<br/>","<br>");
        $replace = array("\n","\n","\n");
        $title =  str_replace($search, $replace, $str);
        return $title;
    }

    function xlsCodepage($codepage) {
        $record    = 0x0042;    // Codepage Record identifier
        $length    = 0x0002;    // Number of bytes to follow

        $header    = pack('vv', $record, $length);
        $data      = pack('v',  $codepage);

        echo $header , $data;
        return;
    }

}

    $excel = new Excel();
    #Send Header
    $excel->setHeader("datagender.xls");
    $excel->BOF();
    //$excel->cleanData();
    $excel->xlsCodepage(65001);

    #header tabel
    $excel->writeLabel(0, 0, "No");
    $excel->writelabel(0, 1, "genderid"); 
    $excel->writelabel(0, 2, "gendername"); 
    $excel->writelabel(0, 3, "modifiedby"); 
    $excel->writelabel(0, 4, "modifiedon");

    #isi data
    $i=0;
    foreach ($sql->result() as $row){
        $i++;
        $excel->writeLabel($i, 0,$i);
        $excel->writelabel($i, 1,$row->genderid); 
        $excel->writelabel($i, 2,$row->gendername); 
        $excel->writelabel($i, 3,$row->modifiedby); 
        $excel->writelabel($i, 4,$row->modifiedon);

    }                
    $excel->EOF();
    exit();
?>