<?php

//   ____             __ _
//  / ___|___  _ __  / _(_) __ _
// | |   / _ \| '_ \| |_| |/ _` |
// | |__| (_) | | | |  _| | (_| |
//  \____\___/|_| |_|_| |_|\__, |
//                         |___/

// Requests must be secure, except for these domains
$insecureDomains = ['formhandlers.test'];

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

// __     __         _       _     _
// \ \   / /_ _ _ __(_) __ _| |__ | | ___  ___
//  \ \ / / _` | '__| |/ _` | '_ \| |/ _ \/ __|
//   \ V / (_| | |  | | (_| | |_) | |  __/\__ \
//    \_/ \__,_|_|  |_|\__,_|_.__/|_|\___||___/

//var_dump($_SERVER);

$headers = '';
$body = '';

if (isset($_POST['*debug'])) {
	$debug = filter_var($_POST['*debug'], FILTER_VALIDATE_BOOLEAN);
} else if (isset($_POST['_debug'])) {
	$debug = filter_var($_POST['_debug'], FILTER_VALIDATE_BOOLEAN);
} else {
	$debug = false;
}

if (isset($_POST['*bcc'])) {
	$bcc = trim($_POST['*bcc']);
} else if (isset($_POST['_bcc'])) {
	$bcc = trim($_POST['_bcc']);
}

if (isset($_POST['*cc'])) {
	$cc = trim( $_POST['*cc'] );
} else if (isset($_POST['_cc'])) {
	$cc = trim( $_POST['_cc'] );
}

if (isset($_POST['*formname'])) {
	$formName = trim( $_POST['*formname'] );
} else if (isset($_POST['_formname'])) {
	$formName = trim( $_POST['_formname'] );
}

if (isset($_POST['*honeypot'])) {
	$honeypot = trim( $_POST['*honeypot'] );
} else if (isset($_POST['_honeypot'])) {
	$honeypot = trim( $_POST['_honeypot'] );
} else if (isset($_POST['*gotcha'])) {
	$honeypot = trim( $_POST['*gotcha'] );
} else if (isset($_POST['_gotcha'])) {
	$honeypot = trim( $_POST['_gotcha'] );
}

if (isset($_POST['*redirect'])) {
	$redirect = trim( $_POST['*redirect'] );
} else if (isset($_POST['_redirect'])) {
	$redirect = trim( $_POST['_redirect'] );
}

if (isset($_POST['*replyto'])) {
	$replyTo = trim( $_POST['*replyto'] );
} else if (isset($_POST['_replyto'])) {
	$replyTo = trim( $_POST['_replyto'] );
}

if (isset($_POST['*subject'])) {
	$subject = trim( $_POST['*subject'] );
} else if (isset($_POST['_subject'])) {
	$subject = trim( $_POST['_subject'] );
}

