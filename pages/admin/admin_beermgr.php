<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>Farao admin portaal | Bieren beheren</title>
        <?php
        include dirname(__FILE__) . '/includers/admin-header.php';
        ?>     
    </head>
    <body>
        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <?php
                $page = 'beermgr';
                include dirname(__FILE__) . '/admin_menu.php';
                ?>
            </nav>

            <!-- Page Content -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Bieren beheren</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    <!-- /.row -->

                    <a href="index.php?action=admin_addBeerPage" class="text-success">
                        <i class="fa fa-lg fa-plus-circle text-success"></i>
                        Bier toevoegen
                    </a>
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Bieren beheren
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="dataTable_wrapper">
                                        <table class="table table-striped table-bordered table-hover confirmation-trigger-parent" id="beers-table">
                                            <thead>
                                                <tr>
                                                    <th>Naam</th>
                                                    <th>Graden</th>
                                                    <th>Brouwerij</th>
                                                    <th>Type</th>
                                                    <th>Beschikbaar</th>
                                                    <th>Operaties</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $beers = $this->getBeerController()->getBeersAll(TRUE);
                                                foreach ($beers as $key => $beer) {
                                                    ?>
                                                    <tr class="">
                                                        <td>
                                                            <?php echo $beer->getName() ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $beer->getDegrees(); ?> &deg;
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo $beer->getBrewery_url(); ?>">
                                                                <?php echo $beer->getBrewery_name(); ?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <?php echo $beer->getType(); ?>
                                                        </td>
                                                        <?php $avail = $beer->getAvailable() ? 'Ja' : 'Neen'; ?>
                                                        <td data-order="<?php echo $avail; ?>">
                                                            <form name="availableSwitch <?php echo $key; ?>" id="onoffTrig <?php echo $key; ?>" method="POST" action="index.php?action=admin_switchAvailable">
                                                                <input type="text" name="switchId" id="switchId echo<?php echo $key; ?>" hidden="TRUE" value="<?php echo $key; ?>">
                                                                <select name="availableVal" onchange="this.form.submit()">
                                                                    <option class="availableS" <?php echo $avail === 'Ja' ? 'SELECTED' : ''; ?>> JA </option>
                                                                    <option class="notAvailableS" <?php echo $avail === 'Neen' ? 'SELECTED' : ''; ?>> NEE </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <?php $delDestin = 'index.php?action=admin_deleteBeer&beerId=' . $key; ?>
                                                            <a href="<?php echo $delDestin; ?>" class="btn btn-sm btn-danger confirmation-trigger" data-confirmation-type="beer-delete" data-destination="<?php echo $delDestin; ?>">
                                                                <i class="fa fa-trash-o fa-lg"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-primary" 
                                                               href="index.php?action=admin_updateBeerPage&beerId=<?php echo $key ?>">
                                                                <i class="fa fa-pencil fa-lg"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Naam</th>
                                                    <th>Graden</th>
                                                    <th>Brouwerij</th>
                                                    <th>Type</th>
                                                    <th>Beschikbaar</th>
                                                    <th>Operaties</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                    <div class="well">
                                        <h4>Bier manager gebruiks tips</h4>
                                        <ul>
                                            <li>
                                                Net boven de tabel kan je klikken op de knop om een bier toe te voegen;
                                            </li>
                                            <li>
                                                Links boven de tabel kan je kiezen hoeveel bieren je wil zien per pagina.
                                            </li>
                                            <li>
                                                Rechts boven de tabel kan je zoeken naar een Bier. Dit kan op naam, aantal graden, brouwerijnaam of type.
                                            </li>
                                            <li>
                                                Je kan de bieren sorteren op naam, aantal graden, brouwerijnaam of type door op de kolomnaam te klikken.
                                            </li>
                                            <li>
                                                In de laatste kolom kan je operaties uitvoeren op het bier in die rij.<br>
                                                De operaties zijn aanpassen (<i class="fa fa-pencil"></i>) en verwijderen (<i class="fa fa-trash"></i>).
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
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->
        <?php
        include dirname(__FILE__) . '/../includers/scripts.php';
        include dirname(__FILE__) . '/includers/admin-scripts.php';
        include dirname(__FILE__) . '/includers/datatables-scripts.php';
        ?>
        <!-- DataTables JavaScript -->

        <script>
            $(document).ready(function () {
                $('#beers-table').DataTable({
                    responsive: true,
                    "aoColumns": [
                        null,
                        null,
                        null,
                        null,
                        null,
                        {"bSortable": false}
                    ]
                });
            });
        </script>
    </body>

</html>
