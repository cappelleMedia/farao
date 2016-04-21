<?php
$messageEr = 'Internal problems';
if (isset($_GET['extraMessage'])) {
    $messagePreFilter = $_GET['extraMessage'];
    $messageEr = filter_var($messagePreFilter, FILTER_SANITIZE_STRING);
}
?>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/../includers/header.php';
        ?>
        <title>Server error</title>
    </head>
    <body class="green-grid">
        <div class="container-fluid first">
            <div class="row js-content-start">
                <div class="col-lg-offset-4 col-lg-4 col-md-8 col-md-offset-2 col-xs-12 error-block">
                    <div class="panel panel-danger text-center">
                        <div class="panel-heading">
                            <h3 class="panel-title">Internal server error</h3>
                        </div>
                        <div class="panel-body">
                            <p>Sorry, het lijkt erop dat er iets is misgelopen:</p>
                            <p class="text-danger"><?php echo $messageEr; ?></p>
                            <p>We proberen dit zo snel mogelijk op te lossen!</p>
                            <a href="index.php?action=home" class="btn btn-success btn-block errorConfirm">Oke</a>
                        </div>
                    </div>
                </div>
                <div class="js-content-end"></div>
            </div>
        </div>
    </body>
</html>

