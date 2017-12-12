<!doctype html>
<html>
    <head>
        <?php  ?>
        <title>Thanks</title>
    </head>
    <body>

    </body>
</html>

<p>Thanks.</p>

<?php
    if (isset($_GET['submission-referrer'])) {
        echo "<p><a href='${_GET['submission-referrer']}'>Return to original form</a></p>";
    }
?>