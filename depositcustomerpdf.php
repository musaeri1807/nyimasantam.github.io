<?php
/*call the FPDF library*/
require('library/fpdf181/fpdf.php');
require_once("config/koneksi.php");
require_once("php/function.php");



$id = $_GET['d'];
// $id='1274';

// $sql= "SELECT * FROM tbldeposit WHERE field_trx_deposit=:id ORDER BY field_trx_deposit DESC";

$sql="SELECT D.*,C.field_member_id,U.field_nama,E.field_name_officer FROM tbldeposit D 
LEFT JOIN tblcustomer C ON D.field_rekening_deposit=C.field_rekening
LEFT JOIN tbluserlogin U ON C.field_member_id=U.field_member_id
LEFT JOIN tblemployeeslogin E ON D.field_officer_id=E.field_user_id
WHERE field_trx_deposit=:id ORDER BY field_trx_deposit DESC";

$stmt =$db->prepare($sql);
$stmt->execute(array(':id'=>$id));
$rows  = $stmt->fetch(PDO::FETCH_ASSOC);



$sql= "SELECT dp.*,P.field_product_code,P.field_product_name FROM tbldepositdetail dp
LEFT JOIN tblproduct P ON dp.field_product=P.field_product_id
WHERE field_trx_deposit=:id ORDER BY field_deposit_id ASC";

$stmt =$db->prepare($sql);
$stmt->execute(array(':id'=>$id));
$result = $stmt->fetchAll();





class PDF extends FPDF
{
// Page header
function Header()
{
   
    // Logo
    // $this->Image('logon.jpg',10,8,40);
    // Arial bold 15
    $this->SetFont('Times','B',14);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,'',0,0,'C');
    // Arial bold 15
    
 

    $this->Ln(20);
    // Line break
    // $this->Line(10,25,200,25);

}



// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Times','I',9);
    // Page number
    $this->Cell(10,10,'CS',1,0,'L');
    $this->Cell(10,10,'NS',1,0,'L');
    // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

	
/*A4 width : 219mm*/

$pdf = new PDF('L','mm',array(210,160));

$pdf->AddPage();
/*output the result*/

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',10);

/*Cell(width , height , text , border , end line , [align] )*/


$pdf->Cell(190 ,5,'BUKTI SETORAN',0,1,'C');
// $pdf->Cell(30 ,10,'',1,1);

// $pdf->SetFont('Arial','B',12);
// $pdf->Cell(71 ,5,'WET',0,0);
// $pdf->Cell(59 ,5,'',0,0);
// $pdf->Cell(59 ,5,'Details',0,1);

$pdf->SetFont('Arial','',8);

$pdf->Cell(22 ,5,'Name',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(100 ,5,$rows['field_nama'],0,0);

$pdf->Cell(22 ,5,'Officer',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(34 ,5,$rows['field_name_officer'],0,1);

$pdf->Cell(22 ,5,'Customer ID',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(100 ,5,$rows['field_rekening_deposit'],0,0);

$pdf->Cell(22 ,5,'Date',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(34 ,5,date("d-m-Y", strtotime($rows["field_date_deposit"])),0,1);
 
$pdf->Cell(22 ,5,'Branch Trx',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(100 ,5,$rows['field_branch'],0,0);
$pdf->Cell(22 ,5,'No',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(34 ,8,$rows['field_no_referensi'],0,1);


// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(130 ,5,'Bill To',0,0);
// $pdf->Cell(59 ,5,'',0,0);
// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(189 ,10,'',0,1);



// $pdf->Cell(50 ,10,' asasas',0,1);

$pdf->SetFont('Arial','B',8);
/*Heading Of the table*/
$pdf->Cell(13 ,6,'No Trx',0,0,'C');
$pdf->Cell(80 ,6,'Description',0,0,'C');
$pdf->Cell(32 ,6,'Qty',0,0,'C');
$pdf->Cell(31 ,6,'Unit Price',0,0,'C');
// $pdf->Cell(20 ,6,'Sales Tax',1,0,'C');
$pdf->Cell(32 ,6,'Total',0,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);
    // for ($i = 1; $i <= 8; $i++) {
  foreach($result as $row) {
       
        
    
		$pdf->Cell(13 ,6,$row['field_deposit_id'],0,0,'C');
		$pdf->Cell(80 ,6,$row['field_product_code'].$row['field_product_name'],0,0);
		$pdf->Cell(32 ,6,$row['field_quantity'],0,0,'C');
		$pdf->Cell(31 ,6,rupiah($row['field_price_product']),0,0,'L');
		// $pdf->Cell(20 ,6,$row['field_deposit_id'],1,0,'R');
		$pdf->Cell(32 ,6,rupiah($row['field_total_price']),0,1,'L');

    // $pdf->Cell(10 ,6,$i,1,0);
		// $pdf->Cell(80 ,6,'HP Laptop',1,0);
		// $pdf->Cell(23 ,6,'1',1,0,'R');
		// $pdf->Cell(30 ,6,'15000.00',1,0,'R');
		// $pdf->Cell(20 ,6,'100.00',1,0,'R');
		// $pdf->Cell(25 ,6,'15100.00',1,1,'R');
	}
		
$pdf->SetFont('Arial','B',8);
$pdf->Cell(125 ,6,'',0,0);
$pdf->Cell(31 ,6,'Subtotal',0,0,'L');
$pdf->Cell(32 ,6,rupiah($rows['field_sub_total']),0,1,'L');

$pdf->Cell(125 ,6,'',0,0);
$pdf->Cell(31 ,6,'Fee',0,0,'L');
$pdf->Cell(32 ,6,rupiah($rows['field_operation_fee_rp']),0,1,'L');

$pdf->Cell(125 ,6,'',0,0);
$pdf->Cell(31 ,6,'Total',0,0,'L');
$pdf->Cell(32 ,6,rupiah($rows['field_total_deposit']),0,1,'L');

$pdf->Cell(125 ,6,'',0,0);
$pdf->Cell(31 ,6,'Gold',0,0,'L');
$pdf->Cell(32 ,6,$rows['field_deposit_gold'],0,1,'L');

//$pdf->Output('D',$rows['field_rekening_deposit'].'.pdf');
$pdf->Output();

?>