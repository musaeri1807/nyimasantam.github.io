	<?php
	/*call the FPDF library*/
	require('../library/fpdf181/fpdf.php');

	require_once("../config/connection.php");
	require_once("../php/function.php");


	require 'phpmailer/PHPMailerAutoload.php';
	require 'credential.php';
	// require('invoice.php');

	$recipients = [
		["ID" => "1122", "Email" => "johnkennedy.indonesia@yahoo.com"],
		["ID" => "1123", "Email" => "musaeri.kjt@gmail.com"],
		["ID" => "1124", "Email" => "musaeri1807@gmail.com"],
		["ID" => "1125", "Email" => "erick.java03@gmail.com"]
	];
	// $data = [
	// 	["id" => "1123", "name" => "musaeri.kjt@gmail.com", "class" => "musaeri.kjt@gmail.com", "mark" => "musaeri.kjt@gmail.com", "gender" => "musaeri.kjt@gmail.com"],
	// 	["id" => "1123", "name" => "musaeri.kjt@gmail.com", "class" => "musaeri.kjt@gmail.com", "mark" => "musaeri.kjt@gmail.com", "gender" => "musaeri.kjt@gmail.com"],
	// 	["id" => "1123", "name" => "musaeri.kjt@gmail.com", "class" => "musaeri.kjt@gmail.com", "mark" => "musaeri.kjt@gmail.com", "gender" => "musaeri.kjt@gmail.com"]
	// ];


	$tgl_dari    = date('2020-m-01', strtotime("-1 months"));
	$tgl_sampai  = date('Y-m-30', strtotime("-1 months"));
	foreach ($recipients as $recipient) {
		$email = $recipient["Email"];
		// $email = "musaeri.kjt@gmail.com";
		$no = 1;
		$sql = "SELECT * FROM tbltrxmutasisaldo M JOIN tbluserlogin U ON M.field_member_id=U.field_member_id 
                                           JOIN tblbranch B ON U.field_branch=B.field_branch_id 
                                           WHERE U.field_email=:email  
                                           ORDER BY field_id_saldo DESC";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':email' => $email));
		$rows  = $stmt->fetch(PDO::FETCH_ASSOC);


		$member_id = $rows['field_member_id'];
		$sqlT = "SELECT * FROM tbltrxmutasisaldo M WHERE  date(field_tanggal_saldo) >=:tgl_dari AND date(field_tanggal_saldo) <= :tgl_sampai 
                                           AND M.field_member_id=:idmember  
                                           ORDER BY field_id_saldo ASC";
		$stmtT = $db->prepare($sqlT);
		$stmtT->execute(array(':tgl_dari' => $tgl_dari, ':tgl_sampai' => $tgl_sampai, ':idmember' => $member_id));
		$result = $stmtT->fetchAll();



		$rekening = $rows['field_rekening'];
		$bulan  = date('F_Y', strtotime("-1 months"));

		$namefile = $rekening . '_' . $bulan;
		$source  = '../image/';

		// echo $source.$namefile;
		// die();

		//name file pada pdf Estatment
		//Bulan tahun Nomor Rekening
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->Image('../image/PT_MSI.png', 10, 9, 15);
		$pdf->SetFont('Times', 'B', 16);
		// 		// Title
		$pdf->Cell(18, 10, '', 0, 0, 'C');
		$pdf->Cell(80, 10, 'bspid.id', 0, 0, 'L');
		// 		// Title
		$pdf->SetFont('Times', 'B', 30);
		$pdf->Cell(0, 10, 'E-Statement', 0, 1, 'R');
		$pdf->SetFont('Times', 'B', 16);
		$pdf->Cell(18, 7, '', 0, 0, 'C');
		$pdf->Cell(172, 7, date('F Y', strtotime("-1 months")), 0, 1, 'R');
		// 		// Arial bold 15
		// $pdf->Cell(190, 20, '00', 1, 0);
		$pdf->Ln(2);
		// 		// Line break
		// $pdf->Line(10, 25, 200, 25);
		$pdf->Cell(190, 21, '', 1, 0); //Kotak dalam
		$pdf->Ln(2);
		$pdf->SetFont('Times', 'i', 12);
		$pdf->Cell(0, 1, '', 0, 1);
		$pdf->Cell(31.67, 6, 'Rekening', 0, 0);
		$pdf->Cell(3, 6, ':', 0, 0);
		$pdf->Cell(76.99, 6, $rows['field_rekening'], 0, 0);
		$pdf->Cell(15, 6, '', 0, 0);
		$pdf->Cell(3, 6, '', 0, 0, 'C');
		$pdf->Cell(25.67, 6, '', 0, 0, 'R');
		$pdf->Cell(9, 6, '', 0, 0, 'C');
		$pdf->Cell(25.67, 6, '', 0, 1, 'R');

		$pdf->Cell(31.67, 6, 'Nasabah', 0, 0);
		$pdf->Cell(3, 6, ':', 0, 0);
		$pdf->Cell(76.99, 6, $rows['field_nama'], 0, 0);
		$pdf->Cell(15, 6, 'Periode', 0, 0);
		$pdf->Cell(3, 6, ':', 0, 0, 'C');
		$pdf->Cell(25.67, 6, date('d M Y', strtotime($tgl_dari)), 0, 0, 'R');
		$pdf->Cell(9, 6, '-', 0, 0, 'C');
		$pdf->Cell(25.67, 6, date('d M Y', strtotime($tgl_sampai)), 0, 1, 'R');

		$pdf->Cell(31.67, 6, 'Cabang', 0, 0);
		$pdf->Cell(3, 6, ':', 0, 0);
		$pdf->Cell(76.99, 6, $rows['field_branch_name'], 0, 0);
		$pdf->Cell(15, 6, '', 0, 0);
		$pdf->Cell(3, 6, '', 0, 0, 'C');
		$pdf->Cell(25.67, 6, '', 0, 0, 'R');
		$pdf->Cell(9, 6, '', 0, 0, 'C');
		$pdf->Cell(25.67, 6, '', 0, 1, 'R');
		// $pdf->Cell(190, 6, 'Rekening', 1, 1);

		$pdf->Ln(1);
		// .............................................................
		$width_cell = array(22, 65, 23, 20, 30, 30, 138, 30);
		$pdf->SetFont('Times', 'B', 11);
		//Background color of header//
		$pdf->SetFillColor(193, 229, 252);
		// Header starts /// 
		//Second header column//
		$pdf->Cell($width_cell[0], 7, 'Tanggal', 1, 0, 'C', true);
		//Second header column//
		$pdf->Cell($width_cell[1], 7, 'Keterangan', 1, 0, 'C', true);
		//Second header column//
		// $pdf->Cell($width_cell[2], 7, 'Waktu', 1, 0, 'C', true);
		$pdf->Cell($width_cell[2], 7, 'Status', 1, 0, 'C', true);
		//Second header column//
		$pdf->Cell($width_cell[3], 7, 'Kode', 1, 0, 'C', true);
		//Third header column//
		$pdf->Cell($width_cell[4], 7, 'Mutasi', 1, 0, 'C', true);
		//Fourth header column//
		$pdf->Cell($width_cell[5], 7, 'Saldo', 1, 1, 'C', true);
		//Third header column//
		//// header ends ///////


		$pdf->SetFont('Times', 'I', 10);
		//Background color of header//
		$pdf->SetFillColor(235, 236, 236);
		//to give alternate background fill color to rows// 
		$fill = false;
		/// each record is one row  ///
		foreach ($result as $row) {
			$Types = $row["field_type_saldo"];
			if ($Types == "200") {
				$Types = 'db';
			} else if ($Types == "100") {
				$Types = 'cr';
			} else if ($Types == "300") {
				$Types = 'bb';
			}

			$Status = $row['field_status'];
			if ($Status == "S") {
				$Status = 'Success';
			} else if ($Status == 'C') {
				$Status = 'Cancel';
			}
			//data looping

			$pdf->Cell($width_cell[0], 7, date("d/m/Y", strtotime($row["field_tanggal_saldo"])), 0, 0, 'C', $fill);
			$pdf->Cell($width_cell[1], 7, $row['field_no_referensi'] . $Status, 0, 0, 'C', $fill);
			$pdf->Cell($width_cell[2], 7, $Status, 0, 0, 'C', $fill);
			if ($row['field_kredit_saldo'] == "0") {
				$pdf->Cell($width_cell[3], 7, $Types, 0, 0, 'C', $fill);
				$pdf->Cell($width_cell[4], 7, $row['field_debit_saldo'] . " g", 0, 0, 'R', $fill);
			} elseif ($row['field_debit_saldo'] == "0") {
				$pdf->Cell($width_cell[3], 7, $Types, 0, 0, 'C', $fill);
				$pdf->Cell($width_cell[4], 7, $row['field_kredit_saldo'] . " g", 0, 0, 'R', $fill);
			}

			$pdf->Cell($width_cell[5], 7, $row['field_total_saldo'] . " g", 0, 1, 'R', $fill);

			//to give alternate background fill  color to rows//
			$fill = !$fill;
		}
		/// end of records /// 
		$pdf->Ln(5);
		$pdf->SetFont('Times', 'B', 12);
		$pdf->Cell(22, 7, '', 0, 0, 'C', $fill);
		$pdf->Cell($width_cell[6], 7, 'Saldo Akhir', 0, 0, 'R', $fill);
		$pdf->Cell($width_cell[7], 7, $rows['field_total_saldo'] . " g", 0, 0, 'R', $fill);
		$pdf->Ln(20);
		$pdf->SetFont('Times', 'i', 11);
		$pdf->Cell(190, 10, 'Demikian informasi yang dapat disampaikan, Apabila tidak sesuai harap hubungi kami.', 0, 1, 'L');
		$pdf->Cell(23, 10, '', 0, 1, 'C');
		$pdf->Cell(23, 5, 'Salam', 0, 1, 'p');
		$pdf->Cell(23, 5, 'Bank Sampah Pintar', 0, 1, 'L');

		// $pdf->Output();

		// die();

		$pdf->Output($source . $namefile . ".pdf", 'I');
		// $pdf->Output();
		//string Output([string dest [, string name [, boolean isUTF8]]])
		// ...........................................................................
	}
	die();
	//Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer(true);

	//Server settings
	// $mail->SMTPDebug = 4;					                    //Enable verbose debug output
	$mail->isSMTP();                                            //Send using SMTP
	$mail->Host       = 'mx.mailspace.id';                     //Set the SMTP server to send through
	$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	$mail->Username   = EMAIL;                     //SMTP username
	$mail->Password   = PASSWORD;                               //SMTP password
	$mail->SMTPSecure = 'tls';        						    //Enable implicit TLS encryption
	$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

	//Recipients
	$mail->setFrom(EMAIL, 'Mailer Example BSP');
	//$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient


	$mail->addAddress($recipient["Email"]);               //Name is optional


	$mail->addReplyTo(INFO, 'Information');
	// $mail->addCC('musaeri1807@gmail.com');
	// $mail->addBCC('musaeri.kjt@gmail.com');

	//Attachments
	//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
	$mail->addAttachment("../image/" . $email . ".pdf");    //Optional name

	//Content
	$mail->isHTML(true);                                  //Set email format to HTML
	$mail->Subject = 'E-Statement';
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if (!$mail->send()) {
		echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo $Msg = 'Message has been sent';
	}

	?>
