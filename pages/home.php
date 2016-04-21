<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/includers/header.php';
        ?>
        <title>Home</title>
    </head>
    <body>
        <div class="wrap">
            <header>
                <?php
                $page = 'home';
                include dirname(__FILE__) . '/includers/menu.php';
                ?>
            </header>
            <main role="main" class="first container-fluid">
                <section id="about-info" class="row">
                    <p>
                        Café Farao wordt uitgebaat door Fabian Pijl, Kenneth Terriere en Mattias Langens.
                        In Augustus 2012 hebben de drie vrienden het Café overgenomen van de voormalige uitbater en baten het sindsdien met veel liefde en plezier uit.
                    </p>
                    <p>
                        Café Farao is een lichtbruin café waar sfeer en amusement niet ver te zoeken zijn.
                        Laat je verassen door onze regelmatig wijzigende bierkaart.
                        Meer zin in sport? Wij beschikken over darts, tafelvoetbal en sport op TV.
                    </p>
                </section>	
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