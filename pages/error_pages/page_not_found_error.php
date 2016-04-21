<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/../includers/header.php';
        ?>
        <title>Page not found error</title>
    </head>
    <body class="green-grid">
        <div class="container-fluid first">
            <div class="row">
                <div class="col-lg-offset-4 col-lg-4">
                    <div class="panel panel-danger text-center">
                        <div class="panel-heading">
                            <h3 class="panel-title">Pagina niet gevonden</h3>
                        </div>
                        <div class="panel-body">
                            <p>Sorry, het lijkt erop dat er iets is misgelopen:</p>  
                            <p class="text-danger">'De pagina die je zocht werd niet gevonden.'</p>
                            <p>Het zou kunnen dat de pagina niet meer bestaat of dat er een intern foutje is gebeurt.</p>
                            <p>Indien het een interne fout is, lossen we dit zo snel mogelijk op!</p>
                            <a href="index.php?action=home">Terug naar de home page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

