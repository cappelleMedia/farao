<!DOCTYPE html>
<html lang="nl">

    <head>

        <title>Farao admin portaal | Events beheren</title>
        <?php
        include dirname(__FILE__) . '/includers/admin-header.php';
        ?>     
    </head>

    <body>

        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <?php
                $page = 'eventmgr';
                include dirname(__FILE__) . '/admin_menu.php';
                ?>
            </nav>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Evenementen beheren</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->
        <?php
        include dirname(__FILE__) . '/../includers/scripts.php';
        ?>
        <?php
        include dirname(__FILE__) . '/includers/admin-scripts.php';
        ?>
    </body>

</html>
