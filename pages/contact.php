<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/includers/header.php';
        ?>
        <title>Contact</title>
    </head>
    <body class="green-grid">
        <div class="wrap">
            <header>
                <?php
                $page = 'contact';
                include dirname(__FILE__) . '/includers/menu.php';
                ?>
            </header>
            <main role="main" class="first container-fluid">
                <div class="col-lg-offset-0 col-lg-6 col-md-offset-2 col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-title">Algemene informatie</h1>
                        </div>
                        <div class="panel-body">
                            <h2>Adres</h2>
                            <p>Oude Markt 42</p>
                            <p>3000 Leuven</p>
                            <h2>Openingsuren</h2>
                            <p>Maandag-Zaterdag: 10 uur - ...</p>
                            <p>Zondag 11 uur - ...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-offset-6 col-lg-6 col-md-offset-2 col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-title">Contact informatie</h1>
                        </div>
                        <div class="panel-body">
                            <h2>Telefoon</h2>
                            <p>0499/24.23.02</p>
                            <h2>Email</h2>
                            <address>
                                <script>
                                    //Spam free mail solution
                                    document.write('<a href="mailto:' + first + '@' + last + '">' + first + '@' + last + '<\/a>');
                                </script>
                                of via het <a href="#contactForm">contactformulier</a>
                                <noscript>
                                U kan ons email adres niet zien omdat u javascript hebt uitgeschakeld.
                                Dit script dient om spam tegen te gaan.
                                Indien u toch wenst te mailen kan u javascript inschakelen of <br>
                                onderstaand contactformulier gebruiken indien u toch wenst te mailen.
                                </noscript>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="col-lg-offset-0 col-lg-6 col-md-offset-2 col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-title">Vind je weg naar ons</h1>
                        </div>
                        <div id="gmap_canvas" class="panel-body maps">
                            <iframe width="100%" height="450" frameborder="0" style="border:0;" src="https://www.google.com/maps/embed/v1/place?q=place_id:EiNPdWRlIE1hcmt0IDQwLCAzMDAwIExldXZlbiwgQmVsZ2nDqw&key=AIzaSyBt5LggmDtCSuPXhYDiPrFdZkI8P0nUpzs" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-lg-offset-6 col-lg-6 col-md-offset-2 col-md-8">
                    <div class="panel panel-default ">
                        <div class="panel-heading">
                            <h1 class="panel-title">Contacteer ons</h1>
                        </div>
                        <div class="panel-body">
                            <form id="contactForm" method="post" action="../scripts/submit.php">
                                <label for="name">Naam:</label>
                                <input type="text" name="name" id="name"/>

                                <label for="email">Uw Email:</label>
                                <input type="email" name="email" id="email"/>

                                <label for="" class="antispam hidden">Laat dit leeg, anders wordt dit bericht als spam beschouwd:</label>
                                <input class="antispam hidden" name="url"/>

                                <label for="message">Boodschap:</label>
                                <textarea name="message" rows="3" id="message"></textarea>

                                <input type="submit" name="submit" value="Verzenden" class="submit-button"/>
                                <!--add javascript validation-->
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
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