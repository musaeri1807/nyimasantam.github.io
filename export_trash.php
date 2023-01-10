<?php
require_once("config/koneksi.php");
require_once("Classes/PHPExcel.php");

$tgl_dari    = $_GET['tanggal_dari'];
$tgl_sampai  = $_GET['tanggal_sampai'];


$objPHPExcel  = new PHPExcel();

$sqlT = "SELECT     T.field_trx_deposit AS ID,
                    T.field_deposit_id,
                    P.field_product_name AS PRODUK,
                    I.field_date_deposit AS TANGGAL,
                    I.field_no_referensi AS REFERENSI,
                    I.field_rekening_deposit AS REKENING,
                    N.No_Rekening,
                    U.field_nama AS NAMA,
                    B.field_branch_name AS CABANG,
                    T.field_price_product AS HARGA,
                    T.field_quantity AS QTY,
                    T.field_total_price AS TOTAL,
                    I.field_operation_fee AS 5PERSEN,
                    T.field_total_price/100*5 AS RESULT_PERSEN,
                    T.field_total_price-T.field_total_price/100*5 AS DEPO,                                      
                    (T.field_total_price-T.field_total_price/100*5)/I.field_gold_price AS GOLD,                                    
                    I.field_gold_price AS HARGA_EMAS,
                    E.field_name_officer AS PETUGAS,                                          
                    E.field_role
                    FROM tbldepositdetail T JOIN tblproduct P ON  T.field_product=P.field_product_id
                    JOIN tbldeposit I ON T.field_trx_deposit=I.field_trx_deposit 
                    JOIN tblnasabah N ON I.field_rekening_deposit=N.No_Rekening
                    JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id
                    JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id                                          
                    JOIN tblbranch B ON U.field_branch=B.field_branch_id                                            
                    WHERE  date(I.field_date_deposit) >=:tgl_dari AND date(I.field_date_deposit) <= :tgl_sampai 

                    ORDER BY T.field_deposit_id ASC";
$stmtT = $db->prepare($sqlT);
$stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai));
$result = $stmtT->fetchAll();



$filename = "Periode_Product_" . $tgl_dari . "-" . $tgl_sampai;

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID_Trx');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Product');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Tanggal');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'No Reff');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Rekening');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Cabang');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Harga');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Quantity');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'SubTotal');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Oprasional Fee');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Total');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Konversi Emas');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Harga Emas');
$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Petugas');
$objPHPExcel->getActiveSheet()->getStyle("A1:O1")->getFont()->setBold(true);

$rowCount = 2;

foreach ($result as $row) {

  // while($row = $result->fetch_assoc()){sprintf("%09s",$number)

  $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, mb_strtoupper(sprintf("%09s", $row['field_deposit_id']), 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, mb_strtoupper($row['PRODUK'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, mb_strtoupper($row['TANGGAL'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, mb_strtoupper($row['REFERENSI'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, mb_strtoupper($row['REKENING'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, mb_strtoupper($row['NAMA'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, mb_strtoupper($row['CABANG'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, mb_strtoupper($row['HARGA'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, mb_strtoupper($row['QTY'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, mb_strtoupper($row['TOTAL'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, mb_strtoupper($row['RESULT_PERSEN'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, mb_strtoupper($row['DEPO'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, mb_strtoupper($row['GOLD'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, mb_strtoupper($row['HARGA_EMAS'], 'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, mb_strtoupper($row['PETUGAS'], 'UTF-8'));
  $rowCount++;
}


$objWriter  = new PHPExcel_Writer_Excel2007($objPHPExcel);


header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
