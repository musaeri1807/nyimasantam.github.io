<?php
/*call the FPDF library*/
require('library/fpdf181/fpdf.php');
require_once("config/koneksi.php");
require_once("php/function.php");



$id = $_GET['w'];
// $id='1274';

//withdraw
$sql = "SELECT I.*,E.field_name_officer,E2.field_name_officer AS Approval,B.field_branch_name,
(SELECT G.field_sell FROM tblgoldprice G WHERE G.field_date_gold=I.field_date_withdraw ORDER BY field_gold_id DESC LIMIT 1) AS PriceGold,
(SELECT U.field_nama FROM tblnasabah N JOIN tbluserlogin U ON N.id_UserLogin=U.field_user_id WHERE N.No_Rekening=I.field_rekening_withdraw ) AS NAMA_NASABAH
FROM tblwithdraw I 

  LEFT JOIN tblbranch B ON I.field_branch=B.field_branch_id
  LEFT JOIN tblemployeeslogin E ON I.field_officer_id=E.field_user_id
  LEFT JOIN tblemployeeslogin E2 ON I.field_approve=E2.field_user_id


WHERE I.field_trx_withdraw=:id ORDER BY I.field_trx_withdraw DESC";

$stmt = $db->prepare($sql);
$stmt->execute(array(':id' => $id));
$rows  = $stmt->fetch(PDO::FETCH_ASSOC);


//withdraw detail di looping dengan ambil where id despit
$sql = "SELECT dp.*,P.id,P.Berat FROM tblwithdrawdetail dp
LEFT JOIN tblgoldbar P ON dp.field_product=P.id
WHERE field_trx_withdraw=:id ORDER BY field_withdraw_id ASC";
$stmt = $db->prepare($sql);
$stmt->execute(array(':id' => $id));
$result = $stmt->fetchAll();





class PDF extends FPDF
{
  // Page header
  function Header()
  {

    // Logo
    // $this->Image('logon.jpg',10,8,40);
    // Arial bold 15
    $this->SetFont('Times', 'B', 14);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30, 10, '', 0, 0, 'C');
    // Arial bold 15



    $this->Ln(20);
    // Line break
    // $this->Line(10,25,200,25);

  }



  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-40);
    // Arial italic 8
    $this->SetFont('Times', 'I', 9);
    // Page number
    $this->Cell(30, 10, 'Petugas', 1, 0, 'C');
    $this->Cell(30, 10, 'Nasabah', 1, 1, 'C');

    // Position at 1.5 cm from bottom
    $this->SetY(-30);
    // Arial italic 8
    $this->SetFont('Times', 'I', 9);
    // Page number
    $this->Cell(30, 20, '', 1, 0);
    $this->Cell(30, 20, '', 1, 1);;

    // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  }
}


/*A4 width : 219mm*/

$pdf = new PDF('L', 'mm', array(210, 160));

$pdf->AddPage();
/*output the result*/

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial', 'B', 10);

/*Cell(width , height , text , border , end line , [align] )*/


$pdf->Cell(190, 5, 'BUKTI PENARIKAN', 0, 1, 'C');
// $pdf->Cell(30 ,10,'',1,1);

// $pdf->SetFont('Arial','B',12);
// $pdf->Cell(71 ,5,'WET',0,0);
// $pdf->Cell(59 ,5,'',0,0);
// $pdf->Cell(59 ,5,'Details',0,1);

$pdf->SetFont('Arial', '', 8);

$pdf->Cell(22, 5, 'Nama', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(100, 5, $rows['NAMA_NASABAH'], 0, 0);

$pdf->Cell(22, 5, 'Petugas', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(34, 5, $rows['field_name_officer'], 0, 1);

$pdf->Cell(22, 5, 'Rekening', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(100, 5, $rows['field_rekening_withdraw'], 0, 0);

$pdf->Cell(22, 5, 'Tanggal', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(34, 5, date("d-m-Y", strtotime($rows["field_date_withdraw"])), 0, 1);

$pdf->Cell(22, 5, 'Cabang Trx', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(100, 5, $rows['field_branch'] . '-' . $rows['field_branch_name'], 0, 0);
$pdf->Cell(22, 5, 'No Reff', 0, 0);
$pdf->Cell(5, 5, ':', 0, 0);
$pdf->Cell(34, 8, $rows['field_no_referensi'], 0, 1);


// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(130 ,5,'Bill To',0,0);
// $pdf->Cell(59 ,5,'',0,0);
// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(189 ,10,'',0,1);



// $pdf->Cell(50 ,10,' asasas',0,1);

$pdf->SetFont('Arial', 'B', 8);
/*Heading Of the table*/
$pdf->Cell(13, 6, 'No Trx', 0, 0, 'C');
$pdf->Cell(80, 6, 'Keterangan', 0, 0, 'C');
$pdf->Cell(32, 6, 'Qty', 0, 0, 'C');
$pdf->Cell(31, 6, 'Satuan', 0, 0, 'L');
// $pdf->Cell(20 ,6,'Sales Tax',1,0,'C');
$pdf->Cell(32, 6, 'Total', 0, 1, 'L');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial', '', 8);
// for ($i = 1; $i <= 8; $i++) {
foreach ($result as $row) {



  $pdf->Cell(13, 6, $row['field_withdraw_id'], 0, 0, 'C');
  $pdf->Cell(80, 6, $row['field_product'] .'-'.$row['field_berat'], 0, 0);
  $pdf->Cell(32, 6, $row['field_quantity'], 0, 0, 'C');
  $pdf->Cell(31, 6, $row['field_berat'], 0, 0, 'L');
  // $pdf->Cell(20 ,6,$row['field_deposit_id'],1,0,'R');
  $pdf->Cell(32, 6, $row['field_total_berat'], 0, 1, 'L');

  // $pdf->Cell(10 ,6,$i,1,0);
  // $pdf->Cell(80 ,6,'HP Laptop',1,0);
  // $pdf->Cell(23 ,6,'1',1,0,'R');
  // $pdf->Cell(30 ,6,'15000.00',1,0,'R');
  // $pdf->Cell(20 ,6,'100.00',1,0,'R');
  // $pdf->Cell(25 ,6,'15100.00',1,1,'R');
}

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(125, 6, '', 0, 0);
$pdf->Cell(31, 6, 'Subtotal', 0, 0, 'L');
$pdf->Cell(32, 6, rupiah($rows['field_rp_withdraw']), 0, 1, 'L');



$pdf->Cell(125, 6, '', 0, 0);
$pdf->Cell(31, 6, 'Total', 0, 0, 'L');
$pdf->Cell(32, 6, rupiah($rows['field_rp_withdraw']), 0, 1, 'L');

$pdf->Cell(125, 6, '', 0, 0);
$pdf->Cell(31, 6, 'Gold', 0, 0, 'L');
$pdf->Cell(32, 6, $rows['field_withdraw_gold'], 0, 1, 'L');

//$pdf->Output('D',$rows['field_rekening_deposit'].'.pdf');
$pdf->Output();
