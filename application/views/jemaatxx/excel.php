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
    $excel->setHeader("datajemaat.xls");
    $excel->BOF();
    //$excel->cleanData();
    $excel->xlsCodepage(65001);

    #header tabel
    $excel->writeLabel(0, 0, "No");
    $excel->writelabel(0, 1, "recno"); 
    $excel->writelabel(0, 2, "relationno"); 
    $excel->writelabel(0, 3, "memberno"); 
    $excel->writelabel(0, 4, "membername"); 
    $excel->writelabel(0, 5, "chinesename"); 
    $excel->writelabel(0, 6, "phoneticname"); 
    $excel->writelabel(0, 7, "aliasname"); 
    $excel->writelabel(0, 8, "dob"); 
    $excel->writelabel(0, 9, "umur"); 
    $excel->writelabel(0, 10, "tel_h"); 
    $excel->writelabel(0, 11, "tel_o"); 
    $excel->writelabel(0, 12, "handphone"); 
    $excel->writelabel(0, 13, "address"); 
    $excel->writelabel(0, 14, "add2"); 
    $excel->writelabel(0, 15, "city"); 
    $excel->writelabel(0, 16, "genderid"); 
    $excel->writelabel(0, 17, "pstatusid"); 
    $excel->writelabel(0, 18, "pob"); 
    $excel->writelabel(0, 19, "bloodid"); 
    $excel->writelabel(0, 20, "kebaktianid"); 
    $excel->writelabel(0, 21, "persekutuanid"); 
    $excel->writelabel(0, 22, "rayonid"); 
    $excel->writelabel(0, 23, "statusid"); 
    $excel->writelabel(0, 24, "serving"); 
    $excel->writelabel(0, 25, "fax"); 
    $excel->writelabel(0, 26, "email"); 
    $excel->writelabel(0, 27, "website"); 
    $excel->writelabel(0, 28, "baptismdocno"); 
    $excel->writelabel(0, 29, "baptismdate"); 
    $excel->writelabel(0, 30, "remark"); 
    $excel->writelabel(0, 31, "relation"); 
    $excel->writelabel(0, 32, "oldgrp"); 
    $excel->writelabel(0, 33, "kebaktian"); 
    $excel->writelabel(0, 34, "tglbesuk"); 
    $excel->writelabel(0, 35, "teambesuk"); 
    $excel->writelabel(0, 36, "description"); 
	$excel->writelabel(0, 37, "baptis"); 
    $excel->writelabel(0, 38, "photofile"); 
    $excel->writelabel(0, 39, "modifiedby"); 
    $excel->writelabel(0, 40, "modifiedon");

    #isi data
    $i=0;
    foreach ($sql->result() as $row){
        $i++;
        $excel->writeLabel($i, 0,$i);
        $excel->writelabel($i, 1,$row->recno); 
        $excel->writelabel($i, 2,$row->relationno); 
        $excel->writelabel($i, 3,$row->memberno); 
        $excel->writelabel($i, 4,$row->membername); 
        $excel->writelabel($i, 5,$row->chinesename); 
        $excel->writelabel($i, 6,$row->phoneticname); 
        $excel->writelabel($i, 7,$row->aliasname); 
        $excel->writelabel($i, 8,$row->dob); 
        $excel->writelabel($i, 9,$row->umur); 
        $excel->writelabel($i, 10,$row->tel_h); 
        $excel->writelabel($i, 11,$row->tel_o); 
        $excel->writelabel($i, 12,$row->handphone); 
        $excel->writelabel($i, 13,$row->address); 
        $excel->writelabel($i, 14,$row->add2); 
        $excel->writelabel($i, 15,$row->city); 
        $excel->writelabel($i, 16,$row->genderid); 
        $excel->writelabel($i, 17,$row->pstatusid); 
        $excel->writelabel($i, 18,$row->pob); 
        $excel->writelabel($i, 19,$row->bloodid); 
        $excel->writelabel($i, 20,$row->kebaktianid); 
        $excel->writelabel($i, 21,$row->persekutuanid); 
        $excel->writelabel($i, 22,$row->rayonid); 
        $excel->writelabel($i, 23,$row->statusid); 
        $excel->writelabel($i, 24,$row->serving); 
        $excel->writelabel($i, 25,$row->fax); 
        $excel->writelabel($i, 26,$row->email); 
        $excel->writelabel($i, 27,$row->website); 
        $excel->writelabel($i, 28,$row->baptismdocno); 
        $excel->writelabel($i, 29,$row->baptismdate); 
        $excel->writelabel($i, 30,$row->remark); 
        $excel->writelabel($i, 31,$row->relation); 
        $excel->writelabel($i, 32,$row->oldgrp); 
        $excel->writelabel($i, 33,$row->kebaktian); 
        $excel->writelabel($i, 34,$row->tglbesuk); 
        $excel->writelabel($i, 35,$row->teambesuk); 
        $excel->writelabel($i, 36,$row->description); 
		$excel->writelabel($i, 37,$row->baptis); 
        $excel->writelabel($i, 38,$row->photofile); 
        $excel->writelabel($i, 39,$row->modifiedby); 
        $excel->writelabel($i, 40,$row->modifiedon);

    }                
    $excel->EOF();
    exit();
?>