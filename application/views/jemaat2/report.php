<HTML>
	<HEAD>
		<title>report</title>
		<meta charset="UTF-8">
	</HEAD>
	<style type="text/css">
	.pagebreak {
		page-break-after: always;
	}
</style>
<body onload="window.print(); window.close();">
<?php 
function myheader(){

?><table border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>No</th>
		<th>recno</th>
		<th>grp_pi</th>
		<th>relationno</th>
		<th>memberno</th>
		<th>membername</th>
		<th>chinesename</th>
		<th>phoneticname</th>
		<th>aliasname</th>
		<th>tel_h</th>
		<th>tel_o</th>
		<th>handphone</th>
		<th>address</th>
		<th>add2</th>
		<th>city</th>
		<th>genderid</th>
		<th>pstatusid</th>
		<th>pob</th>
		<th style="width:200px">dob</th>
		<th>bloodid</th>
		<th>kebaktianid</th>
		<th>persekutuanid</th>
		<th>rayonid</th>
		<th>statusid</th>
	<!--	<th>serving</th>
		<th>fax</th>
		<th>email</th>
		<th>website</th>
		<th>baptis</th>
		<th>baptismdocno</th>
		<th>baptismdate</th>
		<th>remark</th>
		<th>relation</th>
		<th>oldgrp</th>
		<th>kebaktian</th>
		<th>tglbesuk</th>
		<th>teambesuk</th>
		<th>description</th>
		<th>photofile</th>
		<th>modifiedby</th>
		<th>modifiedon</th>-->
	</tr>

	<?php
}

function myfooter(){
	?></table><?php
}

	$no=0;
	foreach ($sql->result() as $row){
	   $no++;
	   if(($no%5)==1){
	   		if($no > 1){
				myfooter();
				echo "<div class=\"pagebreak\"> </div>";
			}
			myheader();
	   }
	   ?>
	   		<tbody>
				<tr>
				<td><?php echo $no; ?></td>
				<td><?php echo $row->recno; ?></td>
				<td><?php echo $row->grp_pi; ?></td>
				<td><?php echo $row->relationno; ?></td>
				<td><?php echo $row->memberno; ?></td>
				<td><?php echo $row->membername; ?></td>
				<td><?php echo $row->chinesename; ?></td>
				<td><?php echo $row->phoneticname; ?></td>
				<td><?php echo $row->aliasname; ?></td>
				<td><?php echo $row->tel_h; ?></td>
				<td><?php echo $row->tel_o; ?></td>
				<td><?php echo $row->handphone; ?></td>
				<td><?php echo $row->address; ?></td>
				<td><?php echo $row->add2; ?></td>
				<td><?php echo $row->city; ?></td>
				<td><?php echo $row->genderid; ?></td>
				<td><?php echo $row->pstatusid; ?></td>
				<td><?php echo $row->pob; ?></td>
				<td><?php echo $row->dob; ?></td>
				<td><?php echo $row->bloodid; ?></td>
				<td><?php echo $row->kebaktianid; ?></td>
				<td><?php echo $row->persekutuanid; ?></td>
				<td><?php echo $row->rayonid; ?></td>
				<td><?php echo $row->statusid; ?></td>
				<!--<td><?php echo $row->serving; ?></td>
				<td><?php echo $row->fax; ?></td>
				<td><?php echo $row->email; ?></td>
				<td><?php echo $row->website; ?></td>
				<td><?php echo $row->baptis; ?></td>
				<td><?php echo $row->baptismdocno; ?></td>
				<td><?php echo $row->baptismdate; ?></td>
				<td><?php echo $row->remark; ?></td>
				<td><?php echo $row->relation; ?></td>
				<td><?php echo $row->oldgrp; ?></td>
				<td><?php echo $row->kebaktian; ?></td>
				<td><?php echo $row->tglbesuk; ?></td>
				<td><?php echo $row->teambesuk; ?></td>
				<td><?php echo $row->description; ?></td>
				<td><?php echo $row->photofile; ?></td>
				<td><?php echo $row->modifiedby; ?></td>
				<td><?php echo $row->modifiedon; ?></td>-->
			</tr>
			</tbody>
		<?php
	}
	myfooter();
?>
</table>


</body>
</HTML>
