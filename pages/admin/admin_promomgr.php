<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>Farao admin portaal | Promoties beheren</title>
        <?php
        include dirname(__FILE__) . '/includers/admin-header.php';
        ?>     
    </head>
    <body>
        <div id="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <?php
                $page = 'promomgr';
                include dirname(__FILE__) . '/admin_menu.php';
                ?>
            </nav>
            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid scrollPanel">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Promoties beheren</h1>
                        </div>
                    </div>
                    <a href="index.php?action=admin_addPromoPage" class="btn btn-lg btn-success btn-block">
                        <i class="fa fa-lg fa-plus-circle"></i>
                        Promoties toevoegen
                    </a>
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Promoties beheren
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="dataTable_wrapper">
                                        <table class="table table-striped table-bordered table-hover confirmation-trigger-parent" id="promos-table">
                                            <thead>
                                                <tr>
                                                    <th>Titel</th>
                                                    <th>Omschrijving</th>
                                                    <th>Start datum</th>
                                                    <th>Eind datum</th>
                                                    <th>Status</th>
                                                    <th>Operaties</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Titel</th>
                                                    <th>Beschrijving</th>
                                                    <th>Start datum</th>
                                                    <th>Eind datum</th>
                                                    <th>Status</th>
                                                    <th>Operaties</th>
                                                </tr>
                                            </tfoot>

                                            <tbody>
                                                <?php
                                                $promos = $this->getPromoController()->getPromos(TRUE);
                                                $format = 'd/m/Y';
                                                foreach ($promos as $key => $promo) {
                                                    $status = $this->getPromoController()->getPromoStatus($promo);
                                                    ?>
                                                    <tr class="">
                                                        <td>
                                                            <?php echo $promo->getTitle() ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $promo->getText(); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $promo->getStartStr($format); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $promo->getEndStr($format); ?>
                                                        </td>           
                                                        <td data-order="<?php echo $status['code']; ?>">
                                                            <?php echo $status['displayName']; ?>
                                                        </td>                                                        
                                                        <td>
                                                            <?php
                                                            echo $this->getPromoController()->getStatusIcon($status['code'], $promo);
                                                            ?>
                                                            <a class="btn btn-sm btn-primary" 
                                                               href="index.php?action=admin_updatePromoPage&promoId=<?php echo $key ?>">
                                                                <i class="fa fa-pencil fa-lg"></i>
                                                            </a>

                                                            <a class="btn btn-sm btn-danger confirmation-trigger" data-confirmation-type="promo-delete" href="index.php?action=admin_deletePromo&promoId=<?php echo $key; ?>">
                                                                <i class="fa fa-trash-o fa-lg"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>                                            
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                    <div class="well">
                                        <h4>Promo manager gebruiks tips</h4>
                                        <ul>
                                            <li>
                                                Net boven de tabel kan je klikken op de knop om een promotie toe te voegen;
                                            </li>
                                            <li>
                                                Links boven de tabel kan je kiezen hoeveel promoties je wil zien per pagina.
                                            </li>
                                            <li>
                                                Rechts boven de tabel kan je zoeken naar een promotie. Dit kan op titel, beschrijving, start datum of eind datum.
                                            </li>
                                            <li>
                                                Je kan de promoties sorteren op titel, beschrijving, start datum of eind datum door op de kolomnaam te klikken.
                                            </li>
                                            <li>
                                                In de laatste kolom kan je operaties uitvoeren op de promotie in die rij.<br>
                                                De operaties zijn aanpassen (<i class="fa fa-pencil"></i>), verwijderen (<i class="fa fa-trash"></i>) en activeren(<i class="fa fa-play"></i>)/deactiveren(<i class="fa fa-pause"></i>).
                                            </li>
                                            <li>
                                                Als het scherm een kleine breedte heeft, worden sommige kolommen van de tabel 'verborgen'. In de eerste kolom staat dan een blauw bolletje met een plus-teken. Wanneer je daarop drukt, worden de kolommen getoond.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                </div>

            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->
        <?php
        include dirname(__FILE__) . '/../includers/scripts.php';
        include dirname(__FILE__) . '/includers/admin-scripts.php';
        include dirname(__FILE__) . '/includers/datatables-scripts.php';
        include dirname(__FILE__) . '/includers/datepicker.php';
        ?>
        <script>
            $(document).ready(function () {
                createPromoTable();
            });
        </script>
    </body>

</html>
