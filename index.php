<?php

$insecureDomains = ['formhandlers.test'];

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

if ('off' == $_SERVER['HTTPS'] && ! in_array($_SERVER['HTTP_HOST'], $insecureDomains)) {
	header('Location: error.php?error-code=not-https');
	die();
}

if ('POST' != $_SERVER['REQUEST_METHOD']) {
	header('Location: error.php?error-code=not-post');
	die();
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

//var_dump($_SERVER);

// var_dump($_SERVER);

$explodedURI = explode('/', $_SERVER['REQUEST_URI']);

//var_dump($explodedURI);

$body     = '';
$to       = trim($explodedURI[1]);
$replyTo  = trim($_POST['*replyto']);
$subject  = trim($_POST['*subject']);
$redirect = trim($_POST['*redirect']);
$formName = trim($_POST['*formname']);
$cc       = trim($_POST['*cc']);
$bcc      = trim($_POST['*bcc']);
$honeypot = trim($_POST['*honeypot']);
$headers  = '';

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

// To

if (0 == strlen($to)) {
	header('Location: error.php?error-code=no-to');
	die();
}

// Subject

if (0 == strlen($subject)) {
	$subject = 'Form Submission';
	if (0 < strlen($formName)) {
		$subject .= ": ${formName}";
	}
}

// Redirect

if (0 == strlen($redirect)) {
	$redirect = '/thanks.php';
}

// Headers

if (0 < strlen($replyTo)) {
	$headers .= "Reply-To: ${replyTo}\r\n";
}
if (0 < strlen($cc)) {
	$headers .= "CC: ${cc}\r\n";
}
if (0 < strlen($bcc)) {
	$headers .= "BCC: ${bcc}\r\n";
}
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

// Body

$body .= formTable();

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

// Testing.k

print("To: ${to}<br>");
print("Subject: ${subject}<br>");
print("Headers: ${headers}");
print("Body: ${body}<br>");

//$sent = mail($to, $subject, $body, $headers);
//
//if ($sent) {
//	header("Location: ${redirect}");
//} else {
//	header('Location: error.php?error-code=not-sent');
//}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

function formTable() {
	$html = '';

	if (isset($_POST)) {
		$html .= '<table>';
		foreach($_POST as $inputName => $inputValue) {
			if ('*' == substr($inputName, 0, 1)) {
				continue;
			}

			$html .= '<tr>';
			$html .= "<td>${inputName}</td>";
			$html .= "<td>${inputValue}</td>";
			$html .= '</tr>';
		}
		$html .= '</table>';
	}

	return $html;
}
