<?php 
require_once("config/koneksi.php");
require_once("Classes/PHPExcel.php");

$tgl_dari    =$_GET['tanggal_dari'];
$tgl_sampai  =$_GET['tanggal_sampai'];


$objPHPExcel  = new PHPExcel();


$sqlT = "SELECT * FROM tbltrxmutasisaldo M JOIN tbluserlogin U ON M.field_member_id=U.field_member_id
                                           JOIN tblgoldprice G ON M.field_tanggal_saldo=G.field_date_gold
                                           JOIN tblbranch B ON U.field_branch=B.field_branch_id
                                           WHERE  date(M.field_tanggal_saldo) >= '$tgl_dari' AND date(M.field_tanggal_saldo) <= '$tgl_sampai'  
										   AND M.field_status='S'
										   ORDER BY M.field_id_saldo DESC";

$stmtT = $db->prepare($sqlT);
$stmtT->execute(array(':tgl_dari'=> $tgl_dari,':tgl_sampai'=>$tgl_sampai));
$result = $stmtT->fetchAll();

// var_dump($result);
// die();

$filename="Periode_Mutasi_".$tgl_dari."-".$tgl_sampai;

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID_Trx_Mutasi');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Date');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Time');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'No_Reff');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Rekening');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Nasabah');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Cabang');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Types');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Amount');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Saldo Akhir');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Sell');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Buyback');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Status');
$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFont()->setBold(true);

$rowCount	=	2;

foreach($result as $row) {
 $Types = $row["field_type_saldo"];
if($Types=="200"){
  $Types = 'Debit';
                    
  }else if($Types=="100"){
  $Types = 'Kredit';
                         
  }else if($Types=="300"){
  $Types = 'Balance';
                        
  }

// while($row	=	$result->fetch_assoc()){

	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, mb_strtoupper(sprintf("%09s",$row['field_id_saldo']),'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($row['field_tanggal_saldo'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, mb_strtoupper($row['field_time'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, mb_strtoupper($row['field_no_referensi'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, mb_strtoupper($row['field_rekening'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, mb_strtoupper($row['field_nama'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, mb_strtoupper($row['field_branch_name'],'UTF-8'));
	 	if ($row['field_kredit_saldo']=="0") {
	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, mb_strtoupper($Types,'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, mb_strtoupper($row['field_debit_saldo'],'UTF-8'));
		}elseif ($row['field_debit_saldo']=="0") { 
	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, mb_strtoupper($Types,'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, mb_strtoupper($row['field_kredit_saldo'],'UTF-8'));
		} 
	$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, mb_strtoupper($row['field_total_saldo'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, mb_strtoupper($row['field_sell'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, mb_strtoupper($row['field_buyback'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, mb_strtoupper($row['11'],'UTF-8'));
	$rowCount++;
}


$objWriter	=	new PHPExcel_Writer_Excel2007($objPHPExcel);


header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
$objWriter->save('php://output');
