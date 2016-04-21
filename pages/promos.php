<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/includers/header.php';
        ?>
        <title>Promo's</title>
    </head>
    <body class="green-grid">
        <div class="wrap">
            <header>
                <?php
                $page = 'promos';
                include dirname(__FILE__) . '/includers/menu.php';
                ?>
            </header>
            <main role="main" class="first container-fluid">
                <div class="row">
                    <article id="bons" class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title">Feestje in café Farao</h1>
                            </div>
                            <div class="panel-body">
                                <h3><b>Wanneer</b></h3>
                                <p>Altijd</p>
                                <h3><b>Details</b></h3>
                                <p>
                                    Wil je een feestje organiseren in Café Farao?<br>
                                    Bestel dan bonnetjes voor jou en jouw vrienden!<br>
                                    Elk bonnetje heeft een waarde van 2€<br>
                                    100 bonnetjes voor 160€ (Dat is 40€ winst!)<br>
                                    200 bonnetjes voor 300€ (Dat is 100€ winst!)<br>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="row">
                    <article id="Limo" class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title">Limoncello shots</h1>
                            </div>
                            <div class="panel-body">
                                <h3><b>Wanneer</b></h3>
                                <p>Altijd</p>
                                <h3><b>Details</b></h3>
                                <p>
                                    Voor slechts 10€ krijg je 6 Limoncello shots!<br>
                                    Dat is 5+1 Gratis!
                                </p>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- template for event
                <div class="row" style="display:none;">
                    <article id="" class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title">title</h1>
                            </div>
                            <div class="panel-body">
                                <h3><b>Wanneer</b></h3>
                                <p>datum</p>
                                <h3><b>Details</b></h3>
                                <p>gedetailleerde beschrijving</p>
                            </div>
                        </div>
                    </article>
                </div>
                End template for event -->
            </main>
            <div class="push"></div>
        </div>
        <footer>
            <?php
            include dirname(__FILE__) . '/includers/footer.php';
            ?>
        </footer>
        <?php
        include dirname(__FILE__) . '/includers/scripts.php';
        ?>
    </body>
</html>