<?php
require_once("config/koneksi.php");
require_once("Classes/PHPExcel.php");

session_start();

if (!isset($_SESSION['userlogin'])) {
	header("location: ../loginv2.php");
}
$idemploye = $_SESSION['idlogin'];
$select_stmt = $db->prepare("SELECT * FROM tblemployeeslogin E JOIN tbldepartment D ON E.field_role=D.field_department_id
                                                               JOIN tblbranch B ON E.field_branch=B.field_branch_id
                                                               JOIN tblpermissions P ON E.field_role=P.role_id
                                                              WHERE E.field_user_id=:uid LIMIT 1");
$select_stmt->execute(array(":uid" => $idemploye));
$rows = $select_stmt->fetch(PDO::FETCH_ASSOC);
$permission = $rows['add'];
$branchid = $rows['field_branch'];

$tgl_dari    = $_GET['tanggal_dari'];
$tgl_sampai  = $_GET['tanggal_sampai'];


$objPHPExcel  = new PHPExcel();

if ($_SESSION['rolelogin'] == 'ADM' or $_SESSION['rolelogin'] == 'MGR') {

	$sqlT = "SELECT 
	  M.field_id_saldo AS ID,
	  M.field_no_referensi AS REFERENSI,
	  M.field_tanggal_saldo AS TANGGAL,
	  M.field_time AS TIMES,
	  M.field_rekening AS REKENING,
	  U.field_nama AS NAMA,
	  M.field_time AS TIMES,
	  B.field_branch_name AS TRX_CABANG,
	  M.field_type_saldo AS TIPE,
	  G.field_sell AS HARGA_EMAS,
	  G.field_buyback AS BUYBACK,
	  M.field_kredit_saldo AS KREDIT,
	  M.field_debit_saldo AS DEBIT,
	  M.field_total_saldo AS SALDO,
	  M.field_status AS STATUS
	  FROM tbltrxmutasisaldo M JOIN tbldeposit D ON M.field_no_referensi=D.field_no_referensi
	  JOIN tblnasabah N ON N.No_Rekening=M.field_rekening
	  JOIN tbluserlogin U ON U.field_user_id=N.id_UserLogin
	  JOIN tblgoldprice G ON M.field_tanggal_saldo=G.field_date_gold
	  JOIN tblbranch B ON B.field_branch_id=D.field_branch
	  WHERE  date(M.field_tanggal_saldo) >= '$tgl_dari' AND date(M.field_tanggal_saldo) <= '$tgl_sampai'
	  AND M.field_status='S'
	  ORDER BY M.field_id_saldo ASC";
	$stmtT = $db->prepare($sqlT);
	$stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai));
	$resultT = $stmtT->fetchAll();
} else {

	$sqlT = "SELECT 
	  M.field_id_saldo AS ID,
	  M.field_no_referensi AS REFERENSI,
	  M.field_tanggal_saldo AS TANGGAL,
	  M.field_time AS TIMES,
	  M.field_rekening AS REKENING,
	  U.field_nama AS NAMA,
	  M.field_time AS TIMES,
	  B.field_branch_name AS TRX_CABANG,
	  M.field_type_saldo AS TIPE,
	  G.field_sell AS HARGA_EMAS,
	  G.field_buyback AS BUYBACK,
	  M.field_kredit_saldo AS KREDIT,
	  M.field_debit_saldo AS DEBIT,
	  M.field_total_saldo AS SALDO,
	  M.field_status AS STATUS
	  FROM tbltrxmutasisaldo M JOIN tbldeposit D ON M.field_no_referensi=D.field_no_referensi
	  JOIN tblnasabah N ON N.No_Rekening=M.field_rekening
	  JOIN tbluserlogin U ON U.field_user_id=N.id_UserLogin
	  JOIN tblgoldprice G ON M.field_tanggal_saldo=G.field_date_gold
	  JOIN tblbranch B ON B.field_branch_id=D.field_branch
	  WHERE  date(M.field_tanggal_saldo) >=:tgl_dari AND date(M.field_tanggal_saldo) <= :tgl_sampai
	  
	  AND D.field_branch=:idbranch AND M.field_status='S' 
	  ORDER BY M.field_id_saldo ASC";
	$stmtT = $db->prepare($sqlT);
	$stmtT->execute(array(
		':tgl_dari'   => $tgl_dari,
		':tgl_sampai' => $tgl_sampai,
		':idbranch'   => $branchid
	));
	$resultT = $stmtT->fetchAll();
}

$filename = "Periode_Mutasi_" . $tgl_dari . "-" . $tgl_sampai;

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID_Trx_Mutasi');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Date');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Time');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'No_Reff');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Rekening');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Nasabah');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Trx Cabang');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Types');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Amount');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Saldo Akhir');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Harga Emas');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Buyback');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Status');
$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->getFont()->setBold(true);

$rowCount	=	2;

foreach ($resultT as $row) {
	$Types = $row["TIPE"];
	if ($Types == "200") {
		$Types = 'Debit';
	} else if ($Types == "100") {
		$Types = 'Kredit';
	} else if ($Types == "300") {
		$Types = 'Balance';
	}

	// while($row	=	$result->fetch_assoc()){

	$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, mb_strtoupper(sprintf("%09s", $row['ID']), 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, mb_strtoupper($row['TANGGAL'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, mb_strtoupper($row['TIMES'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, mb_strtoupper($row['REFERENSI'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, mb_strtoupper($row['REKENING'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, mb_strtoupper($row['NAMA'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, mb_strtoupper($row['TRX_CABANG'], 'UTF-8'));
	if ($row['KREDIT'] == "0") {
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, mb_strtoupper($Types, 'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, mb_strtoupper($row['DEBIT'], 'UTF-8'));
	} elseif ($row['DEBIT'] == "0") {
		$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, mb_strtoupper($Types, 'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, mb_strtoupper($row['KREDIT'], 'UTF-8'));
	}
	$objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, mb_strtoupper($row['SALDO'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, mb_strtoupper($row['HARGA_EMAS'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, mb_strtoupper($row['BUYBACK'], 'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, mb_strtoupper($row['STATUS'], 'UTF-8'));
	$rowCount++;
}


$objWriter	=	new PHPExcel_Writer_Excel2007($objPHPExcel);


header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
