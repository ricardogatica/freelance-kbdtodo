<?php


$response = [];

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
	require_once('vendor/PHPMailer/class.phpmailer.php');
	require_once('vendor/PHPMailer/class.smtp.php');
	require_once('email.php');

	$mail = new PHPMailer();

	$mail->IsSMTP();
	$mail->CharSet = "UTF-8";
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465;
	$mail->SMTPAuth = true;

	$mail->Username = $username;
	$mail->Password = $password;

	$mail->setFrom($mail->Username, 'KBdTODO.cl');
	$mail->AddReplyTo($_POST['email']);
	$mail->AddAddress($mail->Username);

	$mail->IsHTML(true);
	$mail->Subject = "Contacto desde KBdTODO.cl";
	$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";

	$mail->Body = "<h1>{$_POST['name']} envió el siguiente mensaje.</h1>";
	$mail->Body.= "<p>{$_POST['message']}</p>";

	$mail->Body.= "<p>Para poder contactarse con {$_POST['name']} solo se debe responder este correo.</p>";



	if($mail->Send()) {
		$response['status'] = 'success';
		$response['code'] = __LINE__;
	} else {
		$response['status'] = 'error';
		$response['code'] = __LINE__;
		$response['message'] = $mail->ErrorInfo;
	}

}
else {
	$response['status'] = 'error';
	$response['code'] = __LINE__;
	$response['message'] = "Se deben enviar todos los campos.";
}

echo json_encode(compact('response'));

?>