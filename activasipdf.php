<?php
require('library/fpdf181/fpdf.php');
require_once("config/koneksi.php");

// $member_id = '085799990456';
$member_id = $_GET['m'];

$no=1;
$sql= "SELECT * FROM tbluserlogin U JOIN tblbranch B ON U.field_branch=B.field_branch_id 
                                    WHERE U.field_member_id=:idmember  
                                    ORDER BY field_user_id DESC";

$stmt =$db->prepare($sql);
$stmt->execute(array(':idmember'=>$member_id));
$rows  = $stmt->fetch(PDO::FETCH_ASSOC);

// var_dump($rows);
// die();



class PDF extends FPDF
{
// Page header
function Header()
{
   
    // Logo
    $this->Image('logon.jpg',10,4,40);
    // Arial bold 15
    $this->SetFont('Times','B',16);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,5,'Forms Activasi Customer',0,0,'C');
    // Arial bold 15
    
 

    $this->Ln(18);
    // Line break
    $this->Line(10,25,200,25);

}



// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Times','I',9);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// for($i=1;$i<=40;$i++)
//     $pdf->Cell(0,10,'Printing line number '.$i,0,1);



// Memberikan space kebawah agar tidak terlalu rapat
// $pdf->Cell(10,2,'',0,1);
// $pdf->SetFont('Arial','B',10);
$pdf->SetFont('Times','B',16);
$pdf->Cell(35,2,'Customer',0,1);
$pdf->Cell(10,2,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Customer',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(60,6, $rows['field_nama'],0,0);

// $pdf->Cell(10,9,'',0,1);
$pdf->Cell(23,6, date("d F Y",strtotime($rows['field_tanggal_reg'])),0,0);
$pdf->Cell(3,6,',',0,0); 
$pdf->Cell(35,6, $rows['field_time_reg'].' WIB',0,1);
// $pdf->Cell(10,9,'',0,1);

$pdf->Cell(35,6,'Email',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(60,6, $rows['field_email'],0,0);
// $pdf->Cell(10,9,'',0,1);
$pdf->Cell(20,6,'Handphone',0,0);
$pdf->Cell(3,6,':',0,0);
$pdf->Cell(35,6, $rows['field_handphone'],0,1);
// $pdf->Cell(10,9,'',0,1);

$pdf->Cell(35,6,'Member ID',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(60,6,$rows['field_member_id'] ,0,0);

$pdf->Cell(20,6,'Account*',0,0);
$pdf->Cell(4,6,':',0,0);

$pdf->SetFont('Times','',1);
for($i=1;$i<=10;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,0,'',0,1);

$pdf->Cell(10,9,'',0,1);
$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Branch',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(60,6, $rows['field_branch_name'] ,0,0);

$pdf->Cell(20,6,'Date*',0,0);
$pdf->Cell(4,6,':',0,0);

$pdf->SetFont('Times','',1);
for($i=1;$i<=10;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,0,'',0,1);



$pdf->Cell(10,9,'',0,1);
$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'NIK',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Tempat/Tgl Lahir',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Jenis Kelamin',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Alamat',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'      RT/RW',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'      Kel/Desa',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'      Kecamatan',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Agama',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Status Perkawinan',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);
// .......................
$pdf->Cell(10,2,'',0,1);
$pdf->SetFont('Times','B',16);
$pdf->Cell(35,2,'Data Pewaris',0,1);
$pdf->Cell(10,2,'',0,1);


// $pdf->Cell(10,9,'',0,1);
$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Nama',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'NIK',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Tempat/Tgl Lahir',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Jenis Kelamin',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Alamat',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'      RT/RW',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'      Kel/Desa',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'      Kecamatan',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Agama',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Status Perkawinan',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
$pdf->Cell(10,9,'',0,1);

$pdf->SetFont('Times','',12);
$pdf->Cell(35,6,'Handphone',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->SetFont('Times','',1);
for($i=1;$i<=22;$i++)
     $pdf->Cell(7,7,$i,1,0,'C');
// $pdf->Cell(10,6,'',0,1);



$pdf->Cell(10,16,'',0,1);

$pdf->Cell(125,20,'',0,0);
$pdf->SetFont('Times','',11);
$pdf->Cell(35,20,'Officer',1,0);
$pdf->Cell(35,20,'Customer',1,0);



$pdf->Output('D',$rows['field_nama'].'.pdf');
//$pdf->Output();
?>