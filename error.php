<?php

/*
 * Error Codes:
 * no-to
 * not-https
 * not-post
 */

switch ($_GET['error-code']) {
	case 'no-to':
		$title = '&ldquoto&rdquo; address missing';
		$description = <<<EOD
			<p>
				The URI should have a to address in it.
			</p>
EOD;
		break;
	case 'not-https':
		$title = 'Request was not secure';
		$description = <<<EOD
			<p>
				The request submitting the form should be https.
			</p>
EOD;
		break;
	case 'not-post':
		$title = 'Request was not post';
		$description = <<<EOD
			<p>
				The request submitting the form should use the post http verb.
			</p>
EOD;
case 'not-sent':
		$title = 'Email was not sent';
		$description = <<<EOD
			<p>
				The email was not sent.
			</p>
EOD;
		break;
	case 'spam':
		$title = 'Probable Spam';
		$description = <<<EOD
			<p>
				This looked too much like spam.
			</p>
EOD;
		break;
	default:
		$title = 'Unspecified error';
		$description = <<<EOD
			<p>
				An unspecified error occurred. Check the logs.
			</p>
EOD;
}

?>

<!doctype html>
<html>
	<head>
		<title>Error: <?php $title ?></title>
	</head>
	<body>

		<?php echo $description ?>

	</body>
</html>
