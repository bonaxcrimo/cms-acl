<?php
	header('Content-type: text/plain'); 
	header('Content-Disposition: attachment; filename="report.txt"'); 
	foreach ($sql->result() as $row){
		$hp = $row->handphone;
		$search  = array('-',' ','`','~','!','@','#','$','%','^','&','*','(',')','_','','+','=','{','}','[',']','"',':','?','/','>','<',',','|');
		$replace = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
		$nohp  =  str_replace($search, $replace, $hp);
		if($nohp!=""){
			if(strstr($nohp,";")){
				$pecah = explode(';',$nohp);
				$j=0;
				foreach ($pecah as $key) {
	       			echo $pecah[$j];
	       			echo ";";
	       			echo "\n"; 
	       			$j++;
	       		}
			}
			else{
	        	echo $nohp;
	        	echo ";";
	        	echo "\n"; 
			}
		}
	}
?>