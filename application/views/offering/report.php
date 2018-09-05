<?php
$pageLayout = array(50, 50);
// $pdf = new TCPDF('p', 'pt', $pageLayout, true, 'UTF-8', false);
$pdf = new Pdf('P', 'mm', 'A11', true, 'UTF-8', false);
$fontname = TCPDF_FONTS::addTTFfont(FCPATH . 'libraries/tcpdf/fonts/DroidSansFallback.ttf', 'TrueTypeUnicode', '', 32);
// $pdf->addTTFfont();
$pdf->SetTitle('Bukti');
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
$pdf->setMargins($marginLeft, $marginTop, 0,true);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('helvetica','B',18);
$style = array('width' =>  0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,5', 'color' => array(255, 0, 0));
$pdf->Line($marginLeft+3, $marginTop + 9, 80, $marginTop + 9, $style);
$pdf->Cell(80,6,'GMI GLORIA',0,1,'C');
$pdf->SetFont('DroidSansFallback', '', 9, '', false);
$html = '
<div>
    <table border="0" width="300">
        <tr>
            <td width="90"></td>
            <td width="10"></td>
            <td width="180"></td>
        </tr>
        <tr>
            <th align="left">No OFFERING</th>
            <td>:</td>
            <td > '.$offering->offeringno .'</td>
        </tr>
        <tr>
            <th align="left">Member Number</th>
            <td>:</td>
            <td > '.$offering->member_key .'</td>
        </tr>
        <tr>
            <th align="left">Telah terima dari</th>
            <td>:</td>
            <td >'.$offering->membername .'</td>
        </tr>
        <tr>
            <th align="left">Chinese Name</th>
            <td>:</td>
            <td > '.$offering->chinesename .'</td>
        </tr>
        <tr>
            <th align="left">Uang sejumlah</th>
            <td>:</td>
            <td> '.format_rupiah($offering->offeringvalue).'</td>
        </tr>
        <tr>
            <th align="left">Terbilang</th>
            <td>:</td>
            <td>'.strtoupper(Terbilang($offering->offeringvalue)).'</td>
        </tr>
        <tr>
            <th align="left">Untuk Pembayaran</th>
            <td>:</td>
            <td> '.$offering->offeringid.'</td>
        </tr>
        <tr>
            <th align="left">Tanggal Diterima</th>
            <td>:</td>
            <td> '.Date("Y-m-d",strtotime($offering->transdate)) .'</td>
        </tr>
        <tr>
            <th align="left">Tanggal Cetak</th>
            <td>:</td>
            <td> '.Date("Y-m-d H:i:s") .'</td>
        </tr>
    </table>

</div>
';
$pdf->writeHTML($html, true, false, true, false, '');


$pdf->Output('Laporan.pdf', 'I');
?>