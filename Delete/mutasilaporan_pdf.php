<?php
// memanggil library FPDF
require('library/fpdf181/fpdf.php');
require_once("config/koneksi.php");



//   // FUNGSI TERBILANG OLEH : MALASNGODING.COM
//   // WEBSITE : WWW.MALASNGODING.COM
//   // AUTHOR : https://www.malasngoding.com/author/admin
 
 
//   function penyebut($nilai) {
//     $nilai = abs($nilai);
//     $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
//     $temp = "";
//     if ($nilai < 12) {
//       $temp = " ". $huruf[$nilai];
//     } else if ($nilai <20) {
//       $temp = penyebut($nilai - 10). " belas";
//     } else if ($nilai < 100) {
//       $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
//     } else if ($nilai < 200) {
//       $temp = " seratus" . penyebut($nilai - 100);
//     } else if ($nilai < 1000) {
//       $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
//     } else if ($nilai < 2000) {
//       $temp = " seribu" . penyebut($nilai - 1000);
//     } else if ($nilai < 1000000) {
//       $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
//     } else if ($nilai < 1000000000) {
//       $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
//     } else if ($nilai < 1000000000000) {
//       $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
//     } else if ($nilai < 1000000000000000) {
//       $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
//     }     
//     return $temp;
//   }
 
//   function terbilang($nilai) {
//     if($nilai<0) {
//       $hasil = "minus ". trim(penyebut($nilai));
//     } else {
//       $hasil = trim(penyebut($nilai));
//     }         
//     return $hasil;
//   }
 
 
//   $angka = 100000;
//   echo terbilang($angka)."Rupiah";

// die();


// $tgl_dari = $_GET['tanggal_dari'];
// $tgl_sampai = $_GET['tanggal_sampai'];
 $tgl_dari    = '2020-07-01';
 $tgl_sampai  = '2021-09-31';
 $member_id   = '085799990456';

$no=1;
//$sqlT = "SELECT * FROM( (tbltrxmutasisaldo M JOIN tblnasabah N ON M.field_rekening=N.field_rekening) JOIN tblbranch B ON N.field_branch=B.field_branch_id) WHERE  date(field_tanggal_saldo) >= '$tgl_dari' AND date(field_tanggal_saldo) <= '$tgl_sampai'  ORDER BY field_id_saldo DESC";
$sqlT = "SELECT * FROM tbltrxmutasisaldo M JOIN tblcustomer C ON M.field_rekening=C.field_rekening
                                           JOIN tbluserlogin U ON U.field_member_id=C.field_member_id 
                                           JOIN tblbranch B ON U.field_branch=B.field_branch_id 
                                           WHERE  date(field_tanggal_saldo) >=:tgl_dari AND date(field_tanggal_saldo) <= :tgl_sampai AND 
                                           M.field_member_id=:idmember  
                                           ORDER BY field_id_saldo ASC";
$stmtT = $db->prepare($sqlT);
$stmtT->execute(array(':tgl_dari'=> $tgl_dari,':tgl_sampai'=>$tgl_sampai, ':idmember'=>$member_id));
$result = $stmtT->fetchAll();

// $stmt =$db->prepare($sqlT);
// $stmt->execute();
// $result  = $stmt->fetch(PDO::FETCH_ASSOC);
//$nama=$resultT['field_member_id'];


// var_dump($result);
// die();

// class PDF extends FPDF
// {
//   //Page header
//   function Header()
//   {
//     //Logo
//     $this->Image('satu.jpg',10,8);
//     //Arial bold 15
//     $this->SetFont('Arial','B',15);
//     //pindah ke posisi ke tengah untuk membuat judul
//     $this->Cell(80);
//     //judul
//     $this->Cell(30,10,'LAPORAN REKAPITULASI PENERIMAAN MAHASISWA BARU',0,0,'C');
//     //pindah baris
//     $this->Ln(20);
//     //buat garis horisontal
//     $this->Line(10,25,200,25);
//   }
 
//   //Page Content
//   function Content()
//   {
//     $this->SetFont('Times','',12);
//     for($i=1; $i<=40; $i++)
//       $this->Cell(0,10,'Laporan Mahasiswa '.$i,0,1);
//   }
 
//   //Page footer
//   function Footer()
//   {
//     //atur posisi 1.5 cm dari bawah
//     $this->SetY(-15);
//     //buat garis horizontal
//     $this->Line(10,$this->GetY(),200,$this->GetY());
//     //Arial italic 9
//     $this->SetFont('Arial','I',9);
//     //nomor halaman
//     $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
//   }
// }

// intance object dan memberikan pengaturan halaman PDF
$pdf = new FPDF('P','mm','A4');

//$pdf = new FPDF('L','mm',array(215.9,139.7));

// membuat halaman baru
$pdf->AddPage();
//Image
$pdf->Image('logo.jpg',10,8,350);
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','B',12);
// mencetak string 
$pdf->Cell(70,7,'Report Mutasi',0,1,'C');
$pdf->Line(10,25,275,25);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(270,7,'Nasabah',0,1,'C');

