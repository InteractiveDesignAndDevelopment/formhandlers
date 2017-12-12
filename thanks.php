<!doctype html>
<html>
    <head>
	    <?php include('./include-head-top.php'); ?>
        <title>Thanks</title>
    </head>
    <body>
        <?php include('./include-body-top.php'); ?>

        <main class="content">
            <h1>Thank You!</h1>

            <p>Your submission has been received.</p>

            <?php
                if (isset($_GET['submission-referrer'])) {
                    echo "<p><a href='${_GET['submission-referrer']}'>Return to the form.</a></p>";
                }
            ?>

        </main>

        <?php include('./include-body-bottom.php'); ?>
    </body>
</html>
