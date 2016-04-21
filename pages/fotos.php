<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/includers/header.php';
        ?>
        <script src="fotoTools/jbcore/juicebox.js"></script>
        <title>Foto's</title>
    </head>
    <body class="green-grid">
        <div class="wrap">
            <header>
                <?php
                $page = 'pics';
                include dirname(__FILE__) . '/includers/menu.php';
                ?>
            </header>
            <main role="main" class="first container-fluid">
                <div class="row">
                    <figure id="hapje-tapje13" class="col-md-offset-1 col-md-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title">Hapje Tapje 2013</h1>
                            </div>
                            <div class="panel-body">
                                <!-- START JUICEBOX EMBED ---> 
                                <script>
                                    new juicebox({
                                        baseUrl: 'fotoTools/HapjeTapje2013',
                                        containerId: "juicebox-hapje-tapje13",
                                        galleryWidth: "100%",
                                        galleryHeight: "70%",
                                        backgroundColor: "#222222"
                                    })
                                </script>
                                <div id="juicebox-hapje-tapje13"></div>
                            </div>
                        </div>
                    </figure>
                    <figure id="sfeerfotos" class="col-md-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title">Sfeer Foto's</h1>
                            </div>
                            <div class="panel-body">
                                <!-- START JUICEBOX EMBED ---> 
                                <script>
                                    new juicebox({
                                        baseUrl: 'fotoTools/sfeerfotos',
                                        containerId: "juicebox-sfeerfotos",
                                        galleryWidth: "100%",
                                        galleryHeight: "70%",
                                        backgroundColor: "#222222"
                                    })
                                </script>
                                <div id="juicebox-sfeerfotos"></div>
                            </div>
                        </div>
                    </figure>
                </div>
                <div class="row">
                    <div class="col-lg-offset-3 col-lg-6">
                        <div class="alert alert-info text-center">
                            Nieuwe foto's mogen altijd doorgestuurd worden naar : dummy@dummy.be
                        </div>
                    </div>
                </div>
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