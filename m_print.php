<?php
require('library/fpdf181/fpdf.php');
require_once("config/koneksi.php");



// echo 'Waktu sekarang: ' . date('Y-m-d H:i:s') . '<br/>';
// echo '1 menit kedepan: ' . date('Y-m-d H:i:s', time() + 60) . '<br/>';
// echo '1 jam kedepan: ' . date('Y-m-d H:i:s', time() + (60 * 60)) . '<br/>';
// echo '1 hari kedepan: ' . date('Y-m-d H:i:s', time() + (60 * 60 * 24)) . '<br/>';
// echo '7 hari kedepan: ' . date('Y-F-d H:i:s', time() + (60 * 60 * 24 * 7)) . '<br/>';
// echo '7 hari kebelakang: ' . date('Y-F-d H:i:s', time() - (60 * 60 * 24 * 7)) . '<br/>';

// echo date('Y-m-d', strtotime("-3 months"));
// die();


$member_id = $_GET['m'];
// $tgl_dari    = $_GET['t'];
// $tgl_sampai  = $_GET['td'];
$tgl_dari    = date('Y-m-d', strtotime("-3 months"));
$tgl_sampai  = date('Y-m-d');

//$member_id   = '085799990456';


$no = 1;


$sql = "SELECT M.*,U.field_user_id,U.field_member_id,U.field_branch,U.field_nama,BD.organisasi AS field_branch_name FROM tbltrxmutasisaldo M 
LEFT JOIN tbluserlogin U ON M.field_member_id=U.field_member_id 
LEFT JOIN tblbranch B ON U.field_branch=B.field_branch_id 
LEFT JOIN tblbranchdetail BD ON B.field_id=BD.id
WHERE M.field_member_id=:idmember ORDER BY field_id_saldo DESC";

$stmt = $db->prepare($sql);
$stmt->execute(array(':idmember' => $member_id));
$rows  = $stmt->fetch(PDO::FETCH_ASSOC);

// var_dump($rows);
// die();

$sqlT = "SELECT * FROM tbltrxmutasisaldo M WHERE  date(field_tanggal_saldo) >=:tgl_dari AND date(field_tanggal_saldo) <= :tgl_sampai 
                                           AND M.field_member_id=:idmember  
                                           ORDER BY field_id_saldo ASC";
$stmtT = $db->prepare($sqlT);
$stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai, ':idmember' => $member_id));
$result = $stmtT->fetchAll();


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
    $this->Cell(30, 10, 'Mutasi Nasabah', 0, 0, 'C');
    // Arial bold 15



    $this->Ln(20);
    // Line break
    $this->Line(10, 25, 200, 25);
  }



  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Times', 'I', 9);
    // Page number
    $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
  }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 11);
// for($i=1;$i<=40;$i++)
//     $pdf->Cell(0,10,'Printing line number '.$i,0,1);



// Memberikan space kebawah agar tidak terlalu rapat
// $pdf->Cell(10,2,'',0,1);
// $pdf->SetFont('Arial','B',10);

