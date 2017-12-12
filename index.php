<?php

$insecureDomains = ['formhandlers.test'];

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

// Requests must be secure, except for whitelisted insecure domains
if ('off' == $_SERVER['HTTPS'] && ! in_array($_SERVER['HTTP_HOST'], $insecureDomains)) {
	header('Location: /error.php?error-code=not-https');
	die();
}

// Requests must use the POST verb
if ('POST' != $_SERVER['REQUEST_METHOD']) {
	header('Location: /error.php?error-code=not-post');
	die();
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

//var_dump($_SERVER);

$explodedURI = explode('/', $_SERVER['REQUEST_URI']);

//var_dump($explodedURI);

if (isset($_POST['*debug'])) {
	$debug = filter_var($_POST['*debug'], FILTER_VALIDATE_BOOLEAN);
} else {
	$debug = false;
}

$to = trim( $explodedURI[1] );

if (0 == strlen($to)) {
	header('Location: /error.php?error-code=no-to');
}

$headers = '';
$body = '';

if (isset($_POST['*bcc'])) {
	$bcc = trim($_POST['*bcc']);
}

if (isset($_POST['*cc'])) {
	$cc = trim( $_POST['*cc'] );
}

if (isset($_POST['*formname'])) {
	$formName = trim( $_POST['*formname'] );
}

if (isset($_POST['*honeypot'])) {
	$honeypot = trim( $_POST['*honeypot'] );
}

if (isset($_POST['*redirect'])) {
	$redirect = trim( $_POST['*redirect'] );
}

if (isset($_POST['*replyto'])) {
	$replyTo = trim( $_POST['*replyto'] );
}

if (isset($_POST['*subject'])) {
	$subject = trim( $_POST['*subject'] );
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

// To

if (0 == strlen($to)) {
	header('Location: /error.php?error-code=no-to');
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

if (isset($redirect) && 0 == strlen($redirect)) {
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

$body .= emailBody();

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

if ($debug) {
	print(emailBody());
} else {
	$sent = mail($to, $subject, emailBody(), $headers);
//	echo "${sent} = mail(${to}, ${subject}, ..., ${headers});";

	if ($sent) {
		if (isset($redirect)) {
			header("Location: ${redirect}");
		} else {
			header('Location: /thanks.php');
		}
	} else {
		header('Location: /error.php?error-code=not-sent');
	}
}

$submissionId = recordSubmission();
recordFields($submissionId);

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

function dbName() {
	if ('formhandlers.test' == $_SERVER['HTTP_HOST']) {
		return 'formhandlers';
	} else {
		return 'FormHandler';
	}
}

function dbServerName() {
	if ('formhandlers.test' == $_SERVER['HTTP_HOST']) {
		return 'localhost';
	} else {
		return 'wolverine';
	}
}

function debugSummary() {
	if (!$GLOBALS['debug']) {
		return '';
	}

	$html = '';

	$html .= <<< EOHTMLF
		<table bgcolor="#be0623" cellpadding="0" cellspacing="0" width="100%" style="background-color: #be0623; color: #fff;">
			<tr>
				<td style="width: 10px;" width="10">&nbsp;</td>
				<td colspan="3" style="font-size: 14px;">
EOHTMLF;

	if (isset($_POST)) {
		foreach($_POST as $inputName => $inputValue) {
			if ('*' != substr($inputName, 0, 1)) {
				continue;
			}

			if (0 == strlen(trim($inputValue))) {
				$inputValue = '<em>blank</em>';
			}

			$html .= <<< EOHTMLF
			<div>
				<strong>${inputName}</strong>: ${inputValue}
			</div>
EOHTMLF;
		}
	}

	$html .= <<< EOHTMLF
			</td>
			<td style="width: 10px;" width="10">&nbsp;</td>
		</tr>
	</table>
EOHTMLF;

	return $html;
}

function emailBody() {

	$submissionDetails = submissionDetails();
	$responseSummary = responseSummary();
	$debugSummary = debugSummary();

	return <<< EOT
		<!doctype html>
		<html>
			<head>
				<meta charset="utf-8">
		        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		        <meta name="viewport" content="width=device-width">
				<title>${GLOBALS['subject']}</title>
			</head>
			<body itemscope itemtype="http://schema.org/EmailMessage" bgcolor="#F4F2F1" style="background-color: #F4F2F1; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; height: 100%; line-height: 1.6em; margin: 0; width: 100% !important;">
			
				${debugSummary}
			
				<table cellpadding="0" cellspacing="0" style="background-color: #3f3c3b;" width="100%">
					<tr>
						<td height="20" style="line-height: 20px; mso-line-height-rule:exactly; overflow: hidden; vertical-align: middle;"></td>
					</tr>
					<tr>
						<td style="text-align: center;"><img alt="Mercer University" height="75" src="https://assets.mercer.edu/formhandlers/mercer-university-wordmark-785x150.png" style="display: inline;" width="393"></td>
					</tr>
					<tr>
						<td height="20" style="line-height: 20px; mso-line-height-rule:exactly; overflow: hidden; vertical-align: middle;"></td>
					</tr>
				</table>
			
				<br>
			
				${submissionDetails}
				
				<br>
				
				<table cellpadding="0" cellspacing="0" style="background-color: #c95106;" width="100%">
					<tr>
						<td colspan="3" height="5" style="line-height: 5px; mso-line-height-rule:exactly; overflow: hidden; vertical-align: middle;"></td>
					</tr>
					<tr>
						<td style="width: 10px;" width="10">&nbsp;</td>
						<td style="color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase;">Response Summary</td>
						<td style="width: 10px;" width="10">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" height="5" style="line-height: 5px; mso-line-height-rule:exactly; overflow: hidden; vertical-align: middle;"></td>
					</tr>
				</table>
			
				${responseSummary}
	        </body>
        </html>
EOT;
}

function realIpAddr() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		// IP from shared internet
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		// IP passed from proxy
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function recordFields($submissionId) {
	$serverName = dbServerName();
	$connectionInfo = array('Database'=>dbName());

	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if ($conn === false) {
		echo 'Unable to connect.</br>';
		die(print_r(sqlsrv_errors(), true));
	}

	if (isset($_POST)) {
		foreach ( $_POST as $inputName => $inputValue ) {
			if ( '*' == substr( $inputName, 0, 1 ) ) {
				continue;
			}

			$query = <<< EOQUERY
				INSERT INTO fields (
					submission_id,
					name,
					value
				) VALUES (
					?,
					?,
					?
				);
EOQUERY;

			$params = array(
				$submissionId,
				$inputName,
				$inputValue
			);

			$results = sqlsrv_query($conn, $query, $params);
			if ($results === false) {
				echo 'Error in executing query.</br>';
				die(print_r(sqlsrv_errors(), true));
			}
		}
	}
}

function recordSubmission() {
	$serverName = dbServerName();
	$connectionInfo = array('Database'=>dbName());

	if (isset($GLOBALS['bcc'])) {
		$bcc = $GLOBALS['bcc'];
	} else {
		$bcc = NULL;
	}

	if (isset($GLOBALS['cc'])) {
		$cc = $GLOBALS['cc'];
	} else {
		$cc = NULL;
	}

	if (isset($GLOBALS['debug'])) {
		$debug = $GLOBALS['debug'];
	} else {
		$debug = NULL;
	}

	if (isset($GLOBALS['form_name'])) {
		$formName = $GLOBALS['form_name'];
	} else {
		$formName = NULL;
	}

	if (isset($GLOBALS['honeypot'])) {
		$honeypot = $GLOBALS['honeypot'];
	} else {
		$honeypot = NULL;
	}

	if (isset($GLOBALS['redirect'])) {
		$redirect = $GLOBALS['redirect'];
	} else {
		$redirect = NULL;
	}

	if (isset($GLOBALS['reply_to'])) {
		$replyTo = $GLOBALS['reply_to'];
	} else {
		$replyTo = NULL;
	}

	if (isset($GLOBALS['subject'])) {
		$subject = $GLOBALS['subject'];
	} else {
		$subject = NULL;
	}

	if (isset($GLOBALS['to'])) {
		$to = $GLOBALS['to'];
	} else {
		$to = NULL;
	}

	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if ($conn === false) {
		echo 'Unable to connect.</br>';
		die(print_r(sqlsrv_errors(), true));
	}

	$query = <<< EOQUERY
		INSERT INTO submissions (
                request_uri,
                http_referer,
                -- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                bcc,
                cc,
                debug,
                form_name,
                honeypot,
                redirect,
                reply_to,
                [subject],
                [to]
    	) VALUES (
                ?,  -- request_uri
                ?,  -- http_referer
                -- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                ?,  -- bcc
                ?,  -- cc
                ?,  -- debug
                ?,  -- form_name
                ?,  -- honeypot
                ?,  -- redirect
                ?,  -- reply_to
                ?,  -- subject
                ?   -- to
		);
     	SELECT SCOPE_IDENTITY();
EOQUERY;

	$params = array(
		$_SERVER['REQUEST_URI'],
	    $_SERVER['HTTP_REFERER'],
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
		$bcc,
		$cc,
		$debug,
		$formName,
		$honeypot,
		$redirect,
		$replyTo,
		$subject,
		$to
	);

	$results = sqlsrv_query($conn, $query, $params);
	if ($results === false) {
		echo 'Error in executing query.</br>';
		die(print_r(sqlsrv_errors(), true));
	}

	sqlsrv_next_result($results);
	sqlsrv_fetch($results);
	$submissionId = sqlsrv_get_field($results, 0);

	sqlsrv_close($conn);

	return $submissionId;
}

function responseSummary() {
	$html = '';

	$html .= <<< EOHTMLF
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="width: 10px;" width="10">&nbsp;</td>
				<td colspan="3" style="font-size: 14px;">
EOHTMLF;

	if (isset($_POST)) {
		foreach($_POST as $inputName => $inputValue) {
			if ('*' == substr($inputName, 0, 1)) {
				continue;
			}

			if (0 == strlen(trim($inputValue))) {
				$inputValue = '<em>blank</em>';
			}

			$html .= <<< EOHTMLF
				<div style="line-height: 2em;">
					<strong>${inputName}</strong>
				</div>
				<div>
					${inputValue}
				</div>
EOHTMLF;
		}
	}

	$html .= <<< EOHTMLF
				</td>
				<td style="width: 10px;" width="10">&nbsp;</td>
			</tr>
		</table>
EOHTMLF;

	return $html;
}

function submissionDetails() {
	if (!empty($GLOBALS['formName'])) {
		$formValue = $GLOBALS['formName'];
	} else if (!empty($_SERVER['HTTP_REFERER'])) {
		$formValue = $_SERVER['HTTP_REFERER'];
	} else {
		$formValue = 'UNKNOWN FORM';
	}

	if (!empty($_SERVER['HTTP_REFERER'])) {
		$formValue = "<a href='${formValue}'>${formValue}</a>";
	}

	$submittedValue = date('Y-m-d H:i:s');

	$ip = realIpAddr();

	return <<< EOT
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="width: 10px;" width="10">&nbsp;</td>
				<td colspan="3" style="font-size: 14px;">
					<div>
						<strong>Form:</strong> ${formValue}		
					</div>
					<div>
						<strong>Submitted:</strong> ${submittedValue}
					</div>
					<div>
						<strong>IP:</strong> ${ip}
					</div>
				</td>
				<td style="width: 10px;" width="10">&nbsp;</td>
			</tr>
		</table>
EOT;
}