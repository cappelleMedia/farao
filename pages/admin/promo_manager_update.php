<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php
        include dirname(__FILE__) . '/includers/admin-header.php';
        global $promoFormData;
        $updateId = empty($promoFormData) ? $this->getValidator()->sanitizeInput($_GET['promoId']) : $promoFormData['promoIdPrevVal'];
        $promo = isset($_GET['updatePromo']) ? $_GET['updatePromo'] : NULL;
        ?>
        <title>Promo manager|update</title>
    </head>
    <body>
        <div class="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <?php
                $page = 'promomgr';
                include dirname(__FILE__) . '/admin_menu.php';
                ?>
            </nav>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">                 
                        <?php
                        $type = 'update';
                        include dirname(__FILE__) . '/includers/promoform.php';
                        ?>
                    </div>                    
                </div>
            </div>
        </div>
        <?php
        include dirname(__FILE__) . '/../includers/scripts.php';
        include dirname(__FILE__) . '/includers/admin-scripts.php';
        include dirname(__FILE__) . '/includers/datepicker.php';
        ?>
        <script>
            $(function () {
                $('#promoStart').datepicker();
                $('#promoEnd').datepicker();
            });
        </script>
    </body>
</html>