$pdf->Cell(35, 6, 'Rekening', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(35, 6, $rows['field_rekening'], 0, 0);
$pdf->Cell(10, 5, '', 0, 1);
$pdf->Cell(35, 6, 'Nasabah', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(35, 6, $rows['field_nama'], 0, 0);
$pdf->Cell(10, 5, '', 0, 1);
$pdf->Cell(35, 6, 'Cabang', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(35, 6, $rows['field_branch_name'], 0, 0);


$pdf->Cell(30, 6, '', 0, 0);
$pdf->Cell(15, 6, 'Periode', 0, 0);
$pdf->Cell(2, 6, ':', 0, 0);
$pdf->Cell(35, 6, date('d F Y', strtotime($tgl_dari)) . '-', 0, 0);
// $pdf->Cell(10,7,'',0,0);
// $pdf->Cell(5,6,'',0,0);
// $pdf->Cell(5,6,'/',0,0);
$pdf->Cell(10, 6, date('d F Y', strtotime($tgl_sampai)), 0, 0);
$pdf->Cell(10, 0, '', 0, 1);


// $pdf->Cell(35,6,'member ID',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, $result['field_rekening'],0,0);
// $pdf->Cell(35,6,'member ID',0,0);
// $pdf->Cell(5,6,':',0,0);
// $pdf->Cell(35,6, $result['field_rekening'],0,0);

$pdf->Cell(10, 10, '', 0, 1);
$pdf->SetFont('Times', 'B', 11);

$pdf->Cell(10, 7, 'No', 1, 0, 'C');
// $pdf->Cell(23,7,'Rekening',1,0,'C');
// $pdf->Cell(55,7,'Nama Nasabah',1,0,'C');
// $pdf->Cell(35,7,'Branch',1,0,'C');
$pdf->Cell(35, 7, 'No.Referensi', 1, 0, 'C');
$pdf->Cell(22, 7, 'Tanggal', 1, 0, 'C');
$pdf->Cell(20, 7, 'Waktu', 1, 0, 'C');
$pdf->Cell(20, 7, 'Kode', 1, 0, 'C');
$pdf->Cell(30, 7, 'Amount', 1, 0, 'C');
$pdf->Cell(30, 7, 'Saldo', 1, 0, 'C');
$pdf->Cell(23, 7, 'Status', 1, 0, 'C');
// $pdf->Cell(34,7,'KASIR',1,0,'C');
// $pdf->Cell(30,7, 'SUB TOTAL' ,1,0,'C');
// $pdf->Cell(25,7,'DISKON (%)',1,0,'C');
// $pdf->Cell(30,7,'TOTAL BAYAR',1,0);
// $pdf->Cell(30,7,'MODAL',1,0,'C');
// $pdf->Cell(30,7,'LABA',1,0,'C');

foreach ($result as $row) {
  $Types = $row["field_type_saldo"];
  if ($Types == "200") {
    $Types = 'Debit';
  } else if ($Types == "100") {
    $Types = 'Credit';
  } else if ($Types == "300") {
    $Types = 'Balance';
  }

  $Status = $row['field_status'];
  if ($Status == "S") {
    $Status = 'Success';
  } else if ($Status == 'C') {
    $Status = 'Cancel';
  }



  $pdf->Cell(10, 7, '', 0, 1);
  $pdf->SetFont('Times', '', 11);

  $pdf->Cell(10, 7, $no++, 1, 0, 'C');
  //$pdf->Cell(30,5,$no++,1,0,'C');
  // $pdf->Cell(23,7,$row['field_rekening'],1,0,'L');
  // $pdf->Cell(55,7,$row['field_nama_customer'],1,0,'L');
  // $pdf->Cell(35,7,$row['field_branch_name'],1,0,'L');
  $pdf->Cell(35, 7, $row['field_no_referensi'], 1, 0, 'L');
  $pdf->Cell(22, 7, date("d-m-Y", strtotime($row["field_tanggal_saldo"])), 1, 0, 'C');
  $pdf->Cell(20, 7, $row['field_time'], 1, 0, 'C');
  // $pdf->Cell(30,6, "Rp.".number_format($d['invoice_sub_total']).",-" ,1,0,'C');
  //$pdf->Cell(70,7,$row['field_debit_saldo'],1,0,'C');
  // $pdf->Cell(25,6,$row['field_debit_saldo'],1,0,'C');
  // $pdf->Cell(30,6,"Rp.".number_format($d['invoice_total']).",-",1,0,'C');                         

  if ($row['field_kredit_saldo'] == "0") {
    $pdf->Cell(20, 7, $Types, 1, 0, 'C');
    $pdf->Cell(30, 7, $row['field_debit_saldo'] . " g", 1, 0, 'R');
  } elseif ($row['field_debit_saldo'] == "0") {
    $pdf->Cell(20, 7, $Types, 1, 0, 'C');
    $pdf->Cell(30, 7, $row['field_kredit_saldo'] . " g", 1, 0, 'R');
  }
  $pdf->Cell(30, 7, $row['field_total_saldo'] . " g", 1, 0, 'R');
  $pdf->Cell(23, 7, $Status, 1, 0, 'C');
}

$pdf->Cell(10, 7, '', 0, 1);
$pdf->SetFont('Times', '', 11);

// $pdf->Cell(10,7,'',1,0,'C');

// $pdf->Cell(35,7,'',1,0,'L'); 
// $pdf->Cell(22,7,'',1,0,'C');
// $pdf->Cell(20,7,'',1,0,'C');

// $pdf->Cell(20,7,'',1,0,'L');                          
// $pdf->Cell(30,7,'',1,0,'R'); 

$pdf->Cell(167, 7, $rows['field_total_saldo'] . " g", 1, 0, 'R');
$pdf->Cell(23, 7, '', 0, 0, 'C');

$pdf->Output('D', $rows['field_rekening'] . '_Mutasi' . '.pdf');
// $pdf->Output();
