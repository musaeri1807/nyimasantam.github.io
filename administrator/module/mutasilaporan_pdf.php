<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

// memanggil library FPDF
require('../library/fpdf181/fpdf.php');
      
if(!isset($_SESSION['administrator_login'])) {
    header("location: ../index.php");
}
        
        // $id = $_SESSION['user_login'];        
        // $select_stmt = $db->prepare("SELECT * FROM tbluserlogin WHERE field_user_id=:uid");
        // $select_stmt->execute(array(":uid"=>$id));  
        // $rows=$select_stmt->fetch(PDO::FETCH_ASSOC);
        
        //       if(isset($_SESSION['user_login'])){            
         
        //         $trx_id_member=$_SESSION["login_member_id"];
        //       }


$tgl_dari = $_GET['tanggal_dari'];
$tgl_sampai = $_GET['tanggal_sampai'];
$no=1;
$sqlT = "SELECT * FROM tbltrxmutasisaldo WHERE  date(field_tanggal_saldo) >= '$tgl_dari' AND date(field_tanggal_saldo) <= '$tgl_sampai' ORDER BY field_id_saldo ASC";
$stmtT = $db->prepare($sqlT);
$stmtT->execute();
$resultT = $stmtT->fetchAll();

$stmt =$db->prepare($sqlT);
$stmt->execute();
$result  = $stmt->fetch(PDO::FETCH_ASSOC);
//$nama=$resultT['field_member_id'];





// intance object dan memberikan pengaturan halaman PDF
$pdf = new FPDF('P','mm','A4');
//$pdf = new FPDF('L','mm',array(215.9,139.7));

// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','B',22);
// mencetak string 
$pdf->Cell(200,7,'Mutasi',0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(200,7,'Nasabah',0,1,'C');

// Memberikan space kebawah agar tidak terlalu rapat
$pdf->Cell(10,2,'',0,1);
$pdf->SetFont('Arial','B',11);

$pdf->Cell(35,6,'No Rekening',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(35,6, 'Rekening',0,0);
$pdf->Cell(10,5,'',0,1);
$pdf->Cell(35,6,'Nama Nasabah',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(35,6, 'MUSAERI' ,0,0);
$pdf->Cell(10,7,'',0,1);
$pdf->Cell(35,6,'Dari Tanggal',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(25,6, date('d-m-Y', strtotime($tgl_dari)) ,0,0);
// $pdf->Cell(10,7,'',0,0);
$pdf->Cell(35,6,'Sampai Tanggal',0,0);
$pdf->Cell(5,6,':',0,0);
$pdf->Cell(35,6, date('d-m-Y', strtotime($tgl_sampai)) ,0,0);
$pdf->Cell(10,0,'',0,1);


// $pdf->Cell(35,6,'member ID',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, $result['field_rekening'],0,0);
// $pdf->Cell(35,6,'member ID',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, $result['field_rekening'],0,0);

$pdf->Cell(10,10,'',0,1);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(10,7,'No',0,0,'C');
$pdf->Cell(80,7,'No.Reff',0,0,'C');
$pdf->Cell(70,7, 'Amount' ,0,0,'C');
$pdf->Cell(30,7, 'Saldo' ,0,0,'C');
// $pdf->Cell(30,7,'PELANGGAN',1,0,'C');
// $pdf->Cell(34,7,'KASIR',1,0,'C');
// $pdf->Cell(30,7, 'SUB TOTAL' ,1,0,'C');
// $pdf->Cell(25,7,'DISKON (%)',1,0,'C');
// $pdf->Cell(30,7,'TOTAL BAYAR',1,0);
// $pdf->Cell(30,7,'MODAL',1,0,'C');
// $pdf->Cell(30,7,'LABA',1,0,'C');
                           
foreach($resultT as $row) {
 $Types = $row["field_type_saldo"];
if($Types=="200"){
  $Types = 'Debit';
                    
  }else if($Types=="100"){
  $Types = 'Kredit';
                         
  }else if($Types=="300"){
  $Types = 'Balance';
                        
  }

                
  $pdf->Cell(10,7,'',0,1);
  $pdf->SetFont('Arial','',12);

  $pdf->Cell(10,7,$no++,0,0,'C');
  //$pdf->Cell(30,5,$no++,1,0,'C');
  $pdf->Cell(45,7,$row['field_no_referensi'],0,0,'C'); 
  $pdf->Cell(20,7,$row['field_tanggal_saldo'],0,0,'C');
  $pdf->Cell(20,7,$row['field_time'],0,0,'C');
  // $pdf->Cell(30,6, "Rp.".number_format($d['invoice_sub_total']).",-" ,1,0,'C');
  //$pdf->Cell(70,7,$row['field_debit_saldo'],1,0,'C');
  // $pdf->Cell(25,6,$row['field_debit_saldo'],1,0,'C');
  // $pdf->Cell(30,6,"Rp.".number_format($d['invoice_total']).",-",1,0,'C');                         
                         
  if ($row['field_kredit_saldo']=="0") {
  $pdf->Cell(20,7,$Types,0,0,'L');                          
  $pdf->Cell(30,7,$row['field_debit_saldo']." g",0,0,'R'); 

  }elseif ($row['field_debit_saldo']=="0") {  
  $pdf->Cell(20,7,$Types,0,0,'L');                
  $pdf->Cell(30,7,$row['field_kredit_saldo']." g",0,0,'R'); 

  }                   
  $pdf->Cell(40,7,$row['field_total_saldo']." g",0,0,'R');

}

// $pdf->Cell(10,7,'',0,1);
// $pdf->SetFont('Arial','B',10);

// $pdf->Cell(50,6,'Saldo',0,0);
// $pdf->Cell(50,6,':',0,0);
// $pdf->Cell(35,6, $row['field_total_saldo'],0,0);
// $pdf->Cell(10,7,'',0,1);





//$pdf->Output('D');
$pdf->Output();
?>