// Memberikan space kebawah agar tidak terlalu rapat
$pdf->Cell(10,2,'',0,1);
$pdf->SetFont('Arial','B',11);

// $pdf->Cell(35,6,'No Rekening',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, 'ALL REKENING',0,0);
// $pdf->Cell(10,5,'',0,1);
// $pdf->Cell(35,6,'Nama Nasabah',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, 'ALLNAM' ,0,0);
$pdf->Cell(10,7,'',0,1);
$pdf->Cell(20,6,'Periode',0,0);
$pdf->Cell(2,6,':',0,0);
$pdf->Cell(33,6, date('d F Y', strtotime($tgl_dari)) ,0,0);
// $pdf->Cell(10,7,'',0,0);
$pdf->Cell(5,6,'-',0,0);
// $pdf->Cell(5,6,'/',0,0);
$pdf->Cell(33,6, date('d F Y', strtotime($tgl_sampai)) ,0,0);
$pdf->Cell(10,0,'',0,1);


// $pdf->Cell(35,6,'member ID',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, $result['field_rekening'],0,0);
// $pdf->Cell(35,6,'member ID',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, $result['field_rekening'],0,0);

$pdf->Cell(10,10,'',0,1);
$pdf->SetFont('Arial','B',11);

$pdf->Cell(10,7,'No',1,0,'C');
// $pdf->Cell(23,7,'Rekening',1,0,'C');
// $pdf->Cell(55,7,'Nama Nasabah',1,0,'C');
// $pdf->Cell(35,7,'Branch',1,0,'C');
$pdf->Cell(35,7,'No.Reff',1,0,'C');
$pdf->Cell(22,7,'Tanggal' ,1,0,'C');
$pdf->Cell(18,7,'Time' ,1,0,'C');
$pdf->Cell(20,7,'Kode' ,1,0,'C');
$pdf->Cell(30,7,'Amount' ,1,0,'C');
$pdf->Cell(30,7,'Saldo' ,1,0,'C');
// $pdf->Cell(30,7,'PELANGGAN',1,0,'C');
// $pdf->Cell(34,7,'KASIR',1,0,'C');
// $pdf->Cell(30,7, 'SUB TOTAL' ,1,0,'C');
// $pdf->Cell(25,7,'DISKON (%)',1,0,'C');
// $pdf->Cell(30,7,'TOTAL BAYAR',1,0);
// $pdf->Cell(30,7,'MODAL',1,0,'C');
// $pdf->Cell(30,7,'LABA',1,0,'C');
                           
foreach($result as $row) {
 $Types = $row["field_type_saldo"];
if($Types=="200"){
  $Types = 'Debit';
                    
  }else if($Types=="100"){
  $Types = 'Kredit';
                         
  }else if($Types=="300"){
  $Types = 'Balance';
                        
  }

                
  $pdf->Cell(10,7,'',0,1);
  $pdf->SetFont('Arial','',10);

  $pdf->Cell(10,7,$no++,1,0,'C');
  //$pdf->Cell(30,5,$no++,1,0,'C');
  // $pdf->Cell(23,7,$row['field_rekening'],1,0,'L');
  // $pdf->Cell(55,7,$row['field_nama_customer'],1,0,'L');
  // $pdf->Cell(35,7,$row['field_branch_name'],1,0,'L');
  $pdf->Cell(35,7,$row['field_no_referensi'],1,0,'L'); 
  $pdf->Cell(22,7,$row['field_tanggal_saldo'],1,0,'C');
  $pdf->Cell(18,7,$row['field_time'],1,0,'C');
  // $pdf->Cell(30,6, "Rp.".number_format($d['invoice_sub_total']).",-" ,1,0,'C');
  //$pdf->Cell(70,7,$row['field_debit_saldo'],1,0,'C');
  // $pdf->Cell(25,6,$row['field_debit_saldo'],1,0,'C');
  // $pdf->Cell(30,6,"Rp.".number_format($d['invoice_total']).",-",1,0,'C');                         
                         
  if ($row['field_kredit_saldo']=="0") {
  $pdf->Cell(20,7,$Types,1,0,'L');                          
  $pdf->Cell(30,7,$row['field_debit_saldo']." g",1,0,'R'); 

  }elseif ($row['field_debit_saldo']=="0") {  
  $pdf->Cell(20,7,$Types,1,0,'L');                
  $pdf->Cell(30,7,$row['field_kredit_saldo']." g",1,0,'R'); 

  }                   
  $pdf->Cell(30,7,$row['field_total_saldo']." g",1,0,'R');

}

// $pdf->Cell(10,7,'',0,1);
// $pdf->SetFont('Arial','B',10);

// $pdf->Cell(50,6,'Saldo',0,0);
// $pdf->Cell(50,6,':',0,0);
// $pdf->Cell(35,6, $row['field_total_saldo'],0,0);
// $pdf->Cell(10,7,'',0,1);




$pdf->AddPage();
//$pdf->Output('D');
$pdf->Output();
?>