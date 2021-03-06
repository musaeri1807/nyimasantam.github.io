<?php 
require_once("config/koneksi.php");
require_once("Classes/PHPExcel.php");

$tgl_dari    =$_GET['tanggal_dari'];
$tgl_sampai  =$_GET['tanggal_sampai'];


$objPHPExcel  = new PHPExcel();

$sqlT = "SELECT field_deposit_id,
        field_product_name,
        field_date_deposit,
        field_no_referensi,
        field_rekening_deposit,
        field_nama_customer,
        field_branch_name,
        field_price_product,
        field_quantity,
        field_total_price,
        field_name_officer,
        field_role
       FROM tbldepositdetail T JOIN tblproduct P ON  T.field_product=P.field_product_id
                               JOIN tbldeposit I ON T.field_trx_deposit=I.field_trx_deposit 
                               JOIN tblcustomer C ON I.field_rekening_deposit=C.field_rekening 
                               JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
                               JOIN tblbranch B ON E.field_branch=B.field_branch_id 
                               WHERE  date(field_date_deposit) >=:tgl_dari AND date(field_date_deposit) <= :tgl_sampai 
                               ORDER BY field_deposit_id ASC";
$stmtT = $db->prepare($sqlT);
$stmtT->execute(array(':tgl_dari'=> $tgl_dari,':tgl_sampai'=>$tgl_sampai));
$result = $stmtT->fetchAll();

// var_dump($result);
// die();


$filename="Periode_Product_".$tgl_dari."-".$tgl_sampai;

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID_Trx');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Product');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Date');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'No Reff');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Rekening');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Customer');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Branch');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Price');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Quantity');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Total');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Officer Create');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Officer Code');
$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->getFont()->setBold(true);

$rowCount = 2;

foreach($result AS $row) {

// while($row = $result->fetch_assoc()){sprintf("%09s",$number)

  $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, mb_strtoupper(sprintf("%09s",$row['field_deposit_id']),'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($row['field_product_name'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, mb_strtoupper($row['field_date_deposit'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, mb_strtoupper($row['field_no_referensi'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, mb_strtoupper($row['field_rekening_deposit'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, mb_strtoupper($row['field_nama_customer'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, mb_strtoupper($row['field_branch_name'],'UTF-8'));  
  $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, mb_strtoupper($row['field_price_product'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, mb_strtoupper($row['field_quantity'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, mb_strtoupper($row['field_total_price'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, mb_strtoupper($row['field_name_officer'],'UTF-8'));
  $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, mb_strtoupper($row['field_role'],'UTF-8'));
  $rowCount++;
}


$objWriter  = new PHPExcel_Writer_Excel2007($objPHPExcel);


header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
$objWriter->save('php://output');
?>