if (isset($_POST['*to'])) {
	$to = trim($_POST['*to']);
} else if (isset($_POST['_to'])) {
	$to = trim($_POST['_to']);
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

//  _   _           _       _         ____        _        _
// | | | |_ __   __| | __ _| |_ ___  |  _ \  __ _| |_ __ _| |__   __ _ ___  ___
// | | | | '_ \ / _` |/ _` | __/ _ \ | | | |/ _` | __/ _` | '_ \ / _` / __|/ _ \
// | |_| | |_) | (_| | (_| | ||  __/ | |_| | (_| | || (_| | |_) | (_| \__ \  __/
//  \___/| .__/ \__,_|\__,_|\__\___| |____/ \__,_|\__\__,_|_.__/ \__,_|___/\___|
//       |_|

// Only requests sent with POST are considered submissions
if ('POST' == $_SERVER['REQUEST_METHOD']) {
	$submissionId = recordSubmission();
	recordFields($submissionId);
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

// __     __    _ _     _       _   _
// \ \   / /_ _| (_) __| | __ _| |_(_) ___  _ __
//  \ \ / / _` | | |/ _` |/ _` | __| |/ _ \| '_ \
//   \ V / (_| | | | (_| | (_| | |_| | (_) | | | |
//    \_/ \__,_|_|_|\__,_|\__,_|\__|_|\___/|_| |_|

// The email has to go somewhere
if (! isset($to) || 0 == strlen($to)) {
	$errorCode = 'no-to';
}

if (isset($to) && ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
	$errorCode = 'to-invalid';
}

// Requests must be secure, except for whitelisted domains
if ('off' == $_SERVER['HTTPS'] && ! in_array($_SERVER['HTTP_HOST'], $insecureDomains)) {
	$errorCode = 'not-https';
}

// Requests must use the POST verb
if ('POST' != $_SERVER['REQUEST_METHOD']) {
	$errorCode = 'not-post';
}

if (isset($honeypot) && 0 < strlen($honeypot)) {
	$errorCode = 'honeypot';
}

if (isset($redirect) && 0 < strlen($redirect) && ! filter_var($redirect, FILTER_VALIDATE_URL)) {
	$errorCode = 'redirect-invalid';
}

if (isset($errorCode) && 0 < strlen($errorCode)) {
	$redirect = "/error.php?error-code=${errorCode}";
	if (isset($_SERVER['HTTP_REFERER'])) {
		$redirect .= "&submission-referrer=${_SERVER['HTTP_REFERER']}";
	}
	header("Location: ${redirect}");
	die();
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

//  ____        _ _     _   _____                 _ _
// | __ ) _   _(_) | __| | | ____|_ __ ___   __ _(_) |
// |  _ \| | | | | |/ _` | |  _| | '_ ` _ \ / _` | | |
// | |_) | |_| | | | (_| | | |___| | | | | | (_| | | |
// |____/ \__,_|_|_|\__,_| |_____|_| |_| |_|\__,_|_|_|

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

//  ____                 _   _____                 _ _
// / ___|  ___ _ __   __| | | ____|_ __ ___   __ _(_) |
// \___ \ / _ \ '_ \ / _` | |  _| | '_ ` _ \ / _` | | |
//  ___) |  __/ | | | (_| | | |___| | | | | | (_| | | |
// |____/ \___|_| |_|\__,_| |_____|_| |_| |_|\__,_|_|_|


if ($debug) {
	print(emailBody());
} else {
	$sent = mail($to, $subject, emailBody(), $headers);
//	echo "${sent} = mail(${to}, ${subject}, ..., ${headers});";

	if ($sent) {
		recordEmailStatus($submissionId, 1);
	} else {
		recordEmailStatus($submissionId, 0);
		$redirect = '/error.php?error-code=not-sent';
		if (isset($_SERVER['HTTP_REFERER'])) {
			$redirect .= "&submission-referrer=${_SERVER['HTTP_REFERER']}";
		}
		header("Location: ${redirect}");
		die();
	}
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

//  ____          _ _               _
// |  _ \ ___  __| (_)_ __ ___  ___| |_
// | |_) / _ \/ _` | | '__/ _ \/ __| __|
// |  _ <  __/ (_| | | | |  __/ (__| |_
// |_| \_\___|\__,_|_|_|  \___|\___|\__|

if (! isset($redirect)) {
	$redirect = '/thanks.php';
	if (isset($_SERVER['HTTP_REFERER'])) {
		$redirect .= "?submission-referrer=${_SERVER['HTTP_REFERER']}";
	}
}

if ($debug) {
	echo <<<EOHTML
		<div style="background-color: #0f4084; color: #fff; padding: 1rem;">
			Redirect: <a href="${redirect}" style="color: #fff;">${redirect}</a>
		</div>
EOHTML;
} else {
	header("Location: ${redirect}");
	die();
}

/* =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

//  _____                 _   _
// |  ___|   _ _ __   ___| |_(_) ___  _ __  ___
// | |_ | | | | '_ \ / __| __| |/ _ \| '_ \/ __|
// |  _|| |_| | | | | (__| |_| | (_) | | | \__ \
// |_|   \__,_|_| |_|\___|\__|_|\___/|_| |_|___/

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
		$i = 0;
		foreach ( $_POST as $inputName => $inputValue ) {
			if ( '*' == substr( $inputName, 0, 1 ) ) {
				continue;
			}

			$query = <<< EOQUERY
				INSERT INTO fields (
					submission_id,
					position,
					name,
					value
				) VALUES (
					?,  -- submission_id
					?,  -- position
					?,  -- name
					?   -- value
				);
EOQUERY;

			$params = array(
				$submissionId,
				$i,
				$inputName,
				$inputValue
			);

			$results = sqlsrv_query($conn, $query, $params);
			if ($results === false) {
				echo 'Error in executing query.</br>';
				die(print_r(sqlsrv_errors(), true));
			}
			$i++;
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

	if (isset($GLOBALS['formName'])) {
		$formName = $GLOBALS['formName'];
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

	if (isset($GLOBALS['replyTo'])) {
		$replyTo = $GLOBALS['replyTo'];
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
                -- *~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
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
                -- *~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
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
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
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

function recordEmailStatus($submissionId, $emailStatus) {
	$serverName = dbServerName();
	$connectionInfo = array('Database'=>dbName());
	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if ($conn === false) {
		echo 'Unable to connect.</br>';
		die(print_r(sqlsrv_errors(), true));
	}

	$query = <<< EOQUERY
		UPDATE submissions
		   SET email_status = ?
		 WHERE id = ?;
EOQUERY;

	$params = array(
		$emailStatus,
		$submissionId
	);

	sqlsrv_query($conn, $query, $params);

	sqlsrv_close($conn);
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

	if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
		$referrer = $_SERVER['HTTP_REFERER'];
		$formValue = "<a href='${referrer}'>${formValue}</a>";
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
