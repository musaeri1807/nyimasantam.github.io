<?php
require('library/fpdf181/fpdf.php');
require_once("config/koneksi.php");



$member_id = $_GET['m'];

$no = 1;
$sql = "SELECT *,W.id_UserLogin AS UserLogin  FROM tbluserlogin U 
JOIN tblnasabah N ON U.field_user_id=N.id_UserLogin
JOIN tblpewaris W ON U.field_user_id=W.id_UserLogin
JOIN tblbranch B ON U.field_branch=B.field_branch_id
WHERE U.field_user_id=:id 
ORDER BY U.field_user_id DESC";



$stmt = $db->prepare($sql);
$stmt->execute(array(':id' => $member_id));
$rows  = $stmt->fetch(PDO::FETCH_ASSOC);




class PDF extends FPDF
{
     // Page header
     function Header()
     {

          // Logo
          $this->Image('Logo-miga.png', 10, 4, 20);
          // Arial bold 15
          $this->SetFont('Times', 'B', 16);
          // Move to the right
          $this->Cell(80);
          // Title
          $this->Cell(30, 5, 'Formulir Nasabah', 0, 0, 'C');
          // Arial bold 15



          $this->Ln(18);
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

// for($i=1;$i<=40;$i++)
//     $pdf->Cell(0,10,'Printing line number '.$i,0,1);



// Memberikan space kebawah agar tidak terlalu rapat
// $pdf->Cell(10,2,'',0,1);
// $pdf->SetFont('Arial','B',10);
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(35, 2, $rows['id_UserLogin'] . ' Data Nasabah', 0, 1);
$pdf->Cell(10, 5, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Nama', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(80, 6, $rows['field_nama'], 0, 0);

// $pdf->Cell(10,9,'',0,1);
$pdf->Cell(27, 6, date("d F Y", strtotime($rows['field_tanggal_reg'])), 0, 0);
$pdf->Cell(5, 6, ',', 0, 0);
$pdf->Cell(35, 6, $rows['field_time_reg'] . ' WIB', 0, 1);
// $pdf->Cell(10,9,'',0,1);

$pdf->Cell(35, 6, 'Email', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(60, 6, $rows['field_email'], 0, 0);
// $pdf->Cell(10,9,'',0,1);
$pdf->Cell(20, 6, 'Handphone', 0, 0);
$pdf->Cell(3, 6, ':', 0, 0);
$pdf->Cell(35, 6, $rows['field_handphone'], 0, 1);
// $pdf->Cell(10, 5, '', 0, 1);

$pdf->Cell(35, 6, 'Member ID', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(60, 6, $rows['field_member_id'], 0, 0);

$pdf->Cell(20, 6, 'Account*', 0, 0);
$pdf->Cell(4, 6, ':', 0, 0);

$pdf->SetFont('Times', '', 11);
$string = $rows['No_Rekening'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 10) < 0) {
     for ($i = 0; $i < (10 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 6, '', 0, 1);


$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Cabang', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(60, 6, $rows['field_branch_name'], 0, 0);

$pdf->Cell(20, 6, 'Tanggal*', 0, 0);
$pdf->Cell(4, 6, ':', 0, 0);

$pdf->SetFont('Times', '', 11);
$string = $rows['Tgl_Nasabah'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 20) < 0) {
     for ($i = 0; $i < (10 - $total); $i++) {
          $pdf->Cell(7, 7, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 0, '', 0, 1);



$pdf->Cell(10, 7, '', 0, 1);
$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'NIK', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Nik_Nasabah'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................
$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Tempat/Tgl Lahir', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = "DD-MM-YYYY";
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Jenis Kelamin', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);

if ($rows['Jenis_Kelamin_N'] == 'L') {
     $string = 'LAKI-LAKI';
} else {
     $string = 'PEREMPUAN';
}

$string;
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Alamat', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Alamat_Nasabah'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      RT/RW', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = "00 00";
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      Kel/Desa', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Kelurahan_N'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      Kecamatan', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Kecamatan_N'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      Kota/Kabupaten', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Kabupaten_N'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      Provinsi', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Provinsi_N'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Agama', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Agama_N'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Status Perkawinan', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 11);
$string = $rows['Status_N'];
$array_phpmu = str_split($string);
foreach ($array_phpmu as $data) {
     $pdf->Cell(6, 6, $data, 1, 0, 'C');
}
$total = count($array_phpmu);
if (($total - 24) < 0) {
     for ($i = 0; $i < (24 - $total); $i++) {
          $pdf->Cell(6, 6, '', 1, 0, 'L');
     }
}
$pdf->Cell(10, 7, '', 0, 1);
// ............................

$pdf->Cell(10, 3, '', 0, 1);
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(35, 2, $rows['UserLogin'] . ' Data Pewaris', 0, 1);
$pdf->Cell(10, 5, '', 0, 1);


// $pdf->Cell(10,9,'',0,1);
$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Nama', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'NIK', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Tempat/Tgl Lahir', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Jenis Kelamin', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Alamat', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      RT/RW', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      Kel/Desa', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, '      Kecamatan', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Agama', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Status Perkawinan', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Handphone', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->SetFont('Times', '', 1);
for ($i = 1; $i <= 24; $i++)
     $pdf->Cell(6, 6, $i, 1, 0, 'C');
// $pdf->Cell(10,6,'',0,1);



$pdf->Cell(10, 16, '', 0, 1);

$pdf->Cell(115, 20, '', 0, 0);
$pdf->SetFont('Times', '', 11);
$pdf->Cell(35, 6, 'Petugas', 1, 0, 'C');
$pdf->Cell(35, 6, 'Nasabah', 1, 1, 'C');

$pdf->Cell(115, 20, '', 0, 0);
$pdf->SetFont('Times', '', 1);
$pdf->Cell(35, 20, '', 1, 0);
$pdf->Cell(35, 20, '', 1, 0);



// $pdf->Output('D', $rows['field_nama'] . '.pdf');
$pdf->Output();
