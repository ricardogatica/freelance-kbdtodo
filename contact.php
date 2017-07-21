<?php


$response = [];

$response['status'] = 'success';
$response['code'] = __LINE__;


if (!empty($_SERVER['HTTP_REFERER']) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false) {

	if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
		require_once('vendor/PHPMailer/class.phpmailer.php');
		require_once('vendor/PHPMailer/class.smtp.php');
		require_once('email.php');

		$config_charset = "UTF-8";
		$config_smtp_secure = "ssl";
		$config_host = "bh-70.webhostbox.net";
		$config_port = 465;
		$config_smtp_auth = true;

		$mail = new PHPMailer();

		$mail->IsSmtp();
		$mail->CharSet = $config_charset;
		$mail->SMTPSecure = $config_smtp_secure;
		$mail->Host = $config_host;
		$mail->Port = $config_port;
		$mail->SMTPAuth = $config_smtp_auth;

		$mail->Username = $username;
		$mail->Password = $password;

		$mail->setFrom($mail->Username, 'KBdTODO.cl');
		$mail->AddReplyTo($_POST['email']);
		$mail->AddAddress('kbdtodopv@gmail.com');

		$mail->IsHTML(true);
		$mail->Subject = "Contacto desde KBdTODO.cl";
		$mail->AltBody = "{$_POST['name']} envió el siguiente mensaje, {$_POST['message']}";

		$mail->Body = <<<EOL
<h4>{$_POST['name']} envió el siguiente mensaje:</h4>
<p>"{$_POST['message']}"</p>
<p>Para poder contactarse con {$_POST['name']} solo se debe responder este correo.</p>
EOL;

		if($mail->Send()) {
			$response['status'] = 'success';
			$response['code'] = __LINE__;

			$mail->ClearAddresses();
			$mail->AddAddress($_POST['email']);
			$mail->Subject = "¡Hola {$_POST['name']}!";

			$mail->Body = <<<EOL
<p>Hola {$_POST['name']},</p>
<p>Gracias por comunicarte con nosotros, hemos recibido tu mensaje satisfactoriamente, te responderemos a la brevedad.</p>
<p>Saludos cordiales,<br />
Minibodegas KBdTODO.cl</p>
EOL;

			 $mail->Send();
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

}
else {
	$response['status'] = 'error';
	$response['code'] = __LINE__;
	$response['message'] = "Error al intentar enviar mensaje, problemas de seguridad.";
}

echo json_encode(compact('response'));

?>