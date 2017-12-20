<?php

/*
 * Error Codes
 * ===========
 * - honeypot
 * - no-to
 * - not-https
 * - not-post
 * - not-sent
 */

switch ($_GET['error-code']) {
    case 'honeypot':
        $title = 'Honeypot has a value';
        $description = <<<EOD
            <p>The honeypot should not be filled in.</p>
EOD;
	    break;
	case 'no-to':
		$title = '&ldquoto&rdquo; address missing';
		$description = <<<EOD
			<p>There should be an address to which form summaries are sent.</p>
EOD;
		break;
	case 'not-https':
		$title = 'Request was not secure';
		$description = <<<EOD
			<p>The request submitting the form should be https.</p>
EOD;
		break;
	case 'not-post':
		$title = 'Request was not post';
		$description = <<<EOD
			<p>The request submitting the form should use the post http verb.</p>
EOD;
case 'not-sent':
		$title = 'Email was not sent';
		$description = <<<EOD
			<p>The email was not sent.</p>
EOD;
		break;
	case 'redirect-invalid':
		$title = 'Invalid Redirect';
		$description = <<<EOD
			<p>The redirect URL is invalid. The redirect URL is validated using filter_var with FILTER_VALIDATE_URL.</p>
EOD;
		break;
	case 'to-invalid':
		$title = 'Probable Spam';
		$description = <<<EOD
			<p>Invalid to address. The to address is validated using filter_var with FILTER_VALIDATE_EMAIL.</p>
EOD;
		break;
	default:
		$title = 'Unrecognized error';
		$description = <<<EOD
			<p>That error code is not recognized.</p>
EOD;
}

?>

<!doctype html>
<html>
	<head>
		<?php include('./include-head-top.php'); ?>
		<title><?php print $title ?></title>
	</head>
	<body>
        <?php include('./include-body-top.php'); ?>

        <main class="content">

            <h1>An Error Occurred</h1>

            <p>
                A problem prevented your submission from going through.
            </p>

            <p>
                The message below has more details.
            </p>

            <div class="error-description">
		        <?php print $description ?>
            </div>

            <?php
                if (isset($_GET['submission-referrer'])) {
                    echo <<<EOHTML
                    <p>
                        <a href="${_GET['submission-referrer']}">
                            You may return to the form and try again.
                        </a>
                    </p>
EOHTML;
                }
            ?>

        </main>

        <?php include('./include-body-bottom.php'); ?>
	</body>
</html>
