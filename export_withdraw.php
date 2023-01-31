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
                    WD.field_withdraw_id AS ID,
                    WD.field_trx_withdraw AS ID_TX,
                    WD.field_product AS PRODUK,
                    WD.field_berat AS BERAT,
                    WD.field_quantity AS QTY,
                    WD.field_total_berat AS TOTAL,
                    W.field_no_referensi AS REFERENSI,
                    W.field_date_withdraw AS TANGGAL,
                    W.field_rekening_withdraw AS REKENING,
                    U.field_nama AS NAMA,
                    W.field_type_withdraw AS TIPE,
                    B.field_branch_name AS Trx_CABANG,
                    E.field_name_officer AS PETUGAS,
                    W.field_gold_price AS HARGA,
                    W.field_withdraw_gold AS TARIK_EMAS,
                    W.field_rp_withdraw AS RUPIAH,
                    W.field_status AS STATUS,
                    EP.field_name_officer AS APROVAL
                    
                    FROM tblwithdrawdetail WD
                    LEFT JOIN tblwithdraw W ON WD.field_trx_withdraw=W.field_trx_withdraw
                    LEFT JOIN tblnasabah N ON W.field_rekening_withdraw=N.No_Rekening
                    LEFT JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                    LEFT JOIN tblbranch B ON W.field_branch=B.field_branch_id
                    LEFT JOIN tblemployeeslogin E ON W.field_officer_id=E.field_user_id
                    LEFT JOIN tblemployeeslogin EP ON W.field_approve=EP.field_user_id
                    WHERE  date(W.field_date_withdraw) >=:tgl_dari AND date(W.field_date_withdraw) <= :tgl_sampai
                    ORDER BY WD.field_withdraw_id ASC";
  $stmtT = $db->prepare($sqlT);
  $stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai));
  $resultT = $stmtT->fetchAll();
  # code...
  // } elseif ($_SESSION['rolelogin'] == 'SVP' or $_SESSION['rolelogin'] == 'BCO' or $_SESSION['rolelogin'] == 'CMS') {
} else {

  $sqlT = "SELECT
                    WD.field_withdraw_id AS ID,
                    WD.field_trx_withdraw AS ID_TX,
                    WD.field_product AS PRODUK,
                    WD.field_berat AS BERAT,
                    WD.field_quantity AS QTY,
                    WD.field_total_berat AS TOTAL,
                    W.field_no_referensi AS REFERENSI,
                    W.field_date_withdraw AS TANGGAL,
                    W.field_rekening_withdraw AS REKENING,
                    U.field_nama AS NAMA,
                    W.field_type_withdraw AS TIPE,
                    B.field_branch_name AS Trx_CABANG,
                    E.field_name_officer AS PETUGAS,
                    W.field_gold_price AS HARGA,
                    W.field_withdraw_gold AS TARIK_EMAS,
                    W.field_rp_withdraw AS RUPIAH,
                    W.field_status AS STATUS,
                    EP.field_name_officer AS APROVAL
                    
                    FROM tblwithdrawdetail WD
                    LEFT JOIN tblwithdraw W ON WD.field_trx_withdraw=W.field_trx_withdraw
                    LEFT JOIN tblnasabah N ON W.field_rekening_withdraw=N.No_Rekening
                    LEFT JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                    LEFT JOIN tblbranch B ON W.field_branch=B.field_branch_id
                    LEFT JOIN tblemployeeslogin E ON W.field_officer_id=E.field_user_id
                    LEFT JOIN tblemployeeslogin EP ON W.field_approve=EP.field_user_id
                    WHERE  date(I.field_date_deposit) >=:tgl_dari AND date(I.field_date_deposit) <= :tgl_sampai AND
                                      I.field_branch=:idbranch
                    ORDER BY T.field_deposit_id ASC";
  $stmtT = $db->prepare($sqlT);
  $stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai, ':idbranch' => $branchid));

  $resultT = $stmtT->fetchAll();
}

$filename = "Periode_withdraw_" . $tgl_dari . "-" . $tgl_sampai;

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ID_TX');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PRODUK');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'BERAT');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'QTY');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'TOTAL');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'REFERENSI');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'TANGGAL');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'REKENING');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'NAMA');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'TIPE');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Trx_CABANG');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'PETUGAS');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'HARGA');
$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'TARIK EMAS');
$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'RUPIAH');
$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'STATUS');
$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'APROVAL');
$objPHPExcel->getActiveSheet()->getStyle("A1:R1")->getFont()->setBold(true);

$rowCount = 2;

foreach ($resultT as $row) {

  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, mb_strtoupper(sprintf("%09s", $row['ID']), 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, mb_strtoupper($row['ID'], 'UTF-8'));
  if ($row['PRODUK'] = 0) {

    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, mb_strtoupper($row['PRODUK'], 'UTF-8'));
  } else {
    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, mb_strtoupper('EMAS BATANGAN', 'UTF-8'));
  }
  $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, mb_strtoupper($row['BERAT'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, mb_strtoupper($row['QTY'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, mb_strtoupper($row['TOTAL'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, mb_strtoupper($row['REFERENSI'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, mb_strtoupper($row['TANGGAL'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, mb_strtoupper($row['REKENING'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, mb_strtoupper($row['NAMA'], 'UTF-8'));
  if ($row['TIPE'] == 201) {

    $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, mb_strtoupper('Fisik', 'UTF-8'));
  } else {
    $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, mb_strtoupper('buyback', 'UTF-8'));
  }
  $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, mb_strtoupper($row['Trx_CABANG'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, mb_strtoupper($row['PETUGAS'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, mb_strtoupper($row['HARGA'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, mb_strtoupper($row['TARIK_EMAS'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, mb_strtoupper($row['RUPIAH'], 'UTF-8'));
  if ($row['STATUS'] == 'S') {
    $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, mb_strtoupper('Berhasil', 'UTF-8'));
  } else {
    $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, mb_strtoupper($row['STATUS'], 'UTF-8'));
  }
  $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, mb_strtoupper($row['APROVAL'], 'UTF-8'));
  $rowCount++;
}


$objWriter  = new PHPExcel_Writer_Excel2007($objPHPExcel);


header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
